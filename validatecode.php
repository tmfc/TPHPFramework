<?php
/**
 * Script para la generacin de CAPTCHAS
 *
 * @author  Jose Rodriguez <jose.rodriguez@exec.cl>
 * @license GPLv3
 * @link    http://code.google.com/p/cool-php-captcha
 * @package captcha
 *
 */


session_start();


$captcha = new SimpleCaptcha();

// Change configuration...
$captcha->wordsFile = '';
$captcha->session_var = 'validate_code';
$captcha->imageFormat = 'png';
$captcha->scale = 3;
$captcha->blur = true;


$captcha->CreateImage();





/**
 * SimpleCaptcha class
 *
 */
class SimpleCaptcha {

    /** Width of the image */
    public $width  = 100;

    /** Height of the image */
    public $height = 40;

    /** Dictionary word file (empty for randnom text) */
    public $wordsFile = 'words/en.txt';

    /** Min word length (for non-dictionary random text generation) */
    public $minWordLength = 5;

    /**
     * Max word length (for non-dictionary random text generation)
     * 
     * Used for dictionary words indicating the word-length
     * for font-size modification purposes
     */
    public $maxWordLength = 8;

    /** Sessionname to store the original text */
    public $session_var = 'captcha';

    /** Background color in RGB-array */
    public $backgroundColor = array(255, 255, 255);

    /** Foreground colors in RGB-array */
    public $colors = array(
        array(0,110,221), // blue
        array(22,163,35), // green
        array(220,25,25),  // red
    );

    /** Shadow color in RGB-array or false */
    public $shadowColor = false; //array(0, 0, 0);

    /**
     * Font configuration
     *
     * - font: TTF file
     * - spacing: relative pixel space between character
     * - minSize: min font size
     * - maxSize: max font size
     */
    public $fonts = array(
        'Antykwa'  => array('spacing' => -3, 'minSize' => 17, 'maxSize' => 20, 'font' => 'AntykwaBold.ttf'),
        'Candice'  => array('spacing' =>-1.5,'minSize' => 18, 'maxSize' => 21, 'font' => 'Candice.ttf'),
        'DingDong' => array('spacing' => -2, 'minSize' => 14, 'maxSize' => 20, 'font' => 'Ding-DongDaddyO.ttf'),
        'Jura'     => array('spacing' => -2, 'minSize' => 18, 'maxSize' => 22, 'font' => 'Jura.ttf'),
    	'Times'    => array('spacing' => -2, 'minSize' => 18, 'maxSize' => 24, 'font' => 'TimesNewRomanBold.ttf'),
        'VeraSans' => array('spacing' => -1, 'minSize' => 10, 'maxSize' => 18, 'font' => 'VeraSansBold.ttf')
    	////'StayPuft' => array('spacing' =>-1.5,'minSize' => 28, 'maxSize' => 32, 'font' => 'StayPuft.ttf')
        ////'Duality'  => array('spacing' => -2, 'minSize' => 30, 'maxSize' => 38, 'font' => 'Duality.ttf')
        ////'Heineken' => array('spacing' => -2, 'minSize' => 24, 'maxSize' => 34, 'font' => 'Heineken.ttf')
    );

    /** Wave configuracion in X and Y axes */
    public $Yperiod    = 12;		//12
    public $Yamplitude = 1;		//9
    public $Xperiod    = 12;		//11
    public $Xamplitude = 1;

    /** letter rotation clockwise */
    public $maxRotation = 8;

    /**
     * Internal image size factor (for better image quality)
     * 1: low, 2: medium, 3: high
     */
    public $scale = 2;

    /** 
     * Blur effect for better image quality (but slower image processing).
     * Better image results with scale=3
     */
    public $blur = false;

    /** Debug? */
    public $debug = false;
    
    /** Image format: jpeg or png */
    public $imageFormat = 'jpeg';


    /** GD image */
    public $im;










    public function __construct($config = array()) {
    }







    public function CreateImage() {
        $ini = microtime(true);

        /** Initialization */
        $this->ImageAllocate();
        
        /** Text insertion */
        $text = $this->GetCaptchaText();
        $fontcfg  = $this->fonts[array_rand($this->fonts)];
        $this->WriteText($text, $fontcfg);
        $this->WriteLines();

        $_SESSION[$this->session_var] = md5(strtolower($text));

        /** Transformations */
        $this->WaveImage();
        if ($this->blur) {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $this->ReduceImage();

        if ($this->debug) {
            imagestring($this->im, 1, 1, $this->height-8,
                "$text {$fontcfg['font']} ".round((microtime(true)-$ini)*1000)."ms",
                $this->GdFgColor
            );
        }

        /** Output */
        $this->WriteImage();
        $this->Cleanup();
    }









    /**
     * Creates the image resources
     */
    protected function ImageAllocate() {
        // Cleanup
        if (!empty($this->im)) {
            imagedestroy($this->im);
        }

        $this->im = imagecreatetruecolor($this->width*$this->scale, $this->height*$this->scale);

        // Background color
        $this->GdBgColor = imagecolorallocate($this->im,
            $this->backgroundColor[0],
            $this->backgroundColor[1],
            $this->backgroundColor[2]
        );
        imagefilledrectangle($this->im, 0, 0, $this->width*$this->scale, $this->height*$this->scale, $this->GdBgColor);

        // Foreground color
        $color           = $this->colors[mt_rand(0, sizeof($this->colors)-1)];
        $this->GdFgColor = imagecolorallocate($this->im, $color[0], $color[1], $color[2]);

        // Shadow color
        if (!empty($this->shadowColor)) {
            $this->GdShadowColor = imagecolorallocate($this->im,
                $this->shadowColor[0],
                $this->shadowColor[1],
                $this->shadowColor[2]
            );
        }
    }





    /**
     * Text generation
     *
     * @return string Text
     */
    protected function GetCaptchaText() {
        $text = $this->GetDictionaryCaptchaText();
        if (!$text) {
            $text = $this->GetRandomCaptchaText(4);
        }
        return $text;
    }






    /**
     * Random text generation
     *
     * @return string Text
     */
    protected function GetRandomCaptchaText($length = null) {
        if (empty($length)) {
            $length = rand($this->minWordLength, $this->maxWordLength);
        }

        //letters
        //$words  = "abcdefghijmnpqrstvwyz";
        //$vocals = "aeiyu";
        
        //numbers
        $words  = "23456789";
        $vocals = "23456789";

        $text  = "";
        $vocal = rand(0, 1);
        for ($i=0; $i<$length; $i++) {
            if ($vocal) {
                $text .= substr($vocals, mt_rand(0, 7), 1);
            } else {
                $text .= substr($words, mt_rand(0, 7), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }









    /**
     * Random dictionary word generation
     *
     * @param boolean $extended Add extended "fake" words
     * @return string Word
     */
    function GetDictionaryCaptchaText($extended = false) {
        if (empty($this->wordsFile)) {
            return false;
        }

        $fp     = fopen($this->wordsFile, "r");
        $length = strlen(fgets($fp));
        if (!$length) {
            return false;
        }
        $line   = rand(0, (filesize($this->wordsFile)/$length)-1);
        if (fseek($fp, $length*$line) == -1) {
            return false;
        }
        $text = trim(fgets($fp));
        fclose($fp);


        /** Change ramdom volcals */
        if ($extended) {
            $text   = str_split($text, 1);
            $vocals = array('a', 'e', 'i', 'o', 'u');
            foreach ($text as $i => $char) {
                if (mt_rand(0, 1) && in_array($char, $vocals)) {
                    $text[$i] = $vocals[mt_rand(0, 4)];
                }
            }
            $text = implode('', $text);
        }

        return $text;
    }










    /**
     * Text insertion
     */
    protected function WriteText($text, $fontcfg = array()) {
        if (empty($fontcfg)) {
            // Select the font configuration
            $fontcfg  = $this->fonts[array_rand($this->fonts)];
        }
        $fontfile = 'font/'.$fontcfg['font'];

        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength-strlen($text);
        $fontSizefactor = 1+($lettersMissing*0.09);

        // Text generation (char by char)
        $x      = 20*$this->scale;
        $y      = round(($this->height*27/40)*$this->scale);
        $length = strlen($text);
        for ($i=0; $i<$length; $i++) {
            $degree   = rand($this->maxRotation*-1, $this->maxRotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize'])*$this->scale*$fontSizefactor;
            $letter   = substr($text, $i, 1);

            if ($this->shadowColor) {
                $coords = imagettftext($this->im, $fontsize, $degree,
                    $x+$this->scale, $y+$this->scale,
                    $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->im, $fontsize, $degree,
                $x, $y,
                $this->GdFgColor, $fontfile, $letter);
            $x += ($coords[2]-$x) + ($fontcfg['spacing']*$this->scale);
        }
    }

    
    /**
     * Write lines
     */
	protected function WriteLines()
	{
		for($i=0; $i<5; $i++)
		{
			$x1 = rand(0,$this->width);
			$y1 = rand(0,$this->height);
			$x2 = $x1 + rand(-50,350);
			$y2 = $y1 + rand(-50,170);
			imagesetthickness($this->im, 9);
			imageline($this->im, $x1, $y1, $x2, $y2, $this->GdFgColor);
		}		
	}

    /**
     * Wave filter
     */
    protected function WaveImage() {
        // X-axis wave generation
        $xp = $this->scale*$this->Xperiod*rand(1,3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($this->width*$this->scale); $i++) {
            imagecopy($this->im, $this->im,
                $i-1, sin($k+$i/$xp) * ($this->scale*$this->Xamplitude),
                $i, 0, 1, $this->height*$this->scale);
        }

        // Y-axis wave generation
        $k = rand(0, 100);
        $yp = $this->scale*$this->Yperiod*rand(1,2);
        for ($i = 0; $i < ($this->height*$this->scale); $i++) {
            imagecopy($this->im, $this->im,
                sin($k+$i/$yp) * ($this->scale*$this->Yamplitude), $i-1,
                0, $i, $this->width*$this->scale, 1);
        }
    }




    /**
     * Reduce the image to the final size
     */
    protected function ReduceImage() {
        // Reduzco el tamao de la imagen
        $imResampled = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($imResampled, $this->im,
            0, 0, 0, 0,
            $this->width, $this->height,
            $this->width*$this->scale, $this->height*$this->scale
        );
        imagedestroy($this->im);
        $this->im = $imResampled;
    }








    /**
     * File generation
     */
    protected function WriteImage() {
        if ($this->imageFormat == 'png') {
            header("Content-type: image/png");
            imagepng($this->im);
        } else {
            header("Content-type: image/jpeg");
            imagejpeg($this->im, null, 80);
        }
    }







    /**
     * Cleanup
     */
    protected function Cleanup() {
        imagedestroy($this->im);
    }
}

?>


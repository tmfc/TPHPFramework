<?php
require_once("MDB2.php");
require_once(dirname(__FILE__) ."/conf/config.php");
require_once(dirname(__FILE__) ."/const.php");
/**
 *  执行sql语句，返回记录集
 */
function ExecuteQuery($mdb2,$query)
{
	if (PEAR::isError($mdb2)) 
	{
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}

	$res =& $mdb2->query($query);

	if (PEAR::isError($res)) 
	{
		//echo var_dump($res);
		die($res->getMessage());
	}

	if (0 != $res->numRows())
		$data = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
	else
		$data = null;

	$res->free();

	return $data;
}

/**
 *  准备sql语句,执行sql语句，返回记录集
 */
function ExecuteQueryWithPrepare($mdb2,$query,$data)
{
	$res =& $mdb2->prepare($query);
	if (PEAR::isError($res)) {die($res->getMessage());}
	
	$result = $res->execute($data);
	if (PEAR::isError($result)){die($result->getMessage());}
	$data = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
		
	return $data;
}

/**
 * ִ 执行sql语句，返回一条记录
 */
function ExecuteOneRow($mdb2, $query)
{
	if (PEAR::isError($mdb2)) 
	{
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}

	$res =& $mdb2->query($query);

	if (PEAR::isError($res)) 
	{
		//echo var_dump($res);
		die($res->getMessage());
	}

	if (0 != $res->numRows())
		$data = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	else
		$data = null;

	$res->free();

	return $data;
}


/**
 * 执行sql语句，返回单个数值
 */
function ExecuteScaler($mdb2,$query)
{
	if (PEAR::isError($mdb2)) 
	{
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}

	$res =& $mdb2->query($query);

	if (PEAR::isError($res)) 
	{
		//echo var_dump($res);
		die($res->getMessage());
	}

	$data = $res->fetchOne();

	$res->free();

	return $data;
}

/**
 * 执行sql语句，返回单个数值
 */
function ExecuteScalerWithPrepare($mdb2,$query,$data)
{
	$res =& $mdb2->prepare($query);
	if (PEAR::isError($res)) {die($res->getMessage());}
	
	$result = $res->execute($data);
	if (PEAR::isError($result)){die($result->getMessage());}
	
	$data = $result->fetchOne();

	$res->free();
	
	return $data;
}

/**
 * 执行sql语句，返回单列
 */
function ExecuteCol($mdb2,$query,$colnum = 0)
{
	if (PEAR::isError($mdb2)) 
	{
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}	
	
	$res =& $mdb2->query($query);

	if (PEAR::isError($res)) 
	{
		//echo var_dump($res);
		die($res->getMessage());
	}

	$data = $res->fetchCol($colnum);

	$res->free();

	return $data;
}

/**
 * 执行sql语句，返回单列
 */
function ExecuteColWithPrepare($mdb2,$query,$data,$colnum = 0)
{
	$res =& $mdb2->prepare($sql);
	if (PEAR::isError($res)) {die($res->getMessage());}
	
	$result = $res->execute($data);
	if (PEAR::isError($result)){die($result->getMessage());}
	$data = $result->fetchCol($colnum);
		
	return $data;
}

/**
 * 执行插入，更新操作，返回影响行数
 */
function ExecuteNonQuery($mdb2,$query,$stopOnError = true)
{
	if (PEAR::isError($mdb2)) 
	{
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}
	
	$affects =& $mdb2->exec($query);
	if (PEAR::isError($affects)) {
		if($stopOnError)
		{
			die($affects->getMessage());
		}
		else 
		{
			syslog(LOG_ERR,"Error occoured when executing：" . $query);
			return -1;
		}
	}

	return $affects;
}

/**
 * 先在数据库中准备sql语句，再执行插入，更新操作，返回影响行数
 */
function ExecuteNonQueryWithPrepare($mdb2,$query,$types,$data,$stopOnError = true)
{
	$res =& $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
	if (PEAR::isError($res)) {die($res->getMessage());}
	
	$affectedRows = $res->execute($data);
	if (PEAR::isError($affectedRows)){
		if($stopOnError)
		{
			die($affectedRows->getMessage());
		}
		else 
		{
			syslog(LOG_ERR,"Error occoured when executing：" . $query);
			return -1;
		}
	}
	return $affectedRows;
}

/**
 * 执行插入操作，返回插入值ID
 */
function ExecuteInsert($mdb2,$query,$stopOnError = true)
{
	if (PEAR::isError($mdb2)) 
	{
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}
	
	$affects =& $mdb2->exec($query);
	if (PEAR::isError($affects)) {
		if($stopOnError)
		{
			die($affects->getMessage());
		}
		else 
		{
			syslog(LOG_ERR,"Error occoured when executing：" . $query);
			return -1;
		}
	}
	return $mdb2->lastInsertID();
}

function ExecuteInsertWithPrepare($mdb2,$query,$types,$data,$stopOnError = true)
{
	$res =& $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
	if (PEAR::isError($res)) {die($res->getMessage());}
	
	$affectedRows = $res->execute($data);
	if (PEAR::isError($affectedRows)){
		if($stopOnError)
		{
			die($affectedRows->getMessage());
		}
		else 
		{
			syslog(LOG_ERR,"Error occoured when executing：" . $query);
			return -1;
		}
	}
	return $mdb2->lastInsertID();
}

/**
 * 取得MDB2对象
 *
 * @return MDB2 object
 */
function &GetMDB2()
{
	$mdb2 =& MDB2::factory(__DB_CONN_STR);
	if (PEAR::isError($mdb2)) 
	{ 
		//echo var_dump($mdb2);
		die($mdb2->getMessage());
	}
	
	return $mdb2;
}

//获取分页Limit语句
function GetPageLimitClause($page,$page_size)
{
	return ' LIMIT ' . $page * $page_size . ',' . $page_size;
}
?>
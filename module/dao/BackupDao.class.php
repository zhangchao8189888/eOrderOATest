<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class BackupDao extends BaseDao
{
    
    function getBackupMaxUpTime($table){
    	$sql="select * from $table order by op_date desc limit 1 ";
    	$result=$this->g_db_query($sql,"backup");
		return mysql_fetch_array($result);
    }
    function getBackupMaxUpTimeTotal($table){
    	$sql="select * from $table order by update_time desc limit 1 ";
    	$result=$this->g_db_query($sql,"backup");
		return mysql_fetch_array($result);
    }
    function getYuanDataByMaxUpTimeTotal($table,$orderMaxTime){
    	$sql_query_order="select * from $table where update_time > '".$orderMaxTime."' ";
    	$result=$this->g_db_query($sql_query_order);
		return $result;
    }
    function getYuanDataByMaxUpTime($table,$orderMaxTime){
    	$sql_query_order="select * from $table where op_date > '".$orderMaxTime."' ";
    	$result=$this->g_db_query($sql_query_order);
		return $result;
    }
}
?>

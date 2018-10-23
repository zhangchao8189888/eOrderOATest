<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class AdminDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function AdminDao()
    {
        parent::BaseDao();
    }
    function getAdminList(){
		$sql="select *  from  or_admin  where  del_flag<>1";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function getGroupList(){
    	$sql="select *  from  or_auth_group  ";
		$result=$this->g_db_query($sql);
        return $result;
    }
    function delGroupById ($id) {
        $sql="delete from  or_auth_group  where id = $id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function addGroup($group){
    	$sql="insert into or_auth_group (title,status,rules,describes,access_json) values
    	('{$group["title"]}',{$group["status"]},'{$group["rules"]}','{$group["describe"]}','{$group["access_json"]}')";//echo $sql;
		$result=$this->g_db_query($sql);
        return $result;
    }
    function getGroupById($id){
    	$sql="select *  from  or_auth_group  where id = $id";
		$result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function updateGroupById($group){
    	$sql="update or_auth_group set title = '{$group["title"]}',
    	status = {$group["status"]},rules = '{$group["rules"]}',describes = '{$group["describe"]}',access_json = '{$group["access_json"]}' where id =  {$group["id"]}";//echo $sql;
		$result=$this->g_db_query($sql);
        return $result;
    }
    function checklogin($name,$pass){
    	$sql="select *  from  or_admin  where  name='{$name}' and password='{$pass}' and del_flag = 0";
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    /**
     * 添加管理员
     * @param $admin
     */
    function addAdmin($admin){
    	$sql="insert into or_admin (name,real_name,admin_type,password,create_time,memo) values ('{$admin['name']}','{$admin['real_name']}',{$admin['admin_type']},'{$admin['password']}',now(),'{$admin['memo']}')";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function modifyAdmin($admin) {
        $sql="update  or_admin set name = '{$admin['name']}',real_name = '{$admin['real_name']}',
             admin_type = {$admin['admin_type']},
             password = '{$admin['password']}',
             memo = '{$admin['memo']}' where id = {$admin['admin_id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    /**
     * 修改管理员为删除状态
     * @param $adminId
     */
    function updateAdminToDelete($adminId){
    	$sql="update or_admin set del_flag=1 where  id=$adminId";
		$result=$this->g_db_query($sql);
		return $result;
    }
/**
     * 得到操作日志列表
     * @param $listwhere
     */
    function getOpLogList($listwhere){
    	$sql="select or_log.* ,or_admin.name,or_admin.admin_type  from or_log,or_admin where or_log.who=or_admin.id  $listwhere";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function searchEndTimeNain () {
        $search_sql =  "select *  from or_account_coupon where coupon_type = 6";//echo $search_sql;exit;
        $result=$this->g_db_query($search_sql);
        return $result;
    }

}
?>

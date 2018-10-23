<?php
class BaseDao extends db{
    function BaseDao(){
//echo "BaseDao</br> ";
        parent::db();

    }
    function getMaxOrderNo(){
        $sql="select max(order_no) as max from or_order";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getMaxOrderReturnNo(){
        $sql="select max(order_no) as max from or_return_total";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
//取得当前表的最大id数
    function getMaxId($table){
        $sql="select max(id) as max  from $table  ";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getEmByEno($eNo){
        $sql="select *  from or_employ  where e_num='{$eNo}'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function searchCompanyByName($companyName){
        $sql="select * from  or_company where company_name='{$companyName}'";
        $result=$this->g_db_query($sql);
        //var_dump($result);
        return mysql_fetch_array($result);
    }
    function getCompanyById($comId){
        $sql="select *  from or_company where  id=$comId";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function searchCompanyList(){
        $sql="select id,company_name from or_company ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getAdmin($loginName){
        $sql="select *  from or_admin where name='$loginName' and del_flag = 0";
        $admin=$this->g_db_query($sql);
        if(!$admin){
            return false;
        }
        return mysql_fetch_array($admin);

    }
    function getAdminById($loginId){
        $sql="select *  from or_admin where id='$loginId'";
        $admin=$this->g_db_query($sql);
        return mysql_fetch_array($admin);
    }
    /**
     * 根据查询条件取得操作日志内容
     * @param unknown_type $adminId
     * @param unknown_type $taskId
     * @param unknown_type $where
     */
    function getOpLog($adminId=null,$taskId=null,$where=null){
        $sql="select *  from or_log where 1=1  ";
        if($adminId){
            $sql.=" and who={$adminId} ";
        }
        if($taskId){
            $sql.="  and what={$taskId} ";
        }
        if($where){
            $sql.=" and ".$where;
        }
        $sql.=" order by  time desc limit 1";
        $opLog=$this->g_db_query($sql);
        return $opLog;
    }
    function getOpLogByTaskId($taskId,$where=null){
        $sql="select *  from or_log where what='{$taskId}'";
        /*if($where){
            $sql.=" and ".$where;
        }*/
        //echo $sql;exit;
        $opLog=$this->g_db_query($sql);
        return $opLog;
    }
    function addOplog($OpLog){
        $sql="insert into or_log (who,who_name,what,Subject,time,memo)  values({$OpLog['who']},'{$OpLog['who_name']}','{$OpLog['what']}','{$OpLog['Subject']}',now(),'{$OpLog['memo']}')";
        $opLogR=$this->g_db_query($sql);// echo $sql;
        return $opLogR;
    }
    /**
     *修改管理员最后登录时间
     */
    function updateAdminLoginTime($admin){
        $sql="update or_admin  set last_login_time=now()  where id={$admin['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function searchSalTimeByComIdAndSalTime($comId,$salTime){
        $sql="select *  from or_salarytime  where companyId=$comId and salaryTime='{$salTime}'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getSalaryIdBySalaryTime($salTime){
        //$sql="﻿﻿﻿﻿﻿﻿﻿﻿ select st.*,c.company_name from or_salarytime st,or_company c where st.companyId=c.id and st.salaryTime='{$salTime}' ";
        //$sql="﻿﻿﻿﻿﻿﻿﻿﻿ select *  from or_salarytime where salaryTime='{$salTime}' ";
        $sql="select st.*,c.company_name from or_salarytime st,or_company c where st.companyId=c.id and st.salaryTime='{$salTime}' and st.salary_state>0";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getProductByCode($proCode){
        $sql="select *  from or_product where pro_code='$proCode'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);

    }
    function getProductById($proId,$db=null){
        $sql="select *  from or_product where id='$proId'";
        $result=$this->g_db_query($sql,$db);
        return mysql_fetch_array($result);

    }


    function getAccountItemBySourceId ($orderId) {
        $sql="select *  from or_account_item where source_id=$orderId  and account_type= 'cutomer_account'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function updateCustomerChengjiaoe($chengjiaoe,$custId,$custLevel){
        $sql="update or_customer set total_money=$chengjiaoe,custo_level='$custLevel' where id=$custId";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCustById($custId){
        $sql="select *  from or_customer where id=$custId";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    /**
     * 根据id查询客户信息
     * @param $custId
     */
    function getCustomerById($custId,$db=null){
        $sql="select *,discount as custo_discount  from or_customer where id=$custId";
        $result=$this->g_db_query($sql,$db);//echo $sql;
        return mysql_fetch_array($result);
    }
    function runSql($sql,$db=null){
        $result=$this->g_db_query($sql,$db);
        return $result;
    }
    function updateOrderCustomerNameByCustId($cust_id,$companyName){
        $sql ="update or_order set custo_name ='{$companyName}' where customer_id = $cust_id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateOrderTotalCustomerNameByCustId($cust_id,$companyName){
        $sql ="update or_order_total set custer_name ='{$companyName}' where custer_no = $cust_id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateProductNumById ($id,$num) {
        $sql ="update or_product set pro_num ={$num} where id = $id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateCustomerMoneyById ($id,$fee) {
        $sql ="update or_customer set total_money = {$fee} where id = $id";//echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }

    function getSalNumByProId($proId) {
        $sql ="select sum(pro_num) as sale_num from or_order where pro_id= $proId";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);
        //print_r($num);exit;
        if (!$num['sale_num']) {
            return 0;
        }
        return $num['sale_num'];
    }
    function getUserGroup ($userId) {
        $sql ="select *  from or_auth_group_access where uid= $userId";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);

        return $num['group_id'];
    }
    function getUserGroupByUId ($userId) {
        $sql ="select *  from or_auth_group_access where uid= $userId";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);

        return $num['group_id'];
    }
    function getAdminTypeById ($userId,$type) {
        $sql ="update or_admin set admin_type = {$type}  where id= $userId";//echo $sql;
        $result=$this->g_db_query($sql);

        return $result;
    }
    function addGroupAccess ($userId,$groupId) {
        $sql ="insert into  or_auth_group_access (uid,group_id) values ($userId,$groupId)";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);

        return $num['group_id'];
    }
    function updateGroupAccess ($userId,$groupId) {
        $sql ="update  or_auth_group_access set group_id = $groupId where  uid =$userId ";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);

        return $num['group_id'];
    }
    function getUserGroupAccess ($groupId) {
        $sql ="select *  from or_auth_group where id= $groupId";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);

        return $num['rules'];
    }
    function getUserGroupAccessPO ($groupId) {
        $sql ="select *  from or_auth_group where id= $groupId";//echo $sql;
        $result=$this->g_db_query($sql);
        $num = mysql_fetch_array($result);

        return $num;
    }
    function getUserAccessList () {
        $sql ="select * from  or_user_access";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getUserAccessListByUserId ($uId) {
        $sql ="select * from  or_user_access where user_id = $uId";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateUserAccessByUserIdAndMenuId ($uId,$menuId,$access_json) {
        $sql ="update  or_user_access set access_json = '{$access_json}' where user_id = $uId and menu_id = $menuId";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateOrderCustomerName ($customer) {
        $sql ="update  or_order_total set custer_name = '{$customer['realName']}' where custer_no = {$customer['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getAccountItemByOrderId ($orderNo,$source_type = "or_order_total") {

        $sql ="select * from or_account_item where source_id = {$orderNo} and source_type = '{$source_type}'  order by add_time";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
}
?>

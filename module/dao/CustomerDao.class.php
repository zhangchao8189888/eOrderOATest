<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class CustomerDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function CustomerDao()
    {
        parent::BaseDao();
    }
    /**
     * 添加客户信息
     * 

CREATE TABLE `or_customer` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `company_name` varchar(50) NOT NULL DEFAULT '',
    `card_no` varchar(100) NOT NULL DEFAULT '',
    `discount` float(4,2) NOT NULL DEFAULT '0.00',
    `adress` varchar(100) NOT NULL DEFAULT '',
    `realName` varchar(50) NOT NULL DEFAULT '',
    `total_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '储值金额',
    `custo_level` int(2) NOT NULL DEFAULT '0',
    `customer_jingbanren` varchar(50) NOT NULL DEFAULT '',
    `phone` varchar(500) NOT NULL DEFAULT '',
    `mobile` varchar(50) NOT NULL DEFAULT '',
    `qq` varchar(100) NOT NULL DEFAULT '',
    `email` varchar(100) NOT NULL DEFAULT '',
    `remarks` text NOT NULL,
    `custo_info` text NOT NULL,
    `weixin` varchar(100) NOT NULL DEFAULT '',
    `birthday_nongli` varchar(100) NOT NULL DEFAULT '0000-00-00',
    `birthday_gongli` date NOT NULL DEFAULT '0000-00-00',
    `add_time` date NOT NULL,
    `op_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
     */
    function addCustomer($customer){
        $user=$_SESSION['admin'];
        $customer['admin_id']=$user['id'];
        $customer['admin_name']=$user['real_name'];
		$sql="insert into or_customer
		(company_name,card_no,discount,address,realName,total_money,custo_level,customer_jingbanren,
		phone,mobile,qq,email,remarks,custo_info,weixin,birthday_gongli,add_time,op_date,admin_id,admin_name
		)
		values ('{$customer["company_name"]}',
		'{$customer["card_no"]}',
		{$customer["discount"]},
		'{$customer["address"]}',
		'{$customer["realName"]}',
		{$customer["total_money"]},
		{$customer["custo_level"]},
		'{$customer["customer_jingbanren"]}',
		'{$customer["phone"]}',
		'{$customer["mobile"]}',
		'{$customer["qq"]}',
		'{$customer["email"]}',
		'{$customer["remarks"]}',
		'{$customer["custo_info"]}',
		'{$customer["weixin"]}',
		'{$customer["birthday_gongli"]}',
		now(),now(),{$customer['admin_id']},'{$customer['admin_name']}')";
        /*echo $sql;
        exit;*/
		$result=$this->g_db_query($sql);
		return $result;
    }
    function addNewCustom ($customer) {
        $sql="insert into or_customer
		(realName,mobile,custo_level,total_money,admin_id,admin_name,card_no)
		values (
		'{$customer['custo_name']}','{$customer['moveTel_no']}',
		'{$customer['custo_level']}',{$customer['money_val']},{$customer['admin_id']},'{$customer['admin_name']}','{$customer['card_no']}')";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    /**
     * 修改客户信息
     * @param $customer
     */
    function updateCustomerById($customer){
        /**
         * company_name
        card_no
        discount
        address
        realName
        total_money
        custo_level
        customer_jingbanren
        phone
        mobile
        qq
        email
        weixin
        custo_info
        remarks
         */
        $sql="update or_customer
		set company_name='{$customer['company_name']}',
		card_no='{$customer['card_no']}',
		discount={$customer['discount']},total_money={$customer['total_money']},custo_level={$customer['custo_level']},
		address='{$customer['address']}',customer_jingbanren={$customer['customer_jingbanren']},
		realName='{$customer['realName']}',
		phone='{$customer['phone']}',
		mobile='{$customer['mobile']}',
		email='{$customer['email']}',
		weixin='{$customer['weixin']}',
		custo_info='{$customer['custo_info']}',
		remarks='{$customer['remarks']}',
		qq='{$customer['qq']}',op_date=now() where id={$customer['id']}";//echo $sql;exit;
    	$result=$this->g_db_query($sql);
		return $result;
    }
    /**
     * 查询客户信息集合
     * @param $custName
     * @param $custNo
     * @param $custType
     * @param $startIndex
     * @param $pagesize
     */
   function getCustomerList($where,$startIndex = 0,$pagesize = 0){
   	    $sql="select *  from or_customer where 1=1 and del_flag = 0";
        if($where['customer_code']){
   	    	$sql.=" and custo_no  ='{$where['customer_code']}''";
   	    }else{
   	    if($where['realName']){
   	    	$sql.=" and realName like '%".$where['realName']."%'";
   	    }
   	    if($where['customer_type']){
   	    	$sql.=" and custo_type ={$where['customer_type']}";
   	    }
   	    }
       if ($startIndex > 0 || $pagesize > 0) {

           $sql.=" order by id  limit $startIndex,$pagesize";
       }
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function getCustomerAll ($keyWord = ''){
       if (!empty($keyWord)) {
           $sql="select *  from or_customer where realName like '%".$keyWord."%'  and del_flag = 0 order by id";
       } else {

           $sql="select *  from or_customer  and del_flag = 0  order by id";
       }
       $result=$this->g_db_query($sql);
       return $result;
   }
    function  getCurrentMonthOrderFee ($id) {
        $date = date("Y-m",time());
        $sql = "select sum(or_order_total.realChengjiaoer)  as sum_m FROM or_order_total where LEFT(or_order_total.ding_date,7) = '".$date."' and or_order_total.custer_no = $id";
        $result=$this->g_db_query($sql);
        $sum = mysql_fetch_array($result);
        return $sum['sum_m'];
    }
  function getCustomerByName($custName){
   	    $sql="select *  from or_customer where realName like '%".$custName."%' ";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
    function getCustomerByCardNo($cardNo){
   	    $sql="select *  from or_customer where card_no = '".$cardNo."' ";
   	    $result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
   }
   function searchCustomerByName($custName){
        $sql="select *  from or_customer where custo_name = '".$custName."' ";
   	    $result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
   }
    function getCustomerLevelList () {
        $sql="select *  from or_customer_level order by sort DESC ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCustomerLevelSumAccount () {
        $sql="select sum(total_money) as sum_account,custo_level from or_customer group by custo_level;";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCustomerLevelSumAccountVal () {
        $sql="select sum(total_money) as sum_account_val,custo_level from or_customer where del_flag = 0;";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getCustomerLevelById ($id) {
        $sql="select *  from or_customer_level  where  id=$id";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function addCustomerLevel ($level) {
        $sql="insert into or_customer_level  (level_name,discount) values ('{$level["level_name"]}',{$level["discount"]})";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateCustomerLevel ($level) {
        $sql="update or_customer_level  set level_name='{$level["level_name"]}',discount = {$level["discount"]} where  id={$level['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCustomerLevelByName ($level) {
        $sql="select *  from or_customer_level  where level_name='{$level["level_name"]}'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function delCustomerLevel ($id) {
        $sql="delete from or_customer_level  where  id=$id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function modifyCustomMoneyById ($id,$val) {
        $sql="update or_customer_level set total_money = $val where  id=$id";
        $result=$this->g_db_query($sql);
        return $result;
    }
   /**
    * 删除客户信息通过ID
    * @param $custId
    */
   function delCustomerById($custId){
   	    //$sql="delete   from or_customer where id=$custId";
   	    $sql="update or_customer set del_flag = 1 where id=$custId"; //echo $sql;exit;
   	    $result=$this->g_db_query($sql);
		return $result;
   }
    function getJingbanById ($id) {
        $sql="select *   from  or_jingbanren where id = $id ";

        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
   function getJingbanrenList($jingban = '',$flg = true){
   	    $sql="select *   from  or_jingbanren where 1=1 ";
   	    if($flg){
   	    	$sql.=" and status = 0";
   	    }
   	    if(!empty($jingban['jingbanNo'])){
   	    	$sql.=" and jingbanren_no={$jingban['jingbanNo']}";
   	    }
        if(!empty($jingban['jingbanName'])){
   	    	$sql.=" and jingbanren_name='{$jingban['jingbanName']}'";
   	    }
        if(!empty($jingban['jingbanrenType'])){
   	    	$sql.=" and jingbanren_type='{$jingban['jingbanrenType']}'";
   	    }
   	    $sql.=" order by add_time desc";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function saveJingban($jingban){
   	    //$sql="insert into  or_jingbanren (jingbanren_no,jingbanren_name,jingbanren_group,add_time,op_date) values ({$jingban['jingbanNo']},'{$jingban['jingbanName']}','{$jingban['jingbanGroup']}',now(),now())";
   	    $sql="insert into  or_jingbanren (jingbanren_name,jingbanren_type,add_time) values ('{$jingban['jingbanName']}',{$jingban['jingbanGroup']},now())";
        //echo $sql;
       $result=$this->g_db_query($sql);
		return $result;
   }
   function updateJingban($jingban){
   	    //$sql="update or_jingbanren set jingbanren_no={$jingban['jingbanNo']},jingbanren_name='{$jingban['jingbanName']}',jingbanren_group='{$jingban['jingbanGroup']}',add_time=now(),op_date=now() where id={$jingban['id']}";
   	    $sql="update or_jingbanren set jingbanren_name='{$jingban['jingbanName']}',jingbanren_type={$jingban['jingbanGroup']},add_time=now() where id={$jingban['id']}";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function updateZhidan($id,$val){
   	    //$sql="update or_jingbanren set jingbanren_no={$jingban['jingbanNo']},jingbanren_name='{$jingban['jingbanName']}',jingbanren_group='{$jingban['jingbanGroup']}',add_time=now(),op_date=now() where id={$jingban['id']}";
   	    $sql="update or_jingbanren set is_zhidan_type=$val where id=$id";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function delJingban($jingbanId){
   	    $sql="update or_jingbanren set status = 1  where id=$jingbanId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function updateCouponValByCustomerId($customerId,$coupon_val){
   	    $sql="update or_account_coupon set coupon_val = $coupon_val  where customer_id = $customerId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function addCoupon ($coupon) {
       $sql="insert into or_account_coupon (customer_id,coupon_val,coupon_name,coupon_type,admin_id,admin_name,end_date,add_time,up_time)
        values({$coupon['customer_id']},{$coupon['coupon_val']},'{$coupon['coupon_name']}',{$coupon['coupon_type']},{$coupon['admin_id']},'{$coupon['admin_name']}','{$coupon['end_date']}',now(),now())";
       $result=$this->g_db_query($sql);//echo $sql;
       return $result;
   }
   function getCouponByCustomerId ($customerId) {
       $sql="select * from or_account_coupon where customer_id = $customerId and coupon_type not in (2,5,8)";
       $result=$this->g_db_query($sql);
       return $result;
   }
   function addAccountItem ($accountItem) {
       $sql="insert into or_account_item (customer_id,before_val,after_val,deal_val,admin_id,admin_name,add_time,up_time)
        values({$accountItem['customer_id']},{$accountItem['before_val']},{$accountItem['after_val']},{$accountItem['deal_val']},{$accountItem['admin_id']},'{$accountItem['admin_name']}',now(),now())";
       $result=$this->g_db_query($sql);//echo $sql;
       return $result;
   }
   function addAccountItem4Order ($accountItem) {
       $sql="insert into or_account_item (customer_id,before_val,after_val,deal_val,admin_id,admin_name,source_id,source_type,account_id,account_type,add_time,up_time)
        values({$accountItem['customer_id']},{$accountItem['before_val']},{$accountItem['after_val']},{$accountItem['deal_val']},{$accountItem['admin_id']},'{$accountItem['admin_name']}',
        {$accountItem['source_id']},'{$accountItem['source_type']}',{$accountItem['account_id']},'{$accountItem['account_type']}',now(),now())";
       $result=$this->g_db_query($sql);//echo $sql;
       return $result;
   }
   function updateAccountItemJson ($print_json,$orderId) {
       $sql="update or_account_item set print_json = '{$print_json}' where source_id = $orderId";
       $result=$this->g_db_query($sql);//echo $sql;
       return $result;
   }

    function getCouponByCusId($customerId) {
        $sql="select *  from or_account_coupon where customer_id = $customerId and coupon_status = 0 and coupon_type != 6";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function getCouponByCusIdLimit1($customerId) {
        $sql="select *  from or_account_coupon where customer_id = $customerId and coupon_status = 0 and coupon_type != 6 limit 1";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function updateAccountVal($customerId,$val) {
        $sql="update or_account_coupon set coupon_val = $val where customer_id = $customerId and coupon_type != 6";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function updateAccountValById($id,$val) {
        $sql="update or_account_coupon set coupon_val = $val where id = $id";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function updateAccountStatusById($id,$val) {
        $sql="update or_account_coupon set coupon_status = $val where id = $id";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function getCustomerListByLevel($where) {
        $sql="select * from or_customer {$where}";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function getCustomerBuyValListByLevel($from,$to,$level) {
        $sql="select sum(or_order_total.realChengjiaoer) as sum_money,or_order_total.custer_no,or_order_total.custer_name,
or_customer.realName,or_customer.custo_level ,or_customer.customer_jingbanren  
from or_order_total inner join or_customer on or_order_total.custer_no = or_customer.id  
where or_order_total.ding_date >= '{$from}' and or_order_total.ding_date <= '{$to}' ";

        if (!empty($level)) {
            $sql .= " and or_customer.custo_level = $level";
        }
        $sql .= " group by or_order_total.custer_no ORDER BY sum_money desc;";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
    function getConsumeRecodesListByCustomerId($where) {
        $sql = "select * from or_account_item where 1=1";
        if (!empty($where['customer_id'])) {
            $sql .= " and customer_id = {$where['customer_id']}";
        }

        if ($where['type'] == 'recharge') {
            $sql .= " and source_id = 0 or source_type = 'account_recharge' ";
        }

        if ($where['type'] == 'consume') {
            $sql .= " and source_id != 0";
        }
        $sql .= " order by  add_time ";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;

    }
    function getAccountItemBySourceId($sourceId) {
        $sql = "select * from or_account_item where source_id = $sourceId ";
        $result=$this->g_db_query($sql);//echo $sql;
        return $result;
    }
}
?>

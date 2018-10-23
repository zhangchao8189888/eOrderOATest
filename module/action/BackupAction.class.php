<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/BackupDao.class.php");
require_once("module/dao/OrderDao.class.php");
require_once("module/dao/ProductDao.class.php");
require_once("module/dao/CustomerDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
class BackupAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function BackupAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new BackupForm();
        $this->objForm->setFormData("adminDomain",$this->admin);
        $this->actionPath = $actionPath;
    }
  function dispatcher(){
    // (1) mode set
        $this->setMode();
        // (2) COM initialize
        $this->initBase($this->actionPath);
        // (3) controll -> Model
        $this->controller();
        // (4) view
        $this->view();
        // (5) closeConnect
        $this->closeDB();
   }
   function setMode()
    {
        // 模式设定
        $this->mode = $_REQUEST["mode"];
    }
   function controller()
    {
        // Controller -> Model
        switch($this->mode) {
		    case "toBackUp":
		    	$this->toBackUp();
		        break;
		    case "backupOrderByAdmin" :
		    	$this->backupOrderByAdmin();
		        break;
		    case "backupCustomerByAdmin" :
		    	$this->backupCustomerByAdmin();
		        break;
		    case "backupOrderTotalByAdmin" :
		    	$this->backupOrderTotalByAdmin();
		        break;
		    case "backupOrderReturnByAdmin" :
		    	$this->backupOrderReturnByAdmin();
		        break;
		    case "backupCaiwuByAdmin" :
		    	$this->backupCaiwuByAdmin();
		        break;
		    case "backupProductByAdmin" :
		    	$this->backupProductByAdmin();
		        break;
            default :
                $this->modelInput();
                break;
        }
    }
   function toBackUp(){
   	  $this->mode="toBackUp";
   }
   function backupOrderByAdmin(){
 	$time=$_REQUEST['times'];
 	$now=date('Y-m-d H:i:s',time());
    $this->objDao=new BackupDao();
    $orderBpo=$this->objDao->getBackupMaxUpTime("or_order");
 	//查询备份订单表最大的当前日期
 	$orderMaxTime=$orderBpo['op_date'];
 	$order_list=$this->objDao->getYuanDataByMaxUpTime("or_order",$orderMaxTime);
 	$i=0;
 	while ($row_order=mysql_fetch_array($order_list)) {
 		$this->objDao=new OrderDao();
	 	$order_backup_po=$this->objDao->getOrderByOrderId($row_order['id'],"backup");
	 	if($order_backup_po){
	 		$row_order['type']="update";
	 	}else {
	 		$row_order['type']="insert";
	 	}
	 	$runSql=$this->opOrderTable($row_order);
	 	$sql = $this->objDao->runSql($runSql,"backup");
	 	$i++;
	 	/*if($i>50){
	 		break;
	 	}*/
 	}
			 	echo "更新order表".$i."条数据,最新备份时间：".$orderMaxTime;
			 	exit;
		
   }
   function backupOrderTotalByAdmin(){
 	$time=$_REQUEST['times'];
 	$now=date('Y-m-d H:i:s',time());
    $this->objDao=new BackupDao();
    $orderBpo=$this->objDao->getBackupMaxUpTimeTotal("or_order_total");
 	//查询备份订单表最大的当前日期
 	$orderMaxTime=$orderBpo['update_time'];
 	$order_list=$this->objDao->getYuanDataByMaxUpTimeTotal("or_order_total",$orderMaxTime);
 	$i=0;
 	while ($row_order=mysql_fetch_array($order_list)) {
 		$this->objDao=new OrderDao();
	 	$order_backup_po=$this->objDao->getOrderTotalByOrderTotalId($row_order['id'],"backup");
	 	if($order_backup_po){
	 		$row_order['type']="update";
	 	}else {
	 		$row_order['type']="insert";
	 	}
	 	$runSql=$this->opOrderTotalTable($row_order);
	 	$sql = $this->objDao->runSql($runSql,"backup");
	 	$i++;
	 	/*if($i>50){
	 		break;
	 	}*/
 	}
			 	echo "更新OrderTotal".$i."条数据,最新备份时间：".$orderMaxTime;
			 	exit;
		
   }
   function backupCustomerByAdmin(){
 	$time=$_REQUEST['times'];
 	$now=date('Y-m-d H:i:s',time());
    $this->objDao=new BackupDao();
    $orderBpo=$this->objDao->getBackupMaxUpTime("or_customer");
 	//查询备份订单表最大的当前日期
 	$orderMaxTime=$orderBpo['op_date'];
 	$order_list=$this->objDao->getYuanDataByMaxUpTime("or_customer",$orderMaxTime);
 	$i=0;
 	while ($row_order=mysql_fetch_array($order_list)) {
 		$this->objDao=new OrderDao();
	 	$order_backup_po=$this->objDao->getCustomerById($row_order['id'],"backup");
	 	if($order_backup_po){
	 		$row_order['type']="update";
	 	}else {
	 		$row_order['type']="insert";
	 	}
	 	$runSql=$this->opCusomerTable($row_order);
	 	$sql = $this->objDao->runSql($runSql,"backup");
	 	$i++;
	 	if($i>50){
	 		break;
	 	}
 	}
			 	echo "更新Customer".$i."条数据,最新备份时间：".$orderMaxTime;
			 	exit;
		
   }
   function backupProductByAdmin(){
 	$time=$_REQUEST['times'];
 	$now=date('Y-m-d H:i:s',time());
    $this->objDao=new BackupDao();
    $orderBpo=$this->objDao->getBackupMaxUpTime("or_product");
 	//查询备份订单表最大的当前日期
 	$orderMaxTime=$orderBpo['op_date'];
 	$order_list=$this->objDao->getYuanDataByMaxUpTime("or_product",$orderMaxTime);
 	$i=0;
 	while ($row_order=mysql_fetch_array($order_list)) {
 		$this->objDao=new OrderDao();
	 	$order_backup_po=$this->objDao->getProductById($row_order['id'],"backup");
	 	if($order_backup_po){
	 		$row_order['type']="update";
	 	}else {
	 		$row_order['type']="insert";
	 	}
	 	$runSql=$this->opProductTable($row_order);
	 	$sql = $this->objDao->runSql($runSql,"backup");
	 	$i++;
	 	if($i>50){
	 		break;
	 	}
 	}
			 	echo "更新Product".$i."条数据,最新备份时间：".$orderMaxTime;
			 	exit;
		
   }
   function backupCaiwuByAdmin(){
 	$time=$_REQUEST['times'];
 	$now=date('Y-m-d H:i:s',time());
    $this->objDao=new BackupDao();
    $orderBpo=$this->objDao->getBackupMaxUpTime("or_caiwu");
 	//查询备份订单表最大的当前日期
 	$orderMaxTime=$orderBpo['op_date'];
 	$order_list=$this->objDao->getYuanDataByMaxUpTime("or_caiwu",$orderMaxTime);
 	$i=0;
 	while ($row_order=mysql_fetch_array($order_list)) {
 		$this->objDao=new OrderDao();
	 	$order_backup_po=$this->objDao->getCaiwuByCaiwuId($row_order['id'],"backup");
	 	var_dump($order_backup_po);
	 	if($order_backup_po){
	 		$row_order['type']="update";
	 	}else {
	 		$row_order['type']="insert";
	 	}
	 	$runSql=$this->opCaiwuTable($row_order);
	 	$sql = $this->objDao->runSql($runSql,"backup");
	 	$i++;
	 	if($i>50){
	 		
	 		break;
	 	}
 	}
			 	echo "更新".$i."条数据,最新备份时间：".$orderMaxTime;
			 	exit;
		
   }
   function backupOrderReturnByAdmin(){
 	$time=$_REQUEST['times'];
 	$now=date('Y-m-d H:i:s',time());
    $this->objDao=new BackupDao();
    $orderBpo=$this->objDao->getBackupMaxUpTime("or_order_return");
 	//查询备份订单表最大的当前日期
 	$orderMaxTime=$orderBpo['op_date'];
 	$order_list=$this->objDao->getYuanDataByMaxUpTime("or_order_return",$orderMaxTime);
 	$i=0;
 	while ($row_order=mysql_fetch_array($order_list)) {
 		$this->objDao=new OrderDao();
	 	$order_backup_po=$this->objDao->getOrderReturnById($row_order['id'],"backup");
	 	if($order_backup_po){
	 		$row_order['type']="update";
	 	}else {
	 		$row_order['type']="insert";
	 	}
	 	$runSql=$this->opOrderReturnTable($row_order);
	 	$sql = $this->objDao->runSql($runSql,"backup");
	 	$i++;
	 	if($i>50){
	 		break;
	 	}
 	}
			 	echo "更新".$i."条数据,最新备份时间：".$orderMaxTime;
			 	exit;
		
   }
   function opOrderTable($sqlModel){
       $sql="";
	   if($sqlModel['type']=='update'){
	   		$sql.="update or_order set
		pro_code='{$sqlModel['pro_code']}',ding_date='{$sqlModel['ding_date']}',pro_num={$sqlModel['pro_num']},pro_spec='{$sqlModel['pro_spec']}',pro_unit='{$sqlModel['pro_unit']}',
		pro_price={$sqlModel['pro_price']},pro_genNum={$sqlModel['pro_genNum']},pro_flag={$sqlModel['pro_flag']},customer_id={$sqlModel['customer_id']},order_jiner={$sqlModel['order_jiner']},
		mark='{$sqlModel['mark']}',custo_name='{$sqlModel['custo_name']}',zhekou={$sqlModel['zhekou']},op_date='{$sqlModel['op_date']}' where id={$sqlModel['id']}";
	   	}elseif($sqlModel['type']=='insert'){
	   		$sql.="insert into or_order 
		(id,order_no,pro_zubiao,pro_code,ding_date,jiao_date,pro_num,pro_spec,pro_unit,pro_price
		,pro_genNum,pro_flag,customer_id,order_jiner,mark,add_date,custo_name,zhekou,mujufei,op_date) 
		values ({$sqlModel['id']},{$sqlModel['order_no']},'{$sqlModel['pro_zubiao']}','{$sqlModel['pro_code']}','{$sqlModel['ding_date']}','',{$sqlModel['pro_num']},'{$sqlModel['pro_spec']}','{$sqlModel['pro_unit']}',
		{$sqlModel['pro_price']},{$sqlModel['pro_genNum']},{$sqlModel['pro_flag']},{$sqlModel['customer_id']},
		{$sqlModel['order_jiner']},'{$sqlModel['mark']}','{$sqlModel['add_date']}','{$sqlModel['custo_name']}',{$sqlModel['zhekou']},{$sqlModel['mujufei']},'{$sqlModel['op_date']}')";
		
	   	}
	   return $sql;
   }
   /**
    * `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) DEFAULT NULL,
  `custer_no` int(11) DEFAULT NULL,
  `custer_name` varchar(100) DEFAULT NULL,
  `chengjiaoer` float(11,2) DEFAULT '0.00',
  `tuikuanger` float(11,2) DEFAULT '0.00',
  `zhekou` float(10,2) DEFAULT NULL,
  `isChuku` int(2) DEFAULT '0',
  `ding_date` date DEFAULT NULL,
  `yunfei` float(11,2) DEFAULT '0.00',
  `shigongfei` float(11,2) DEFAULT '0.00',
  `baozhuangfei` float(11,2) DEFAULT '0.00',
  `jingbanren` int(11) DEFAULT NULL,
  `fapiao_type` varchar(100) DEFAULT NULL,
  `jiezhang_type` int(2) DEFAULT '0',
  `order_endDate` date DEFAULT NULL,
  `song_adress` varchar(1000) DEFAULT '',
  `song_lianxiren` varchar(100) DEFAULT '',
  `song_tel` varchar(100) DEFAULT '',
  `song_date` varchar(100) DEFAULT '',
  `op_name` varchar(100) DEFAULT NULL,
  `op_date` datetime DEFAULT NULL,
  `mark` varchar(1000) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
    * @param $order
    */
   function opOrderTotalTable($order){
       $sql="";
	   if($order['type']=='insert'){
	   		$sql.="insert into or_order_total 
		(id,order_no,custer_no,custer_name,chengjiaoer,tuikuanger,zhekou,isChuku,ding_date,yunfei,shigongfei,baozhuangfei,jingbanren,fapiao_type,jiezhang_type,order_endDate,song_adress,song_lianxiren,
		song_tel,song_date,op_name,op_date,mark,,update_time) 
		values ({$order['id']},{$order['order_no']},{$order['custer_no']},
		'{$order['custer_name']}',{$order['chengjiaoer']},{$order['tuikuanger']},{$order['zhekou']},{$order['isChuku']},
		'{$order['ding_date']}',{$order['yunfei']},{$order['shigongfei']},{$order['baozhuangfei']},{$order['jingbanren']},{$order['fapiao_type']},{$order['jiezhang_type']},'{$order['order_endDate']}','{$order['song_adress']}','{$order['song_lianxiren']}',
		'{$order['song_tel']}','{$order['song_date']}',
		'{$order['op_name']}','{$order['op_date']}','{$order['update_time']}')";
	   	}elseif($order['type']=='update'){
	   		$sql.="update  or_order_total 
		set custer_no={$order['custer_no']},custer_name='{$order['custer_name']}',chengjiaoer={$order['chengjiaoer']},tuikuanger={$order['tuikuanger']},
		zhekou={$order['zhekou']},yunfei = {$order['yunfei']}, shigongfei = {$order['shigongfei']}, baozhuangfei = {$order['baozhuangfei']},jingbanren={$order['jingbanren']} ,
		fapiao_type ={$order['fapiao_type']},jiezhang_type = {$order['jiezhang_type']},order_endDate = '{$order['order_endDate']}',song_adress = '{$order['song_adress']}',song_lianxiren = '{$order['song_lianxiren']}',
		'{$order['song_tel']}','{$order['song_date']}',
		op_name='{$order['op_name']}' ,op_date='{$order['op_date']}',update_time='{$order['update_time']}'
		where order_no={$order['order_no']}";
		
	   	}
	   return $sql;
   }
   function opCaiwuTable($caiwuPO){
       $sql="";
	   if($caiwuPO['type']=='insert'){
	   		$sql="insert into or_caiwu (id,order_no,yushoujine,weijiekuan,cust_no,cust_name,add_date,isjiekuan,caiwu_type,shoukuan_date,op_date)
   	values ({$caiwuPO['id']},{$caiwuPO['order_no']},{$caiwuPO['yushoujine']},{$caiwuPO['weijiekuan']},{$caiwuPO['cust_no']},
   	'{$caiwuPO['cust_name']}','{$caiwuPO['add_date']}',{$caiwuPO['isjiekuan']},{$caiwuPO['caiwu_type']},'{$caiwuPO['shoukuan_date']}','{$caiwuPO['op_date']}')";
	   	}elseif($caiwuPO['type']=='update'){
	   		$sql="update or_caiwu set order_no={$caiwuPO['order_no']},yushoujine={$caiwuPO['yushoujine']},weijiekuan={$caiwuPO['weijiekuan']},
	   		cust_no={$caiwuPO['cust_no']},cust_name='{$caiwuPO['cust_name']}',
	   		add_date='{$caiwuPO['add_date']}',isjiekuan={$caiwuPO['isjiekuan']},caiwu_type={$caiwuPO['caiwu_type']},
	   		shoukuan_date='{$caiwuPO['shoukuan_date']}',op_date='{$caiwuPO['op_date']}' where id={$caiwuPO['id']}";
	   	}
	   return $sql;
   }
   function opCusomerTable($customer){
       $sql="";
	   if($customer['type']=='update'){
	   		$sql="update or_customer 
		set custo_name='{$customer['custo_name']}',op_id={$customer['op_id']},custo_type={$customer['custo_type']},custo_discount={$customer['custo_discount']},adress='{$customer['adress']}',
		post_no='{$customer['post_no']}',telphone_no='{$customer['telphone_no']}',custo_mail='{$customer['custo_mail']}',
		accounter_name='{$customer['accounter_name']}',bank_name='{$customer['bank_name']}',
		total_money={$customer['total_money']},custo_level='{$customer['custo_level']}',custoHaed_name='{$customer['custoHaed_name']}',
		remarks='{$customer['remarks']}',custo_info='{$customer['custo_info']}',bank_no='{$customer['bank_no']}',op_date='{$customer['op_date']}' where id={$customer['id']}";
	   	}elseif($customer['type']=='insert'){
	   		$sql="insert into or_customer 
		(id,custo_name,op_id,custo_type,custo_discount,adress,post_no,telphone_no,bank_no,custo_mail,bank_name,total_money,custo_level,custoHaed_name,add_time,remarks,custo_info,op_date) 
		values ({$customer['id']},'{$customer['custo_name']}',{$customer['op_id']},{$customer['custo_type']},{$customer['custo_discount']},
		'{$customer['adress']}','{$customer['post_no']}','{$customer['telphone_no']}','{$customer['bank_no']}',
		'{$customer['custo_mail']}','{$customer['bank_name']}',{$customer['total_money']},
		'{$customer['custo_level']}','{$customer['custoHaed_name']}','{$customer['add_time']}','{$customer['remarks']}','{$customer['custo_info']}','{$customer['op_date']}')";
		
	   	}
	   return $sql;
   }
   function opProductTable($product){
       $sql="";
	   if($product['type']=='update'){
	   		$sql="update or_product 
		set pro_code='{$product['pro_code']}',pro_spec='{$product['pro_spec']}',pro_unit='{$product['pro_unit']}',pro_price={$product['pro_price']},pro_flag={$product['pro_flag']},
		pro_group='{$product['pro_group']}',op_date='{$product['op_date']}' where id={$product['id']}";
	   	}elseif($product['type']=='insert'){
	   		$sql="insert into or_product 
		(id,pro_code,pro_spec,pro_unit,pro_price,pro_flag,pro_group,add_time,op_date) 
		values ({$product['id']},'{$product['pro_code']}','{$product['pro_spec']}','{$product['pro_unit']}',{$product['pro_price']},{$product['pro_flag']},
		'{$product['pro_group']}','{$product['add_time']}','{$product['op_date']}')";
	   	}
	   return $sql;
   }
   function opOrderReturnTable($order){
       $sql="";
	   if($order['type']=='insert'){
	   		$sql.="insert into or_order_return 
		(order_id,order_no,pro_code,return_date,pro_num,pro_spec,pro_unit,pro_price
		,pro_genNum,pro_flag,customer_id,return_jiner,custo_name,op_date) 
		values ({$order['order_id']},{$order['order_no']},'{$order['pro_code']}','{$order['return_date']}',{$order['pro_num']},'{$order['pro_spec']}','{$order['pro_unit']}',
		{$order['pro_price']},{$order['pro_genNum']},{$order['pro_flag']},{$order['customer_id']},
		{$order['return_jiner']},'{$order['custo_name']}','{$order['op_date']}')";
	   	}elseif($order['type']=='update'){
	   		$sql.="";
	   	}
	   return $sql;
   }
}


$objModel = new BackupAction($actionPath);
$objModel->dispatcher();



?>

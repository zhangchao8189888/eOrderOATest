<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class OrderDao extends BaseDao
{
 
    /**
     *
     * @return ProductDao
     */
    function OrderDao()
    {
        parent::BaseDao();
    }
    /**
     * 添加产品信息
     * 
CREATE TABLE `or_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_code` varchar(200) NOT NULL,
  `ding_date` date DEFAULT NULL,
  `jiao_date` date DEFAULT NULL,
  `pro_num` float(11,2) DEFAULT NULL,
  `pro_spec` varchar(200) DEFAULT NULL,
  `pro_unit` varchar(50) DEFAULT NULL,
  `pro_price` float(10,2) DEFAULT NULL,
  `pro_genNum` int(10) DEFAULT NULL,
  `pro_flag` int(2) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_jiner` float(11,2) DEFAULT NULL,
   `mark` varchar(1000) DEFAULT NULL,
    `add_date` date DEFAULT NULL,
    `custo_name` varchar(100) DEFAULT NULL,
    `zhekou` float(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

     */

    function addOrder($order){
		if (empty($order['ding_date'])) {
			$order['ding_date']=date("Y-m-d");
		}
    	
		$sql="insert into or_order 
		(order_no,pro_id,pro_code,ding_date,jiao_date,pro_num,pro_type,pro_unit,pro_price
		,price,pro_flag,customer_id,order_jiner,jingban_id,customer_type,mark,add_date,custo_name,zhekou,op_date,real_price,real_order_jiner)
		values ({$order['order_no']},{$order['pro_id']},'{$order['pro_code']}','{$order['ding_date']}','{$order['jiao_date']}',{$order['pro_num']},
		'{$order['pro_type']}','{$order['pro_unit']}',
		{$order['pro_price']},{$order['price']},1,{$order['customer_id']},
		{$order['order_jiner']},{$order['jingban_id']},{$order['customer_type']},'{$order['mark']}',now(),'{$order['custo_name']}',{$order['zhekou']},now(),{$order['real_price']},{$order['real_order_jiner']})";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }
    function addOrderDel($order){
		if (empty($order['ding_date'])) {
			$order['ding_date']=date("Y-m-d");
		}

		$sql="insert into or_order_del
		(order_no,pro_id,pro_code,ding_date,jiao_date,pro_num,pro_type,pro_unit,pro_price
		,price,pro_flag,customer_id,order_jiner,jingban_id,customer_type,mark,add_date,custo_name,zhekou,op_date)
		values ({$order['order_no']},{$order['pro_id']},'{$order['pro_code']}','{$order['ding_date']}','{$order['jiao_date']}',{$order['pro_num']},
		'{$order['pro_type']}','{$order['pro_unit']}',
		{$order['pro_price']},{$order['price']},1,{$order['customer_id']},
		{$order['order_jiner']},{$order['jingban_id']},{$order['customer_type']},'{$order['mark']}',now(),'{$order['custo_name']}',{$order['zhekou']},now())";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }
    function addOrderReturn($order){
		if (empty($order['ding_date'])) {
			$order['ding_date']=date("Y-m-d");
		}
        $sql="insert into or_order_return
		(order_no,pro_id,pro_code,return_date,pro_num,pro_type,pro_unit,
		pro_price,price,pro_flag,customer_id,return_jiner,custo_name,zhekou,op_date)
		values ({$order['order_no']},{$order['pro_id']},'{$order['pro_code']}',now(),{$order['pro_num']},
		'{$order['pro_type']}','{$order['pro_unit']}',
		{$order['pro_price']},{$order['price']},{$order['pro_flag']},{$order['customer_id']},
		{$order['return_jiner']},'{$order['custo_name']}',{$order['zhekou']},now())";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }

    function updateOrderForReturnJine($tuikuane,$orderId){
    	$sql="update or_order set tuikuane=$tuikuane,op_date=now() where id=$orderId";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function updateOrder($order){
		$sql="update or_order set pro_num={$order['pro_num']},order_jiner={$order['order_jiner']},
		mark='{$order['mark']}',op_date=now() where id={$order['id']}";
        //echo $sql;
        $result=$this->g_db_query($sql);
		return $result;
    }
    function saveTotalOrderTabel($order){
        if (empty($order["room_id"])) {
            $order["room_id"] = 0;
        }
    	$now=date("Y-m-d H:i:s");
    	$sql="insert into or_order_total 
		(order_no,custer_no,custer_name,chengjiaoer,realChengjiaoer,zhekou,ding_date,pay_type,
		pay_status,cash_val,jingbanren,zhidanren_name,op_id,add_time,mark,isOff,update_time,coupon_val,coupon_type,room_id)
		values ({$order['order_no']},{$order['custer_no']},'{$order['custer_name']}',
		{$order['chengjiaoer']},{$order['realChengjiaoer']},{$order['zhekou']},now(),
		{$order['pay_type']},{$order['pay_status']},{$order['cash_val']},
		{$order['jingbanren']},'{$order['zhidanren_name']}',{$order['op_id']},now(),'{$order['mark']}',{$order['isOff']},now(),{$order['coupon_val']},{$order['coupon_type']},{$order['room_id']})";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }

    function saveTotalOrderRecharge($order){

    	$sql="insert into or_order_total 
		(order_no,custer_no,custer_name,chengjiaoer,realChengjiaoer,ding_date,zhidanren_name,op_id,add_time,update_time,order_type,pay_status)
		values ({$order['order_no']},{$order['custer_no']},'{$order['custer_name']}',
		{$order['chengjiaoer']},{$order['realChengjiaoer']},'{$order['ding_date']}',
		'{$order['zhidanren_name']}',{$order['op_id']},'{$order['add_time']}','{$order['update_time']}',1,1)";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }

    function saveTotalOrderDelTabel($order){
    	$now=date("Y-m-d H:i:s");
    	$sql="insert into or_order_total_del
		(order_no,custer_no,custer_name,chengjiaoer,realChengjiaoer,zhekou,ding_date,pay_type,
		pay_status,cash_val,jingbanren,zhidanren_name,op_id,add_time,mark,isOff,update_time)
		values ({$order['order_no']},{$order['custer_no']},'{$order['custer_name']}',
		{$order['chengjiaoer']},{$order['realChengjiaoer']},{$order['zhekou']},now(),
		{$order['pay_type']},{$order['pay_status']},{$order['cash_val']},
		{$order['jingbanren']},'{$order['zhidanren_name']}',{$order['op_id']},now(),'{$order['mark']}',{$order['isOff']},now())";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }
    function saveTotalOrderReturn($order){

    	$sql="insert into or_return_total
		(order_no,customer_id,customer_name,return_jin,return_real_jin,order_type,op_id,mark,pay_type,
		add_time,op_time)
		values ({$order['order_no']},{$order['customer_id']},'{$order['customer_name']}',
		{$order['return_jin']},{$order['return_real_jin']},{$order['order_type']},{$order['op_id']},
		'{$order['mark']}',{$order['pay_type']},now(),now())";
        //echo $sql;
		$result=$this->g_db_query($sql);
		return $result;
    }
    function updateTotalOrderTabel($order){

    	$sql="update  or_order_total 
		set chengjiaoer={$order['chengjiaoer']},realChengjiaoer={$order['realChengjiaoer']},isOff={$order['isOff']},update_time=now()
		where order_no={$order['order_no']}";
		$result=$this->g_db_query($sql);
        //echo $sql;
		return $result;
    }
    function updateTotalOrderDetail($order){

    	$sql="update  or_order_total
		set ";
        if ($order['payType']) {
            $sql.=" pay_type = {$order['payType']} ,";
        }
        if ($order['mark']) {
            $sql.=" mark = '{$order['mark']}',";
        }
        if ($order['ding']) {
            $sql.=" ding_date = '{$order['ding']}',";
        }
        if ($order['payStatus']) {
            $sql.=" pay_status = '{$order['payStatus']}',";
        }
        if ($order['realChengjiaoer']) {
            $sql.=" realChengjiaoer = '{$order['realChengjiaoer']}',";
        }
        $sql.=" update_time=now()
		where order_no={$order['oId']}";
		$result=$this->g_db_query($sql);
        //echo $sql;
        if (!$result) {
            return $result;
        } else {

            return $sql;
        }
    }
    function updateOrderChengjiaoE($order) {
    	$now=date("Y-m-d H:i:s");
    	$sql="update  or_order_total 
		set chengjiaoer={$order['chengjiaoer']} where order_no={$order['order_no']}";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function updateTotalOrderForReturnJin($returnJin,$orderNo){
    	$sql="update  or_order_total 
		set tuikuanger=$returnJin,update_time=now()  where order_no={$orderNo}";
		$result=$this->g_db_query($sql);
		return $result;
    }
 /**
    * 删除订单信息通过ID
    * @param $orId
    */
   function delOrderById($orId){
   	    $sql="delete   from or_order where id=$orId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function getOrderList($orderDate=NULL,$customerName=NULL,$productCode=NULL,$orderNo=NULL){
   	    $sql="select *  from or_order where 1=1";
   	    $todayOne=date("Y")."-".date("m")."-1";
   	    if($orderDate==1){
   	    	$today=date("Y-m-d");
   	    	$sql.=" and add_date  ='$today'";
   	    }elseif($orderDate==2){
   	    	
   	    	$sql.=" and  add_date >= '$todayOne'";
   	    	
   	    }elseif($orderDate==3){
   	    	$sql.=" and add_date  <'$todayOne'";
   	    }
   	    if($customerName){
   	    	$sql.=" and custo_name like '%".$customerName."%'";
   	    }
   	    if($productCode){
   	    	$sql.=" and pro_code like '%".$productCode."%'";
   	    }
        if($orderNo){
   	    	$sql.=" and order_no=$orderNo ";
   	    }
        $sql.=" order by pro_code ";echo $sql;
   	    $result=$this->g_db_query($sql);
		return $result;
   	
   }
    function getOrderDetailListByWhereArray($where) {
        $sql="select *  from or_order where 1=1";
        if (!empty($where['pro_type'])) {
            $sql.= " and  pro_type = {$where['pro_type']}";
        }

        if(!empty($where['fromDate'])){

           $sql.=" and  ding_date >= '{$where['fromDate']}'";

       }
       if(!empty($where['toDate'])){

           $sql.=" and  ding_date <= '{$where['toDate']}'";

       }
        $result=$this->g_db_query($sql);
        return $result;
    }
   function getOrderById($orId){
   	    $sql="select or_order.*,or_product.pro_name,or_product.e_name   from or_order,or_product where or_order.pro_id = or_product.id and  order_no=$orId  order by id";
   	    $result=$this->g_db_query($sql);//echo $sql;
		return $result;
   }
   function getOrderReturnByReturnNo($orId) {
       $sql="select or_order_return.*,or_product.pro_name   from or_order_return,or_product where or_order_return.pro_id = or_product.id and order_no=$orId  order by id";
       $result=$this->g_db_query($sql);
       return $result;
   }
   function getSumOrderJinByOrderNo($orNo) {
   	    $sql="select sum(order_jiner) as sumJin ,sum(mujufei) as sumMujufei from or_order where order_no=$orNo";
   	    $result=$this->g_db_query($sql);
   	    $sumJinSqlResult = mysql_fetch_array($result);
		return $sumJinSqlResult['sumJin']+$sumJinSqlResult['sumMujufei'];
   }
   function getOrderByOrderId($orId,$db=null){
        $sql="select *   from or_order where id=$orId";
   	    $result=$this->g_db_query($sql,$db);
		return mysql_fetch_array($result);
   }
   function getOrderTotalByOrderNo($orId){
        $sql="select *   from or_order_total where order_no=$orId";
   	    $result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
   }
   function getOrderTotalByOrderTotalId($orId,$db=null){
   	    $sql="select *   from or_order_total where id=$orId  and is_refund = 0";
   	    $result=$this->g_db_query($sql,$db);
		return mysql_fetch_array($result);
   }
   function getReturnDate($orId){
   	    $sql="select distinct  return_date   from or_order_return  where order_no=$orId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function getOrderReturnSumNum($orId){
   	    $sql="select sum(pro_num)  as sumReNum from or_order_return  where order_id=$orId ";
   	    $result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
   }
   function searchReturnByDateOrderNo($orId,$date){
   	    $sql="select *   from or_order_return  where order_no=$orId and return_date='$date'";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function searchReturnListByDate($dateFrom,$dateTo){
   	    $sql="select * ,sum(return_jiner )  as sumJin from or_order_return  where  return_date>='$dateFrom'  and return_date<='$dateTo' group by order_no";
   	    $result=$this->g_db_query($sql); 
   	    
		return $result;
   }
function searchReturnByOrderNo($orId){
   	    $sql="select *  ,sum(return_jiner ) as sumJin from or_order_return  where order_no=$orId  group by order_no";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function  deleteReturnById($returnId){
   	    $sql="delete    from or_order_return  where id=$returnId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function updateInvoiceOrder($order){
   	$date=date("Y-m-d");
   	   $sql="update or_order_total set yunfei={$order['yunfei']},shigongfei={$order['shigongfei']},baozhuangfei={$order['baozhuangfei']},
   	   fapiao_type='{$order['fapiao_type']}',order_endDate='$date',chengjiaoer='{$order['chengjiaoer']}',update_time=now()  where order_no={$order['order_no']}";
   	    $result=$this->g_db_query($sql);
		return $result;
   	
   	
   }
   function updateOrderMujufei($orderId,$mujufei){
   	  $sql="update or_order  set mujufei=$mujufei,op_date=now() where id=$orderId ";
   	    $result=$this->g_db_query($sql);
		return $result;
   	
   }
   function updateOrderTotalSongList($adress,$lianxiren,$tel,$songDate,$orderId,$mark=""){
	  $sql="update or_order_total  set song_adress='$adress',song_lianxiren='$lianxiren',song_tel='$tel',song_date='$songDate',mark='$mark',update_time=now() where order_no=$orderId ";
   	    $result=$this->g_db_query($sql);
		return $result;
   
   
   }
    function getOrderTotalPage($where,$order = array(),$startIndex = 0,$pagesize = 0,$containRefund = 0){
        $sql="select *  from or_order_total where 1=1 $where";
        if (!$containRefund) {
            $sql .= " and is_refund = 0";
        }
        if (!empty($order)) {
            $sql.=" order by {$order['by']} {$order['up']}";
        }
        //echo $sql;exit;
        if ($startIndex > 0 || $pagesize > 0 ) {
            $sql .= " limit $startIndex,$pagesize";
        }//echo $sql;exit;
        $result=$this->g_db_query($sql);
        return $result;


    }
    function getOrderReturnTotalPage($where,$order,$startIndex = 0,$pagesize = 0){
        $sql="select *  from or_return_total where 1=1 $where";

        $sql.=" order by {$order['by']} {$order['up']} ";
        if ($startIndex > 0 || $pagesize > 0 ) {
            $sql .= " limit $startIndex,$pagesize";
        }
        $result=$this->g_db_query($sql);
        return $result;

    }
    function getOrderReturnTotalByNo ($order_no) {
        $sql="select *  from or_return_total where order_no = $order_no";

        $result=$this->g_db_query($sql);//echo $sql;exit;
        return mysql_fetch_array($result);
    }
    function getOrderListByDates($dateFrom,$dateTo) {
        $sql="select * from or_order_total where is_refund = 0 and  ding_date >= '{$dateFrom}' and ding_date <= '{$dateTo}'";

        $result=$this->g_db_query($sql);
        //echo $sql;
        return $result;
    }
    function getOrderReturnListByDates($dateFrom,$dateTo) {
        $sql="select * from or_return_total where add_time >= '{$dateFrom}' and add_time <= '{$dateTo}'";

        $result=$this->g_db_query($sql);
        //echo $sql;
        return $result;
    }
    function getOrderTotalListByMonth() {
        $sql="select count(order_no) as order_count, DATE_FORMAT(ding_date,'%Y-%m') as month,
sum(realChengjiaoer) as money  from or_order_total where  is_refund = 0  group by month
order by month ";

        $result=$this->g_db_query($sql);
        return $result;
    }
    function getReturnTotalListByMonth() {
        $sql="select count(order_no) as return_count, DATE_FORMAT(add_time,'%Y-%m') as month,
sum(return_real_jin) as return_money  from or_return_total group by month
order by month ";

        $result=$this->g_db_query($sql);
        return $result;
    }
   function getOrderTotal($orderDate=NULL,$customerName=NULL,$productCode=NULL,$orderNo=NULL){
   	    $sql="select *  from or_order_total where 1=1 and is_refund = 0 ";
   	    $todayOne=date("Y")."-".date("m")."-1";
   	    if($orderDate==1){
   	    	$today=date("Y-m-d");
   	    	$sql.=" and ding_date  ='$today'";
   	    }elseif($orderDate==2){
   	    	
   	    	$sql.=" and  ding_date >= '$todayOne'";
   	    	
   	    }elseif($orderDate==3){
   	    	$sql.=" and ding_date  <'$todayOne'";
   	    }
   	    if($customerName){
   	    	$sql.=" and custer_name like '%".$customerName."%'";
   	    }
   	    if($productCode){
   	    	$sql.=" and pro_code like '%".$productCode."%'";
   	    }
        if($orderNo){
   	    	$sql.=" and order_no=$orderNo ";
   	    }
   	    $result=$this->g_db_query($sql);
		return $result;
   	
   	
   }
   function searchOrderTotalListByJingbanren($jingbanren){
   	$sql="select * from or_order_total  where jingbanren=$jingbanren ";
   	$result=$this->g_db_query($sql);
		return $result;
   	
   }
   function searchOrderTotalListByOrderNo($order_no){
       $sql="select *  from or_order_total where order_no = $order_no";
       $result=$this->g_db_query($sql);
       return mysql_fetch_array($result);
   }
    function searchOrderTotalListByAddress($where,$listwhere) {
        $sql = " select *  from or_order_total  where song_adress  like '%{$where["address"]}%'  order by  order_no";
        $sql.= $listwhere;
        $result=$this->g_db_query($sql);
        return $result;
    }
   function searchOrderTotalListByType($where){
   	/**
   	 *  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) DEFAULT NULL,
  `custer_no` int(11) DEFAULT NULL,
  `custer_name` varchar(100) DEFAULT NULL,
  `chengjiaoer` float(11,2) DEFAULT '0.00',
  `tuikuanger` float(11,2) DEFAULT '0.00',
  `zhekou` float(10,2) DEFAULT NULL,
  `isChuku` int(2) DEFAULT NULL,
  `ding_date` date DEFAULT NULL,
  `yunfei` float(11,2) DEFAULT '0.00',
  `shigongfei` float(11,2) DEFAULT '0.00',
  `baozhuangfei` float(11,2) DEFAULT '0.00',
  `jingbanren` int(11) DEFAULT NULL,
  `fapiao_type` varchar(100) DEFAULT NULL,
  `jiezhang_type` int(2) DEFAULT '0',
   	 * @var unknown_type
   	 */
   	   $sql="select *  from or_order_total where 1=1 and is_refund = 0 ";
   	   if(isset($where['chuku'])){
   	   	  $sql.=" and isChuku  ={$where['chuku']} ";
   	   }
   	   if($where['jingbanren']){
   	   	  $sql.=" and jingbanren  ={$where['jingbanren']} ";
   	   }
       if($where['jiezhang_type']=="1"||$where['jiezhang_type']=="0"){
   	   	  $sql.=" and jiezhang_type  ={$where['jiezhang_type']} ";
   	   }
       if($where['custoId']){
   	   	  $sql.=" and custer_no  ={$where['custoId']} ";
   	   }
       /*if(!empty($where['fromDate'])){

           $sql.=" and  ding_date >= '{$where['fromDate']}'";

       }
       if(!empty($where['toDate'])){

           $sql.=" and  ding_date <= '{$where['toDate']}'";

       }*/
   	   //$sql.=" order by  order_no ";
       if($where['cusType']){
   	   	  $sql="select *  from or_order_total as oot,or_customer as oc where  oot.is_refund = 0 oc.id=oot.custer_no and oc.custo_type={$where['cusType']} and  
   	   	   oot.ding_date>='{$where['datefrom']}' and  oot.ding_date<='{$where['dateto']}' ";
	         if($where['orderbyType']==1){
	   	    	$sql.=" order by  custer_name ";
	   	    }elseif($where['orderbyType']==2){
	   	    	$sql.=" order by  chengjiaoer ";
	   	    }elseif($where['orderbyType']==3){
	   	    	$sql.=" order by  order_no ";
	   	    }elseif($where['orderbyType']==4){
	   	    	$sql.=" order by  ding_date ";
	   	    }

   	   	  $result=$this->g_db_query($sql);
          return $result;
   	   }
       if($where['jingbanType']){
   	   	 $sql.=" and jingbanren  in(select jingbanren_no from or_jingbanren where jingbanren_group='{$where['jingbanType']}')";
   	   	
   	   }
   	    $sql.=" and ding_date>='{$where['datefrom']}' and  ding_date<='{$where['dateto']}'";
   	    if($where['orderbyType']==1){
   	    	$sql.=" order by  custer_name ";
   	    }elseif($where['orderbyType']==2){
   	    	$sql.=" order by  chengjiaoer ";
   	    }elseif($where['orderbyType']==3){
   	    	$sql.=" order by  order_no ";
   	    }elseif($where['orderbyType']==4){
   	    	$sql.=" order by  ding_date ";
   	    }else{
   	    	$sql.=" order by  order_no ";
   	    }
       //echo $sql;exit;
   	   $result=$this->g_db_query($sql);
       return $result;
   }
   function getOrderTotalByCustNo($custoNo){
   	   $sql="select *  from or_order_total where custer_no =$custoNo and is_refund = 0";
   	$result=$this->g_db_query($sql);
    return $result;
   	
   }
   function  searchOrderListByType($where){
   	  $sql=" select distinct oo.id as order_id,op.pro_group,oo.*
   	   	   from or_order as oo,or_product  as op 
   	   	   where   op.pro_code=oo.pro_code ";
       if($where['proZubiao']){
   	   	  
   	   	  $sql.=" and op.pro_group='{$where['proZubiao']}' ";
   	   	   
   	   	  
   	   }elseif($where['proNo']){
        $sql.=" and op.pro_code='{$where['proNo']}' ";
   	   }
   	   $sql.=" and oo.add_date>='{$where['datefrom']}' and  oo.add_date<='{$where['dateto']}' ";
       if($where['orderbyType']==1){
   	    	$sql.=" order by  custo_name ";
   	    }elseif($where['orderbyType']==2){
   	    	$sql.=" order by  order_jiner ";
   	    }elseif($where['orderbyType']==3){
   	    	$sql.=" order by  order_no ";
   	    }elseif($where['orderbyType']==4){
   	    	$sql.=" order by  ding_date ";
   	    }
   	   
   	   $result=$this->g_db_query($sql);
          return $result;
   }
   function getOrderTotalListByFanwei($type,$from,$to){
   	$sql="select *  from or_order_total  where 1=1 and is_refund = 0";
   	if($type==0){
   		$sql.=" and  ding_date >= '$from' and ding_date <= '$to' ";
   	}else if($type==1){
   		$sql.=" and  order_no >= $from and order_no <= $to ";
   	}
   	$sql.=" order by order_no";
   	$result=$this->g_db_query($sql);
    return $result;
   	
   }
   function getOrderListByIds($orderNos){
   	$sql="select *  from or_order_total where order_no in ( $orderNos )";
   	$result=$this->g_db_query($sql);
    return $result;
   }
   function delOrderTotalById($orderId){
   	$sql="delete from or_order_total where id =$orderId";
   	$result=$this->g_db_query($sql);
    return $result;
   }
   function orderCheck($order_no,$checktype){
   	$sql="update  or_order_total set jiezhang_type=$checktype,update_time=now() where order_no=$order_no";
   	$result=$this->g_db_query($sql);
    return $result;
   	
   }
   function orderChuku($order_no,$checktype){
   	$sql="update  or_order_total set isChuku=$checktype,update_time=now() where order_no=$order_no";
   	$result=$this->g_db_query($sql);
    return $result;
   	
   }
   function getOrderReturnByOrderId($orderId){
   	$sql="select or_order_return.* ,or_product.pro_name,or_product.e_name   from or_order_return,or_product where or_order_return.pro_id = or_product.id and  order_no=$orderId  order by id";
   	$result=$this->g_db_query($sql); //echo $sql;
    return $result;
   }
    function getOrderReturnById($Id,$db=null){
   	$sql="select * from  or_order_return where id=$Id";
   	$result=$this->g_db_query($sql,$db);
    return mysql_fetch_array($result);
   }
   
   /**
    * 财务dao
    */
   /**
    *  
 CREATE TABLE `or_caiwu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) DEFAULT NULL,
  `yushoujine` float(11,2) DEFAULT NULL,
  `weijiekuan` float(11,2) DEFAULT NULL,
  `cust_no` int(11) DEFAULT NULL,
  `cust_name` varchar(500) DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  `shoukuan_date` date DEFAULT NULL,
  `isjiekuan` int(2) DEFAULT NULL,
  `op_id` int(10) DEFAULT NULL,
    * @param $orderNo
    */
   function getCaiwuListByOrderNo($orderNo,$caiwuType=1){
   	$sql="select * from or_caiwu where order_no=$orderNo  and caiwu_type=$caiwuType";
   	$result=$this->g_db_query($sql);
   	return mysql_fetch_array($result);
   }
   function getCaiwuListByCustNo($custNo){
   	$sql="select * from or_caiwu where cust_no=$custNo and caiwu_type=1";
   	$result=$this->g_db_query($sql);
   	return $result;
   }
   function getCaiwuByCaiwuId($custId,$db=null){
   	$sql="select * from or_caiwu where id=$custId";
   	$result=$this->g_db_query($sql,$db);
   	return mysql_fetch_array($result);
   }
   function addCaiwu($caiwuPO,$caiwuType=NULL){
   	$sql="insert into or_caiwu (order_no,yushoujine,weijiekuan,cust_no,cust_name,add_date,isjiekuan,caiwu_type,shoukuan_date,op_date)
   	values ({$caiwuPO['order_no']},{$caiwuPO['yushoujine']},{$caiwuPO['weijiekuan']},{$caiwuPO['cust_no']},'{$caiwuPO['cust_name']}',now(),{$caiwuPO['isjiekuan']},
   	";
   	if($caiwuType){
   		$sql.="  $caiwuType,";
   	}else{
   		$sql.="1 ,";
   	}
   	if($caiwuPO['shoukuan_date']){
   	$sql.=" '{$caiwuPO['shoukuan_date']}',";
   }else{
   	$sql.=" now(),";
   	}
   	$sql.="now())";
   	$result=$this->g_db_query($sql);
   	return $result;
   }
   function updateCaiwuByOrderNo($caiwuPO,$caiwuType=1){
   	$sql="update or_caiwu set yushoujine={$caiwuPO['yushoujine']},
   	weijiekuan={$caiwuPO['weijiekuan']},isjiekuan={$caiwuPO['isjiekuan']} ,shoukuan_date=now(),op_date=now() where  order_no={$caiwuPO['order_no']} and caiwu_type=$caiwuType";
   	$result=$this->g_db_query($sql);
   	return $result;
   	
   }
   function updateCaiwuJiekuanTypeByOrderNo($isJiekuan,$orderNo,$caiwuType=NULL){
   	 $sql="update or_caiwu set isjiekuan=$isJiekuan,shoukuan_date=now(),op_date=now()  where  order_no=$orderNo ";
   if($caiwuType){
   		$sql.=" and caiwu_type=$caiwuType ";
   	}else{
   		$sql.=" and caiwu_type=1 ";
   	}
   	$result=$this->g_db_query($sql);
   	return $result;
   	
   }
   function updateCaiwuYushouBuOrderNo($yushou,$weijie,$orderNo,$caiwuType=1){
   	$sql="update or_caiwu set yushoujine=$yushou,weijiekuan=$weijie,shoukuan_date=now(),op_date=now()  where  order_no=$orderNo and caiwu_type=$caiwuType";
   	$result=$this->g_db_query($sql);
   	return $result;
   }
   function deleteCaiwuByOrderNo($orderNo){
   	$sql="delete  from or_caiwu where order_no=$orderNo";
   	$result=$this->g_db_query($sql);
   	return $result;
   	
   }
   function getUpdateOrderNoByOpTime(){
   	$sql="select order_no from or_order_total where and is_refund = 0 order by op_date desc limit 1 ";
   	$result=$this->g_db_query($sql);
   	return mysql_fetch_array($result);
   }
    function getOrderTotalByRang ($where) {
        $sql = "select *  from or_order_total where  ding_date>='{$where['start_date']}' and ding_date <= '{$where['end_date']}' and is_refund = 0";
        if ($where['keyword']) {
            $sql.= " and  custer_name like '%{$where['keyword']}%'";
        }
        $sql .= " order by ding_date , order_no "; //echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getOrderInfoListByRang ($where) {
        $sql="select *  from or_order where ding_date>='{$where['start_date']}' and ding_date <= '{$where['end_date']}'";
        if ($where['pro_type']) {
            $sql.= " and  pro_type = {$where['pro_type']}";
        }
        if ($where['customer_jingbanren']) {
            $sql.= " and  jingban_id = {$where['customer_jingbanren']}";
        }
        if ($where['searchKey']) {
            $sql.= " and  pro_code like '%{$where['searchKey']}%'";
        }

        if ($where['keyword']) {
            $sql.= " and  pro_code like '%{$where['keyword']}%'";
        }

        if ($where['customer_type']) {
            $sql.= " and  customer_type like '%{$where['customer_type']}%'";
        }
        if (!empty($where['customer_ids'])) {
            $sql.= " and  customer_id in ({$where['customer_ids']}) ";
        }
        //print_r($where);
        //echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateOrderRefundByOrderNO ($orderNo) {
        global $orderType;
        $sql = "update or_order_total set is_refund = 1 where order_no = '{$orderNo}' "; //echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }
}
?>

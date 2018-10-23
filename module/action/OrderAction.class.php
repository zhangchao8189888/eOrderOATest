<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/CustomerDao.class.php");
require_once("module/dao/ProductDao.class.php");
require('tools/fpdf16/chinese-unicode.php'); 
require_once('tools/tcpdf/config/lang/eng.php');
require_once('tools/tcpdf/tcpdf.php');
require_once("tools/JPagination.php");
require_once("tools/printClass.php");
class OrderAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
    const PAY_TYPE_VIP = 2;
    const PAY_TYPE_FREE = 10;
    const PAY_TYPE_CARD_FREE = 18;
    const PAY_TYPE_PINJIAN_CARD = 19;
    const PAY_STATUS_TRUE = 1;
    const PAY_STATUS_FALSE = 0;
 function OrderAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new OrderForm();
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
            case "toOrderPage" :
                $this->toOrderPage();
                break;
            case "toAdd" :
                $this->toAddOrder();
                break;
            case "orderDel" :
                $this->orderDel();
                break;
            case "getCustomerListByName" :
                $this->getCustomerListByName();
                break;
            case "getProListByName":
                $this->getProListByName();
                break;
            case "getProById";
                $this->getProById();
                break;
            case "getCustoById":
            	$this->getCustoById();
                break;
            case "addOrder":
            	$this->addOrder();
                break;
            case "saveOrderListJson":
            	$this->saveOrderListJson();
                break;
            case "getOrderPDF":
            	$this->getOrderPDF();
                break;
            case "delOrder":
            	$this->delOrder();
                break;
            case "toPrintOrder":
            	$this->toPrintOrder();
                break;
            case "getOrderList":
            	$this->getOrderList();
                break;
            case "orderPrint":
                $this->orderPrintTest();
                break;
            case "toPrintInvoice":
                $this->toPrintInvoice();
                break;
            case "getOrderListForInvoice":
                $this->getOrderListForInvoice();
                break;
            case "invoicePrint":
                $this->invoicePrint();
                break;
            case "orderSearch":
                $this->orderSearch();
                break;
            case "getOrderByIdAjax":
                $this->getOrderByIdAjax();
                break;
            case "getOrderById":
                $this->getOrderById();
                break;
            case "updateOrder":
                $this->updateOrder();
                break;
            case "getOrderListByOrderNo":
                $this->getOrderListByOrderNo();
                break;
            case "toOrderCheck":
                $this->toOrderCheck();
                break;
            case "getOrderCheckList":
            	$this->getOrderCheckList();
                break;
            case "orderCheck":
            	$this->orderCheck();
                break;
            case "orderChuku":
            	$this->orderChuku();
                break;
            case "orderCheckList":
            	$this->orderCheckList();
                break;
            case "orderChukuList":
            	$this->orderChukuList();
                break;
            case "toOrderChuku":
            	$this->toOrderChuku();
                break;
            case "getOrderChukuList":
            	$this->getOrderChukuList();
            	break;
            case "toOrderReturn":
            	$this->toOrderReturn();
            	break;
            case "orderReturn":
            	$this->orderReturn();
            	break;
            case "orderReturnAll":
            	$this->orderReturnAll();
            	break;
            case "toOrderReturnCancel":
            	$this->toOrderReturnCancel();
            	break;
            case "searchOrderReturn":
            	$this->searchOrderReturn();
            	break;
            case "getReturnDate":
            	$this->getReturnDate();
            	break;
            case "cancelReturn":
            	$this->cancelReturn();
            	break;
            case "toSearchOrderList":
            	$this->toSearchOrderList();
            	break;
            case "toSearchOrderListAddress":
            	$this->toSearchOrderListAddress();
            	break;
            case "searchOrderListByAddress":
            	$this->searchOrderListByAddress();
            	break;
            case "searchOrderList":
            	$this->searchOrderList();
            	break;
            case "orderListPrint":
            	$this->orderListPrint();
            	break;
            case "orderExcelByOrderNo":
            	$this->orderExcelByOrderNo();
            	break;
            case "toYueTongji":
            	$this->toYueTongji();
            	break;
            case "getSongListByOrderNo":
            	$this->getSongListByOrderNo();
            	break;
            case "toDelDoubleOrder":
            	$this->toDelDoubleOrder();
            	break;
            case "delDoubleOrder":
            	$this->delDoubleOrder();
            	break;
            case "addCustomerJson":
            	$this->addCustomerJson();
            	break;
            case "updateCustomerJson":
            	$this->updateCustomerJson();
            	break;
            case "modifyOrder":
            	$this->modifyOrder();
            	break;
            case "updateOrderDetail":
            	$this->updateOrderDetail();
            	break;
            case "toOrderReturnAdd":
            	$this->toOrderReturnAdd();
            	break;
            case "toOrderReturnList":
            	$this->toOrderReturnList();
            	break;
            case "saveOrderReturnJson":
            	$this->saveOrderReturnJson();
            	break;
            case "getOrderReturnDetail":
            	$this->getOrderReturnDetail();
            	break;
            case "toOrderStatistics":
            	$this->toOrderStatistics();
            	break;
            case "toOrderSearchList":
            	$this->toOrderSearchList();
            	break;
            case "toDemo":
            	$this->toDemo();
            	break;
            case "sendSms":
                $this->sendSms(1);
                break;
            case "printOrderAjax":
            	$this->printOrderAjax();
            	break;
            case "printReturnOrderAjax":
            	$this->printReturnOrderAjax();
            	break;
            case "toFinanceStat":
            	$this->toFinanceStat();
            	break;
            case "ajaxSendSms":
            	$this->ajaxSendSms();
            	break;
            case "orderExport":
            	$this->orderExport();
            	break;
            case "orderExportForSale":
            	$this->orderExportForSale();
            	break;
            case "orderReturnExport":
            	$this->orderReturnExport();
            	break;
            case "orderOrderCodeAdd":
            	$this->toCodeAdd();
            	break;
            case "productListOrder":
            	$this->productListOrder();
            	break;
            case "saveRechargeOrderList":
            	$this->saveRechargeOrderList();
            	break;
            case "orderExportForOrder":
            	$this->orderExportForOrder();
            	break;
            case "ajaxPrintAddMoney":
            	$this->ajaxPrintAddMoney();
            	break;
            case "orderRefundAjax":
            	$this->orderRefundAjax();
            	break;
            default :
                $this->toSearchOrderList();
                break;
        }



    }
    function toOrderPage (){
        $this->mode="toOrderPage";
        $this->objDao=new OrderDao();
        $searchKey=trim($_REQUEST['searchKey']);
        $searchType = $_REQUEST['searchType'];
        $dateFrom = $_REQUEST['dateFrom_s'];
        $dateTo = $_REQUEST['dateTo_s'];
        $payType_s = $_REQUEST['payType_s'];
        $customer_name = $_REQUEST['customer_name'];
        global $searchPayType;
        $where = '';
        if ($searchType =='yifu' || $searchType =='weifu') {
                $payStatus = $searchType =='yifu'? 1 : 0;
            $where.= ' and pay_status ='.$payStatus;
        }
        /*elseif (!empty($searchType) && $searchType !='all') {
            $payType = $searchPayType[$searchType];
            $where.= ' and pay_type ='.$payType;
        }*/
        if (!empty($searchKey)) {
            $where.= ' and order_no ='.$searchKey;
        }
        if (!empty($dateFrom)) {
            $where.= ' and add_time >="'.$dateFrom.' 00:00:00"';
        }
        if (!empty($dateTo)) {
            $where.= ' and add_time <="'.$dateTo.' 24:00:00"';
        }
        if (!empty($customer_name)) {
            $where.= ' and custer_name like "%'.$customer_name.'%"';
        }
        if (!empty($payType_s)) {
            if ($payType_s == 'recharge') {
                $payType = 0;
            } else {
                $payType = $payType_s;
            }
            $where.= ' and pay_type = '.$payType;

        }
        //print_r($_REQUEST);print_r($where);exit;
        $sum =$this->objDao->g_db_count("or_order_total","*","1=1 $where");
        $pageSize=PAGE_SIZE;
        $count = intval($_GET['c']);
        $page = intval($_GET['page']);
        if ($count == 0){
            $count = $pageSize;
        }
        if ($page == 0){
            $page = 1;
        }

        $startIndex = ($page-1)*$count;
        $total = $sum;
        $order['by'] = $_REQUEST['by'];
        $order['up'] = $_REQUEST['up'];
        if (empty($order['by'])) {
            $order['by'] = 'ding_date';
            $order['up'] = 'desc';
        }
        $searchResult=$this->objDao->getOrderTotalPage($where,$order,$startIndex,$pageSize,1);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $orderList = array();
        global $payType;
        global $jiezhangType;
        while ($row = mysql_fetch_array($searchResult)) {
            $order['id'] = $row['id'];
            $order['order_no'] = $row['order_no'];
            $order['ding_date'] = $row['ding_date'];
            $order['custer_name'] = $row['custer_name'];
            $order['custer_name'] = $row['custer_name'];
            $order['chengjiaoer'] = $row['chengjiaoer'];
            $order['order_type'] = $row['order_type'];
            $order['realChengjiaoer'] = $row['realChengjiaoer'];
            $order['isOff'] = $row['isOff'];
            $order['is_refund'] = $row['is_refund'];
            $order['pay_status'] = $jiezhangType[$row['pay_status']];
            $order['pay_type'] = $payType[$row['pay_type']];
            $orderList[] = $order;
        }
        $this->objForm->setFormData("orderList",$orderList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("dateTo",$dateTo);
        $this->objForm->setFormData("dateFrom",$dateFrom);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("payType_s",$payType_s);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("customer_name",$customer_name);
        $this->objForm->setFormData("by",$order['by']);
        $this->objForm->setFormData("up",$order['up']);
    }

    function orderDel () {
        $order_total_id = $_REQUEST['order_id'];

        $adminPO = $_SESSION['admin'];
        $this->objDao = $orderTotalDao = new OrderDao();
        $orderTotalDao->beginTransaction();
        $orderTotalPO = $orderTotalDao->getOrderTotalByOrderTotalId($order_total_id);
        $totalRes = $orderTotalDao->saveTotalOrderDelTabel($orderTotalPO);
        if ($totalRes) {
            $delTotalRes = $orderTotalDao->delOrderTotalById($order_total_id);
            if (!$delTotalRes) {
                $orderTotalDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "订单作废失败请重试！";
                echo json_encode($data);
                exit;
            }
            global  $payTypeForVip;
            if (in_array($orderTotalPO['pay_type'],$payTypeForVip)) {//会员卡消费
                $customerDao = new CustomerDao();
                $customerPO = $customerDao->getCustomerById($orderTotalPO['custer_no']);
                $val = (float)$customerPO['total_money'] + (float)$orderTotalPO['realChengjiaoer'];
                $cuRes = $customerDao->updateCustomerMoneyById($orderTotalPO['custer_no'],$val);
                if (!$cuRes) {
                    $orderTotalDao->rollback();
                    $data['code'] = 100001;
                    $data['message'] = "订单作废失败请重试！";
                    echo json_encode($data);
                    exit;
                } else {
                    $opLog = array();
                    $opLog['who'] = $adminPO['id'];
                    $opLog['who_name'] = $adminPO['real_name'];
                    $opLog['what'] = $orderTotalPO['order_no'];
                    $opLog['Subject'] = OP_LOG_MODIFY_CUSTOMER_VAL;
                    $opLog['memo'] = "修改{$customerPO['realName']}储值金额，原金额：{$customerPO['total_money']}，增加{$orderTotalPO['realChengjiaoer']}，修改后{$val}";
                    $this->addOpLog($opLog);
                }
            }


        }

        $result=$orderTotalDao->getOrderById($orderTotalPO['order_no']);
        $kucunDao = new ProductDao();

        while ($row=mysql_fetch_array($result)){
            $productPO = $kucunDao->getProductById($row['pro_id']);
            $kucunNum = $productPO['pro_num'] +  $row['pro_num'];
            $update = array();
            $update['pro_num'] = $kucunNum;
            $update['pro_id'] = $row['pro_id'];
            $res = $kucunDao->updateProductNumByProId($update);
            if ($res) {
                $opLog = array();
                $opLog['who'] = $adminPO['id'];
                $opLog['who_name'] = $adminPO['real_name'];
                $opLog['what'] = $orderTotalPO['order_no'];
                $opLog['Subject'] = OP_LOG_DEL_ORDER;
                $opLog['memo'] = "产品{$productPO['e_name']}恢复库存，原库存：{$productPO['pro_num']}，增加库存{$row['pro_num']}，修改后库存{$kucunNum}";
                $this->addOpLog($opLog);
                $productDao = new ProductDao();
                $produce = array();//
                $produce['pro_id'] = $productPO['id'];
                $produce['pro_code'] = $productPO['pro_code'];
                $produce['pro_num'] = $row['pro_num'];
                $produce['into_date'] = date('Y-m-d h:i:s',time());
                $produce['memo'] = '删除订单号'.$orderTotalPO['order_no'];
                $produce['old_storage'] = $productPO['pro_num'];
                $produce['new_storage'] = $kucunNum;
                $produce['op_type'] = 3;
                $result = $productDao->addIntoStorage($produce);
                if (!$result) {
                    $this->objDao->rollback();
                    $data['code'] = 100002;
                    $data['message'] = "修改库存数量失败！";
                    echo json_encode($data);
                    exit;
                }
            }
            $saveResult = $orderTotalDao->addOrderDel($row);
            if ($saveResult) {
                $delRes = $orderTotalDao->delOrderById($row['id']);
                if (!$delRes) {
                    $this->objDao->rollback();
                    $data['code'] = 100001;
                    $data['message'] = "订单作废失败请重试！";
                    echo json_encode($data);
                    exit;
                }
            }
        }
        $orderTotalDao->commit();
        $data['code'] = 100000;
        $data['message'] = "订单作废成功！";
        echo json_encode($data);
        exit;
    }
    function toCodeAdd($orderNo=NULL) {
        $this->mode="toCodeAdd";
        $this->objDao=new OrderDao();

        if(!$orderNo){
            $result=$this->objDao->getMaxOrderNo();
            $orderNo=$result['max'];
        }else{
            $orderNo-=1;
        }
        $this->objDao=new CustomerDao();
        $dianyuanPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '1'));
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $jingliList = $this->objDao->getJingbanrenList();

        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $this->objForm->setFormData("levelList",$levelList);
        $this->objForm->setFormData("orderNo",$orderNo);
        $this->objForm->setFormData("dianyuan",$dianyuanPO);
        $this->objForm->setFormData("jingliList",$jingliList);
        $this->objForm->setFormData("jingli",$jingliPO);
    }
    /**
     * 得到管理员列表
     */
  function toAddOrder($orderNo=NULL){
  	    $this->mode="toAdd";
  	    $this->objDao=new OrderDao();
  	    
  	    if(!$orderNo){
  	    	$result=$this->objDao->getMaxOrderNo();
  	    	$orderNo=$result['max'];
  	    }else{
  	    	$orderNo-=1;
  	    }
      $this->objDao=new CustomerDao();
        $dianyuanPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '1'));
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $jingliList = $this->objDao->getJingbanrenList();

      $result = $this->objDao->getCustomerLevelList();
      $levelList = array();
      while ($row = mysql_fetch_array($result)) {
          $levelList[$row['id']]= $row;
      }
      $this->objForm->setFormData("levelList",$levelList);
  	    $this->objForm->setFormData("orderNo",$orderNo);
  	    $this->objForm->setFormData("dianyuan",$dianyuanPO);
  	    $this->objForm->setFormData("jingliList",$jingliList);
  	    $this->objForm->setFormData("jingli",$jingliPO);
  }
/**
	 * 得到操作日志列表
	 */
	function getCustomerListByName(){	
	$custName=$_REQUEST['custName'];
	$custName=$this->js_unescape($custName);
  	$this->objDao=new CustomerDao();
    $salrayList=$this->objDao->getCustomerByName($custName);
    $salayString="";
  	while($row=mysql_fetch_array($salrayList)){
  		$salayString.="$".$row['id']."|".$row['custo_name'];
  	}
  	echo $salayString;
  	exit;
	}
    function getProListByName(){
    $proName=$_REQUEST['proNo'];
  	$this->objDao=new ProductDao();
    $salrayList=$this->objDao->getProductByCodeLike($proName);
    $salayString="";
  	while($row=mysql_fetch_array($salrayList)){
  		$salayString.="$".$row['id']."|".$row['pro_code'];
  	}
  	echo $salayString;
  	exit;	
    	
    }
    function getProById(){
    $proId=$_REQUEST['proId'];
  	$this->objDao=new ProductDao();
    $product=$this->objDao->getProductById($proId);
  	echo json_encode($product);
  	exit;	
    	
    }
    function getCustoById(){
    $custId=$_REQUEST['custId'];
  	$this->objDao=new CustomerDao();
    $customer=$this->objDao->getCustomerById($custId);
    $cutString="|";
  	$cutString.=$customer['id']
  	."|".$customer['custo_discount'];
  	echo $cutString;
  	exit;	
    		
    }
    function printOrderAjax () {
        $orderId = $_REQUEST["order_id"];
        $this->objDao = new OrderDao();
        $orderTotal=$this->objDao->getOrderTotalByOrderNo($orderId);
        $result=$this->objDao->getOrderById($orderId);
        $c_objDao=new CustomerDao();
        $jingliPO = $c_objDao->getJingbanrenList();
        $jingliList = array();
        while($val = mysql_fetch_array($jingliPO)){
            $jingliList[$val['id']] = $val["jingbanren_name"];
        }
        $jingbanren_name = $jingliList[$orderTotal['jingbanren']];
        $printArray = array();
        $i=0;
        global $payType;
        $payTypeName = $payType[$orderTotal['pay_type']];

        while ($row=mysql_fetch_array($result)){

            $print['pro_code'] = mysql_real_escape_string($row['e_name']);
            if (empty($print['pro_code'])) {
                $print['pro_code'] = mysql_real_escape_string($row['c_name']);
            }
            $print['pro_num'] = $row['pro_num'];

            $print['price'] = $row['price'];

            $printArray[] = $print;

            $i++;
        }
        $otherObj = array();
        $otherObj['order_no'] = $orderTotal['order_no'];
        if ($orderTotal['coupon_val'] > 0) {
            $otherObj['isUseCoupon'] = 1;
            $otherObj['subVal'] = $orderTotal['coupon_val'];
        }

        if ($orderTotal['isOff']) {
            $otherObj['is_off'] =  $orderTotal['isOff'];
        }

        $result = $this->printOrder($printArray,$orderTotal['chengjiaoer'],$orderTotal['realChengjiaoer'],$jingbanren_name,$orderTotal['zhidanren_name'],$payTypeName,$orderTotal['custer_name'],$orderTotal['mark'],$orderTotal['cash_val'],$otherObj);
        print_r($result,true);exit;
    }
    function printReturnOrderAjax () {
        $orderId = $_REQUEST["order_id"];
        $this->objDao = new OrderDao();
        $orderTotal=$this->objDao->getOrderReturnTotalByNo($orderId);
        $result=$this->objDao->getOrderReturnByOrderId($orderId);
        $c_objDao=new CustomerDao();
        $jingliPO = $c_objDao->getJingbanrenList();
        $jingliList = array();
        while($val = mysql_fetch_array($jingliPO)){
            $jingliList[$val['id']] = $val["jingbanren_name"];
        }
        $jingbanren_name = $jingliList[$orderTotal['jingbanren']];
        $printArray = array();
        $i=0;
        global $payType;
        $payTypeName = $payType[$orderTotal['pay_type']];

        while ($row=mysql_fetch_array($result)){
            $print['pro_code'] = mysql_real_escape_string($row['e_name']);
            if (empty($print['pro_code'])) {
                $print['pro_code'] = mysql_real_escape_string($row['c_name']);
            }
            $print['pro_num'] = $row['pro_num'];

            $print['price'] = $row['price'];

            $printArray[] = $print;

            $i++;
        }
        //print_r($orderTotal);exit;
        //$this->printReturnOrder($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$payTypeName,$customerName,$remark);
        $result = $this->printReturnOrder($printArray,$orderTotal['return_jin'],$orderTotal['return_real_jin'],$jingbanren_name,$payTypeName,$orderTotal['customer_name'],$orderTotal['mark']);
        print_r($result,true);exit;
    }
    function saveOrderListJson () {
        $ids = $_REQUEST['ids'];
        $price = $_REQUEST['price'];
        $proNum = $_REQUEST['proNum'];
        $sumMoney = $_REQUEST['sumMoney'];
        $totalMoney = $_REQUEST['totalMoney'];
        $realTotalMoney = $_REQUEST['realTotalMoney'];
        $customerId = $_REQUEST['customerId'];
        $payTypeVal = $_REQUEST['payType'];
        $payStatus = $_REQUEST['payStatus'];
        $dingDate = $_REQUEST['dingDate'];
        $remark = $_REQUEST['remark'];
        $more = $_REQUEST['more'];
        $isOff = $_REQUEST['isOff'];
        $printTow = $_REQUEST['printTow'];
        $un_save = $_REQUEST['un_save'];
        $custo_discount = $_REQUEST['custo_discount'];
        $jingbanren = $_REQUEST['jingbanren'];
        $zhidanren = $_REQUEST['zhidanren'];

        $cash_val = $_REQUEST['cash_val'];
        $addCash = $_REQUEST['addCash'];
        $isUseCoupon = $_REQUEST['isUseCoupon'];
        $val_500 = $_REQUEST['val_500'];
        $subVal = $_REQUEST['subVal'];
        $monthCard = $_REQUEST['monthCard'];
        $monthVal = $_REQUEST['monthVal'];
        $roomId = $_REQUEST['roomId'];

        $data['code'] = 100000;
        $this->objDao=new CustomerDao();
        $this->objDao->beginTransaction();

        //save orderTotal table
        $user=$_SESSION['admin'];
        $adminId=$user['id'];
        $adminName=$user['real_name'];
        $result = $this->objDao->getMaxOrderNo();

        $customerPO = $this->objDao->getCustomerById($customerId);
        $jingliPO = $this->objDao->getJingbanrenList();
        $returnList = array();
        if ($isOff) {
            $returnList = $this->getEachSubPrice($ids,$proNum,$price,$totalMoney,$realTotalMoney);
            if (!$returnList) {
                $data['code'] = 100001;
                $data['message'] = "该订单超出毛利率15%！";
                echo json_encode($data);
                exit;
            }
        }

        $jingliList = array();
        while($val = mysql_fetch_array($jingliPO)){
            $jingliList[$val['id']] = $val["jingbanren_name"];
        }
        if (($payTypeVal == self::PAY_TYPE_VIP || $payTypeVal == self::PAY_TYPE_FREE|| $payTypeVal == self::PAY_TYPE_CARD_FREE|| $payTypeVal == self::PAY_TYPE_PINJIAN_CARD)&& $payStatus == self::PAY_STATUS_TRUE) {

            if ($addCash) {
                $yueMoney = $customerPO['total_money'] + (float)$cash_val - $realTotalMoney;
            } else {
                $yueMoney = $customerPO['total_money'] - $realTotalMoney;
                $cash_val = 0;
            }

            if ($yueMoney < 0) {
                $data['code'] = 100001;
                $data['message'] = "会员余额不足！";
                echo json_encode($data);
                exit;
            }
            $resultC = $this->objDao->updateCustomerMoneyById($customerPO['id'],$yueMoney);
            if(!$resultC){
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "修改客户余额失败！";
                echo json_encode($data);
                exit;
            }
            $accountItem = array();
            $accountItem["customer_id"] = $customerPO['id'];
            $accountItem["before_val"] = $customerPO['total_money'];
            $accountItem["deal_val"] = bcsub($realTotalMoney,$cash_val,2);
            $accountItem["after_val"] = $yueMoney;
            $accountItem["admin_id"] = $adminId;
            $accountItem["admin_name"] = $adminName;
            $accountItem["source_id"] = $result['max']+1;
            $accountItem["source_type"] = "or_order_total";
            $accountItem["account_id"] = $customerPO['id'];
            $accountItem["account_type"] = "cutomer_account";
            $resultI = $this->objDao->addAccountItem4Order($accountItem);
            if(!$resultI){
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "修改客户交易详情失败！";
                echo json_encode($data);
                exit;
            }
        }



        $orderNo =$result['max']+1;
        if ($orderNo < 2){
            $orderNo = ORDER_BASE_NO + 1;
        }
        $orderTotalPo['order_no']=$orderNo;
        $orderTotalPo['custer_no']=$customerId;
        $orderTotalPo['custer_name']=$customerPO['realName'];
        //$orderTotalPo['zhekou']=$customerPO['discount'];
        $orderTotalPo['zhekou']=$custo_discount;//改为会员级别折扣
        $orderTotalPo['chengjiaoer']=$totalMoney;
        $orderTotalPo['realChengjiaoer']=$realTotalMoney;
        $orderTotalPo['jingbanren'] = empty($jingbanren)?$customerPO['customer_jingbanren']:$jingbanren;//销售经理
        //$orderTotalPo['zhidanren'] = empty($zhidanren)?0:$zhidanren;//销售经理
        $orderTotalPo['op_id']=$adminId;//制单人
        $orderTotalPo['pay_type']=$payTypeVal;
        $orderTotalPo['pay_status']=$payStatus;
        $orderTotalPo['cash_val']=$cash_val;
        $orderTotalPo['mark']=$remark;
        $orderTotalPo['isOff']=$isOff;
        $orderTotalPo['room_id']=$roomId;
        if ($val_500 > 0) {
            $otherObj['isVal500'] = 1;
            $otherObj['val500'] = '500.00';
            $couponRes = $this->objDao->getCouponByCusId($customerId);
            while ($row = mysql_fetch_array($couponRes)) {
                if ($row['coupon_type'] == 2) {
                    $res = $this->objDao->updateAccountStatusById($row['id'],1);
                    if (!$res) {
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "：不找零优惠券修改失败！";
                        echo json_encode($data);
                        exit;
                    }
                    $accountItem = array();
                    $accountItem["customer_id"] = $customerPO['id'];
                    $accountItem["before_val"] = 500.00;
                    $accountItem["deal_val"] = 500.00;
                    $accountItem["after_val"] = 0.00;
                    $accountItem["admin_id"] = $adminId;
                    $accountItem["admin_name"] = $adminName;
                    $accountItem["source_id"] = $result['max']+1;
                    $accountItem["source_type"] = "or_order_total";
                    $accountItem["account_id"] = $row['id'];
                    $accountItem["account_type"] = "or_account_coupon";
                    $resultCO = $this->objDao->addAccountItem4Order($accountItem);
                    if(!$resultCO){
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "修改优惠券交易详情失败！";
                        echo json_encode($data);
                        exit;
                    }
                }
            }
            $orderTotalPo['coupon_type']=2;
        }
        if ($monthCard > 0) {
            $otherObj['monthCard'] = 1;
            $otherObj['monthVal'] = $monthVal;
            $couponRes = $this->objDao->getCouponByCusId($customerId);
            while ($row = mysql_fetch_array($couponRes)) {
                if ($row['coupon_type'] == 5) {
                    $demainVal = $row['coupon_val'] - $monthVal;

                    $otherObj['demainVal'] = $demainVal;
                    $res = $this->objDao->updateAccountValById($row['id'],$demainVal);
                    if (!$res) {
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "：月卡修改失败！";
                        echo json_encode($data);
                        exit;
                    }
                    $accountItem = array();
                    $accountItem["customer_id"] = $customerPO['id'];
                    $accountItem["before_val"] = $row['coupon_val'];
                    $accountItem["deal_val"] = $monthVal;
                    $accountItem["after_val"] = $demainVal;
                    $accountItem["admin_id"] = $adminId;
                    $accountItem["admin_name"] = $adminName;
                    $accountItem["source_id"] = $result['max']+1;
                    $accountItem["source_type"] = "or_order_total";
                    $accountItem["account_id"] = $row['id'];
                    $accountItem["account_type"] = "or_account_coupon";
                    $resultCO = $this->objDao->addAccountItem4Order($accountItem);
                    if(!$resultCO){
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "修改优惠券交易详情失败！";
                        echo json_encode($data);
                        exit;
                    }
                }
            }
            $orderTotalPo['coupon_type']=2;
        } else{
            $orderTotalPo['coupon_type']=1;
        }
        $orderTotalPo['coupon_val']=$subVal;
        //todo 修改优惠券余额
        if ($isUseCoupon > 0) {
            $otherObj['isUseCoupon'] = 1;
            $otherObj['subVal'] = $subVal;
            $couponRes = $this->objDao->getCouponByCusId($customerId);
            $cha_val  = 0.00;
            while ($row = mysql_fetch_array($couponRes)) {
                if ($row['coupon_type'] != 2 && $row['coupon_type'] != 5 ) {
                    if ($cha_val > 0) {
                        $subVal = $cha_val;
                    }
                    $demainVal = $row['coupon_val'] - $subVal;

                    if ($demainVal < 0) {
                        $cha_val = - $demainVal;
                        $subVal = $row['coupon_val'];
                        $demainVal = 0;
                    }
                    $res = $this->objDao->updateAccountValById($row['id'],$demainVal);
                    if (!$res) {
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "：优惠券修改失败！";
                        echo json_encode($data);
                        exit;
                    }
                    $accountItem = array();
                    $accountItem["customer_id"] = $customerPO['id'];
                    $accountItem["before_val"] = $row['coupon_val'];
                    $accountItem["deal_val"] = $subVal;
                    $accountItem["after_val"] = $demainVal;
                    $accountItem["admin_id"] = $adminId;
                    $accountItem["admin_name"] = $adminName;
                    $accountItem["source_id"] = $result['max']+1;
                    $accountItem["source_type"] = "or_order_total";
                    $accountItem["account_id"] = $row['id'];
                    $accountItem["account_type"] = "or_account_coupon";
                    $resultCO = $this->objDao->addAccountItem4Order($accountItem);
                    if(!$resultCO){
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "修改优惠券交易详情失败！";
                        echo json_encode($data);
                        exit;
                    }
                }

            }
        }
        /*if () {

        }*/
        $orderTotalPo['zhidanren_name'] = $zhidanren_name = $user['real_name'];
        $this->objDao=new OrderDao();
        $resultTotal=$this->objDao->saveTotalOrderTabel($orderTotalPo);

        if(!$resultTotal){
            $this->objDao->rollback();
            $data['code'] = 100001;
            $data['message'] = "添加订单总表失败！";
            echo json_encode($data);
            exit;
        }
        $printArray = array();
        for($i=0;$i<count($ids);$i++){
            $order=array();
            $print=array();
            $this->objDao=new ProductDao();
            if (!$ids[$i]) continue;
            if ($customerPO['custo_level'] > 0) {

            }
            $productPO = $this->objDao->getProductById($ids[$i]);
            if ($proNum[$i] > $productPO['pro_num']) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = $productPO['pro_name']."：{$productPO['pro_num']}，库存数量不足，添加订单总表失败！";
                echo json_encode($data);
                exit;
            }

            $tobjDao=new CustomerDao();
            $customerLevel = $tobjDao->getCustomerLevelById($customerPO['custo_level']);

            $customerPO['custo_discount'] = $customerLevel['discount'];
            $res = $this->checkOrder($customerPO,$productPO,$proNum[$i],$sumMoney[$i],$_REQUEST['isCommonPrice']);
            if (!$res['val']) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = $res['msg'];
                echo json_encode($data);
                exit;
            }
            $order['order_no']=$orderTotalPo['order_no'];
            $order['pro_id']=$ids[$i];
            $print['pro_code'] = $order['pro_code']= mysql_real_escape_string($productPO['pro_name']);
            $order['ding_date']=$dingDate;
            $order['jiao_date']=$dingDate;
            $print['pro_num'] = $order['pro_num'] = $proNum[$i];
            $order['pro_type']=$productPO['pro_type'];
            $order['pro_unit']=$productPO['pro_unit'];
            $order['pro_price']=$productPO['market_price'];
            $print['price'] = $order['price']=$price[$i];
            $order['pro_flag']=$productPO['pro_flag'];
            $order['customer_id']=$customerId;
            $order['order_jiner']=$sumMoney[$i];
            $order['jingban_id']= empty($jingbanren)?$customerPO['customer_jingbanren']:$jingbanren;
            $order['customer_type'] = $customerPO['custo_level'];//销售经理
            $order['mark']='';
            $order['custo_name']=$customerPO['realName'];
            $order['zhekou']=$custo_discount;
            if($isOff && $returnList) {
                $order['real_price'] =  sprintf("%.2f", bcsub($sumMoney[$i],$returnList[$ids[$i]],2)/$print['pro_num']);
                $order['real_order_jiner'] = bcsub($sumMoney[$i],$returnList[$ids[$i]],2);
            } else {
                $order['real_price']= $order['price'];
                $order['real_order_jiner'] = $order['order_jiner'];
            }

            $printArray[] = $print;
            $this->objDao=new OrderDao();
            $rasult=$this->objDao->addOrder($order);
            if(!$rasult){
                $this->objDao->rollback();
                $data['code'] = 100002;
                $data['message'] = "添加订单失败！";
                echo json_encode($data);
                exit;
            }

            $newProNum = (int)$productPO['pro_num'] - (int)$proNum[$i];
            $res = $this->objDao->updateProductNumById($ids[$i],$newProNum);
            if (!$res) {
                $this->objDao->rollback();
                $data['code'] = 100002;
                $data['message'] = "修改库存数量失败！";
                echo json_encode($data);
                exit;
            } else {
                $productDao = new ProductDao();
                $produce = array();
                $produce['pro_id'] = $productPO['id'];
                $produce['pro_code'] = mysql_real_escape_string($productPO['pro_name']);
                $produce['pro_num'] = $proNum[$i];
                $produce['into_date'] = $dingDate;
                $produce['memo'] = '订单号'.$orderTotalPo['order_no'];
                $produce['old_storage'] = $productPO['pro_num'];
                $produce['new_storage'] = $newProNum;
                $produce['op_type'] = 1;
                $result = $productDao->addIntoStorage($produce);
                if (!$result) {
                    $this->objDao->rollback();
                    $data['code'] = 100002;
                    $data['message'] = "修改库存数量失败！";
                    echo json_encode($data);
                    exit;
                }
            }


        }


        if ($data['code'] == 100000) {
            $adminPO = $_SESSION['admin'];
            $opLog = array();
            $opLog['who'] = $adminPO['id'];
            $opLog['who_name'] = $adminPO['real_name'];
            $opLog['what'] = $orderNo;
            $opLog['Subject'] = OP_LOG_ORDER;
            $opLog['memo'] = OP_LOG_ADD_ORDER;
            $this->addOpLog($opLog);
        }
        if (empty($un_save) ||  $un_save == 0) {
            //事务提交
            $this->objDao->commit();
        }
        $jingbanren_name = $jingliList[$jingbanren];
        $otherObj['customer_no'] =  $customerPO['card_no'];
        $otherObj['order_no'] =  $orderTotalPo['order_no'];
        $otherObj['un_save'] =  $un_save ? $un_save : 0;
        if ($isOff) {
            $otherObj['is_off'] =  $isOff;
        }
        global $payType;
        $payTypeName = $payType[$payTypeVal];
        $customerName = $customerPO['realName'];

        $this->printOrder($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$zhidanren_name,$payTypeName,$customerName,$remark,$cash_val,$otherObj);
        if($printTow) {
            $this->printOrder($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$zhidanren_name,$payTypeName,$customerName,$remark,$cash_val,$otherObj);
        }
        if ($payTypeVal == self::PAY_TYPE_VIP && $payStatus == self::PAY_STATUS_TRUE) {
            $sms = $_REQUEST['sms'];
            $time = date('Y年m月d日',strtotime($dingDate));
            if ($sms && !empty($customerPO['mobile'])) {
                if (empty($un_save) ||  $un_save == 0) {
                    $this->sendSms($time, $customerName, $realTotalMoney, $yueMoney, $customerPO['mobile']);
                }
                //$this->sendSms($time,$customerName,$realTotalMoney,$yueMoney);
            }
        }
        echo json_encode($data);
        exit;

    }
    private function getEachSubPrice ($ids,$proNum,$price,$totalMoney,$realTotalMoney) {
        $i = 0;
        $sumPercent = 0;
        $retainVal = $totalMoney - $realTotalMoney;
        $sumChengbenVal = 0.0;
        $returnList = array();
        foreach ($ids as $id) {
            $productPO = $this->objDao->getProductById($id);
            $sumChengbenVal += $productPO['cost_price'];
            $sumPerPrice = $proNum[$i] * $price[$i];
            $percent =  number_format($sumPerPrice/$totalMoney * 100,0);

            if ($i == (count($ids)-1)) {
                $percent = 100 - $sumPercent;
            } else {
                $sumPercent += $percent;
            }
            $disVal = round($retainVal * $percent/100,2);
            $returnList[$id] = $disVal;
            $i++;
        }
        //print_r($returnList);//exit;
        $salPertCent = ($realTotalMoney - $sumChengbenVal) /$realTotalMoney;
        if ($salPertCent < 0.10) {
            return false;
        }
        return $returnList;
    }
    private function checkOrder($customerPO,$productPO,$proNum,$sum,$isCommonPrice = 0){
        $return = array();
        $price = 0.00;

        if ($customerPO['custo_level'] > 0 && !$isCommonPrice) {
/**
if (Customer.oCustomer.info.custo_level == 16 || Customer.oCustomer.info.custo_level == 18|| Customer.oCustomer.info.custo_level == 25) {
                            proData.pro_price = proData.market_price;
                        } else if (Customer.oCustomer.info.custo_level == 19) {
                            proData.pro_price = proData.channel_price;
                        }  else if (Customer.oCustomer.info.custo_level == 26) {
                            proData.pro_price = proData.after_dis_price;
                        }  else if (Customer.oCustomer.info.custo_level == 27) {
                            proData.pro_price = proData.com_channel_price;
                        } else {
                            proData.pro_price = proData.vip_price;
                        }
**/
            if ($customerPO['custo_level'] == 16 || $customerPO['custo_level'] == 18 ||$customerPO['custo_level'] == 32 || $customerPO['custo_level'] == 25 || $customerPO['custo_level'] == 34 || $customerPO['custo_level'] == 38) {
                $price = $productPO['market_price'];
            } else if ($customerPO['custo_level'] == 19) {
                $price = $productPO['channel_price'];
            }else if ($customerPO['custo_level'] == 26) {
				$price = $productPO['after_dis_price'];
			} else if ($customerPO['custo_level'] == 27) {
				$price = $productPO['com_channel_price'];
			} else {
                $price = $productPO['vip_price'];
            }
        } else {
            $price = $productPO['market_price'];
        }
        if ($customerPO['custo_discount'] && $customerPO['custo_discount'] > 0) {
            $price = $price * ($customerPO['custo_discount']/100);
        }

        $sumPrice = $price*$proNum;
        if (round($sum,2) != round($sumPrice,2)) {
            $return['val'] = false;
            $return['msg'] = "{$productPO['e_name']} 的为 $proNum(数量)*$price(单价)=$sumPrice(总计) 和打印订单金额 $sum 不符，需要重新计算！ ".floatval($sum)." !=". floatval($sumPrice);
            return $return;
        }
        $return['val'] = true;
        return $return;
    }
    function floatcmp($f1,$f2,$precision = 10) // are 2 floats equal
    {
        $e = pow(10,$precision);
        $i1 = intval($f1 * $e);
        $i2 = intval($f2 * $e);
        return ($i1 == $i2);
    }
    function getOrderPDF() {
        $order_data = $_POST["order_data"];
        $order_data = json_decode($order_data,true);

        $danwei = $_POST["danwei"];
        $lianxiren = $_POST["lianxiren"];
        $lianxifangshi = $_POST["lianxifangshi"];
        $songdizhi = $_POST["songdizhi"];
        $songDate = $_POST["songDate"];


        $pro_ids = $order_data['ids'];
        $proNum = $order_data['proNum'];
        $price = $order_data['price'];
        $sumMoney = $order_data['sumMoney'];
        $orderList=array();
        $i=1;
        $productDao = new ProductDao();
        $bit = 0;
        foreach ($pro_ids as $proId){
            $obj = array();
            $productModel = $productDao->getProductById($proId);
            $obj[0] = $i;
            $obj[1] = $productModel['c_name'];
            $obj[2] = $productModel['e_name'];
            $obj[3] = $productModel['pro_address'];
            $obj[4] = $proNum[$bit];
            $obj[5] = $productModel['market_price'];
            $obj[6] = $price[$bit];
            $obj[7] = $sumMoney[$bit];
            $orderList[] =$obj;
            $i++;$bit++;
        }
        $shijieChengjiaoer = $order_data['realTotalMoney'];
        ob_end_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->Open();
        $pdf->SetMargins(2, 10,PDF_MARGIN_RIGHT);
        $pdf->AddPage();
        $fontSize = 10;
        $Fonthight = 7;
        $image = "common/img/indexlogo.jpg";
        /******************************************
         * 标题
         ******************************************/
        $pdf->SetFont('stsongstdlight','B',13);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->Image("common/img/indexlogo.jpg", 87, 1, 30, '', 'JPG', '', '', true, 300, '', false, false, 0, "C", false, false);
        $pdf->Ln(17, false);
        $pdf->Cell(0,2,"北京尚嘉品鉴销售清单（发货单）",0,1,"C");
        $pdf->Ln(5, false);
        /******************************************
         * 分割线
         ******************************************/
        $pdf->SetFont('stsongstdlight','B',5);
        //$pdf->Cell(0,2,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
        $x1 = $pdf->GetX();
        $y1 = $pdf->GetY();
        $pdf->Line(0, $y1-1, 400, $y1-1, $style=array());
        $pdf->SetFont('stsongstdlight','B',9);
        $pdf->setCellPaddings(5, 0, 0, 0);
        $pdf->Cell(0,3,"购货单位：{$danwei}                                                                                                                             日期: {$songDate}",0,0,"L");
        $pdf->Cell(0,1,"",0,1);
        $pdf->Cell(0,3,"联系人：{$lianxiren}                                                                                                                                   联系方式:010-57755080",0,0,"L");
        $pdf->Cell(0,1,"",0,1);
        $pdf->Cell(0,5,"联系方式:{$lianxifangshi}                                                                                                                              发 货 人：CHAMPLUS尚嘉品鉴",0,0,"L");
        $pdf->Cell(0,1,"",0,1);
        $pdf->Cell(0,5,"地址: {$songdizhi}                                                                                                                           地    址：北京市朝阳区东三环北路27号院嘉铭中心B1层尚嘉品鉴",0,0,"L");
        $x1 = $pdf->GetX();
        $y1 = $pdf->GetY();
        $pdf->Line(0, $y1+5, 400, $y1+5, $style=array());
        $pdf->Ln(10, false);
        $text = '您好！欢迎选用CHAMPLUS尚嘉品鉴的葡萄酒产品和服务。本公司供应的产品之规格型号、数量及单价均已在本发货单上注明，请购货方在发货前确认并付款，本公司在收到款后7天内安排到货，谢谢合作！';
        $pdf->MultiCell(180, 3, $text, $border=0, $align='L',$fill=false, $ln=1, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);
        $pdf->Ln(10, false);
        $header = array(0=>"序列",1=>"货物英文名称",2=>"货物中文名称",3=>"产区",4=>"数量/瓶",5=>"零售价",6=>"会员价",7=>"会员总价");
        $data = array();
        $pdf->setCellPaddings(0, 0, 0, 0);
        $this->ColoredTable($header,$orderList,$pdf,$shijieChengjiaoer);
        $pdf->setCellPaddings(5, 0, 0, 0);
        $pdf->Ln(10, false);
        $pdf->Cell(0,3,"对公账户	1109 1659 4310 301                                 ",0,0,"L");
        $pdf->Cell(0,1,"",0,1);
        $pdf->Cell(0,3,"         开户名：北京尚嘉品鉴信息咨询有限公司	 ",0,0,"L");
        $pdf->Cell(0,1,"",0,1);
        $pdf->Cell(0,3,"         开户行：招商银行股份有限公司北京东四环支行		 ",0,0,"L");
        $pdf->Ln(10, false);
        $pdf->Cell(0,3,"一般账户	收款账号：6226 0901 1278 7672                                 ",0,0,"L");
        $pdf->Cell(0,3,"",0,1);
        $pdf->Cell(0,3,"         开户名：洪剑		 ",0,0,"L");
        $pdf->Cell(0,3,"",0,1);
        $pdf->Cell(0,3,"         开户行：招商银行北京分行长安街支行			 ",0,0,"L");
        $pdf->Ln(10, false);
        $text = "备注：购货单位应于提货之前按照合同规定向供货方付款，并对货物进行验收。若发现货物短少、规格型号不符或者质量缺陷影响使用的，应立即与供货方联系！				 ";
        $pdf->MultiCell(180, 3, $text, $border=0, $align='L',$fill=false, $ln=1, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);
        $pdf->Cell(0,3,"",0,1);
        $pdf->Cell(0,3,"",0,1);
        $pdf->Cell(0,3," 发货单位签章：北京尚嘉品鉴信息咨询有限公司   	                                                    客户确认：",0,0,"L");

        $pdf->SetDisplayMode("real");
        $date_dir = date('Ymd');
        $pdf->Output();
    }
    function orderExportForSale(){
        $where['fromDate'] = $_REQUEST['fromDate'];
        $where['toDate'] = $_REQUEST['toDate'];
        $where['jingbanren'] = $_REQUEST['jingbanren'];

        $this->objDao=new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $this->objForm->setFormData("jingli",$jingliPO);
        $this->objDao = new OrderDao();
        $result = $this->objDao->getOrderInfoListByRang($where);
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val;
        }
        require 'tools/php-excel.class.php';

        $stuffSumList = array();
        $stuffSumList[]  = array('销售人员','订单号','订单日期','客户名称','付款方式','实收金额','订单备注');

        //$list[] = $head_json;
        global $payType;
        $sum = 0.00;
        while ($row = mysql_fetch_array($result)) {
            $data =array();
            $data[0] = $jingbanList[$row['jingbanren']];
            $data[1] = $row['order_no'];
            $data[2] = $row['ding_date'];
            $data[4] = $row['custer_name'];
            $data[5] = $payType[$row['pay_type']];
            $data[6] = (float)$row['realChengjiaoer'];
            $sum += $data[7] = $row['mark'];
            $stuffSumList[] = $data;

        }
        $stuffSumList[] = array(1=> '合计',2=>$sum);
        $time = 'order';
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($jingbanList[$where['jingbanren']]);
        exit;
    }
    function orderReturnExport () {
        require 'tools/php-excel.class.php';

        $fromDate = $_REQUEST['fromDate'];
        $toDate = $_REQUEST['toDate'];
        $this->objDao = new OrderDao();
        $where= " and add_time >= '{$fromDate}' and add_time <= '{$toDate}'";
        $order_by= array();
        if (empty($order['by'])) {
            $order_by['by'] = 'add_time';
            $order_by['up'] = 'desc';
        }
        $searchResult=$this->objDao->getOrderReturnTotalPage($where,$order_by);

        $stuffSumList = array();
        $stuffSumList[]  = array('序列号','退单号','退单日期','客户名称','产品名称','数量','合计金额','状态','备注');


        $i = 1;
        global $returnType;
        while ($row_o = mysql_fetch_array($searchResult)) {
            $orderPO = $this->objDao->getOrderReturnByReturnNo($row_o['order_no']);
            while ($row = mysql_fetch_array($orderPO)) {
                $order[0] = $i;$i++;
                $order[1] = $row['order_no'];
                $order[2] = $row['return_date'];
                $order[3] = $row['custo_name'];
                $order[4] = $row['pro_code'];
                $order[5] = (int)$row['pro_num'];
                $order[6] = (float)$row['return_jiner'];
                $order[7] = $returnType[$row_o['order_type']];
                $order[8] = $row_o['mark'];
                $stuffSumList[] = $order;
            }


        }
        $time = 'order';
        //$produceList = array_merge($head_json,$list);print_r($produceList);exit;
        //print_r($stuffSumList);exit;
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($time);
        exit;
    }

    function orderExportForOrder () {
        require 'tools/php-excel.class.php';

        $fromDate = $_REQUEST['fromDate'];
        $toDate = $_REQUEST['toDate'];
        $this->objDao=new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val;
        }
        $this->objDao = new OrderDao();
        $where= " and ding_date >= '{$fromDate}' and ding_date <= '{$toDate}'";
        $order= array();
        $searchResult=$this->objDao->getOrderTotalPage($where,$order);

        $stuffSumList = array();
        $stuffSumList[]  = array('序列号','订单号','订单日期','客户名称','付款方式','会员类型','产品名称','购买数量','零售单价','会员单价','应收金额','实收总计','优惠券','现金','订单总金额','订单备注','销售人员');

        $total = array();

        //$list[] = $head_json;
        global $payType;
        $i = 1;
        while ($row = mysql_fetch_array($searchResult)) {
            $orderPO = $this->objDao->getOrderList("","","",$row['order_no']);
            //exit;
            if ($row['order_type'] == 1) {
                $data =array();
                $data[] = $i;$i++;
                $data[] = "客户充值";
                $data[] = $row['ding_date'];
                $data[] = $row['custer_name'];
                $data[] = "充值";
                $data[] = $levelList[$row['customer_type']]['level_name'];
                $data[] = '';
                $data[] = 0;
                $data[] = 0;

                $data[] = (float)$row['realChengjiaoer'];
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = 0.00;
                $data[] = 0.00;
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = $row['mark'];
                $data[] = $row['zhidanren_name'];
                $stuffSumList[] = $data;
            } else {
                $j = 0;
                while ($order = mysql_fetch_array($orderPO)) {

                    $productPO = $this->objDao->getProductById($order['pro_id']);
                    $data =array();
                    $data[] = $i;$i++;
                    $data[] = $row['order_no'];
                    $data[] = $row['ding_date'];
                    $data[] = $row['custer_name'];
                    $data[] = $payType[$row['pay_type']];
                    $data[] = $levelList[$order['customer_type']]['level_name'];
                    $data[] = $order['pro_code'];
                    $data[] = (int)$order['pro_num'];
                    $data[] = (float)$productPO['market_price'];
                    $data[] = (float)$order['price'];
                    if ($order['real_price'] == 0)  {
                        $order['real_price'] = $order['price']*$order['pro_num'];
                    }

                    $data[] = (float)$order['order_jiner'];
                    $data[] = (float)$order['real_price'];
                    if ($j == 0) {


                        $data[] = (float)$row['coupon_val'];
                        $data[] = (float)$row['cash_val'];
                    } else {
                        $data[] = 0.00;
                        $data[] = 0.00;
                    }
                    if ($j == 0) {

                        $data[] = (float)$row['realChengjiaoer'];
                    } else {
                        $data[] = 0.00;
                    }
                    $data[] = $row['mark'];
                    $data[] = $jingbanList[$row['jingbanren']]['jingbanren_name'];
                    $stuffSumList[] = $data;
                    $j++;
                }
            }

            $total['order_money']+= $row['realChengjiaoer'];

            if ($row['pay_type'] == 2) {//会员卡
                $total['vip_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 1) {//现金
                $total['cash_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 3) {//转账
                $total['zhuan_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 4) {//赠送
                $total['free_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 5) {//活动赠送
                $total['huodong_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 6) {//转银行卡
                $total['toCard_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 8) {//微信
                $total['weiChat_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 9) {//支付宝
                $total['aliPay_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 10) {//赠送会员卡
                $total['freeVip_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 11) {//刷卡
                $total['shuaka_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 12) {//抵扣款项
                $total['dikou_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 13) {//支票
                $total['zhipiao_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 14) {//回款
                $total['huikuan_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 15) {//退单
                $total['tuidan_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 16) {//客户存酒
                $total['cunjiu_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 17) {//预收款
                $total['yuShou_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 0 && $row['order_type'] == 1) {//充值
                $total['recharge_money']+= $row['realChengjiaoer'];
            }elseif ($row['pay_type'] == 7) {//充值
                $total['weijie_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 19) {//充值
                $total['tuihuo_money']+= $row['realChengjiaoer'];
            }

        }
        $time = 'order';
        //$produceList = array_merge($head_json,$list);print_r($produceList);exit;
        //print_r($stuffSumList);exit;

        $stuffSumList[] = $lastArr = array(
            0=> "总金额：",1=> (float)$total['order_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "现金合计：",1=> (float)$total['cash_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "转银行卡合计：",1=> (float)$total['toCard_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "微信合计：",1=> (float)$total['weiChat_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "支付宝合计：",1=> (float)$total['aliPay_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "会员卡合计：",1=> (float)$total['vip_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "赠送会员卡合计：",1=> (float)$total['freeVip_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "赠送合计：",1=> (float)$total['free_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "预收款合计：",1=> (float)$total['yuShou_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "充值合计：",1=> (float)$total['recharge_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "转账合计：",1=> (float)$total['zhuan_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "刷卡合计：",1=> (float)$total['shuaka_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "抵扣款项合计：",1=> (float)$total['dikou_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "支票合计：",1=> (float)$total['zhipiao_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "回款合计：",1=> (float)$total['huikuan_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "退单合计：",1=> (float)$total['tuidan_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "客户存酒合计：",1=> (float)$total['cunjiu_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "未结款合计：",1=> (float)$total['weijie_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "退货合计：",1=> (float)$total['tuihuo_money'],
        );

        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($time);
        exit;
    }

    function orderExport () {
        require 'tools/php-excel.class.php';

        $fromDate = $_REQUEST['fromDate'];
        $toDate = $_REQUEST['toDate'];
        $this->objDao=new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val;
        }
        $this->objDao = new OrderDao();
        $where= " and ding_date >= '{$fromDate}' and ding_date <= '{$toDate}'";
        $order= array();
        $searchResult=$this->objDao->getOrderTotalPage($where,$order);

        $stuffSumList = array();
        $stuffSumList[]  = array('序列号','订单号','订单日期','客户名称','付款方式','会员类型','产品名称','购买数量','零售单价','会员单价','应收金额','单品实收','会员卡','其他付款','优惠券','现金','充值','实收总金额','订单备注','销售人员');

        //$list[] = $head_json;

        $NeedPayVip = array(2,10,18,19);
        global $payType;
        $total = array();
        $i = 1;
        while ($row = mysql_fetch_array($searchResult)) {
            $orderPO = $this->objDao->getOrderList("","","",$row['order_no']);
            //exit;
            if ($row['order_type'] == 1) {
                $data =array();
                $data[] = $i;$i++;
                $data[] = "客户充值";
                $data[] = $row['ding_date'];
                $data[] = $row['custer_name'];
                $data[] = "充值";
                $data[] = $levelList[$row['customer_type']]['level_name'];
                $data[] = '';
                $data[] = 0;
                $data[] = 0;

                $data[] = (float)0.00;
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = 0.00;
                $data[] = 0.00;
                $data[] = 0.00;
                $data[] = 0.00;
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = (float)$row['realChengjiaoer'];
                $data[] = $row['mark'];
                $data[] = $row['zhidanren_name'];
                $stuffSumList[] = $data;
            } else {
                $j = 0;
                while ($order = mysql_fetch_array($orderPO)) {
                    $shishou_sum = 0.00;
                    $productPO = $this->objDao->getProductById($order['pro_id']);
                    $data =array();
                    $data[] = $i;$i++;
                    $data[] = $row['order_no'];
                    $data[] = $row['ding_date'];
                    $data[] = $row['custer_name'];
                    $data[] = $payType[$row['pay_type']];
                    $data[] = $levelList[$order['customer_type']]['level_name'];
                    $data[] = $order['pro_code'];
                    $data[] = (int)$order['pro_num'];
                    $data[] = (float)$productPO['market_price'];
                    $data[] = (float)$order['price'];
                    if ($order['real_price'] == 0)  {
                        $order['real_price'] = $order['price']*$order['pro_num'];
                    }

                    $data[] = (float)$order['order_jiner'];
                    $data[] = (float)$order['real_price'];
                    if ($j == 0) {//会员卡 其他付款

                        if (in_array($row['pay_type'],$NeedPayVip)) {
                            $item_res = $this->objDao->getAccountItemBySourceId($row['order_no']);
                            if (!empty($item_res['deal_val'])) {
                                $shishou_sum+= $data[] = (float)$item_res['deal_val'];
                                $shishou_sum+= $data[] = (float)0.00;
                            } else {

                                $shishou_sum+= $data[] = (float)$row['realChengjiaoer'];
                                $shishou_sum+= $data[] = (float)0.00;
                            }
                        } else {

                            $shishou_sum+= $data[] = (float)0.00;
                            $shishou_sum+= $data[] = (float)$row['realChengjiaoer'];
                        }
                    } else {
                        $shishou_sum+= $data[] = 0.00;
                        $shishou_sum+= $data[] = 0.00;
                    }

                    if ($j == 0) {//优惠券 现金


                        $shishou_sum+= $data[] = (float)$row['coupon_val'];
                        $shishou_sum+= $data[] = (float)$row['cash_val'];
                    } else {
                        $shishou_sum+= $data[] = 0.00;
                        $shishou_sum+= $data[] = 0.00;
                    }
                    $data[] = 0.00;
                    if ($j == 0) {

                        $data[] = (float)$shishou_sum;
                    } else {
                        $data[] = 0.00;
                    }
                    $data[] = $row['mark'];
                    $data[] = $jingbanList[$row['jingbanren']]['jingbanren_name'];
                    $stuffSumList[] = $data;
                    $j++;


                }
            }
            $total['order_money']+= $row['realChengjiaoer'];

            if ($row['pay_type'] == 2) {//会员卡
                $total['vip_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 1) {//现金
                $total['cash_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 3) {//转账
                $total['zhuan_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 4) {//赠送
                $total['free_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 5) {//活动赠送
                $total['huodong_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 6) {//转银行卡
                $total['toCard_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 8) {//微信
                $total['weiChat_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 9) {//支付宝
                $total['aliPay_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 10) {//赠送会员卡
                $total['freeVip_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 11) {//刷卡
                $total['shuaka_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 12) {//抵扣款项
                $total['dikou_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 13) {//支票
                $total['zhipiao_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 14) {//回款
                $total['huikuan_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 15) {//退单
                $total['tuidan_money']+= $row['realChengjiaoer'];
            }  elseif ($row['pay_type'] == 16) {//客户存酒
                $total['cunjiu_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 17) {//预收款
                $total['yuShou_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 0 && $row['order_type'] == 1) {//充值
                $total['recharge_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 7) {//充值
                $total['weijie_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 19) {//充值
                $total['tuihuo_money']+= $row['realChengjiaoer'];
            }
        }

        $total['order_money'] = sprintf("%.2f", $total['order_money']);
        $total['vip_money'] = sprintf("%.2f", $total['vip_money']);
        $total['cash_money'] = sprintf("%.2f", $total['cash_money']);
        $total['free_money'] = sprintf("%.2f", $total['free_money']);
        $total['zhuan_money'] = sprintf("%.2f", $total['zhuan_money']);
        $total['aliPay_money'] = sprintf("%.2f", $total['aliPay_money']);
        $total['weiChat_money'] = sprintf("%.2f", $total['weiChat_money']);
        $total['freeVip_money'] = sprintf("%.2f", $total['freeVip_money']);
        $total['yuShou_money'] = sprintf("%.2f", $total['yuShou_money']);
        $total['recharge_money'] = sprintf("%.2f", $total['recharge_money']);
        $total['toCard_money'] = sprintf("%.2f", $total['toCard_money']);

        $stuffSumList[] = $lastArr = array(
            0=> "总金额：",1=> (float)$total['order_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "现金合计：",1=> (float)$total['cash_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "转银行卡合计：",1=> (float)$total['toCard_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "微信合计：",1=> (float)$total['weiChat_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "支付宝合计：",1=> (float)$total['aliPay_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "会员卡合计：",1=> (float)$total['vip_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "赠送会员卡合计：",1=> (float)$total['freeVip_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "赠送合计：",1=> (float)$total['free_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "预收款合计：",1=> (float)$total['yuShou_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "充值合计：",1=> (float)$total['recharge_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "转账合计：",1=> (float)$total['zhuan_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "刷卡合计：",1=> (float)$total['shuaka_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "抵扣款项合计：",1=> (float)$total['dikou_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "支票合计：",1=> (float)$total['zhipiao_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "回款合计：",1=> (float)$total['huikuan_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "退单合计：",1=> (float)$total['tuidan_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "客户存酒合计：",1=> (float)$total['cunjiu_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "未结款合计：",1=> (float)$total['weijie_money'],
        );
        $stuffSumList[] = $lastArr = array(
            0=> "退货合计：",1=> (float)$total['tuihuo_money'],
        );

        $time = 'order';
        //$produceList = array_merge($head_json,$list);print_r($produceList);exit;
        //print_r($stuffSumList);exit;
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($time);
        exit;
    }
    function ajaxSendSms () {
        $orderNo = $_REQUEST['order_id'];
        $this->objDao = new OrderDao();
        $orderTotal = $this->objDao->getOrderTotalByOrderNo($orderNo);
        $time = date('Y年m月d日',strtotime($orderTotal['ding_date']));
        $customerDao = new CustomerDao();
        $customer = $customerDao->getCustomerById($orderTotal['custer_no']);
        //print_r($customer);
        //print_r($orderTotal);
        if (!empty($customer['mobile'])) {
            $this->sendSms($time,$customer['realName'],$orderTotal['realChengjiaoer'],$customer['total_money'],$customer['mobile']);
        } else {

            $data['code'] = 100001;
            $data['message'] = "客户手机为空请填写！";
            echo json_encode($data);
            exit;
        }
        $data['code'] = 100000;
        $data['message'] = "发送成功！";
        echo json_encode($data);
        exit;
    }
    function sendSms ($time,$real_name = "张超",$money ="100",$reMoney = "500",$mobile = "18501017983") {


        //+$mobile = "18501017983";
        require_once("tools/sms/YunpianAutoload.php");
/*// 获取用户信息
        $userOperator = new UserOperator();
        $result = $userOperator->get();
        print_r($result);*/

// 发送单条短信
        $smsOperator = new SmsOperator();
        $data['mobile'] = $mobile;
        $data['text'] = "【尚嘉品鉴】尊敬的Champlus尚嘉品鉴会员{$real_name}：您好！您的会员卡于{$time}消费{$money}元,余额为{$reMoney}元，感谢您的支持！希望您多提出宝贵的意见！会员专线：13810424267！";
        $result = $smsOperator->single_send($data);
        error_log( "*************".date('Y-m-d H:i:s')."*****************\n", 3, "log/sms.log" );
        error_log( print_r($result,true)."\n", 3, "log/sms.log" );

    }
    function sendRefundSms ($time,$real_name = "张超",$money ="100",$reMoney = "500",$mobile = "18501017983",$couponVal = "0.00") {


        //+$mobile = "18501017983";
        require_once("tools/sms/YunpianAutoload.php");
/*// 获取用户信息
        $userOperator = new UserOperator();
        $result = $userOperator->get();
        print_r($result);*/

// 发送单条短信
        $smsOperator = new SmsOperator();
        $data['mobile'] = $mobile;
        $data['text'] = "【尚嘉品鉴】尊敬的Champlus尚嘉品鉴会员{$real_name}：您好！您的会员卡于{$time}退款{$money}元,退款后余额为{$reMoney}元，感谢您的支持！希望您多提出宝贵的意见！会员专线：13810424267！";
        $result = $smsOperator->single_send($data);
        error_log( "*************".date('Y-m-d H:i:s')."*****************\n", 3, "log/sms.log" );
        error_log( print_r($result,true)."\n", 3, "log/sms.log" );

    }
    function ajaxPrintAddMoney () {
        $orderId = $_REQUEST["orderId"];
        $customerDao = new CustomerDao();
        $res = $customerDao->getAccountItemBySourceId($orderId);
        $print_message = mysql_fetch_array($res);
        //print_r(json_decode($print_message['print_json']));
        $this->printByMessage($print_message['print_json']);
        $data['code'] = 100000;
        $data['message'] = '打印成功';
        echo json_encode($data);
        exit;
    }

    function printByMessage ($printInfo) {
        //print_r($printInfo);exit;
        //$printInfo = $printInfo['text'];
        if (empty($printInfo)) {
            return;
        }
        header("Content-type: text/html; charset=utf-8");


        $apiKey       = 'cd63291a9a38904a11d90dfc908b8c1c9506a2ed';//apiKey
        // $mKey         = '84detvv43yah';//秘钥
        //$partner      = '4901';//用户id
        //$machine_code = '4004510510';//机器码
        $mKey         = 'zfn2rpnk232i';//秘钥
        $partner      = '4901';//用户id
        $machine_code = '4004533328';//机器码
        $ti = time();
        $params = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
            'time'=>$ti

        );
        $sign = PrintClass::generateSign($params,$apiKey,$mKey);

        $params['sign'] = $sign;
        $params['content'] = $printInfo;


        $url = 'open.10ss.net:8888';//接口端点

        $p = '';
        foreach ($params as $k => $v) {
            $p .= $k.'='.$v.'&';
        }
        $data = rtrim($p, '&');
        //return 1;
        return PrintClass::liansuo_post($url,$data);//exit;
    }
    function printAddMoney ($printInfo) {
        header("Content-type: text/html; charset=utf-8");
        $printObj = new PrintClass();
        $message = $printObj->getAddMoneyTitle();
        $tmp = $printObj->generateAddMoneyFormat("充值",$printInfo['money_val'],"");
        $message = $message.$tmp.'
';
        if (!empty($printInfo['coupon'])) {
            $tmp = $printObj->generateAddMoneyFormat("优惠券",$printInfo['coupon_name'],"");
            $message = $message.$tmp.'
';
        }
        if (!empty($printInfo['month'])) {
            $tmp = $printObj->generateAddMoneyFormat("优惠券",$printInfo['month_name'],"");
            $message = $message.$tmp.'
';
        }
        if (!empty($printInfo['val500'])) {
            $tmp = $printObj->generateAddMoneyFormat("优惠券",$printInfo['val500_name'],"");
            $message = $message.$tmp.'
';
        }
        if (!empty($printInfo['nian800'])) {
            $tmp = $printObj->generateAddMoneyFormat("会员年费","-".$printInfo['nian800_name'],"");
            $message = $message.$tmp.'
';
        }
        $adminPO = $_SESSION['admin'];
        $message .= '
--------------------------------
当前余额：';
        $message = $message.$printInfo['total_money'].'元

优惠券金额：'.$printInfo['sum_val'].'元
客户名称：'.$printInfo['customer_name'].'
制单人：  '.$printInfo['admin_name'].'
充值Id：'.$printInfo['addId'].'
时间：'.date("Y-m-d H:i:s").'
尚嘉品鉴欢迎您
电话：010-57755080
                               ';
        //echo $message;//exit;
        $customerDao = new CustomerDao();
        $print_array = array();
        $print_array['text'] = $message;
        $customerDao->updateAccountItemJson($message,$printInfo['orderSaveId']);
        $apiKey       = 'cd63291a9a38904a11d90dfc908b8c1c9506a2ed';//apiKey
        // $mKey         = '84detvv43yah';//秘钥
        //$partner      = '4901';//用户id
        //$machine_code = '4004510510';//机器码
        $mKey         = 'zfn2rpnk232i';//秘钥
        $partner      = '4901';//用户id
        $machine_code = '4004533328';//机器码
        $ti = time();
        $params = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
            'time'=>$ti

        );
        $sign = PrintClass::generateSign($params,$apiKey,$mKey);

        $params['sign'] = $sign;
        $params['content'] = $message;


        $url = 'open.10ss.net:8888';//接口端点

        $p = '';
        foreach ($params as $k => $v) {
            $p .= $k.'='.$v.'&';
        }
        $data = rtrim($p, '&');
        //return 1;
        return PrintClass::liansuo_post($url,$data);//exit;
    }
    function printOrder ($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$zhidanren_name,$payTypeName,$customerName,$more ='',$cash_val = 0,$otherObj = array()) {

        //$realTotalMoney += $cash_val;
        header("Content-type: text/html; charset=utf-8");
        $printObj = new PrintClass();
        $msg = '';
        if ($otherObj['un_save']) {
            $msg = '(样单)';
        }
        $message = $printObj->getTitle($msg);

        foreach ($printArray as $value) {
            //从数据库获得订单的具体信息
            //获得格式化打印字符串
            $tmp = $printObj->generateFormat($this->getCodeName($value['pro_code']),$value['price'],$value['pro_num'],1);
            $message = $message.$tmp.'
';
            //$total = $total+$fen*$menu[1];
        }
        //$message          = $message; //打印内容
        $isUseCoupon_str = '';
        $isOff_str = '';
        $sumMoney = $realTotalMoney;
        if (!empty($otherObj['isUseCoupon'])) {
            $sumMoney += $otherObj['subVal'];
            $tmp = $printObj->generateFormat("优惠券抵扣","-{$otherObj['subVal']}","");
            $isUseCoupon_str = '现金券抵扣：-'.$otherObj['subVal'].'元';
            $message = $message.$tmp.'
';
        }

        if(!empty($otherObj['monthCard'])) {
            $sumMoney += $otherObj['monthVal'];
            $tmp = $printObj->generateFormat("月卡抵扣","-{$otherObj['monthVal']}","剩余{$otherObj['demainVal']}");
            $message = $message.$tmp.'
';
        }
        if(!empty($otherObj['isVal500'])) {
            $sumMoney += $otherObj['val500'];
            $tmp = $printObj->generateFormat("500不找零抵扣","-{$otherObj['val500']}","");
            $message = $message.$tmp.'
';
        }
        if (!empty($otherObj['is_off'])) {

            $isOff_str = "申请特价：".$sumMoney;
        }
        $adminPO = $_SESSION['admin'];
        $ext = "";
        if ($cash_val > 0) {
            $ext = '
现金支付：'.$cash_val;
        }
        $card_no_str= '';
        if (!empty($otherObj['customer_no'])) {
            $card_no_str = '会员卡号：'.$otherObj['customer_no'];
        }
        $message = $message.'

备注：'.$more.'
--------------------------------
客户名称：'.$customerName.'
'.$card_no_str.'
制单人：  '.$zhidanren_name.'
销售人员：  '.$jingbanren_name.'
总价：';
        $message = $message.$totalMoney.'元
'.$isOff_str.'
'.$isUseCoupon_str.'
实收 ：'.$realTotalMoney.$ext.'
订单号：  '.$otherObj['order_no'].'
付款方式：  '.$payTypeName.'
时间：'.date("Y-m-d H:i:s").'
尚嘉品鉴欢迎您
电话：010-57755080
                               ';
        //echo $message;exit;
        $apiKey       = 'cd63291a9a38904a11d90dfc908b8c1c9506a2ed';//apiKey
       // $mKey         = '84detvv43yah';//秘钥
        //$partner      = '4901';//用户id
        //$machine_code = '4004510510';//机器码
        $mKey         = 'zfn2rpnk232i';//秘钥
        $partner      = '4901';//用户id
        $machine_code = '4004533328';//机器码
        $ti = time();
        $params = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
            'time'=>$ti

        );
        $sign = PrintClass::generateSign($params,$apiKey,$mKey);

        $params['sign'] = $sign;
        $params['content'] = $message;


        $url = 'open.10ss.net:8888';//接口端点

        $p = '';
        foreach ($params as $k => $v) {
            $p .= $k.'='.$v.'&';
        }
        $data = rtrim($p, '&');

        return PrintClass::liansuo_post($url,$data);//exit;
    }
    function printReturnOrder ($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$payTypeName,$customerName,$more ='') {
        header("Content-type: text/html; charset=utf-8");
        $printObj = new PrintClass();
        $message = $printObj->getReturnTitle();
        foreach ($printArray as $value) {
            //从数据库获得订单的具体信息
            //获得格式化打印字符串
            $tmp = $printObj->generateFormat($this->getCodeName($value['pro_code']),$value['price'],$value['pro_num']);
            $message = $message.$tmp.'
';
            //$total = $total+$fen*$menu[1];
        }
        //$message          = $message; //打印内容
        $adminPO = $_SESSION['admin'];
        $message = $message.'

备注：'.$more.'
--------------------------------
合计：';
        $message = $message.$totalMoney.'元

客户名称：'.$customerName.'
实际金额：'.$realTotalMoney.'
时间：'.date("Y-m-d H:i:s").'
销售人员：  '.$jingbanren_name.'
付款方式：  '.$payTypeName.'
尚嘉品鉴欢迎您
电话：010-57755080
                               ';
        //echo $message;exit;
        $apiKey       = 'cd63291a9a38904a11d90dfc908b8c1c9506a2ed';//apiKey
        $mKey         = 'zfn2rpnk232i';//秘钥
        $partner      = '4901';//用户id
        $machine_code = '4004533328';//机器码
        $ti = time();
        $params = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
            'time'=>$ti

        );
        $sign = PrintClass::generateSign($params,$apiKey,$mKey);

        $params['sign'] = $sign;
        $params['content'] = $message;


        $url = 'open.10ss.net:8888';//接口端点

        $p = '';
        foreach ($params as $k => $v) {
            $p .= $k.'='.$v.'&';
        }
        $data = rtrim($p, '&');

        return PrintClass::liansuo_post($url,$data);//exit;
    }
    private function sendSmsTest () {

// 1. 首先在 conf/config.php   中配置自己的相关信息

// 返回格式可参考官网:   www.yunpian.com
// 2. require the file
        require_once("tools/sms/YunpianAutoload.php");
// 获取用户信息
        $userOperator = new UserOperator();
        $result = $userOperator->get();
        //print_r($result);

// 发送单条短信
        $smsOperator = new SmsOperator();
        $data['mobile'] = '18501017983';
        $data['text'] = '【尚嘉品鉴】亲爱的张先生，您的尚嘉品鉴会员卡于2016-06-01消费100元，余额为500元，感谢支持。';
        $result = $smsOperator->single_send($data);
        //print_r($result);
        exit;
    }
    function getCodeName ($name) {
        $encode = 'utf-8';
        $str = '';
        for($i = 0; $i < mb_strlen($name,$encode);$i++){
            //包含中文处理
            if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",mb_substr($name,$i,1,$encode))){
                $str = mb_substr($name,$i,mb_strlen($name,$encode),$encode);
                break;
            }
        }
        if (empty($str)) {
            return $name;
        }
        return $str;
    }
    function updateOrder(){
        $ids = $_REQUEST['ids'];
        $orderIds = $_REQUEST['orderIds'];
        $price = $_REQUEST['price'];
        $proNum = $_REQUEST['proNum'];
        $sumMoney = $_REQUEST['sumMoney'];
        $totalMoney = $_REQUEST['totalMoney'];
        $realTotalMoney = $_REQUEST['realTotalMoney'];
        $customerId = $_REQUEST['customerId'];
        $orderNo = $_REQUEST['orderNo'];
        $dingDate = $_REQUEST['dingDate'];
        $custo_discount = $_REQUEST['custo_discount'];
        $isOff = $_REQUEST['isOff'];
        $data['code'] = 100000;
    
        $this->objDao=new CustomerDao();
        $customerPO=$this->objDao->getCustomerById($customerId);
        $this->objDao=new OrderDao();
  	    //开始事务
		$this->objDao->beginTransaction();

        $orderTotalPo['order_no']=$orderNo;//订单编号
        $orderTotalPo['chengjiaoer']=$totalMoney;
        $orderTotalPo['realChengjiaoer']=$realTotalMoney;
        $orderTotalPo['isOff']=$isOff;
        $result=$this->objDao->updateTotalOrderTabel($orderTotalPo);

        if(!$result){
            $this->objDao->rollback();
            $data['code'] = 100001;
            $data['message'] = "修改订单总表失败！";
            echo json_encode($data);
            exit;
        }
        for($i=0;$i<count($ids);$i++){
            $order=array();
            $this->objDao=new ProductDao();
            $productPO = $this->objDao->getProductById($ids[$i]);
            $order['order_no']=$orderTotalPo['order_no'];
            $order['id']=$orderIds[$i];
            $order['pro_id']=$ids[$i];
            $order['pro_code']=$productPO['pro_code'];
            $order['ding_date']=$dingDate;
            $order['jiao_date']=$dingDate;
            $order['pro_num']=$proNum[$i];
            $order['pro_type']=$productPO['pro_type'];
            $order['pro_unit']=$productPO['pro_unit'];
            $order['pro_price']=$productPO['pro_price'];
            $order['price']=$price[$i];
            $order['pro_flag']=$productPO['pro_flag'];
            $order['customer_id']=$customerId;
            $order['order_jiner']=$sumMoney[$i];
            $order['mark']='';
            $order['custo_name']=$customerPO['custo_name'];
            $order['zhekou']=$custo_discount;
            $this->objDao=new OrderDao();
            if (!empty($orderIds[$i])){
                $resultAdd=$this->objDao->updateOrder($order);
            } else {
                if (!$ids[$i]) continue;
                $resultAdd=$this->objDao->addOrder($order);
            }

            if(!$resultAdd){
                $this->objDao->rollback();
                $data['code'] = 100002;
                $data['message'] = "修改订单失败！";
                echo json_encode($data);
                exit;
            }
        }
			
	    if ($data['code'] == 100000) {
            $adminPO = $_SESSION['admin'];
            $opLog = array();
            $opLog['who'] = $adminPO['id'];
            $opLog['who_name'] = $adminPO['real_name'];
            $opLog['what'] = $orderNo;
            $opLog['Subject'] = OP_LOG_ORDER;
            $opLog['memo'] = OP_LOG_MODIFY_ORDER;
            $this->addOpLog($opLog);
        }
        //事务提交
        $this->objDao->commit();
        echo json_encode($data);
        exit;
    	
    	
    	
    }
   function delOrder(){
   	$orderId=$_REQUEST['orderId'];
   	$this->objDao=new OrderDao();
   	//开始事务    
	$this->objDao->beginTransaction();
	$orderTotalPO=$this->objDao->getOrderByOrderId($orderId);
   	$exmsg=new EC();//设置错误信息类
   	$orderTotalP=$this->objDao->getOrderTotalByOrderNo($orderTotalPO['order_no']);
   	$jiezhangType=$orderTotalP['jiezhang_type'];
   	if($jiezhangType==0){
    $result=$this->objDao->delOrderById($orderId);
   if(!$result){
					$exmsg->setError(__FUNCTION__, "del  or_order    faild ");
					$this->objForm->setFormData("warn","删除产品订单失败");
					//事务回滚  
					$this->objDao->rollback();
					$this->objDao->commit(); 
					throw new Exception ($exmsg->error());
				}
    $orderTotalP['chengjiaoer'] =$orderTotalP['chengjiaoer'] - $orderTotalPO['order_jiner'];
    $result=$this->objDao->updateOrderChengjiaoE($orderTotalP);
    $this->objDao=new CustomerDao();
    $cutomer=$this->objDao->getCustomerById($orderTotalPO['customer_id']);
  	$this->objDao=new OrderDao();
   $totalChengjiaoe=($cutomer['total_money']-$orderTotalPO['order_jiner']);
			$custLevel="潜在客户";
  	if($totalChengjiaoe<500000&&$totalChengjiaoe>0){
  		$custLevel="初级客户";
  	}elseif($totalChengjiaoe>=500000&&$totalChengjiaoe<2000000){
  		$custLevel="中级客户";
  	}elseif($totalChengjiaoe>=2000000){
  		$custLevel="高级客户";
  	}
  	$result=$this->objDao->updateCustomerChengjiaoe($totalChengjiaoe,$orderTotalPO['customer_id'],$custLevel);
   if(!$result){
					$exmsg->setError(__FUNCTION__, "del  or_order    faild ");
					$this->objForm->setFormData("warn","删除产品订单失败");
					//事务回滚  
					$this->objDao->rollback();
					$this->objDao->commit(); 
					throw new Exception ($exmsg->error());
				} 
  	//事务提交
	    $this->objDao->commit();  
	   if($result){
	    	echo "删除成功";
	    	
	    }else{
	    	echo "删除失败";
	    }
   	}elseif($jiezhangType==1){
   		echo "该定单已经结帐不能删除!";
   	}
    exit;	
   }
   function toPrintOrder(){
   	$this->mode="toPrint";
    $this->objDao=new OrderDao();
   	$result=$this->objDao->getUpdateOrderNoByOpTime();
  	    
  	    $this->objForm->setFormData("orderNo",$result['order_no']);
   }
   function toOrderCheck(){
   	$this->mode="toCheck";
   	$today=date("Y-m-d");
   	$this->objDao=new OrderDao();
   	$orderList=$this->objDao->getOrderTotal(1);
   	$this->objForm->setFormData("orderList",$orderList);
   }
   function toOrderChuku(){
   	$this->mode="toChuku";
   	$today=date("Y-m-d");
   	$this->objDao=new OrderDao();
   	$orderList=$this->objDao->getOrderTotal(1);
   	$this->objForm->setFormData("orderList",$orderList);
   }
   function getOrderListByOrderNo(){
   	$this->mode="orderSearch";
   	$orderId=$_REQUEST['orId'];
   	$this->objDao=new OrderDao();
   	$orderList=$this->objDao->getOrderList("","","",$orderId);
   	$this->objForm->setFormData("orderList",$orderList);
   	
   }
   function toPrintInvoice(){
   	$this->mode="toInvoice";
   	$this->objDao=new OrderDao();
   	$result=$this->objDao->getUpdateOrderNoByOpTime();
  	    
  	    $this->objForm->setFormData("orderNo",$result['order_no']);
   }
   function getOrderList(){
   	$orderDate=$_REQUEST['orderDate'];
   	$orderId=$_REQUEST['orderId'];
   	$custName=$_REQUEST['custName'];
   	$this->mode="toPrint";
   	$today=date("Y-m-d");
   	$this->objDao=new OrderDao();
   	$result=$this->objDao->getOrderList($orderDate,$custName,"",$orderId);
   	$orderList=array();
   	$i=0;
   	while ($row=mysql_fetch_array($result) ){
   		if($i===0){
   			$custName=$row['custo_name'];
   		}
   		$orderList[$i]=$row;
   		$i++;
   	}
   	$this->objForm->setFormData("orderList",$orderList);
   	$this->objForm->setFormData("dateType",$orderDate);
   	$this->objForm->setFormData("orderId",$orderId);
   	$this->objForm->setFormData("custName",$custName);
   }
   function getOrderCheckList(){
   	
   	$orderId=$_REQUEST['orderId'];
   	$custName=$_REQUEST['custName'];
   	$fromTo=$_REQUEST['fromTo'];
   	$orderFrom=$_REQUEST['orderFrom'];
    $orderTo=$_REQUEST['orderTo'];
   	$this->mode="toCheck";
   	$today=date("Y-m-d");
   	$this->objDao=new OrderDao();
   	if(!empty($orderFrom)&&!empty($orderTo)){
   	   $orderList=$this->objDao->getOrderTotalListByFanwei($fromTo,$orderFrom,$orderTo);
   	   $this->objForm->setFormData("fromTo",$fromTo);
   	   $this->objForm->setFormData("from",$orderFrom);
   	   $this->objForm->setFormData("to",$orderTo);
   	}else{
   	$orderList=$this->objDao->getOrderTotal("",$custName,"",$orderId);
   	$this->objForm->setFormData("custName",$custName);
   	$this->objForm->setFormData("orderId",$orderId);
   	}
   	$this->objForm->setFormData("orderList",$orderList);
   	$this->objForm->setFormData("dateType",$orderDate);
   }
   function getOrderChukuList(){
   	$orderId=$_REQUEST['orderId'];
   	$orderFrom=$_REQUEST['orderFrom'];
    $orderTo=$_REQUEST['orderTo'];
    $this->objDao=new OrderDao();
    $this->mode="toChuku";
   if(!empty($orderFrom)&&!empty($orderTo)){
   	   $fromTo=1;
   	   $orderList=$this->objDao->getOrderTotalListByFanwei($fromTo,$orderFrom,$orderTo);
   	   $this->objForm->setFormData("fromTo",$fromTo);
   	   $this->objForm->setFormData("from",$orderFrom);
   	   $this->objForm->setFormData("to",$orderTo);
   	}else{
   	$orderList=$this->objDao->getOrderTotal("","","",$orderId);
   	$this->objForm->setFormData("orderId",$orderId);
   	}
   	$this->objForm->setFormData("orderList",$orderList);
   }
   function orderCheck(){
   	$orderNo=$_REQUEST['orderNo'];
   	$checktype=$_REQUEST['checktype'];
   	$this->objDao=new OrderDao();
   	$result=$this->objDao->orderCheck($orderNo,$checktype);
   	$orderList=$this->objDao->getOrderTotal("","","",$orderNo);
   	$this->objForm->setFormData("orderList",$orderList);
   	$this->mode="toCheck";
   }
   function orderChuku(){
   	$orderNo=$_REQUEST['orderNo'];
   	$checktype=$_REQUEST['checktype'];
   	$this->objDao=new OrderDao();
   	$result=$this->objDao->orderChuku($orderNo,$checktype);
   	$orderList=$this->objDao->getOrderTotal("","","",$orderNo);
   	$this->objForm->setFormData("orderList",$orderList);
   	$this->mode="toChuku";
   }
   function orderCheckList(){
   	$orderListStr=$_REQUEST["orderNoList"];
   	$checktype=$_REQUEST["checkType"];
   	$orderList=split(",",$orderListStr);
   	$this->objDao=new OrderDao();
   	$str="";
   	foreach ($orderList as $orderNo){
   		if($orderNo=="")continue;
   		$result=$this->objDao->orderCheck($orderNo,$checktype);
   	}
   	$this->mode="toCheck";
   	$orderListStr=substr($orderListStr,0,-1); 
   	$orderList=$this->objDao->getOrderListByIds($orderListStr);
   	$this->objForm->setFormData("orderList",$orderList);
   }
   function orderChukuList(){
   	$orderListStr=$_REQUEST["orderNoList"];
   	$checktype=$_REQUEST["checkType"];
   	$orderList=split(",",$orderListStr);
   	$this->objDao=new OrderDao();
   	$str="";
   	foreach ($orderList as $orderNo){
   		if($orderNo=="")continue;
   		$result=$this->objDao->orderChuku($orderNo,$checktype);
   	}
   	$this->mode="toChuku";
   	$orderListStr=substr($orderListStr,0,-1); 
   	$orderList=$this->objDao->getOrderListByIds($orderListStr);
   	$this->objForm->setFormData("orderList",$orderList);
   }
   function getOrderByIdAjax(){
   	$orderId=$_REQUEST['orderId'];
   	$this->objDao=new OrderDao();
    $result=$this->objDao->getOrderById($orderId);
    $orderList=array();

    $i=0;
   	while ($row=mysql_fetch_array($result)){
   		
   		if($i==0){
   			$company=$this->objDao->getCustomerById($row['customer_id']);
            $orderList['custo_discount'] = $company['custo_discount'];
   		}
        $orderList['data'][] = $row;

   		$i++;
   	}
   	echo json_encode($orderList);
   	exit;
   }
   function getOrderById () {
       $this->mode = 'toOrderDetail';
       $orderId=$_REQUEST['orderId'];
       $this->objDao=new CustomerDao();
       $result = $this->objDao->getCustomerLevelList();
       $jingliPO = $this->objDao->getJingbanrenList();
       $jingliList =array();
       while($val = mysql_fetch_array($jingliPO)){
           $jingliList[$val['id']] = $val["jingbanren_name"];
       }
       $levelList = array();
       while ($row = mysql_fetch_array($result)) {
           $levelList[$row['id']]= $row;
       }
       $this->objDao=new OrderDao();



       $orderTotal=$this->objDao->getOrderTotalByOrderNo($orderId);
       $result=$this->objDao->getOrderById($orderId);
       $orderList=array();
       $customer = array();
       $i=0;
       while ($row=mysql_fetch_array($result)){

           if($i==0){
               $customer=$this->objDao->getCustomerById($row['customer_id']);
               $level = $levelList[$customer['custo_level']];
               $customer['custo_level_name'] = $level['level_name'];
               $orderList['custo_discount'] = $customer['custo_discount'];
           }
           $productModel = $this->objDao->getProductById($row['pro_id']);
           $row['market_price'] = $productModel['market_price'];
           $row['vip_price'] = $productModel['vip_price'];
           $orderList['data'][] = $row;

           $i++;
       }
       $where = ' subject = "'.OP_LOG_ORDER.'"';
       $logResult = $this->objDao->getOpLogByTaskId($orderId,$where);
       $logList = array();
       while ($row=mysql_fetch_array($logResult)){
           $logList[] = $row;
       }
       $this->objForm->setFormData("customer",$customer);
       $this->objForm->setFormData("orderNo",$orderId);
       $this->objForm->setFormData("logList",$logList);
       $this->objForm->setFormData("jingliList",$jingliList);
       $this->objForm->setFormData("orderTotal",$orderTotal);
       $this->objForm->setFormData("orderList",$orderList);
   }
   function getOrderListForInvoice(){
   	$orderDate=$_REQUEST['orderDate'];
   	$orderId=$_REQUEST['orderId'];
   	$custName=$_REQUEST['custName'];
   	$this->mode="toInvoice";
   	$today=date("Y-m-d");
   	$this->objDao=new OrderDao();
	$result=$this->objDao->getOrderTotal("","","",$orderId);
	$orderTotal=mysql_fetch_array($result);
	
   	$orderList=$this->objDao->getOrderList($orderDate,$custName,"",$orderId);
	if(empty($custName)){
		$custName=$orderTotal['custer_name'];
	}
   	$this->objForm->setFormData("orderList",$orderList);
   	$this->objForm->setFormData("dateType",$orderDate);
    $this->objForm->setFormData("orderTotal",$orderTotal);
	$this->objForm->setFormData("orderNo",$orderId);
	$this->objForm->setFormData("custName",$custName);
   }
   function orderPrintTest() {
        $danwei = $_POST["danwei"];
        $lianxiren = $_POST["lianxiren"];
        $lianxifangshi = $_POST["lianxifangshi"];
        $songdizhi = $_POST["songdizhi"];
        $songDate = $_POST["songDate"];
        $orderId = $_POST["orderId"];
       $this->objDao = new OrderDao();
       $result=$this->objDao->getOrderById($orderId);
       $orderTotal=$this->objDao->getOrderTotalByOrderNo($orderId);
       $orderList=array();
       $i=1;
       $productDao = new ProductDao();
       while ($row=mysql_fetch_array($result)){
            $obj = array();
           $productModel = $productDao->getProductById($row['pro_id']);
           $obj[0] = $i;
           $obj[1] = $productModel['c_name'];
           $obj[2] = $productModel['e_name'];
           $obj[3] = $productModel['pro_address'];
           $obj[4] = $row['pro_num'];
           $obj[5] = $productModel['market_price'];
           $obj[6] = $row['price'];
           $obj[7] = $row['order_jiner'];
           $orderList[] =$obj;
           $i++;
       }
       $shijieChengjiaoer = $orderTotal['realChengjiaoer'];
       ob_end_clean();
       $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
       $pdf->setPrintHeader(false);
       $pdf->setPrintFooter(false);
       $pdf->Open();
       $pdf->SetMargins(2, 10,PDF_MARGIN_RIGHT);
       $pdf->AddPage();
       $fontSize = 10;
       $Fonthight = 7;
       $image = "common/img/indexlogo.jpg";
       /******************************************
        * 标题
        ******************************************/
       $pdf->SetFont('stsongstdlight','B',13);
       $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
       $pdf->Image("common/img/indexlogo.jpg", 87, 1, 30, '', 'JPG', '', '', true, 300, '', false, false, 0, "C", false, false);
       $pdf->Ln(17, false);
       $pdf->Cell(0,2,"北京尚嘉品鉴销售清单（发货单）",0,1,"C");
       $pdf->Ln(5, false);
       /******************************************
        * 分割线
        ******************************************/
       $pdf->SetFont('stsongstdlight','B',5);
       //$pdf->Cell(0,2,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
       $x1 = $pdf->GetX();
       $y1 = $pdf->GetY();
       $pdf->Line(0, $y1-1, 400, $y1-1, $style=array());
       $pdf->SetFont('stsongstdlight','B',9);
       $pdf->setCellPaddings(5, 0, 0, 0);
       $pdf->Cell(0,3,"购货单位：{$danwei}                                                                                                                             日期: {$songDate}",0,0,"L");
       $pdf->Cell(0,1,"",0,1);
       $pdf->Cell(0,3,"联系人：{$lianxiren}                                                                                                                                   联系方式:010-57755080",0,0,"L");
       $pdf->Cell(0,1,"",0,1);
       $pdf->Cell(0,5,"联系方式:{$lianxifangshi}                                                                                                                              发 货 人：CHAMPLUS尚嘉品鉴",0,0,"L");
       $pdf->Cell(0,1,"",0,1);
       $pdf->Cell(0,5,"地址: {$songdizhi}                                                                                                                                     地    址：北京市朝阳区东三环北路27号嘉铭中心B1",0,0,"L");
       $x1 = $pdf->GetX();
       $y1 = $pdf->GetY();
       $pdf->Line(0, $y1+5, 400, $y1+5, $style=array());
       $pdf->Ln(10, false);
       $text = '您好！欢迎选用CHAMPLUS尚嘉品鉴的葡萄酒产品和服务。本公司供应的产品之规格型号、数量及单价均已在本发货单上注明，请购货方在发货前确认并付款，本公司在收到款后7天内安排到货，谢谢合作！';
       $pdf->MultiCell(180, 3, $text, $border=0, $align='L',$fill=false, $ln=1, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);
       $pdf->Ln(10, false);
       $header = array(0=>"序列",1=>"货物英文名称",2=>"货物中文名称",3=>"产区",4=>"数量/瓶",5=>"零售价",6=>"会员价",7=>"会员总价");
       $data = array();
       $pdf->setCellPaddings(0, 0, 0, 0);
       $this->ColoredTable($header,$orderList,$pdf,$shijieChengjiaoer);
       $pdf->setCellPaddings(5, 0, 0, 0);
       $pdf->Ln(10, false);
       $pdf->Cell(0,3,"对公账户	1109 1659 4310 301                                 ",0,0,"L");
       $pdf->Cell(0,1,"",0,1);
       $pdf->Cell(0,3,"         开户名：北京尚嘉品鉴信息咨询有限公司	 ",0,0,"L");
       $pdf->Cell(0,1,"",0,1);
       $pdf->Cell(0,3,"         开户行：招商银行股份有限公司北京东四环支行		 ",0,0,"L");
       $pdf->Ln(10, false);
       $pdf->Cell(0,3,"一般账户	收款账号：6222 6209 1003 9718 402                                 ",0,0,"L");
       $pdf->Cell(0,3,"",0,1);
       $pdf->Cell(0,3,"         开户名：姚玉梅		 ",0,0,"L");
       $pdf->Cell(0,3,"",0,1);
       $pdf->Cell(0,3,"         开户行：交通银行北京东三环支行			 ",0,0,"L");
       $pdf->Ln(10, false);
       $text = "备注：购货单位应于提货之前按照合同规定向供货方付款，并对货物进行验收。若发现货物短少、规格型号不符或者质量缺陷影响使用的，应立即与供货方联系！				 ";
       $pdf->MultiCell(180, 3, $text, $border=0, $align='L',$fill=false, $ln=1, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);
       $pdf->Cell(0,3,"",0,1);
       $pdf->Cell(0,3,"",0,1);
       $pdf->Cell(0,3," 发货单位签章：北京尚嘉品鉴信息咨询有限公司   	                                                    客户确认：",0,0,"L");

       $pdf->SetDisplayMode("real");
       $date_dir = date('Ymd');
       $pdf->Output();
   }
    private function ColoredTable($header,$data,$pdf,$shijieChengjiaoer) {
        // Colors, line width and bold font
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');

        // Header
        $w = array(8,65,60,15,13,13,15,15);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;
        $strLen = mb_strlen("荣誉维拉格纳查干红葡萄酒","utf8");
        $en_strLen = strlen("les bonheurs vino tempranillo 2015");
        $strLen = 30;
        $en_strLen = 35;
        foreach($data as $row) {
            $pdf->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $ch_name = $row[2];
            $str = "";
            if ($len = mb_strlen($row[2],"utf8") > $strLen) {
                $ch_name = mb_substr($row[2], 0, $strLen,"utf8");
                $strArr = explode($ch_name,$row[2]);
                $str = $strArr[1];
            }
            $en_name = $row[1];
            $en_str = "";
            if ($en_len = mb_strlen($row[1],"utf8") > $en_strLen) {
                $en_name = mb_substr($row[1], 0, $en_strLen,"utf8");
                $strArr = explode($en_name,$row[1]);
                $en_str = $strArr[1];
            }
            $pdf->Cell($w[1], 6, $en_name, 'LR', 0, 'L', $fill);


            $pdf->Cell($w[2], 6, $ch_name, 'LR', 0, 'C', $fill);
            $pdf->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill);
            $pdf->Cell($w[4], 6, $row[4], 'LR', 0, 'C', $fill);
            $pdf->Cell($w[5], 6, $row[5], 'LR', 0, 'C', $fill);
            $pdf->Cell($w[6], 6, $row[6], 'LR', 0, 'C', $fill);
            $pdf->Cell($w[7], 6, $row[7], 'LR', 0, 'C', $fill);
            $pdf->Ln();

            if (!empty($str) || !empty($en_str)) {
                $this->setAddCell($str,$en_str,$pdf,$w,$fill);
            }
            $fill=!$fill;
        }
        $pdf->Cell($w[0], 6, "", 'LT', 0, 'C', $fill);
        $pdf->Cell($w[1], 6, "", 'T', 0, 'L', $fill);
        $pdf->Cell($w[2], 6, "", 'T', 0, 'C', $fill);
        $pdf->Cell($w[3], 6, "", 'T', 0, 'C', $fill);
        $pdf->Cell($w[4], 6, "", 'T', 0, 'C', $fill);
        $pdf->Cell($w[5], 6, "合计：", 'T', 0, 'C', $fill);
        $pdf->Cell($w[6], 6, $shijieChengjiaoer, 'T', 0, 'C', $fill);
        $pdf->Cell($w[7], 6, "", 'RT', 0, 'C', $fill);
        $pdf->Ln();
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }
    private function setAddCell ($str,$en_str,$pdf,$w,$fill) {

        $pdf->Cell($w[0], 6, "", 'LR', 0, 'C', $fill);
        $pdf->Cell($w[1], 6, $en_str, 'LR', 0, 'L', $fill);
        $pdf->Cell($w[2], 6, $str, 'LR', 0, 'C', $fill);
        $pdf->Cell($w[3], 6, "", 'LR', 0, 'C', $fill);
        $pdf->Cell($w[4], 6, "", 'LR', 0, 'C', $fill);
        $pdf->Cell($w[5], 6, "", 'LR', 0, 'C', $fill);
        $pdf->Cell($w[6], 6, "", 'LR', 0, 'C', $fill);
        $pdf->Cell($w[7], 6, "", 'LR', 0, 'C', $fill);
        $pdf->Ln();
    }
   function orderPrint(){
			$orderNo=$_REQUEST['orderId'];
			$songDate=$_REQUEST['songDate'];
			$orderType=$_REQUEST['orderType'];
			//$today=date("Y-m-d");
            if($orderType==2){
            	$songDate=" ";
            }
			$songAdress=$_REQUEST['songAdress'];
			$songTel=$_REQUEST['songTel'];
			$telPerson=$_REQUEST['telPerson'];
			$mark=$_REQUEST['mark'];
			$this->objDao=new OrderDao();
			$result=$this->objDao->updateOrderTotalSongList($songAdress,$telPerson,$songTel,$songDate,$orderNo,$mark);
		    $result=$this->objDao->getOrderTotal("","","",$orderNo);
			$orderTotal=mysql_fetch_array($result);
			$orderPO=$this->objDao->getOrderById($orderNo);
			$orderList=array();
			$i=1;
			/*while ($row=mysql_fetch_array($orderPO)){
				$orderList[$i]=$row;
				$i++;
			}*/
			ob_end_clean();
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->Open();
            $pdf->AddPage();
            
            $fontSize = 10;
            $Fonthight = 7;
            /******************************************
             * 标题
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',13);
            $pdf->Image("logo.jpg", 70, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(70,2,"北京泛太成远装饰材料有限公司",0,1,"C");
            $pdf->SetFont('stsongstdlight','B',13);
           if($orderType==1){
            	$title=" 产 品 订 单  ";
            	$pdf->Cell(205,2," 产 品 订 单  ",0,1,"C");
            }elseif($orderType==2){
            	$title=" 出 库 通 知 单";
            	$pdf->Cell(205,2," 出 库 通 知 单",0,1,"C");
            }
            
            /******************************************
             * 分割线
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',5);
            $pdf->Cell(0,1,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");

            $pdf->SetFont('stsongstdlight','B',11);//11hao
            $today=date("Y-m-d");
            for($i=0;$i<30;$i++){$space1.=" ";}
            $pdf->Cell(0,2,"PPB/XZ/QD/7.2.2-4-2013                                               订单日期：".date("Y-m-d")."              
                                订单编号: $orderNo   W",0,1);

            //客户信息
            $pdf->SetFont('stsongstdlight','B',$fontSize);
            if($orderType==1){
            $pdf->Cell(150,$Fonthight,"订货单位: {$orderTotal['custer_name']}",1,0);
            
            }elseif($orderType==2){
             $pdf->Cell(150,$Fonthight,"合同编号",1,0);	
            }
            //$pdf->Cell(30,$Fonthight,"送货安排：",1,0);
            $pdf->Cell(40,$Fonthight,"出 货 情 况",1,0,"C");
            $pdf->Cell(0,$Fonthight,"","L",1);
            $lenn=strlen($songDate);
            $a1=substr($songDate,0,21);
            $a2=substr($songDate,21,$lenn);
            if($orderType==2){
               $songAdress="";
			   $telPerson="";
			   $songTel="";
			   $mark="";
			   $a1="";
				}
			$pdf->Cell(120,$Fonthight,"送货地址：$songAdress",1,0);
            $pdf->Cell(30,$Fonthight,"送货安排：","L",0);
			$pdf->Cell(40,$Fonthight,"出库日期：","RL",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
           
            $pdf->Cell(40,$Fonthight,"联系人：$telPerson ",1,0);
            $pdf->Cell(80,$Fonthight,"联系电话：$songTel ",1,0);
            $pdf->Cell(30,$Fonthight,"$songDate","L",0);
			$pdf->Cell(40,$Fonthight,"______年____月____日","RL",0);
			$pdf->Cell(0,$Fonthight,"","L",1);
           
            $pdf->Cell(150,$Fonthight,"备      注：$mark",1,0);
            //$pdf->Cell(110,$Fonthight,"$mark",1,0);
			$pdf->Cell(40,$Fonthight,"产品完好无损，数量无误","RL",0);
			$pdf->Cell(0,$Fonthight,"","L",1);
		   /******************************************
             * 循环列表部分
             ******************************************/
		    $pdf->Cell(28,$Fonthight,"商品型号",1,0,"C");
            $pdf->Cell(53,$Fonthight,"规格",1,0,"C");
            $pdf->Cell(10,$Fonthight,"单位",1,0,"C");
            $pdf->Cell(17,$Fonthight,"数量",1,0,"C");
            $pdf->Cell(12,$Fonthight,"根数",1,0,"C");
			$pdf->Cell(30,$Fonthight,"备注",1,0,"C");
            $pdf->Cell(40,$Fonthight,"请客户验收签字：","RL",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
			$num=mysql_num_rows($orderPO);
            $i=1;
            while ($row=mysql_fetch_array($orderPO)){
            $pdf->SetFont('stsongstdlight','B',11);	
		    $pdf->Cell(28,$Fonthight,"{$row['pro_code']}","L",0);
            $pdf->Cell(53,$Fonthight,"{$row['pro_spec']}","L",0);
            $pdf->Cell(10,$Fonthight,"{$row['pro_unit']}","L",0,"C");
            $pdf->Cell(17,$Fonthight,"{$row['pro_num']}","L",0,"R");
            $pdf->Cell(12,$Fonthight,"{$row['pro_genNum']}","L",0,"C");
            $pdf->SetFont('stsongstdlight','B',$fontSize);
            //$mark1 =substr($orderList[1]['mark'],0,15);
            //$mark2=substr($orderList[1]['mark'],15,30);
			$pdf->Cell(30,$Fonthight,"{$row['mark']} ","L",0);
            $tiTle="";
                if($i==3){
                	if($orderType==1){
                	$tiTle="客户意见：";	
                	}else{
                	$tiTle="";	
                	}
                	
                	
                }elseif($i==5){
					$tiTle="";
				}
				elseif($num==$i&&$num<29){
					if($orderType==1){
					$tiTle="名称：石膏装饰制品";
					}else{
					$tiTle="";		
					}
				}
				
			if($i==1){
				$pdf->Cell(40,$Fonthight,"","RL",0);
			}
			elseif($i==2){
				$pdf->Cell(40,$Fonthight,"_____________________","RL",0);
			}elseif($i==3){
				$pdf->Cell(40,9,$tiTle,"L",0);
			}else{	
				$pdf->Cell(40,9,"","L",0);
			}
            
            $pdf->Cell(0,$Fonthight,"","L",1);
            $i++;
            if($i%29==0){
            $pdf->Cell(28,$Fonthight,"","LB",0);
            $pdf->Cell(53,$Fonthight,"","LB",0);
            $pdf->Cell(10,$Fonthight,"","LB",0);
            $pdf->Cell(17,$Fonthight,"","LB",0);
            $pdf->Cell(12,$Fonthight,"","LB",0);
			$pdf->Cell(30,$Fonthight,"","LB",0);
            if($orderType==1){
					$tiTle="";
		    }else{
				    $tiTle="";		
			}
            $pdf->Cell(40,$Fonthight,"$tiTle","RLB",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
			$num+=1;
            	/********************************************************
            	 * 分页部分                                                                                                                         *
            	 ********************************************************/
            $pdf->AddPage();
            
            $fontSize = 10;
            $Fonthight = 7;
            /******************************************
             * 标题
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',13);
            $pdf->Image("logo.jpg", 70, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(70,2,"北京泛太成远装饰材料有限公司",0,1,"C");
            $pdf->SetFont('stsongstdlight','B',13);
           if($orderType==1){
            	$title=" 产 品 订 单  ";
            	$pdf->Cell(205,2," 产 品 订 单  ",0,1,"C");
            }elseif($orderType==2){
            	$title=" 出 库 通 知 单";
            	$pdf->Cell(205,2," 出 库 通 知 单",0,1,"C");
            }
            
            /******************************************
             * 分割线
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',5);
            $pdf->Cell(0,1,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");

            $pdf->SetFont('stsongstdlight','B',11);
            $today=date("Y-m-d");
            for($i=0;$i<30;$i++){$space1.=" ";}
            $pdf->Cell(0,2,"PPB/XZ/QD/7.2.2-4-2013                                               订单日期：".date("Y-m-d")."              
                                订单编号: $orderNo   W",0,1);

            //客户信息
            $pdf->SetFont('stsongstdlight','B',$fontSize);
            if($orderType==1){
            $pdf->Cell(150,$Fonthight,"订货单位: {$orderTotal['custer_name']}",1,0);
            
            }elseif($orderType==2){
             $pdf->Cell(150,$Fonthight,"合同编号",1,0);	
            }
            //$pdf->Cell(30,$Fonthight,"送货安排：",1,0);
            $pdf->Cell(40,$Fonthight,"出 货 情 况",1,0,"C");
            $pdf->Cell(0,$Fonthight,"","L",1);
            $lenn=strlen($songDate);
            $a1=substr($songDate,0,21);
            $a2=substr($songDate,21,$lenn);
            
			$pdf->Cell(120,$Fonthight,"送货地址:$songAdress",1,0);
            $pdf->Cell(30,$Fonthight,"送货安排：","L",0);
			$pdf->Cell(40,$Fonthight,"出库日期：","RL",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
           
            $pdf->Cell(40,$Fonthight,"联系人：$telPerson ",1,0);
            $pdf->Cell(80,$Fonthight,"联系电话：$songTel ",1,0);
            $pdf->Cell(30,$Fonthight,"$songDate","L",0);
			$pdf->Cell(40,$Fonthight,"______年____月____日","RL",0);
			$pdf->Cell(0,$Fonthight,"","L",1);
           
            $pdf->Cell(40,$Fonthight,"备      注：",1,0);
            $pdf->Cell(110,$Fonthight,"$mark",1,0);
			$pdf->Cell(40,$Fonthight,"产品完好无损，数量无误","RL",0);
			$pdf->Cell(0,$Fonthight,"","L",1);
		   /******************************************
             * 循环列表部分
             ******************************************/
		    $pdf->Cell(28,$Fonthight,"商品型号",1,0,"C");
            $pdf->Cell(53,$Fonthight,"规格",1,0,"C");
            $pdf->Cell(10,$Fonthight,"单位",1,0,"C");
            $pdf->Cell(17,$Fonthight,"数量",1,0,"C");
            $pdf->Cell(12,$Fonthight,"根数",1,0,"C");
			$pdf->Cell(30,$Fonthight,"备注",1,0,"C");
            $pdf->Cell(40,$Fonthight,"请客户验收签字：","RL",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
            }
			}
			if($i>29){
				$i=$i%29;
			}
			if($i<=7){
			for($z=$i;$z<=7;$z++){

			$pdf->Cell(28,$Fonthight,"","L",0);
            $pdf->Cell(53,$Fonthight,"","L",0);
            $pdf->Cell(10,$Fonthight,"","L",0);
            $pdf->Cell(17,$Fonthight,"","L",0);
            $pdf->Cell(12,$Fonthight,"","L",0);
			$pdf->Cell(30,$Fonthight,"","L",0);
			$tiTle="";
				if($z==3){
                	
				if($orderType==1){
					$tiTle="客户意见：";
					}else{
					$tiTle="";		
					}
                }elseif($z==5){
					$tiTle="";
				}
				elseif($z==7){
					if($orderType==1){
					$tiTle="名称：石膏装饰制品";
					}else{
					$tiTle="";		
					}
				}
			if($z==1){
				$pdf->Cell(40,$Fonthight,"","RL",0);
			}
			elseif($z==2){
				$pdf->Cell(40,$Fonthight,"_____________________","RL",0);
			}elseif($z==3){
				$pdf->Cell(40,9,$tiTle,"L",0);
			}else{	
				$pdf->Cell(40,9,$tiTle,"L",0);
			}
            $pdf->Cell(0,$Fonthight,"","L",1);
			}
			}else{
			$pdf->Cell(28,$Fonthight,"","L",0);
            $pdf->Cell(53,$Fonthight,"","L",0);
            $pdf->Cell(10,$Fonthight,"","L",0);
            $pdf->Cell(17,$Fonthight,"","L",0);
            $pdf->Cell(12,$Fonthight,"","L",0);
			$pdf->Cell(30,$Fonthight,"","L",0);
            if($orderType==1){
					$tiTle="名称：石膏装饰制品";
		    }else{
				    $tiTle="";		
			}
            $pdf->Cell(40,$Fonthight,"$tiTle","RL",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
			}
			$pdf->Cell(28,$Fonthight,"","LB",0);
            $pdf->Cell(53,$Fonthight,"","LB",0);
            $pdf->Cell(10,$Fonthight,"","LB",0);
            $pdf->Cell(17,$Fonthight,"","LB",0);
            $pdf->Cell(12,$Fonthight,"","LB",0);
			$pdf->Cell(30,$Fonthight,"","LB",0);
            if($orderType==1){
					$tiTle="品牌： 太平洋";
					}else{
					$tiTle="";		
					}
			$num+=1;
            $pdf->Cell(40,$Fonthight,"$tiTle","RL",0);
            $pdf->Cell(0,$Fonthight,"","L",1);
            //$pdf->Cell(190,2,"",1,0);
            // $pdf->Cell(0,$Fonthight,"服","L",1);
            	if($orderType==1){
            $pdf->Cell(150,5,"提示 ： 1.本产品送达至货车可到达位置原地卸货，不负责搬运入户，敬请谅解。","L",0,"L");
            	}else{
            	$pdf->Cell(150,5,"","L",0,"L");
            	}
			$pdf->Cell(40,5,"注：请将产品平置存放于","LT",0);
			$pdf->Cell(0,5,"","L",1);
			if($orderType==1){
            $pdf->Cell(150,5,"                2.本产品属于特殊定制产品，无质量问题不退换货，谢谢合作。","L",0,"L");
			}else{
			$pdf->Cell(150,5,"","L",0,"L");
				
			}
			$pdf->Cell(40,5,"室内,并注意防损、防潮！","L",0);
			$pdf->Cell(0,5,"","L",1);
		   //服务商信息
			if($orderType==1){
            $pdf->Cell(150,5,"公司地址：北京市朝阳区广渠路3号竞园51-D    电话：87212708/18/28    传真：87212738",1,0,"L");
			}else{
			$pdf->Cell(150,5,"",1,0,"L");
			}
			$pdf->Cell(40,5,"","LB",0);
			$pdf->Cell(0,5,"","L",1);

            $pdf->Cell(0,7,"厂务:                                库房:                               司机 :                                    车号:                                      订单员: {$orderTotal['op_name']}           业务员:  {$orderTotal['jingbanren']}",0,1);
           // $pdf->Cell(0,$Fonthight," ","L",1);

            $pdf->SetDisplayMode("real");
            $date_dir = date('Ymd');
            $pdf->Output();
   	
   	
   }
   function invoicePrint(){
   	
   	 $orderNo=$_REQUEST['orderId'];
			$yunfei=$_REQUEST['yunfei'];
			$shigong=$_REQUEST['shigong'];
			$dingjin=$_REQUEST['dingjin'];
			$endDate=$_REQUEST['endDate'];
			$totaljin=$_REQUEST['totaljin'];
			$baozhuang=$_REQUEST['baozhuang'];
			$invoiceType=$_REQUEST['invoiceType'];
			$mujufei=$_REQUEST['mujufei'];
			$orderId=$_REQUEST['orderID'];
			$this->objDao=new OrderDao();
			//开始事务    
		    $this->objDao->beginTransaction();
		    $exmsg=new EC();//设置错误信息类
		    for($i=0;$i<count($orderId);$i++){
		    	
			$result=$this->objDao->updateOrderMujufei($orderId[$i],$mujufei[$i]);
		     if(!$result){
					$exmsg->setError(__FUNCTION__, "insert into or_order    faild ");
					$this->objForm->setFormData("warn","修改产品订单失败");
					//事务回滚  
					$this->objDao->rollback();
					throw new Exception ($exmsg->error());
				}
		    }
			$orderModel=array();
			$orderModel['yunfei']=$yunfei;
			$orderModel['shigongfei']=$shigong;
			$orderModel['baozhuangfei']=$baozhuang;
			$orderModel['fapiao_type']=$invoiceType;
			$orderModel['order_no']=$orderNo;
			$orderModel['chengjiaoer']=$totaljin;
			$exmsg=new EC();
			$result=$this->objDao->updateInvoiceOrder($orderModel);
           if(!$result){
					$exmsg->setError(__FUNCTION__, "update  InvoiceOrder  faild ");
					$this->objForm->setFormData("warn","修改订单发票失败");
					//事务回滚  
					$this->objDao->rollback();
					throw new Exception ($exmsg->error());
				}
			$orderPO=$this->objDao->getOrderById($orderNo);
			$result=$this->objDao->getOrderTotal("","","",$orderNo);
			$orderTotal=mysql_fetch_array($result);
			$orderList=array();
			
	        //事务提交
	        $this->objDao->commit();
	    
			ob_end_clean();
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->Open();
            $pdf->AddPage();

            $fontSize = 11;
            $Fonthight = 7;
            
            $pdf->SetFont('stsongstdlight','B',14);
            $pdf->Image("logo.jpg", 60, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(75,2,"北京泛太成远装饰材料有限公司",0,1,"C");
            $pdf->SetFont('stsongstdlight','B',14);
            $pdf->Cell(0,2,"商品发票清单",0,1,"C");
            /*$pdf->Image("3G_logo.jpg",10,5,40,15);*/
            
             /******************************************
             * 分割线
             * $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,1,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
            
             ******************************************/
            

            $pdf->SetFont('stsongstdlight','B',$fontSize);
            for($i=0;$i<30;$i++){$space1.=" ";}
            //$pdf->Cell(0,1,"PPB/XZ/QD/7.2.2-5-2013",0,1);
            //$pdf->Cell(0,1,"订货单位：{$orderTotal['custer_name']}                        订货日期：{$orderTotal['ding_date']}                 订单编号: $orderNo $invoiceType",0,1);
			$custNameStr=$orderTotal['custer_name'];
			if(strlen($custNameStr)>50) $custNameStr=substr($custNameStr,0,50);
			$pdf->Cell(85,$Fonthight,"订货单位：$custNameStr",0,0);
			$pdf->Cell(65,$Fonthight,"订货日期：{$orderTotal['ding_date']}",0,0);
			$pdf->Cell(0,$Fonthight,"订单编号: $orderNo  $invoiceType",0,1);
            $pdf->SetFont('stsongstdlight','',3);
			$pdf->Cell(0,1," ",0,1);
            //客户信息
            $pdf->SetFont('stsongstdlight','B',$fontSize);
		   /******************************************
             * 循环部分
             ******************************************/
		    $pdf->Cell(30,$Fonthight,"商品型号",1,0,"C");
            $pdf->Cell(60,$Fonthight,"规格",1,0,"C");
            $pdf->Cell(15,$Fonthight,"单位",1,0,"C");
            $pdf->Cell(20,$Fonthight,"数量",1,0,"C");
            $pdf->Cell(20,$Fonthight,"单价",1,0,"C");
            $pdf->Cell(20,$Fonthight,"金额",1,0,"C");
            $pdf->Cell(20,$Fonthight,"模具费",1,1,"C");
            $totalMuju=0.00;
            $totalJine=0.00;
            $i=1;
			while ($row=mysql_fetch_array($orderPO)){
				$row['pro_num']=sprintf("%01.2f",$row['pro_num']);
			$pdf->Cell(30,$Fonthight,"{$row['pro_code']}","L",0);
            $pdf->Cell(60,$Fonthight,"{$row['pro_spec']}","L",0);
            $pdf->Cell(15,$Fonthight,"{$row['pro_unit']}","L",0,"C");
            $pdf->Cell(20,$Fonthight,"{$row['pro_num']}","L",0,"C");
            $pdf->Cell(20,$Fonthight,"{$row['pro_price']}","L",0,"C");
			$pdf->Cell(20,$Fonthight,"{$row['order_jiner']}","L",0,"C");
			$pdf->Cell(20,$Fonthight,"{$mujufei[($i-1)]}","RL",1,"C");
			$totalMuju+=$mujufei[($i-1)]+0.00;
			$totalJine+=$row['order_jiner'];
			$i++;
			}
            for($z=$i;$z<=7;$z++){

			$pdf->Cell(30,$Fonthight,"","L",0);
            $pdf->Cell(60,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(20,$Fonthight,"0.00","L",0,"C");
            $pdf->Cell(20,$Fonthight,"0.00","L",0,"C");
            $pdf->Cell(20,$Fonthight,"0.00","L",0,"C");
			$pdf->Cell(20,$Fonthight,"0.00","RL",1,"C");
			}
			$pdf->Cell(30,$Fonthight,"","LB",0);
            $pdf->Cell(60,$Fonthight,"","LB",0);
            $pdf->Cell(15,$Fonthight,"","LB",0);
            $pdf->Cell(20,$Fonthight,"","LB",0);
            $pdf->Cell(20,$Fonthight,"","LB",0);
			
            $totalJine=sprintf("%01.2f",$totalJine);
            $pdf->Cell(20,$Fonthight,"$totalJine","LB",0,"C");
            $totalMuju=sprintf("%01.2f",$totalMuju);
			$pdf->Cell(20,$Fonthight,"$totalMuju","RLB",1,"C");

            
            $totaljin=sprintf("%01.2f",$totaljin);
			$pdf->SetFont('stsongstdlight','',3);
			$pdf->Cell(0,1," ",0,1);
            $pdf->SetFont('stsongstdlight','B',$fontSize); 
           // $pdf->Cell(0,5,"总金额：    $totaljin	                     运费：    $yunfei		                   施工费：    $shigong		              包装费：       $baozhuang",0,1);
			
            $pdf->Cell(50,$Fonthight,"总金额：    $totaljin",0,0);
			$pdf->Cell(50,$Fonthight,"运费：      $yunfei",0,0);
			$pdf->Cell(50,$Fonthight,"施工费：    $shigong",0,0);
			$pdf->Cell(50,$Fonthight,"包装费：    $baozhuang",0,1);
            $pdf->Cell(0,3,"业务员：{$orderTotal['jingbanren']}    				                                                                                                                   结算时间：$endDate
            ",0,1);
            $pdf->Cell(0,3,"公司地址:  北京市朝阳区广渠路3号竞园51-D      电话: 87212708/18/28        传真: 87212738  ",0,1);
           // $pdf->Cell(0,$Fonthight," ","L",1);

            $pdf->SetDisplayMode("real");
            $date_dir = date('Ymd');
            $pdf->Output();
   	
   	
   }
   function orderSearch(){
   	$this->mode="orderSearch";
   	
   	
   }
   function toOrderReturnCancel(){
   	     $this->mode="toOrderReturnCancel";
   	
   }
   function toOrderReturn(){
   	$this->mode="orderReturn";
   	$orderNo=$_REQUEST['orderId'];
   	if(!empty($orderNo)){
   	$this->objDao=new OrderDao();
   	$result=$this->objDao->getOrderList("","","",$orderNo);
   	$orderList=array();
   	$i=0;
   	while ($row=mysql_fetch_array($result) ){

   		$sumResult=$this->objDao->getOrderReturnSumNum($row['id']);
   		$row['reNum']=$sumResult['sumReNum'];
   		$orderList[$i]=$row;
   		$i++;
   	}

   	$orderTotal=$this->objDao->getOrderTotal("","","",$orderNo);
   	$this->objForm->setFormData("orderList",$orderList);
   	$this->objForm->setFormData("orderNo",$orderNo);
   	$this->objForm->setFormData("orderTotal",mysql_fetch_array($orderTotal));
   	}
   }
   function searchOrderReturn(){
   $orderId=$_REQUEST['orderNo'];
   $date=$_REQUEST['date'];
   	$this->objDao=new OrderDao();
    $result=$this->objDao->searchReturnByDateOrderNo($orderId,$date);
    $orderList="";
    /**
     * `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL,
  `pro_code` varchar(200) NOT NULL,
  `return_date` date DEFAULT NULL,
  `pro_num` float(11,2) DEFAULT NULL,
  `pro_spec` varchar(200) DEFAULT NULL,
  `pro_unit` varchar(50) DEFAULT NULL,
  `pro_price` float(10,2) DEFAULT NULL,
  `pro_genNum` int(10) DEFAULT NULL,
  `pro_flag` int(2) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `return_jiner` float(11,2) DEFAULT NULL,
  `custo_name` varchar(100) DEFAULT NULL,
     */
   	while ($row=mysql_fetch_array($result)){
   		$str="";
   	    $str.="$".$row['id']."$".$row['order_no']."$".$row['pro_code']."$".$row['pro_spec']."$".$row['pro_unit']."$".$row['pro_num']
   	         ."$".$row['pro_genNum']."$".$row['return_jiner']."$".$row['order_id'] ;
   		$orderList.="|".$str;
   	}
   	echo $orderList;
   	exit;
   	
   }
   function getReturnDate(){
   	$orderNo=$_REQUEST['orderNo'];
   	$this->objDao=new OrderDao();
    $result=$this->objDao->getReturnDate($orderNo);
    $orderList="";
   	while ($row=mysql_fetch_array($result)){
   		$orderList.="|".$row['return_date'];
   	}
   	echo $orderList;
   	exit;
   }
   function cancelReturn(){
   	 $this->mode="toOrderReturnCancel";
   	$orderNo=$_REQUEST['returnNo'];
    $returnId=$_REQUEST['returnId'];
   	$returnJine=$_REQUEST['totalJin'];
   	$orderIds=$_REQUEST['orderId'];
   	
   	$this->objDao=new OrderDao();
   	//开始事务    
		$this->objDao->beginTransaction();
		$exmsg=new EC();//设置错误信息类
    $rs=$this->objDao->getOrderTotal("","","",$orderNo[0]);
    $orderPo=mysql_fetch_array($rs);
	$result=$this->objDao->updateTotalOrderForReturnJin(($orderPo['tuikuanger']-$returnJine),$orderNo[0]);
    if(!$result){
				$exmsg->setError(__FUNCTION__, "update orderTotal faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","更新退款额失败 订单编号：{$orderNo[0]}");
				throw new Exception ($exmsg->error());
			}
	 $this->objDao=new CustomerDao();
    $cutomer=$this->objDao->getCustomerById($orderPo['custer_no']);
    $this->objDao=new OrderDao();
   $totalChengjiaoe=($cutomer['total_money']+$returnJine);
			$custLevel="潜在客户";
  	if($totalChengjiaoe<500000&&$totalChengjiaoe>0){
  		$custLevel="初级客户";
  	}elseif($totalChengjiaoe>=500000&&$totalChengjiaoe<2000000){
  		$custLevel="中级客户";
  	}elseif($totalChengjiaoe>=2000000){
  		$custLevel="高级客户";
  	}
    //修改客户累计销售金额
               $result=$this->objDao->updateCustomerChengjiaoe($totalChengjiaoe,$orderPo['custer_no'],$custLevel); 
			   if(!$result){
					$exmsg->setError(__FUNCTION__, "update  or_order  tuikuane  faild ");
					$this->objForm->setFormData("warn","修改订单退款失败！");
					//事务回滚  
					$this->objDao->rollback();
					throw new Exception ($exmsg->error());
				}
   $i=0;
   	foreach ($returnId as $id){
   		$result=$this->objDao->deleteReturnById($id);
   	    if(!$result){
				$exmsg->setError(__FUNCTION__, "update orderTotal faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","取消返厂记录失败，id:$id");
				throw new Exception ($exmsg->error());
				break;
			}
		$result=$this->objDao->updateOrderForReturnJine(0.0,$orderIds[$i]);	
   	     if(!$result){
				$exmsg->setError(__FUNCTION__, "update order returnjine faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","更新返厂金额失败，id:$id");
				throw new Exception ($exmsg->error());
				break;
			}
	     $i++;
   	}
   	$this->objForm->setFormData("succ","取消返厂记录成功");
   	//事务提交
   	$this->objDao->commit();
   }


   function toSearchOrderListAddress () {
       $this->mode="toSearchOrderListAddress";
   }
    function searchOrderListByAddress () {
        $this->mode ="toSearchOrderListAddress";
        ini_set("max_execution_time", "1800");
        $this->objDao=new OrderDao();
        $address=$_REQUEST['address'];
        $orderbyType=$_REQUEST['orderbyType'];
        $whereCount = " song_adress  like '%{$address}%' ";
        $sum =$this->objDao->g_db_count("or_order_total","*",$whereCount);
        //$sum=10;
        $pagesize=PAGE_SIZE;
        //$sum=$rs['sum'];
        $count = intval($_REQUEST["c"]);
        $page = intval($_REQUEST["p"]);
        if ($count == 0){
            $count = $pagesize;
        }
        if ($page == 0){
            $page = 1;
        }

        $startIndex = ($page-1)*$count;
        $total = $sum;
        $pageindex=$page;
        //得到商品列表
        $listwhere =" limit $startIndex,$pagesize";
        $where=array();

        $where['address']=$address;
        $searchStr=$address;
        $orderList=$this->objDao->searchOrderTotalListByAddress($where,$listwhere);
        $i =0;
        while($row=mysql_fetch_array($orderList)){
            $orderSearchList[$i]['order_no']=$row['order_no'];
            $orderSearchList[$i]['custer_no']=$row['custer_no'];
            $orderSearchList[$i]['custer_name']=$row['custer_name'];
            $orderSumJin = $this->objDao->getSumOrderJinByOrderNo($row['order_no']);
            $orderSearchList[$i]['chengjiaoer']=$orderSumJin + $row['yunfei'] + $row['shigongfei'] + $row['baozhuangfei'];
            $orderSearchList[$i]['tuikuanger']=$row['tuikuanger'];
            $orderSearchList[$i]['zhekou']=$row['zhekou'];
            $orderSearchList[$i]['isChuku']=$row['isChuku'];
            $orderSearchList[$i]['ding_date']=$row['ding_date'];
            $orderSearchList[$i]['yunfei']=$row['yunfei'];
            $orderSearchList[$i]['shigongfei']=$row['shigongfei'];
            $orderSearchList[$i]['baozhuangfei']=$row['baozhuangfei'];
            $orderSearchList[$i]['jingbanren']=$row['jingbanren'];
            $orderSearchList[$i]['fapiao_type']=$row['fapiao_type'];
            $orderSearchList[$i]['song_adress']=$row['song_adress'];
            if($row['jiezhang_type']==1){
                $orderSearchList[$i]['jiezhang_type']='已结款';
            }else{
                $orderSearchList[$i]['jiezhang_type']='未结款';
            }
            $i++;
        }
        $orderNum=$i;
        $this->objForm->setFormData("startIndex",$startIndex);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("pageindex",$pageindex);
        $this->objForm->setFormData("pagesize",$pagesize);
        $this->objForm->setFormData("orderNum",$orderNum);
        $this->objForm->setFormData("orderList",$orderSearchList);
        $this->objForm->setFormData("orderType",$orderbyType);
        $this->objForm->setFormData("searchStr",$searchStr);
    }
   function toSearchOrderList(){
   	  $this->mode="toSearchOrderList";
   	  $date_month = $_REQUEST['date'];
       $this->objDao = new OrderDao();
       $date_from = $date_month."-01";
       $date_to = $date_month."-31";
       $result = $this->objDao->getOrderTotalListByFanwei(0,$date_from,$date_to);
       $orderList = array();
       global $payType;
       global $jiezhangType;
       while ($row = mysql_fetch_array($result)) {
           $order['id'] = $row['id'];
           $order['order_no'] = $row['order_no'];
           $order['ding_date'] = $row['ding_date'];
           $order['custer_name'] = $row['custer_name'];
           $order['custer_name'] = $row['custer_name'];
           $order['chengjiaoer'] = $row['chengjiaoer'];
           $order['realChengjiaoer'] = $row['realChengjiaoer'];
           $order['isOff'] = $row['isOff'];
           $order['pay_status'] = $jiezhangType[$row['pay_status']];
           $order['pay_type'] = $payType[$row['pay_type']];
           $orderList[] = $order;
       }
       $this->objForm->setFormData("orderList",$orderList);
   	
   }
   function orderListPrint(){
   	$dateFrom=$_REQUEST['datefrom'];
   	$dateTo=$_REQUEST['dateto'];
   	$searchType=$_REQUEST['searchType'];
   	$printType=$_REQUEST['printTy'];
   	$totalProNum=$_REQUEST['totalProNum'];
   	$totalReturnProNum=$_REQUEST['totalReturnProNum'];
   	$orderNum=$_REQUEST['orderNum'];
   	$orderTotalJine=$_REQUEST['orderTotalJine'];
   	$orderTotalJine=sprintf("%01.2f",$orderTotalJine);
   	$orderTotalReturnJine=$_REQUEST['orderTotalReturnJine'];
   	$orderTotalReturnJine=sprintf("%01.2f",$orderTotalReturnJine);
   	$pFrom=$_REQUEST['pFrom'];
   	$pTo=$_REQUEST['pTo'];
   	$searchStr=$_REQUEST['searchTtr'];
   	$totalProGenNum=$_REQUEST['totalProGenNum'];
   	session_start();
    $orderSearchList=$_SESSION['orderSearchList'];
    /*var_dump($orderSearchList);
    exit;*/
    $countNum=count($orderSearchList);
    $pageSize=38;
    $pageFrom=0;
    $pageTo=0;
    if($printType==1){
    	$pageFrom=0;
    	$pageTo=$pageSize;
    }elseif($printType==3){
    	$pageFrom=$pFrom*$pageSize-1;
    	$pageTo=$pTo*$pageSize;
    }elseif($printType==4){
    	$pageFrom=0;
    	$pageTo=$countNum;
    }
   // echo $pageSize."---".$pageFrom."---".$pageTo."---".$countNum;
   // exit;
   	ob_end_clean();
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->Open();
            $pdf->AddPage();

           $fontSize = 12;
            $Fonthight = 7;
            /******************************************
             * 标题
             ******************************************/
            $strBao="";
            if($printType!=2){
            	$strBao="报表";
            }
            $pdf->SetFont('stsongstdlight','B',16);
            $pdf->Image("logo.jpg", 55, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(93,2,"北京泛太成远装饰材料有限公司$strBao",0,1,"C");
            if($printType==2){
            $pdf->Cell(0,2,"订单查询总加表",0,1,"C");
            }
            /******************************************
             * 分割线
             ******************************************/
            $pdf->SetFont('stsongstdlight','',7);
            $pdf->Cell(0,2,"",0,1);
           //$pdf->Cell(0,2,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',10);
            $strHead="";
   if($searchType==1){
   	global $chukuType;
   	  	$strHead="按出库情况查询：{$chukuType[$searchStr]}";
   	  	
   	  }elseif($searchType==3){
   	  	$strHead="按业务员查询：$searchStr ";

   	  }elseif($searchType==4){
   	  	if($searchStr==1){
   	  		$searchStr='已结款';
   	  	}else{
   	  		$searchStr='未结款';
   	  	}
   	  	$strHead="按结账情况查询：$searchStr ";
   	  	
   	  }elseif($searchType==5){
   	  	$strHead="按产品组标查询：$searchStr ";
   	  	
   	  }elseif($searchType==6){
   	  	$strHead="按商品型号查询：$searchStr ";
   	  	
   	  }elseif($searchType==7){
   	  	$lenn=strlen($searchStr);
        $a1=substr($searchStr,0,21);
        $a2=substr($searchStr,21,$lenn);
   	  	$strHead="按客户名称查询：$a1 ";
   	  	
   	  }elseif($searchType==8){
   	  	global $customerType;
   	  	$strHead="按客户类型查询：{$customerType[$searchStr]}";
   	  }elseif($searchType==9){
   	  	//按经办人组查询
   	  	$strHead="按业务组查询：$searchStr";
   	  }
            for($i=0;$i<30;$i++){$space1.=" ";}
            //$pdf->Cell(0,5,"查询日期：   $dateFrom 至  $dateTo                                     $strHead                                                                   打印日期：".date('Y')."-".date('m')."-".date('d')."",0,1);
			$pdf->Cell(70,$Fonthight,"查询日期：   $dateFrom 至  $dateTo ",0,0);
			$pdf->Cell(80,$Fonthight,"$strHead",0,0);
			$pdf->Cell(0,2," 打印日期：".date('Y')."-".date('m')."-".date('d'),0,1);
            $pdf->Cell(70,$Fonthight,"",0,0);
			$pdf->Cell(0,$Fonthight,"             $a2",0,1);
           if($printType==2){
           //if(1){
           	$fontSize = 13;
            $Fonthight = 8;
            $pdf->SetFont('stsongstdlight','B',$fontSize);
           	if($searchType==5||$searchType==6){
           	$pdf->Cell(20,$Fonthight,"",0,0,"C");
           	$pdf->Cell(70,$Fonthight,"售 出 总 数 量",1,0,"C");
            $pdf->Cell(90,$Fonthight,"{$totalProNum}",1,1,"C");
            $pdf->Cell(20,$Fonthight,"",0,0,"C");
           	$pdf->Cell(70,$Fonthight,"售 出 总 根  数 ",1,0,"C");
            $pdf->Cell(90,$Fonthight,"{$totalProGenNum}",1,1,"C");
            $pdf->Cell(20,$Fonthight,"",0,0,"C");
            $pdf->Cell(70,$Fonthight,"返 厂  总 数 量",1,0,"C");
            $pdf->Cell(90,$Fonthight,"{$totalReturnProNum}",1,1,"C");
           	}
           	$pdf->Cell(20,$Fonthight,"",0,0,"C");
           	$pdf->Cell(70,$Fonthight,"总   合   同   数",1,0,"C");
            $pdf->Cell(90,$Fonthight,"{$orderNum}",1,1,"C");
            $pdf->Cell(20,$Fonthight,"",0,0,"C");
            $pdf->Cell(70,$Fonthight,"成       交      额",1,0,"C");
            $pdf->Cell(90,$Fonthight,"{$orderTotalJine}",1,1,"C");
            $pdf->Cell(20,$Fonthight,"",0,0,"C");
            $pdf->Cell(70,$Fonthight,"退       款      额",1,0,"C");
            $pdf->Cell(90,$Fonthight,"{$orderTotalReturnJine}",1,1,"C");
            $pdf->Cell(20,$Fonthight,"",0,0,"C");
            $pdf->Cell(70,$Fonthight,"实   际   金  额",1,0,"C");
            $shijijine=$orderTotalJine-$orderTotalReturnJine;
            $shijijine=sprintf("%01.2f",$shijijine);
            $pdf->Cell(90,$Fonthight,"{$shijijine}",1,1,"C");
           }else{
            $fontSize = 9;
            $Fonthight = 6;
            //客户信息
            $pdf->SetFont('stsongstdlight','',$fontSize);
        if($searchType==5||$searchType==6){
		    $pdf->Cell(15,$Fonthight,"订单编号",1,0,"C");
            $pdf->Cell(38,$Fonthight,"商品编号",1,0,"C");
            $pdf->Cell(40,$Fonthight,"商品规格",1,0,"C");
            $pdf->Cell(20,$Fonthight,"单价",1,0,"C");
            $pdf->Cell(20,$Fonthight,"订货数量",1,0,"C");
            $pdf->Cell(10,$Fonthight,"单位",1,0,"C");
            $pdf->Cell(15,$Fonthight,"折扣率",1,0,"C");
            $pdf->Cell(20,$Fonthight,"返厂数量",1,0,"C");
            $pdf->Cell(20,$Fonthight,"订货日期",1,1,"C");
            $z=1;
          foreach ($orderSearchList as $row){
          	if($z<$pageFrom){
          		$z++;
          		continue;
          	}
          	$fontSize = 10;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
          	$row['pro_num']=sprintf("%01.2f",$row['pro_num']);
          	$row['return_num']=sprintf("%01.2f",$row['return_num']);
          	$strName=$row['custo_name'];
		    $pdf->Cell(15,$Fonthight,"{$row['order_no']}",1,0);
		    
            $pdf->Cell(38,$Fonthight,"{$row['pro_code']}",1,0);
            $pdf->Cell(40,$Fonthight,"{$row['pro_spec']}",1,0);
            $pdf->Cell(20,$Fonthight,"{$row['pro_price']}",1,0);
            $pdf->Cell(20,$Fonthight,"{$row['pro_num']}",1,0);
            $pdf->Cell(10,$Fonthight,"{$row['pro_unit']}",1,0);
            $pdf->Cell(15,$Fonthight,"{$row['zhekou']}",1,0);
            $pdf->Cell(20,$Fonthight,"{$row['return_num']}",1,0);
            $pdf->Cell(20,$Fonthight,"{$row['ding_date']}",1,1);
            if($z%$pageSize==0&&$z<$pageTo){
		            	$pdf->AddPage();
		
		    $fontSize = 13;
            $Fonthight = 7;
            /******************************************
             * 标题
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',16);
            $pdf->Image("logo.jpg", 55, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(93,2,"北京泛太成远装饰材料有限公司$strBao",0,1,"C");
            if($printType==2){
            $pdf->Cell(0,2,"订单查询总加表",0,1,"C");
            }
            /******************************************
             * 分割线
             ******************************************/
            $pdf->SetFont('stsongstdlight','',7);
            $pdf->Cell(0,2,"",0,1);
           //$pdf->Cell(0,2,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',10);

            for($i=0;$i<30;$i++){$space1.=" ";}
            //$pdf->Cell(0,5,"查询日期：   $dateFrom 至  $dateTo                         $strHead                     打印日期：".date('Y')."-".date('m')."-".date('d')."",0,1);
            $pdf->Cell(80,$Fonthight,"查询日期：   $dateFrom 至  $dateTo ",0,0);
			$pdf->Cell(80,$Fonthight,"$strHead",0,0);
			$pdf->Cell(0,$Fonthight," 打印日期：".date('Y')."-".date('m')."-".date('d'),0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);   
            $fontSize = 9;
            $Fonthight = 6;
			            //客户信息
			$pdf->SetFont('stsongstdlight','',$fontSize);
			$pdf->Cell(15,$Fonthight,"订单编号",1,0,"C");
            $pdf->Cell(38,$Fonthight,"商品编号",1,0,"C");
            $pdf->Cell(40,$Fonthight,"商品规格",1,0,"C");
            $pdf->Cell(20,$Fonthight,"单价",1,0,"C");
            $pdf->Cell(20,$Fonthight,"订货数量",1,0,"C");
            $pdf->Cell(10,$Fonthight,"单位",1,0,"C");
            $pdf->Cell(15,$Fonthight,"折扣率",1,0,"C");
            $pdf->Cell(20,$Fonthight,"返厂数量",1,0,"C");
            $pdf->Cell(20,$Fonthight,"订货日期",1,1,"C");
            }
            $z++;
            if($z>$pageTo){
            	break;
            }
			}
			
            
         }else{//按经办人
         	
            $pdf->Cell(13,$Fonthight,"订单编号",1,0,"C");
            $pdf->Cell(65,$Fonthight,"客户名称",1,0,"C");
            $pdf->Cell(17,$Fonthight,"成交额",1,0,"C");
            $pdf->Cell(17,$Fonthight,"退款额",1,0,"C");
            $pdf->Cell(12,$Fonthight,"运费",1,0,"C");
            $pdf->Cell(12,$Fonthight,"施工费",1,0,"C");
            $pdf->Cell(12,$Fonthight,"包装费",1,0,"C");
            $pdf->Cell(10,$Fonthight,"折扣",1,0,"C");
            $pdf->Cell(7,$Fonthight,"出库",1,0,"C");
            $pdf->Cell(17,$Fonthight,"订货日期",1,0,"C");
            $pdf->Cell(7,$Fonthight,"结款",1,0,"C");
            $pdf->Cell(10,$Fonthight,"业务员",1,1,"C");
            
            $z=1;
           foreach ($orderSearchList as $row){
           if($z<$pageFrom){
           	$z++;
          		continue;
          	}
          	$strName=$row['custer_name'];
          	 if(strlen($strName)>58) $strName=substr($strName,0,58);
          	 $fontSize = 10;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
		    $pdf->Cell(13,$Fonthight,"{$row['order_no']}",1,0);
		     $fontSize = 9;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
            $pdf->Cell(65,$Fonthight,$strName,1,0);
             $fontSize = 10;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
            $pdf->Cell(17,$Fonthight,"{$row['chengjiaoer']}",1,0);
            $pdf->Cell(17,$Fonthight,"{$row['tuikuanger']}",1,0);
            $pdf->Cell(12,$Fonthight,"{$row['yunfei']}",1,0);
            $pdf->Cell(12,$Fonthight,"{$row['shigongfei']}",1,0);
            $pdf->Cell(12,$Fonthight,"{$row['baozhuangfei']}",1,0);
            $pdf->Cell(10,$Fonthight,"{$row['zhekou']}",1,0);
            $pdf->Cell(7,$Fonthight,"{$row['isChuku']}",1,0);
            $pdf->Cell(17,$Fonthight,"{$row['ding_date']}",1,0,"L");
            if($row['jiezhang_type']=='已结款'){
            	$row['jiezhang_type']='是';
            }else{
            	$row['jiezhang_type']='否';
            }
            $pdf->Cell(7,$Fonthight,"{$row['jiezhang_type']}",1,0);
            $pdf->Cell(10,$Fonthight,"{$row['jingbanren']}",1,1);
            
            
           if($z%$pageSize==0&&$z<$pageTo){
            
		            	$pdf->AddPage();
		
		          $fontSize = 12;
            $Fonthight = 7;
            /******************************************
             * 标题
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',16);
            $pdf->Image("logo.jpg", 55, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(93,2,"北京泛太成远装饰材料有限公司$strBao",0,1,"C");
            if($printType==2){
            $pdf->Cell(0,2,"订单查询总加表",0,1,"C");
            }
            /******************************************
             * 分割线
             ******************************************/
            $pdf->SetFont('stsongstdlight','',7);
            $pdf->Cell(0,2,"",0,1);
           //$pdf->Cell(0,2,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',10);

            for($i=0;$i<30;$i++){$space1.=" ";}
            //$pdf->Cell(0,5," 查询日期：   $dateFrom 至  $dateTo                 $strHead                  打印日期：".date('Y')."-".date('m')."-".date('d')."",0,1);
            $pdf->Cell(80,$Fonthight,"查询日期：   $dateFrom 至  $dateTo ",0,0);
			$pdf->Cell(80,$Fonthight,"$strHead",0,0);
			$pdf->Cell(0,$Fonthight," 打印日期：".date('Y')."-".date('m')."-".date('d'),0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);   
            $fontSize = 9;
            $Fonthight = 6;
			            $pdf->SetFont('stsongstdlight','',$fontSize);
			            //客户信息
			$pdf->Cell(13,$Fonthight,"订单编号",1,0,"C");
            $pdf->Cell(65,$Fonthight,"客户名称",1,0,"C");
            $pdf->Cell(17,$Fonthight,"成交额",1,0,"C");
            $pdf->Cell(17,$Fonthight,"退款额",1,0,"C");
            $pdf->Cell(12,$Fonthight,"运费",1,0,"C");
            $pdf->Cell(12,$Fonthight,"施工费",1,0,"C");
            $pdf->Cell(12,$Fonthight,"包装费",1,0,"C");
            $pdf->Cell(10,$Fonthight,"折扣",1,0,"C");
            $pdf->Cell(7,$Fonthight,"出库",1,0,"C");
            $pdf->Cell(17,$Fonthight,"订货日期",1,0,"C");
            $pdf->Cell(7,$Fonthight,"结款",1,0,"C");
            $pdf->Cell(10,$Fonthight,"业务员",1,1,"C");
	            }
	            $z++;
	            if($z>$pageTo){
	            	break;
	            }
			}
         }
         if(0){
         	
            $pdf->Cell(13,$Fonthight,"订单编号",1,0,"C");
            $pdf->Cell(65,$Fonthight,"客户名称",1,0,"C");
            $pdf->Cell(20,$Fonthight,"成交额",1,0,"C");
            $pdf->Cell(15,$Fonthight,"退款额",1,0,"C");
            $pdf->Cell(10,$Fonthight,"折扣",1,0,"C");
            $pdf->Cell(7,$Fonthight,"出库",1,0,"C");
            $pdf->Cell(15,$Fonthight,"订货日期",1,0,"C");
            $pdf->Cell(12,$Fonthight,"运费",1,0,"C");
            $pdf->Cell(12,$Fonthight,"施工费",1,0,"C");
            $pdf->Cell(12,$Fonthight,"包装费",1,0,"C");
            $pdf->Cell(10,$Fonthight,"业务员",1,0,"C");
            $pdf->Cell(7,$Fonthight,"结款",1,1,"C");
            $z=1;
           foreach ($orderSearchList as $row){
           if($z<$pageFrom){
           	$z++;
          		continue;
          	}
          	$strName=$row['custer_name'];
          	 if(strlen($strName)>58) $strName=substr($strName,0,58);
          	 $fontSize = 10;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
		    $pdf->Cell(13,$Fonthight,"{$row['order_no']}",1,0);
		    $fontSize = 9;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
            $pdf->Cell(65,$Fonthight,$strName,1,0);
            $fontSize = 10;
		    $pdf->SetFont('stsongstdlight','B',$fontSize);
            $pdf->Cell(20,$Fonthight,"{$row['chengjiaoer']}",1,0);
            $pdf->Cell(15,$Fonthight,"{$row['tuikuanger']}",1,0);
            $pdf->Cell(10,$Fonthight,"{$row['zhekou']}",1,0);
            $pdf->Cell(7,$Fonthight,"{$row['isChuku']}",1,0);
            $pdf->Cell(15,$Fonthight,"{$row['ding_date']}",1,0);
            $pdf->Cell(12,$Fonthight,"{$row['yunfei']}",1,0);
            $pdf->Cell(12,$Fonthight,"{$row['shigongfei']}",1,0);
            $pdf->Cell(12,$Fonthight,"{$row['baozhuangfei']}",1,0);
            $pdf->Cell(10,$Fonthight,"{$row['jingbanren']}",1,0);
           if($row['jiezhang_type']=='已结款'){
            	$row['jiezhang_type']='是';
            }else{
            	$row['jiezhang_type']='否';
            }
            $pdf->Cell(7,$Fonthight,"{$row['jiezhang_type']}",1,1);
            
           if($z%$pageSize==0&&$z<$pageTo){
            
		            	$pdf->AddPage();
		
		          $fontSize = 12;
            $Fonthight = 7;
            /******************************************
             * 标题
             ******************************************/
            $pdf->SetFont('stsongstdlight','B',16);
            $pdf->Image("logo.jpg", 55, 8, 8, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Cell(93,2,"北京泛太成远装饰材料有限公司$strBao",0,1,"C");
            if($printType==2){
            $pdf->Cell(0,2,"订单查询总加表",0,1,"C");
            }
            /******************************************
             * 分割线
             ******************************************/
            $pdf->SetFont('stsongstdlight','',7);
            $pdf->Cell(0,2,"",0,1);
           //$pdf->Cell(0,2,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"C");
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
            $pdf->SetFont('stsongstdlight','',10);

            for($i=0;$i<30;$i++){$space1.=" ";}
            //$pdf->Cell(0,5,"查询日期：   $dateFrom 至  $dateTo                 $strHead                  打印日期：".date('Y')."-".date('m')."-".date('d')."",0,1);
            $pdf->Cell(80,$Fonthight,"查询日期：   $dateFrom 至  $dateTo ",0,0);
			$pdf->Cell(80,$Fonthight,"$strHead",0,0);
			$pdf->Cell(0,$Fonthight," 打印日期：".date('Y')."-".date('m')."-".date('d'),0,1);
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);   
            $fontSize = 9;
            $Fonthight = 6;
			            $pdf->SetFont('stsongstdlight','',$fontSize);
			            //客户信息
			            $pdf->Cell(12,$Fonthight,"订单编号",1,0,"C");
			            $pdf->Cell(65,$Fonthight,"客户名称",1,0,"C");
			            $pdf->Cell(20,$Fonthight,"成交额",1,0,"C");
			            $pdf->Cell(15,$Fonthight,"退款额",1,0,"C");
			            $pdf->Cell(10,$Fonthight,"折扣",1,0,"C");
			            $pdf->Cell(7,$Fonthight,"出库",1,0,"C");
			            $pdf->Cell(15,$Fonthight,"订货日期",1,0,"C");
			            $pdf->Cell(12,$Fonthight,"运费",1,0,"C");
			            $pdf->Cell(12,$Fonthight,"施工费",1,0,"C");
			            $pdf->Cell(12,$Fonthight,"包装费",1,0,"C");
			            $pdf->Cell(10,$Fonthight,"业务员",1,0,"C");
			            $pdf->Cell(7,$Fonthight,"结款",1,1,"C");
	            }
	            $z++;
	            if($z>$pageTo){
	            	break;
	            }
			}

            	
            	
            }
            $pdf->SetFont('stsongstdlight','',5);
            $pdf->Cell(0,2,"",0,1);
			$pdf->SetFont('stsongstdlight','B',13);
          // $pdf->Cell(0,8,"	                                            	            		总退款额：{$orderTotalReturnJine}",0,1);
            
           $pdf->Cell(70,8,"合同总数：$orderNum",0,0);
		   $pdf->Cell(70,8,"总金额：$orderTotalJine",0,0);
		   $pdf->Cell(0,8,"总退款额：{$orderTotalReturnJine}",0,1);
           $totalProNum=sprintf("%01.2f",$totalProNum);
           $totalReturnProNum=sprintf("%01.2f",$totalReturnProNum);
           if($searchType==5||$searchType==6){
           //$pdf->Cell(0,8,"售出总数量：$totalProNum	                                  返厂总数量：$totalReturnProNum	                                售出总根数：{$totalProGenNum} ",0,1);
           
           $pdf->Cell(70,8,"售出总数量：$totalProNum",0,0);
		   $pdf->Cell(70,8,"返厂总数量：$totalReturnProNum",0,0);
		   $pdf->Cell(0,8,"售出总根数：{$totalProGenNum}",0,1);
           }
           }
 
            $pdf->lastPage();
            $pdf->Output();
   }
   function orderExcelByOrderNo(){
   	require 'tools/php-excel.class.php';
		
		
		$orderList=$_SESSION['excelList'];

//var_dump($orderList);
		// create a simple 2-dimensional array
ob_end_flush();
		session_start();
		// generate file (constructor parameters are optional)
		$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
		$xls->addArray($orderList);
		$xls->generateXML('my-test');
   }
   function toYueTongji(){
   	   $this->mode="toYueTongji";
   	
   	
   }
   function getSongListByOrderNo(){
   	$orderNo=$_REQUEST['orderNo'];
  	$this->objDao=new OrderDao();
    $result=$this->objDao->getOrderTotal("","","",$orderNo);
    $orderTotal=mysql_fetch_array($result);
    $proString="$";
  	$proString.=$orderTotal['song_adress']
  	."$".$orderTotal['song_lianxiren']
  	."$".$orderTotal['song_tel']
  	."$".$orderTotal['song_date']
  	."$".$orderTotal['mark'];
  	echo $proString;
  	exit;	
   	
   }
   function toDelDoubleOrder(){
   	$this->mode="toDelDoubleOrder";
   }
   function delDoubleOrder(){
   	$this->mode="toDelDoubleOrder";
   	$orderNo=$_REQUEST['orderNo'];
  	$this->objDao=new OrderDao();
    $result=$this->objDao->getOrderTotal("","","",$orderNo);
    $i=0;
    $orderId=array();
    while($row=mysql_fetch_array($result)){
    	$orderId[$i]=$row['id'];
    	$i++;
    }
    if($i>1){
    $result=$this->objDao->delOrderTotalById($orderId[0]);
    $this->objForm->setFormData("succ","删除重复订单号:".$orderNo);	
    }else{
    $this->objForm->setFormData("warn","订单号未重复:".$orderNo);		
    }
   }
   function updateCustomerJson () {
       $user=$_SESSION['admin'];
       $adminId=$user['id'];

       $moneyVal = $customer['money_val'] = $add_fee = $_REQUEST["fee"];
       $customerId = $_REQUEST["customer_id"];

       $adminName=$user['real_name'];
       $result = array();
       if(!is_numeric($add_fee)) {
           $result["code"] = "100001";
           $result["message"] = "输入金额有误";
           echo json_encode($result);exit;
       }

       $coupon = $customer['coupon'] = $_REQUEST['coupon'];
       $monCard = $customer['monCard'] = $_REQUEST['monCard'];
       $val500 = $customer['val_500'] = $_REQUEST['val_500'];
       $nian800 = $customer['nian_800'] = $_REQUEST['nian_800'];
       $this->objDao = new CustomerDao();
       $this->objDao->beginTransaction();
       if(empty($customer['money_val'])){
           $add_fee = $customer['money_val']  = 0.00;
       }


       $customerPO = $this->objDao->getCustomerById($customerId);

       $afterFee = bcadd($add_fee,$customerPO['total_money'],2);


       $orderPO = array();
       $orderPO['order_no'] = 0;
       $orderPO['custer_no'] = $customerId;
       $orderDao = new OrderDao();
       //$customer = $orderDao->getCustomerById($customerId);
       $orderPO['custer_name'] = $customerPO['realName'];

       $orderPO['zhidanren_name'] = $user['real_name'];
       $orderPO['op_id'] = $user['id'];
       $orderPO['chengjiaoer'] = $moneyVal;
       $orderPO['realChengjiaoer'] = $moneyVal;
       $orderPO['ding_date'] = date("Y-m-d",time());
       $orderPO['add_time'] = date("Y-m-d h:i:s",time());
       $orderPO['update_time'] = date("Y-m-d h:i:s",time());
       $res_order = $orderDao->saveTotalOrderRecharge($orderPO);
       $orderSaveId = $this->objDao->g_db_last_insert_id();
       if (!$res_order) {
           $this->objDao->rollback();
           $data['code'] = 100001;
           $data['message'] = "添加客户充值金额失败！";
           echo json_encode($data);
           exit;
       }


       $accountItem = array();
       $accountItem['customer_id'] = $customerId;
       $accountItem['before_val'] = $customerPO['total_money'];
       $accountItem['after_val'] = $afterFee;
       $accountItem['deal_val'] = $add_fee;
       $accountItem['admin_id'] = $user['id'];
       $accountItem['admin_name'] = $user['real_name'];
       $accountItem['source_id'] = $orderSaveId;
       $accountItem['source_type'] = 'account_recharge';
       $accountItem["account_id"] = $customerId;
       $accountItem["account_type"] = "or_customer_account";
       $res = $this->objDao->addAccountItem4Order($accountItem);
       $addId = $this->objDao->g_db_last_insert_id();
       if (!$res) {
           $this->objDao->rollback();
           $data['code'] = 100001;
           $data['message'] = "添加充值明细失败！";
           echo json_encode($data);
           exit;
       }

       if ($nian800) {
           $add_fee = $customer['money_val'] = bcsub($customer['money_val'],800,2);
       }
       //$afterFee = bcadd($add_fee,$customerPO['total_money'],2);
       if ($nian800) {
           $accountItem = array();
           $accountItem["customer_id"] = $customerId;
           $accountItem["before_val"] = $afterFee;
           $accountItem["deal_val"] = 800;
           $accountItem["after_val"] = bcsub($afterFee,800,2);
           $accountItem["admin_id"] = $adminId;
           $accountItem["admin_name"] = $adminName;
           $accountItem["source_id"] = $orderSaveId;
           $accountItem["source_type"] = "nian_800";
           $accountItem["account_id"] = $customerId;
           $accountItem["account_type"] = "or_customer_account";
           $resultCO = $this->objDao->addAccountItem4Order($accountItem);
           if(!$resultCO){
               $this->objDao->rollback();
               $data['code'] = 100001;
               $data['message'] = "修改年费扣减失败！";
               echo json_encode($data);
               exit;
           }
           $couponObj = array();
           $couponObj['customer_id'] = $customerId;
           $couponObj['coupon_name'] = "nian_800";
           $couponObj['coupon_val'] = 800;
           $couponObj['coupon_type'] = 6;
           $couponObj['admin_id'] = $adminId;
           $couponObj['admin_name'] = $adminName;
           $couponObj['end_date'] = date("Y-m-d",strtotime("+1 year"));
           //print_r($couponObj);
           $res = $this->objDao->addCoupon($couponObj);

           if (!$res) {
               $this->objDao->rollback();
               $data['code'] = 100001;
               $data['message'] = "添加年费优惠券失败！";
               echo json_encode($data);
               exit;
           }

       }


       $res = $this->objDao->updateCustomerMoneyById($customerId,$accountItem['after_val']);
       if (!$res) {
           $this->objDao->rollback();
           $data['code'] = 100001;
           $data['message'] = "添加客户充值金额失败！";
           echo json_encode($data);
           exit;
       }
       if ($moneyVal > 0) {


           if ($nian800) {

           }

       }
       global $coupon_name;
       global $coupon_val;
       if($customer['coupon'] > 0) {
           $couponObj = array();
           $couponObj['customer_id'] = $customerId;
           $couponObj['coupon_name'] = $coupon_name[$coupon];
           $couponObj['coupon_val'] = $coupon_val[$coupon];
           $couponObj['coupon_type'] = $coupon;
           $couponObj['admin_id'] = $adminId;
           $couponObj['admin_name'] = $adminName;
           if ($coupon == 5) {
               $couponObj['end_date'] = date("Y-m-d",strtotime("+1 month"));
           }
           //print_r($couponObj);
           $res = $this->objDao->addCoupon($couponObj);

           if (!$res) {
               $this->objDao->rollback();
               $data['code'] = 100001;
               $data['message'] = "添加优惠券失败！";
               echo json_encode($data);
               exit;
           }
       }
       if ($monCard > 0) {
           $couponObj = array();
           $couponObj['customer_id'] = $customerId;
           $couponObj['coupon_name'] = $coupon_name[5];
           $couponObj['coupon_val'] = $coupon_val[5];
           $couponObj['coupon_type'] = 5;
           $couponObj['admin_id'] = $adminId;
           $couponObj['admin_name'] = $adminName;
           if ($coupon == 5) {
               $couponObj['end_date'] = date("Y-m-d",strtotime("+1 month"));
           }
           //print_r($couponObj);
           $res = $this->objDao->addCoupon($couponObj);

           if (!$res) {
               $this->objDao->rollback();
               $data['code'] = 100001;
               $data['message'] = "添加优惠券失败！";
               echo json_encode($data);
               exit;
           }
       }
       if ($val500 > 0) {
           $couponObj = array();
           $couponObj['customer_id'] = $customerId;
           $couponObj['coupon_name'] = $coupon_name[2];
           $couponObj['coupon_val'] = $coupon_val[2];
           $couponObj['coupon_type'] = 2;
           $couponObj['admin_id'] = $adminId;
           $couponObj['admin_name'] = $adminName;
           $couponObj['end_date'] = date("Y-m-d",strtotime("+1 year"));
           //print_r($couponObj);
           $res = $this->objDao->addCoupon($couponObj);

           if (!$res) {
               $this->objDao->rollback();
               $data['code'] = 100001;
               $data['message'] = "添加优惠券失败！";
               echo json_encode($data);
               exit;
           }
       }

       $data = array();
       if (!$res) {
           $data['code'] = 100001;
           $data['message'] = '添加失败';
       } else {
           $data['code'] = 100000;
           if ($moneyVal > 0) {
               $printInfo = array();
               $printInfo['money_val'] = $moneyVal;
               $printInfo['total_money'] = $accountItem['after_val'];
               $printInfo['customer_name'] = $customerPO['realName'] ;
               $printInfo['admin_name'] = $adminName ;
               $printInfo['addId'] = $addId ;
               $printInfo['coupon_name'] = "";
               $printInfo['coupon_val'] = "0.00";
               $printInfo['sum_val'] = 0.00;

               if ($coupon > 0) {

                   $printInfo['coupon'] = $coupon;
                   $printInfo['coupon_name'] = $coupon_name[$coupon];
                   $printInfo['coupon_val'] = $coupon_val[$coupon];
                   $printInfo['sum_val'] += $printInfo['coupon_val'];
               }
               if ($monCard > 0){
                   $printInfo['month'] = 1;
                   $printInfo['month_name'] = $coupon_name[5];
                   $printInfo['month_val'] = $coupon_val[5];
                   $printInfo['sum_val'] += $printInfo['month_val'];
               }
               if ($val500 > 0){
                   $printInfo['val500'] = 1;
                   $printInfo['val500_name'] = $coupon_name[2];
                   $printInfo['val500_val'] = $coupon_val[2];

                   $printInfo['sum_val'] += $printInfo['val500_val'];
               }
               if ($nian800 > 0){
                   $printInfo['nian800'] = 1;
                   $printInfo['nian800_name'] = "会员年费800";
                   $printInfo['nian800_val'] = -800.00;

               }
               $printInfo["orderSaveId"] = $orderSaveId;
               $this->printAddMoney($printInfo);
               $this->printAddMoney($printInfo);
           }
           $this->objDao->commit();
       }
       echo json_encode($data);
       exit;
   }
    function addCustomerJson () {

        $user=$_SESSION['admin'];
        $adminId=$user['id'];
        $adminName=$user['real_name'];
        $customer['custo_name'] = $_REQUEST['customer_name'];
        $customer['custo_level'] = $_REQUEST['level_name'];
        $customer['moveTel_no'] = $_REQUEST['customer_tel'];
        $customer['card_no'] = $_REQUEST['card_no'];
        $moneyVal = $customer['money_val'] = $_REQUEST['money_val'];

        $coupon = $customer['coupon'] = $_REQUEST['coupon'];
        $monCard = $customer['monCard'] = $_REQUEST['monCard'];
        $val500 = $customer['val_500'] = $_REQUEST['val_500'];
        $nian800 = $customer['nian_800'] = $_REQUEST['nian_800'];
        $this->objDao = new CustomerDao();
        $this->objDao->beginTransaction();
        if(empty($customer['money_val'])){
            $customer['money_val']  = 0.00;
        }
        if ($nian800) {
            $customer['money_val'] = bcsub($customer['money_val'],800,2);
        }
        $customer['admin_id'] = $adminId;
        $customer['admin_name'] = $adminName;
        $result = $this->objDao->addNewCustom($customer);

        $customerId = $this->objDao->g_db_last_insert_id();
        if(!$result){
            $this->objDao->rollback();
            $data['code'] = 100001;
            $data['message'] = "添加客户失败！";
            echo json_encode($data);
            exit;
        }
        if ($moneyVal > 0) {

            $orderPO = array();
            $orderPO['order_no'] = 0;
            $orderPO['custer_no'] = $customerId;
            $orderDao = new OrderDao();
            $customerPO = $orderDao->getCustomerById($customerId);
            $orderPO['custer_name'] = $customerPO['realName'];

            $orderPO['zhidanren_name'] = $user['real_name'];
            $orderPO['op_id'] = $user['id'];
            $orderPO['chengjiaoer'] = $moneyVal;
            $orderPO['realChengjiaoer'] = $moneyVal;
            $orderPO['ding_date'] = date("Y-m-d",time());
            $orderPO['add_time'] = date("Y-m-d h:i:s",time());
            $orderPO['update_time'] = date("Y-m-d h:i:s",time());
            $res_order = $orderDao->saveTotalOrderRecharge($orderPO);
            $orderSaveId = $this->objDao->g_db_last_insert_id();
            if (!$res_order) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加客户充值金额失败！";
                echo json_encode($data);
                exit;
            }

            $accountItem = array();
            $accountItem['customer_id'] = $customerId;
            $accountItem['before_val'] = 0.00;
            $accountItem['after_val'] = $customer['money_val'];
            $accountItem['deal_val'] = $customer['money_val'];
            $accountItem['admin_id'] = $adminId;
            $accountItem['admin_name'] = $adminName;
            $accountItem['source_id'] = $orderSaveId;
            $accountItem['source_type'] = 'account_recharge';
            $accountItem["account_id"] = $customerId;
            $accountItem["account_type"] = "or_customer_account";

            $res = $this->objDao->addAccountItem4Order($accountItem);
            $addId = $this->objDao->g_db_last_insert_id();
            if (!$res) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加充值明细失败！";
                echo json_encode($data);
                exit;
            }
            if ($nian800) {
                $accountItem = array();
                $accountItem["customer_id"] = $customerId;
                $accountItem["before_val"] = $_REQUEST['money_val'];
                $accountItem["deal_val"] = 800;
                $accountItem["after_val"] = $customer['money_val'];
                $accountItem["admin_id"] = $adminId;
                $accountItem["admin_name"] = $adminName;
                $accountItem["source_id"] = $orderSaveId;
                $accountItem["source_type"] = "nian_800";
                $accountItem["account_id"] = $customerId;
                $accountItem["account_type"] = "or_customer_account";
                $resultCO = $this->objDao->addAccountItem4Order($accountItem);
                if(!$resultCO){
                    $this->objDao->rollback();
                    $data['code'] = 100001;
                    $data['message'] = "修改年费扣减失败！";
                    echo json_encode($data);
                    exit;
                }
                $couponObj = array();
                $couponObj['customer_id'] = $customerId;
                $couponObj['coupon_name'] = "nian_800";
                $couponObj['coupon_val'] = 800;
                $couponObj['coupon_type'] = 6;
                $couponObj['admin_id'] = $adminId;
                $couponObj['admin_name'] = $adminName;
                $couponObj['end_date'] = date("Y-m-d",strtotime("+1 year"));
                //print_r($couponObj);
                $res = $this->objDao->addCoupon($couponObj);

                if (!$res) {
                    $this->objDao->rollback();
                    $data['code'] = 100001;
                    $data['message'] = "添加年费优惠券失败！";
                    echo json_encode($data);
                    exit;
                }
            }

        }
        global $coupon_name;
        global $coupon_val;
        if($customer['coupon'] > 0) {
            $couponObj = array();
            $couponObj['customer_id'] = $customerId;
            $couponObj['coupon_name'] = $coupon_name[$coupon];
            $couponObj['coupon_val'] = $coupon_val[$coupon];
            $couponObj['coupon_type'] = $coupon;
            $couponObj['admin_id'] = $adminId;
            $couponObj['admin_name'] = $adminName;
            if ($coupon == 5) {
                $couponObj['end_date'] = date("Y-m-d",strtotime("+1 month"));
            }
            //print_r($couponObj);
            $res = $this->objDao->addCoupon($couponObj);

            if (!$res) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加优惠券失败！";
                echo json_encode($data);
                exit;
            }
        }
        if ($monCard > 0) {
            $couponObj = array();
            $couponObj['customer_id'] = $customerId;
            $couponObj['coupon_name'] = $coupon_name[5];
            $couponObj['coupon_val'] = $coupon_val[5];
            $couponObj['coupon_type'] = 5;
            $couponObj['admin_id'] = $adminId;
            $couponObj['admin_name'] = $adminName;
            if ($coupon == 5) {
                $couponObj['end_date'] = date("Y-m-d",strtotime("+1 month"));
            }
            //print_r($couponObj);
            $res = $this->objDao->addCoupon($couponObj);

            if (!$res) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加优惠券失败！";
                echo json_encode($data);
                exit;
            }
        }
        if ($val500 > 0) {
            $couponObj = array();
            $couponObj['customer_id'] = $customerId;
            $couponObj['coupon_name'] = $coupon_name[2];
            $couponObj['coupon_val'] = $coupon_val[2];
            $couponObj['coupon_type'] = 2;
            $couponObj['admin_id'] = $adminId;
            $couponObj['admin_name'] = $adminName;
            $couponObj['end_date'] = date("Y-m-d",strtotime("+1 year"));
            //print_r($couponObj);
            $res = $this->objDao->addCoupon($couponObj);

            if (!$res) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加优惠券失败！";
                echo json_encode($data);
                exit;
            }
        }

        $data = array();
        if (!$res) {
            $data['code'] = 100001;
            $data['message'] = '添加失败';
        } else {
            $data['code'] = 100000;
            if ($moneyVal >= 0) {
                $printInfo = array();
                $printInfo['money_val'] = $moneyVal;
                $printInfo['total_money'] = $accountItem['after_val'];
                $printInfo['customer_name'] = $customer['realName'] ;
                $printInfo['admin_name'] = $adminName ;
                $printInfo['addId'] = $addId ;
                $printInfo['coupon_name'] = "";
                $printInfo['coupon_val'] = "0.00";
                $printInfo['sum_val'] = 0.00;

                if ($coupon > 0) {

                    $printInfo['coupon'] = $coupon;
                    $printInfo['coupon_name'] = $coupon_name[$coupon];
                    $printInfo['coupon_val'] = $coupon_val[$coupon];
                    $printInfo['sum_val'] += $printInfo['coupon_val'];
                }
                if ($monCard > 0){
                    $printInfo['month'] = 1;
                    $printInfo['month_name'] = $coupon_name[5];
                    $printInfo['month_val'] = $coupon_val[5];
                    $printInfo['sum_val'] += $printInfo['month_val'];
                }
                if ($val500 > 0){
                    $printInfo['val500'] = 1;
                    $printInfo['val500_name'] = $coupon_name[2];
                    $printInfo['val500_val'] = $coupon_val[2];

                    $printInfo['sum_val'] += $printInfo['val500_val'];
                }
                if ($nian800 > 0){
                    $printInfo['nian800'] = 1;
                    $printInfo['nian800_name'] = "会员年费800";
                    $printInfo['nian800_val'] = -800.00;

                }
                $printInfo["orderSaveId"] = $orderSaveId;
                $this->printAddMoney($printInfo);
                $this->printAddMoney($printInfo);
            }
            $this->objDao->commit();
        }
        echo json_encode($data);
        exit;
    }
    function modifyOrder () {
        $this->mode = 'toOrderModify';
        $orderId=$_REQUEST['orderId'];
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $this->objDao=new OrderDao();

        $orderTotal=$this->objDao->getOrderTotalByOrderNo($orderId);
        $result=$this->objDao->getOrderById($orderId);
        $orderList=array();
        $customer = array();
        $i=1;
        while ($row=mysql_fetch_array($result)){

            if($i==1){
                $customer=$this->objDao->getCustomerById($row['customer_id']);
                $level = $levelList[$customer['custo_level']];
                $customer['custo_level_name'] = $level['level_name'];
                $orderList['custo_discount'] = $customer['custo_discount'];
            }
            $row['inputText'] = $row['pro_code'].' '.$row['pro_name'];
            $productModel = $this->objDao->getProductById($row['pro_id']);
            $row['orderId'] = $row['id'];
            $row['market_price'] = $productModel['market_price'];
            $row['vip_price'] = $productModel['vip_price'];
            $orderList['data'][$i] = $row;

            $i++;
        }
        $this->objForm->setFormData("customer",$customer);
        $this->objForm->setFormData("orderTotal",$orderTotal);
        $this->objForm->setFormData("orderList",$orderList);
    }
    function updateOrderDetail () {
        $update = array();
        $update['oId'] = $_REQUEST['oId'];
        $update['mark'] = $_REQUEST['mark'];
        $update['ding'] = $_REQUEST['ding'];
        $update['realChengjiaoer'] = $_REQUEST['realChengjiaoer'];
        $payTypeVal = $payType= $_REQUEST['payType'];
        $payStatus= $_REQUEST['payStatus'];
        $update['payType'] = $payType;
        $update['payStatus'] = $payStatus;

        $this->objDao = new OrderDao();
        $orderTotal=$this->objDao->getOrderTotalByOrderNo($update['oId']);
        $needPayVip = array(2,10,18,19);

        $user=$_SESSION['admin'];
        $adminId=$user['id'];

        $this->objDao->beginTransaction();
        /************
        如果付款方式为会员卡需要修改客户余额
         ***********/
        if (empty($payStatus)) {
            $payStatus = $orderTotal['pay_status'];
        }

        if (empty($payTypeVal)) {
            $payTypeVal = $orderTotal['pay_type'];
        }

        $customerPO = $this->objDao->getCustomerById($orderTotal['custer_no']);

        if (in_array($payTypeVal,$needPayVip) && $payStatus == self::PAY_STATUS_TRUE) {



            $cash_val = $orderTotal['cash_val'];
            if ($cash_val > 0) {
                $yueMoney = $customerPO['total_money'] + (float)$cash_val - $orderTotal['realChengjiaoer'];
            } else {
                $yueMoney = $customerPO['total_money'] - $orderTotal['realChengjiaoer'];
                $cash_val = 0;
            }

            //$yueMoney = $customerPO['total_money'] - $orderTotal['realChengjiaoer'];

            if ($yueMoney < 0) {
                $data['code'] = 100001;
                $data['message'] = "会员余额不足！";
                echo json_encode($data);
                exit;
            }
            $resultC = $this->objDao->updateCustomerMoneyById($customerPO['id'],$yueMoney);
            if(!$resultC){
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "修改客户余额失败！";
                echo json_encode($data);
                exit;
            }
            $accountItem = array();
            $accountItem["customer_id"] = $customerPO['id'];
            $accountItem["before_val"] = $customerPO['total_money'];
            $accountItem["deal_val"] = $orderTotal['realChengjiaoer'];
            $accountItem["after_val"] = $yueMoney;
            $accountItem["admin_id"] = $adminId;
            $accountItem["admin_name"] = $user['real_name'];
            $accountItem["source_id"] = $update['oId'];
            $accountItem["source_type"] = "or_order_total";
            $accountItem["account_id"] = $customerPO['id'];
            $accountItem["account_type"] = "cutomer_account";
            $customerDao = new CustomerDao();
            $resultI = $customerDao->addAccountItem4Order($accountItem);
            if(!$resultI){
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "修改客户交易详情失败！";
                echo json_encode($data);
                exit;
            }

        }
        $adminName=$user['real_name'];
        if ($orderTotal['coupon_val'] > 0) {

            $couponRes = $customerDao->getCouponByCusId($customerPO['id']);

            while ($row = mysql_fetch_array($couponRes)) {
                if ($row['coupon_type'] != 2 && $row['coupon_type'] != 5 ) {

                    $domainVal = $row['coupon_val'] + $orderTotal['coupon_val'];

                    $res = $customerDao->updateAccountValById($row['id'],$domainVal);
                    if (!$res) {
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "：优惠券修改失败！";
                        echo json_encode($data);
                        exit;
                    }
                    $accountItem = array();
                    $accountItem["customer_id"] = $customerPO['id'];
                    $accountItem["before_val"] = $row['coupon_val'];
                    $accountItem["deal_val"] = $orderTotal['coupon_val'];
                    $accountItem["after_val"] = $domainVal;
                    $accountItem["admin_id"] = $adminId;
                    $accountItem["admin_name"] = $adminName;
                    $accountItem["source_id"] = $update['oId'];
                    $accountItem["source_type"] = "or_order_total";
                    $accountItem["account_id"] = $row['id'];
                    $accountItem["account_type"] = "or_account_coupon";
                    $resultCO = $customerDao->addAccountItem4Order($accountItem);
                    if(!$resultCO){
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "修改优惠券交易详情失败！";
                        echo json_encode($data);
                        exit;
                    }
                }

            }
        }
        /************
        end
         ***********/
        $result = $this->objDao->updateTotalOrderDetail($update);
        if (!$result) {
            $data['code'] = 100001;
            $data['message'] = '修改失败请重试';
            $this->objDao->rollback();
        } else {
            $data['code'] = 100000;
            $data['message'] = '修改成功';
            if ($result) {
                $opLog = array();
                $opLog['who'] = $adminId;
                $opLog['who_name'] = $user['real_name'];
                $opLog['what'] = $update['oId'];
                $opLog['Subject'] = OP_LOG_MODIFY_ORDER;
                $opLog['memo'] = mysql_escape_string($result) ;
                $this->addOpLog($opLog);
            }
            $this->objDao->commit();
        }
        echo json_encode($data);
        exit;
    }
    function toOrderReturnAdd () {
        $this->mode = 'toOrderReturnAdd';
    }

   function getOrderReturnDetail(){
       $this->mode = 'getOrderReturnDetail';
       $orderId=$_REQUEST['orderId'];
       $this->objDao=new CustomerDao();
       $result = $this->objDao->getCustomerLevelList();
       $levelList = array();
       while ($row = mysql_fetch_array($result)) {
           $levelList[$row['id']]= $row;
       }
       $this->objDao=new OrderDao();

       $orderTotal=$this->objDao->getOrderReturnTotalByNo($orderId);
       $result=$this->objDao->getOrderReturnByReturnNo($orderId);
       $orderList=array();
       $customer = array();
       $i=0;
       while ($row=mysql_fetch_array($result)){

           if($i==0){
               $customer=$this->objDao->getCustomerById($row['customer_id']);
               $level = $levelList[$customer['custo_level']];
               $customer['custo_level_name'] = $level['level_name'];
               $orderList['custo_discount'] = $customer['custo_discount'];
           }
           $orderList['data'][] = $row;

           $i++;
       }
       //$where = ' subject = "'.OP_LOG_RETURN_ORDER.'"';
       $where = '';
       $logResult = $this->objDao->getOpLogByTaskId("R".$orderId,$where);
       $logList = array();
       while ($row=mysql_fetch_array($logResult)){
           $logList[] = $row;
       }
       $this->objForm->setFormData("customer",$customer);
       $this->objForm->setFormData("orderNo",$orderId);
       $this->objForm->setFormData("logList",$logList);
       $this->objForm->setFormData("orderTotal",$orderTotal);
       $this->objForm->setFormData("orderList",$orderList);
   }
    function saveOrderReturnJson () {
        $ids = $_REQUEST['ids'];
        $price = $_REQUEST['price'];
        $proNum = $_REQUEST['proNum'];
        $sumMoney = $_REQUEST['sumMoney'];
        $totalMoney = $_REQUEST['totalMoney'];
        $realTotalMoney = $_REQUEST['realTotalMoney'];
        $customerId = $_REQUEST['customerId'];
        $dingDate = $_REQUEST['dingDate'];
        $remark = $_REQUEST['remark'];
        $payTypeVal = $_REQUEST['payType'];
        $custo_discount = $_REQUEST['custo_discount'];

        $data['code'] = 100000;
        $this->objDao=new CustomerDao();
        $this->objDao->beginTransaction();

        //save orderTotal table
        $result = $this->objDao->getMaxOrderReturnNo();

        $customerPO = $this->objDao->getCustomerById($customerId);
        $user=$_SESSION['admin'];
        $adminId=$user['id'];

        $orderNo =$result['max']+1;
        if ($orderNo < 2){
            $orderNo = ORDER_BASE_NO + 1;
        }
        $orderTotalPo['order_no']=$orderNo;
        $orderTotalPo['customer_id']=$customerId;
        $orderTotalPo['customer_name']=$customerPO['realName'];
        $orderTotalPo['return_jin']=$totalMoney;
        $orderTotalPo['return_real_jin']=$realTotalMoney;
        $orderTotalPo['pay_type']=$payTypeVal;
        $orderTotalPo['op_id']=$adminId;//店员
        $orderTotalPo['order_type']=1;//确认退款
        $orderTotalPo['mark']=$remark;
        $this->objDao=new OrderDao();
        $resultTotal=$this->objDao->saveTotalOrderReturn($orderTotalPo);

        if(!$resultTotal){
            $this->objDao->rollback();
            $data['code'] = 100001;
            $data['message'] = "添加退单总表失败！";
            echo json_encode($data);
            exit;
        }
        $printArray = array();
        for($i=0;$i<count($ids);$i++){
            $order=array();
            $print=array();
            $this->objDao=new ProductDao();
            $kucunDao = new ProductDao();
            if (!$ids[$i]) continue;
            $productPO = $this->objDao->getProductById($ids[$i]);
            $order['order_no']=$orderTotalPo['order_no'];
            $order['pro_id']=$ids[$i];
            $print['pro_code'] = $order['pro_code']= mysql_real_escape_string($productPO['pro_name']);
            $order['ding_date']=$dingDate;
            $order['jiao_date']=$dingDate;
            $print['pro_num'] = $order['pro_num'] = $proNum[$i];
            $order['pro_type']=$productPO['pro_type'];
            $order['pro_unit']=$productPO['pro_unit'];
            $order['pro_price']=$productPO['pro_price'];
            $print['price'] = $order['price']=$price[$i];
            $order['pro_flag']=1;
            $order['customer_id']=$customerId;
            $order['return_jiner']=$sumMoney[$i];
            $order['jingban_id']= empty($jingbanren)?$customerPO['customer_jingbanren']:$jingbanren;
            $order['customer_type'] = $customerPO['custo_level'];//销售经理
            $order['mark']='';
            $order['custo_name']=$customerPO['realName'];
            $order['zhekou']=$custo_discount;

            $printArray[] = $print;
            $this->objDao=new OrderDao();
            $rasult=$this->objDao->addOrderReturn($order);
            if(!$rasult){
                $this->objDao->rollback();
                $data['code'] = 100002;
                $data['message'] = "添加退单表失败！";
                echo json_encode($data);
                exit;
            } else {
                $productPO = $kucunDao->getProductById($order['pro_id']);
                $kucunNum = $productPO['pro_num'] +  $order['pro_num'];
                $update = array();
                $update['pro_num'] = $kucunNum;
                $update['pro_id'] = $order['pro_id'];
                $res = $kucunDao->updateProductNumByProId($update);
                if ($res) {
                    $opLog = array();
                    $opLog['who'] = $user['id'];
                    $opLog['who_name'] = $user['real_name'];
                    $opLog['what'] = "R".$orderTotalPo['order_no'];
                    $opLog['Subject'] = OP_LOG_DEL_ORDER;
                    $opLog['memo'] = "产品{$productPO['e_name']}退货恢复库存，原库存：{$productPO['pro_num']}，增加库存{$order['pro_num']}，修改后库存{$kucunNum}";
                    $this->addOpLog($opLog);

                    $productDao = new ProductDao();
                    $produce = array();//
                    $produce['pro_id'] = $productPO['id'];
                    $produce['pro_code'] = $productPO['pro_code'];
                    $produce['pro_num'] = $order['pro_num'];
                    $produce['into_date'] = date('Y-m-d h:i:s',time());
                    $produce['memo'] = '退单'."R".$orderTotalPo['order_no'];
                    $produce['old_storage'] = $productPO['pro_num'];
                    $produce['new_storage'] = $kucunNum;
                    $produce['op_type'] = 4;
                    $result = $productDao->addIntoStorage($produce);
                } else {
                    $this->objDao->rollback();
                    $data['code'] = 100002;
                    $data['message'] = "修改库存失败！";
                    echo json_encode($data);
                    exit;
                }
            }


        }
        if ($data['code'] == 100000) {
            $adminPO = $_SESSION['admin'];
            $opLog = array();
            $opLog['who'] = $adminPO['id'];
            $opLog['who_name'] = $adminPO['real_name'];
            $opLog['what'] = "R".$orderNo;
            $opLog['Subject'] = OP_LOG_RETURN_ORDER;
            $opLog['memo'] = OP_LOG_RETURN_ADD;
            $this->addOpLog($opLog);
        }

        if ($payTypeVal == 2) {//会员卡消费
            $customerDao = new CustomerDao();
            $customerPO = $customerDao->getCustomerById($customerId);
            $val = (float)$customerPO['total_money'] + (float)$orderTotalPo['return_real_jin'];
            $cuRes = $customerDao->updateCustomerMoneyById($customerId,$val);
            if (!$cuRes) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加退单表修改客户信息失败！";
                echo json_encode($data);
                exit;
            } else {
                $this->objDao = new CustomerDao();
                $accountItem = array();
                $accountItem["customer_id"] = $customerPO['id'];
                $accountItem["before_val"] = $customerPO['total_money'];
                $accountItem["deal_val"] = $orderTotalPo['return_real_jin'];
                $accountItem["after_val"] = bcadd($customerPO['total_money'],$orderTotalPo['return_real_jin'],2);
                $accountItem["admin_id"] = $adminPO['id'];
                $accountItem["admin_name"] = $adminPO['real_name'];
                $accountItem["source_id"] = $customerPO['id'];
                $accountItem["source_type"] = "order_return";
                $accountItem["account_id"] = $customerPO['id'];
                $accountItem["account_type"] = "cutomer_account";
                $resultI = $this->objDao->addAccountItem4Order($accountItem);
                if (!$resultI) {
                    $this->objDao->rollback();
                    $data['code'] = 100001;
                    $data['message'] = "添加修改客户账户明细失败！";
                    echo json_encode($data);
                    exit;
                }
                $opLog = array();
                $opLog['who'] = $adminPO['id'];
                $opLog['who_name'] = $adminPO['real_name'];
                $opLog['what'] = "R".$orderTotalPo['order_no'];
                $opLog['Subject'] = OP_LOG_MODIFY_CUSTOMER_VAL;
                $opLog['memo'] = "修改{$customerPO['realName']}储值金额，原金额：{$customerPO['total_money']}，增加{$orderTotalPo['return_real_jin']}，修改后{$val}";
                $this->addOpLog($opLog);
            }
        }
        //事务提交
        $this->objDao->commit();
        $this->objDao=new CustomerDao();

        $jingliPO = $this->objDao->getJingbanrenList();
        $jingliList = array();
        while($val = mysql_fetch_array($jingliPO)){
            $jingliList[$val['id']] = $val["jingbanren_name"];
        }
        $jingbanren_name = $user['real_name'];
        global $payType;
        $payTypeName = $payType[$payTypeVal];
        $customerName = $customerPO['realName'];
        $this->printReturnOrder($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$payTypeName,$customerName,$remark);
        $this->printReturnOrder($printArray,$totalMoney,$realTotalMoney,$jingbanren_name,$payTypeName,$customerName,$remark);

        echo json_encode($data);
        exit;

    }
    function toOrderSearchList () {
        $this->mode = 'toOrderSearchList';
        $date_month = $_REQUEST['orderDateMonth'];
        if (!empty($date_month)) {

            $dateFrom = $date_month."-01";
            $dateTo = $date_month."-31";
        } else {

            $dateFrom = $_REQUEST['dateFrom'];
            $dateTo = $_REQUEST['dateTo'];
        }
        $searchType = $_REQUEST['searchType'];
        $this->objDao=new OrderDao();
        if (empty($searchType) || $searchType == 'all') {
            $result = $this->objDao->getOrderListByDates($dateFrom,$dateTo);
            $reResult = $this->objDao->getOrderReturnListByDates($dateFrom,$dateTo);
        } elseif ($searchType == 'order'){
            $result = $this->objDao->getOrderListByDates($dateFrom,$dateTo);
        } elseif ($searchType == 'return') {
            $reResult = $this->objDao->getOrderReturnListByDates($dateFrom,$dateTo);
        }

        $orderList =array();
        $returnList =array();
        $total = array();
        $total['order_num'] = 0;
        $total['return_num'] = 0;
        $total['order_money'] = 0.00;
        $total['return_money'] = 0.00;
        while($row = mysql_fetch_array($result)){
            $orderList[] = $row;
            ++$total['order_num'];
            $total['order_money']+= $row['realChengjiaoer'];
            $total['order_money'] = sprintf("%.2f", $total['order_money']);
        }
        while($row = mysql_fetch_array($reResult)){
            $returnList[] = $row;
            ++$total['return_num'];
            $total['return_money']+= $row['return_real_jin'];
            $total['return_money'] = sprintf("%.2f", $total['return_money']);
        }
        $this->objForm->setFormData("orderList",$orderList);
        $this->objForm->setFormData("returnList",$returnList);
        $this->objForm->setFormData("dateFrom",$dateFrom);
        $this->objForm->setFormData("dateTo",$dateTo);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("total",$total);
    }
    function toOrderStatistics () {
        $this->mode = 'toOrderStatistics';
        $this->objDao=new OrderDao();
        $result = $this->objDao->getOrderTotalListByMonth();
        $reResult = $this->objDao->getReturnTotalListByMonth();
        $total['order_count'] = 0;
        $total_money['order_money'] = 0.00;
        $total['return_count'] = 0;
        $total_money['return_money'] = 0.00;

        $tongJiList =array();
        while($row = mysql_fetch_array($result)){
            $PO = array();
            $PO['month'] =  $row['month'];
            $PO['order_money'] =  $row['money'];
            $PO['order_count'] =  $row['order_count'];
            $tongJiList[$PO['month']]['order'] = $PO;
            $total['order_count'] += $PO['order_count'];
            $total_money['order_money'] += $PO['order_money'];
        }
        while($row = mysql_fetch_array($reResult)){
            $PO = array();
            $PO['month'] = $row['month'];
            $PO['return_money'] =  $row['return_money'];
            $PO['return_count'] =  $row['return_count'];
            $tongJiList[$PO['month']]['return'] = $PO;
            $total['return_count'] += $PO['return_count'];
            $total_money['return_money'] += $PO['return_money'];
        }
        $this->objForm->setFormData("tongJiList",$tongJiList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("total_money",$total_money);
    }
    function toFinanceStat () {
        $this->mode = 'toFinanceStat';

        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }

        $this->objDao=new OrderDao();

        $this->objForm->setFormData("levelList",$levelList);
    }
    function toOrderReturnList () {
        $this->mode = 'toOrderReturnList';
        $this->objDao=new OrderDao();
        $searchType = $_REQUEST['searchType'];
        global $searchPayType;
        $where = '';
        if ($searchType =='yifu' || $searchType =='weifu') {
            $payStatus = $searchType =='yifu'? 1 : 0;
            $where.= ' and pay_status ='.$payStatus;
        } elseif (!empty($searchType) && $searchType !='all') {
            $payType = $searchPayType[$searchType];
            $where.= ' and pay_type ='.$payType;
        }
        $sum =$this->objDao->g_db_count("or_return_total","*","1=1 $where");
        $pageSize=PAGE_SIZE;
        $count = intval($_GET['c']);
        $page = intval($_GET['page']);
        if ($count == 0){
            $count = $pageSize;
        }
        if ($page == 0){
            $page = 1;
        }

        $startIndex = ($page-1)*$count;
        $total = $sum;
        $order['by'] = $_REQUEST['by'];
        $order['up'] = $_REQUEST['up'];
        if (empty($order['by'])) {
            $order['by'] = 'add_time';
            $order['up'] = 'desc';
        }
        $searchResult=$this->objDao->getOrderReturnTotalPage($where,$order,$startIndex,$pageSize);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $orderList = array();
        global $returnType;
        while ($row = mysql_fetch_array($searchResult)) {
            $order['id'] = $row['id'];
            $order['order_no'] = $row['order_no'];
            $order['add_time'] = $row['add_time'];
            $order['custer_name'] = $row['customer_name'];
            $order['return_jin'] = $row['return_jin'];
            $order['return_real_jin'] = $row['return_real_jin'];
            $order['order_type'] = $returnType[$row['order_type']];
            $orderList[] = $order;
        }
        $this->objForm->setFormData("orderList",$orderList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("by",$order['by']);
        $this->objForm->setFormData("up",$order['up']);
    }
    function toDemo () {
        $this->mode = 'toDemo';
    }
    function saveRechargeOrderList () {
       $this->objDao = new CustomerDao();
        $where['type'] = 'recharge';
       $rechargeList = $this->objDao->getConsumeRecodesListByCustomerId($where);
       $orderDao = new OrderDao();
       while($row = mysql_fetch_array($rechargeList)){
           $orderPO = array();
           $orderPO['order_no'] = 0;
           $orderPO['custer_no'] = $row['customer_id'];

           $customer = $orderDao->getCustomerById($row['customer_id']);
           $orderPO['custer_name'] = $customer['realName'];

           $orderPO['zhidanren_name'] = $row['admin_name'];
           $orderPO['op_id'] = $row['admin_id'];
           $orderPO['chengjiaoer'] = $row['deal_val'];
           $orderPO['realChengjiaoer'] = $row['deal_val'];
           $orderPO['ding_date'] = $row['add_time'];
           $orderPO['add_time'] = $row['add_time'];
           $orderPO['update_time'] = $row['add_time'];
           $orderDao->saveTotalOrderRecharge($orderPO);
           //exit;
       }
    }
    function productListOrder(){
        $this->mode = 'productListOrder';
        $pro_list = array();
        $dao = new ProductDao();
        global $productType;
        $productTypeList = $productType;
        foreach($productTypeList as $k=>$v) {

            $res = $dao->getProductListAll($k);
            while($row = mysql_fetch_array($res)){
                $pro_list[$k][] = $row;
            }
        }

        $this->objForm->setFormData("pro_list",$pro_list);
    }

    function orderRefundAjax () {
        $orderId = $_REQUEST['orderId'];
        $remark = $_REQUEST['remark'];
        $this->objDao=new OrderDao();
        $this->objDao->beginTransaction();

        //todo 修改订单标记为退单
        $orderPO = $this->objDao->getOrderTotalByOrderNo($orderId);
        $res = $this->objDao->updateOrderRefundByOrderNO($orderId);
        //todo 添加日志
        $user = $adminPO = $_SESSION['admin'];
        if ($res) {
            $date = date("Y-m-d h:i:s");
            $opLog = array();
            $opLog['who'] = $adminPO['id'];
            $opLog['who_name'] = $adminPO['real_name'];
            $opLog['what'] = $orderId;
            $opLog['Subject'] = OP_LOG_REFUND_ORDER;
            $opLog['memo'] = "{$adminPO['real_name']} $date 操作退单 $orderId";
            $this->addOpLog($opLog);
        }
        //todo 还原用户余额
        global $orderType;
        if (in_array($orderPO['pay_type'],$orderType)) {//会员卡消费
            $customerDao = new CustomerDao();
            $customerPO = $customerDao->getCustomerById($orderPO['custer_no']);
            $val = (float)$customerPO['total_money'] + (float)$orderPO['realChengjiaoer'];
            $cuRes = $customerDao->updateCustomerMoneyById($orderPO['custer_no'],$val);
            if (!$cuRes) {
                $this->objDao->rollback();
                $data['code'] = 100001;
                $data['message'] = "添加退单表修改客户信息失败！";
                echo json_encode($data);
                exit;
            } else {
                $this->objDao = new CustomerDao();
                $accountItem = array();
                $accountItem["customer_id"] = $customerPO['id'];
                $accountItem["before_val"] = $customerPO['total_money'];
                $accountItem["deal_val"] = $orderPO['realChengjiaoer'];
                $accountVal = $accountItem["after_val"] = bcadd($customerPO['total_money'],$orderPO['realChengjiaoer'],2);
                $accountItem["admin_id"] = $adminPO['id'];
                $accountItem["admin_name"] = $adminPO['real_name'];
                $accountItem["source_id"] = $customerPO['id'];
                $accountItem["source_type"] = "order_return";
                $accountItem["account_id"] = $customerPO['id'];
                $accountItem["account_type"] = "cutomer_account";
                $resultI = $this->objDao->addAccountItem4Order($accountItem);
                if (!$resultI) {
                    $this->objDao->rollback();
                    $data['code'] = 100001;
                    $data['message'] = "添加修改客户账户明细失败！";
                    echo json_encode($data);
                    exit;
                }
                $opLog = array();
                $opLog['who'] = $adminPO['id'];
                $opLog['who_name'] = $adminPO['real_name'];
                $opLog['what'] = $orderPO['order_no'];
                $opLog['Subject'] = OP_LOG_MODIFY_CUSTOMER_VAL;
                $opLog['memo'] = "退单{$orderPO['order_no']}修改{$customerPO['realName']}储值金额，原金额：{$customerPO['total_money']}，增加{$orderPO['realChengjiaoer']}，修改后{$val}";
                $this->addOpLog($opLog);
            }
        }
        //todo 还原优惠券
        $customerId = $orderPO['custer_no'];
        if ($orderPO['coupon_val'] > 0) {
            $otherObj['isUseCoupon'] = 1;
            $subVal = $otherObj['subVal'] = $orderPO['coupon_val'];
            $couponRes = $this->objDao->getCouponByCusIdLimit1($customerId);
            $cha_val  = 0.00;
            while ($row = mysql_fetch_array($couponRes)) {
                if ($row['coupon_type'] != 2 && $row['coupon_type'] != 5 ) {
                    $demainVal = $row['coupon_val'] + $subVal;

                    $res = $this->objDao->updateAccountValById($row['id'],$demainVal);
                    if (!$res) {
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "：优惠券修改失败！";
                        echo json_encode($data);
                        exit;
                    }
                    $accountItem = array();
                    $accountItem["customer_id"] = $customerPO['id'];
                    $accountItem["before_val"] = $row['coupon_val'];
                    $accountItem["deal_val"] = $subVal;
                    $accountItem["after_val"] = $demainVal;
                    $accountItem["admin_id"] = $adminPO['id'];;
                    $accountItem["admin_name"] = $adminPO['real_name'];
                    $accountItem["source_id"] = $orderId;
                    $accountItem["source_type"] = "or_order_total";
                    $accountItem["account_id"] = $row['id'];
                    $accountItem["account_type"] = "or_account_coupon";
                    $resultCO = $this->objDao->addAccountItem4Order($accountItem);
                    if(!$resultCO){
                        $this->objDao->rollback();
                        $data['code'] = 100001;
                        $data['message'] = "修改优惠券交易详情失败！";
                        echo json_encode($data);
                        exit;
                    }
                }

            }
        }
        //todo 还原数量
        $orderDao = new OrderDao();
        $orderRes = $orderDao->getOrderById($orderId);
        $kucunDao = new ProductDao();
        $printArray = array();
        while($order = mysql_fetch_array($orderRes)){

            $print['pro_code'] =  mysql_real_escape_string($order['pro_name']);

            $print['pro_num'] = $order['pro_num'] ;

            $print['price'] = $order['price'];
            $printArray[] = $print;
            $productPO = $kucunDao->getProductById($order['pro_id']);
            $kucunNum = $productPO['pro_num'] +  $order['pro_num'];
            $update = array();
            $update['pro_num'] = $kucunNum;
            $update['pro_id'] = $order['pro_id'];
            $res = $kucunDao->updateProductNumByProId($update);
            if ($res) {
                $opLog = array();
                $opLog['who'] = $user['id'];
                $opLog['who_name'] = $user['real_name'];
                $opLog['what'] = $orderId;
                $opLog['Subject'] = OP_LOG_REFUND_ORDER;
                $opLog['memo'] = "产品{$productPO['e_name']}退货恢复库存，原库存：{$productPO['pro_num']}，增加库存{$order['pro_num']}，修改后库存{$kucunNum}";
                $this->addOpLog($opLog);

                $productDao = new ProductDao();
                $produce = array();//
                $produce['pro_id'] = $productPO['id'];
                $produce['pro_code'] = $productPO['pro_code'];
                $produce['pro_num'] = $order['pro_num'];
                $produce['into_date'] = date('Y-m-d h:i:s',time());
                $produce['memo'] = '退单'.$orderId;
                $produce['old_storage'] = $productPO['pro_num'];
                $produce['new_storage'] = $kucunNum;
                $produce['op_type'] = 4;
                $result = $productDao->addIntoStorage($produce);
            } else {
                $this->objDao->rollback();
                $data['code'] = 100002;
                $data['message'] = "修改库存失败！";
                echo json_encode($data);
                exit;
            }


        }
        $this->objDao->commit();
        $this->objDao=new CustomerDao();

        $jingliPO = $this->objDao->getJingbanrenList();
        $jingliList = array();
        while($val = mysql_fetch_array($jingliPO)){
            $jingliList[$val['id']] = $val["jingbanren_name"];
        }
        $jingbanren_name = $user['real_name'];


        global $payType;
        $payTypeName = $payType[$orderPO['pay_type']];
        $customerName = $customerPO['realName'];

        if (in_array($orderPO['pay_type'],$orderType)) {
            //$sms = $_REQUEST['sms'];

            $time = date('Y年m月d日',time());
            if (!empty($customerPO['mobile'])) {

                $this->sendRefundSms($time, $customerName, $orderPO['realChengjiaoer'], $accountVal, $customerPO['mobile']);

            }
        }

        $this->printReturnOrder($printArray,$orderPO['chengjiaoer'],$orderPO['realChengjiaoer'],$jingbanren_name,$payTypeName,$customerName,$remark);
        $this->printReturnOrder($printArray,$orderPO['chengjiaoer'],$orderPO['realChengjiaoer'],$jingbanren_name,$payTypeName,$customerName,$remark);


        $data['code'] = 100000;
        $data['message'] = "退款成功！";
        echo json_encode($data);
        exit;
    }

}


$objModel = new OrderAction($actionPath);
$objModel->dispatcher();



?>

<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/CustomerDao.class.php");
require_once("module/dao/ProductDao.class.php");
require_once("module/dao/OrderDao.class.php");
require_once('tools/fpdf16/chinese-unicode.php');
require_once('tools/tcpdf/config/lang/eng.php');
require_once('tools/tcpdf/tcpdf.php');
require_once("tools/JPagination.php");
require_once("tools/printClass.php");
class StatAction extends BaseAction{
    /*
        *
        * @param $actionPath
        * @return AdminAction
        */
    function StatAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new StatForm();
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
            case "toProductStat" :
                $this->toProductStat();
                break;
            case "toCustomerStat" :
                $this->toCustomerStat();
                break;
            case "toCustomerOrderStat" :
                $this->toCustomerOrderStat();
                break;
            case "getProductStatList" :
                $this->getProductStatList();
                break;
            case "getCustomerStatList" :
                $this->getCustomerStatList();
                break;
            case "orderExportForSale" :
                $this->orderExportForSale();
                break;
            case "orderExportForIncomeList" :
                $this->orderExportForIncomeList();
                break;
            case "getCustomerOrderStatList" :
                $this->getCustomerOrderStatList();
                break;
            case "orderExportForCustomer" :
                $this->orderExportForCustomer();
                break;
            case "toOrderStatistics" :
                $this->toOrderStatistics();
                break;
            case "toOrderSearchList":
                $this->toOrderSearchList();
                break;
            case "orderExportForCustomerSum":
                $this->orderExportForCustomerSum();
                break;
            case "modelExport":
                $this->modelExport();
                break;
            default :
                $this->toAddProductPage();
                break;
        }



    }
    function toProductStat () {
        $this->mode = "stat_product";
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));

        $this->objForm->setFormData("levelList",$levelList);
        $this->objForm->setFormData("jingli",$jingliPO);
    }
    function toCustomerOrderStat () {
        $this->mode = "toCustomerOrderStat";
    }
    function toCustomerStat () {
        $this->mode = "stat_customer";
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));

        $this->objForm->setFormData("levelList",$levelList);
        $this->objForm->setFormData("jingli",$jingliPO);
    }
    function getProductStatList () {
        $where['start_date'] = $_REQUEST['from_date'];
        $where['end_date'] = $_REQUEST['to_date'];
        $where['order_by'] = $_REQUEST['order_by'];
        $where['pro_type'] = $_REQUEST['pro_type'];
        $where['keyword'] = $_REQUEST['keyword'];
        $where['customer_jingbanren'] = $_REQUEST['customer_jingbanren'];
        $this->objDao=new OrderDao();
        $result = $this->objDao->getOrderInfoListByRang($where);
        $this->objDao=new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val;
        }
        global $productType;
        global $payType;
        $orderList = array();
        $this->objDao=new OrderDao();
        while ($row = mysql_fetch_array($result)) {

            $orderTotal = $this->objDao->getOrderTotalByOrderNo($row['order_no']);
            $product = $this->objDao->getProductById($row['pro_id']);
            $row['jinban_name'] = !empty($jingbanList[$row['jingban_id']]['jingbanren_name']) ? $jingbanList[$row['jingban_id']]['jingbanren_name']:'';
            $row['pro_type_name'] = $productType[$row['pro_type']] ;
            $row['pay_type_name'] = $payType[$orderTotal['pay_type']];
            if ($row['real_price'] == 0)  {

                $row['real_per_price'] = $row['price'];
            } else {
                $row['real_per_price'] = $row['real_price'];
                //$row['real_order_jiner'] = $row['real_order_jiner'];

            }
            if ($row['real_order_jiner'] == 0) {

                $row['real_order_jiner'] = $row['order_jiner'];
            }
            $row['cost_price'] = $product['cost_price'];//成本单价
            $row['sum_cost'] = sprintf("%.2f", $product['cost_price']*$row['pro_num']);//成本合计
            $row['earn'] = bcsub($row['real_order_jiner'],$row['sum_cost'],2);//收益
            $row['pay_type_name'] = $payType[$orderTotal['pay_type']];
            $row['mark'] = $orderTotal['mark'];
            $row['option'] = '<a title="订单查详情" class="theme-color" target="_blank" href="index.php?action=Order&mode=getOrderById&orderId='.$row['order_no'].'&n='.$_REQUEST['n'].'">订单详情</a>';
            $orderList[] = $row;
        }
        $json['data'] = $orderList;
        echo json_encode($json);
        exit;
    }
    function getCustomerStatList () {
        $where['start_date'] = $_REQUEST['from_date'];
        $where['end_date'] = $_REQUEST['to_date'];
        $where['order_by'] = $_REQUEST['order_by'];
        $where['pro_type'] = $_REQUEST['pro_type'];
        $where['customer_keyword'] = $_REQUEST['keyword'];
        $where['customer_jingbanren'] = $_REQUEST['customer_jingbanren'];

        $this->objDao=new CustomerDao();
        if (!empty($where['customer_keyword'])) {
            $result = $this->objDao->getCustomerByName($where['customer_keyword']);
            $c = array();
            while ($row = mysql_fetch_array($result)) {
                $c[] = $row['id'];
            }
            $where['customer_ids'] = implode(",",$c);
        }
        $OrderDao=new OrderDao();
        $result = $OrderDao->getOrderInfoListByRang($where);
        $this->objDao=new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val;
        }
        global $productType;
        $orderList = array();
        while ($row = mysql_fetch_array($result)) {
            $orderTotal = $OrderDao->getOrderTotalByOrderNo($row['order_no']);
            $row['jinban_name'] = !empty($jingbanList[$row['jingban_id']]['jingbanren_name']) ? $jingbanList[$row['jingban_id']]['jingbanren_name']:'';
            $row['pro_type_name'] = $productType[$row['pro_type']] ;
            $row['zhidanren_name'] = $orderTotal['zhidanren_name'] ;

            $orderList[] = $row;
        }
        $json['data'] = $orderList;
        echo json_encode($json);
        exit;
    }
    function getCustomerOrderStatList () {
        $where['keyword'] = $_REQUEST['keyword'];
        $where['start_date'] = $_REQUEST['from_date'];
        $where['end_date'] = $_REQUEST['to_date'];
        $custo_level = $_REQUEST['custo_level'];
        $this->objDao=new OrderDao();
        $result = $this->objDao->getOrderTotalByRang($where);

        $this->objDao=new CustomerDao();
        $result_l = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result_l)) {
            $levelList[$row['id']]= $row;
        }

        global $payType;
        global $jiezhangType;
        while ($row = mysql_fetch_array($result)) {

            $customer = $this->objDao->getCustomerById($row['custer_no']);
            if ($row['order_no'] == 0 && $row['order_type'] == 1) {

                $account_item = $this->objDao->getAccountItemByOrderId($row['id'],'account_recharge');
            } else {

                $account_item = $this->objDao->getAccountItemByOrderId($row['order_no']);
            }

            if ($custo_level !=0 && $custo_level !=$customer['custo_level']) {
                continue;
            }
            $order['id'] = $row['id'];
            $order['order_no'] = $row['order_no'];
            $order['ding_date'] = $row['ding_date'];
            $order['total_money'] = $customer['total_money'];

            $order['level_name'] = $levelList[$customer['custo_level']]['level_name'];
            $order['custer_name'] = $row['custer_name'];
            $order['chengjiaoer'] = $row['chengjiaoer'];
            $order['mark'] = $row['mark'];
            $order['realChengjiaoer'] = $row['realChengjiaoer'];
            $order['isOff'] = $row['isOff'];
            $order['pay_status'] = $jiezhangType[$row['pay_status']];
            $order['pay_type'] = $payType[$row['pay_type']];

            $order['before_val'] = $account_item ? $account_item['before_val'] : 0;
            $order['after_val'] = $account_item ? $account_item['after_val']: 0 ;

            $order['option'] = '<a title="订单查详情" class="theme-color" target="_blank" href="index.php?action=Order&mode=getOrderById&orderId='.$row['order_no'].'&n='.$_REQUEST['n'].'">订单详情</a>';

            $orderList[] = $order;
        }
        $json['data'] = $orderList;
        echo json_encode($json);
        exit;
    }
    function orderExportForCustomer () {
        $where['keyword'] = $_REQUEST['keyword'];
        $where['start_date'] = $_REQUEST['from_date'];
        $where['end_date'] = $_REQUEST['to_date'];
        $this->objDao=new OrderDao();
        $result = $this->objDao->getOrderTotalByRang($where);

        $orderList = array();
        $orderList[]  = array('订单号','下单时间','客户名称','客户级别','实际金额','付款方式','付款状态','会员卡余额','备注');
        $customerDao = new CustomerDao();
        $results = $customerDao->getCustomerLevelList();
        $levelList = array();
        while ($rows = mysql_fetch_array($results)) {
            $levelList[$rows['id']]= $rows;
        }

        global $payType;
        global $jiezhangType;
        $sum = 0.00;
        while ($row = mysql_fetch_array($result)) {
            $order = array();
            $customerPO = $customerDao->getCustomerById($row['custer_no']);
            //print_r($customerPO);
            $order[] = (int)$row['order_no'];
            $order[] = $row['ding_date'];
            $order[] = $row['custer_name'];
            $order[] = $levelList[$customerPO['custo_level']]['level_name'];
            $sum += $order[] = (float)$row['realChengjiaoer'];
            $order[] = $payType[$row['pay_type']];
            $order[] = $jiezhangType[$row['pay_status']];
            $customer = $this->objDao->getCustomerById($row['custer_no']);
            $order[] = (float)$customer['total_money'];
            $order[] = $row['mark'];

            $orderList[] = $order;
        }
        //print_r($orderList);exit;
        require 'tools/php-excel.class.php';

        $orderList[] = array(1=> '合计',2=>$sum);
        $time = 'order';
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($orderList);
        $xls->generateXML($time."list");
        exit;
    }
    function orderExportForCustomerSum () {


        $customerList = array();
        $customerList[]  = array('序号','客户名称','公司名称','级别','本月消费金额','会员卡余额','联系方式','本月日期');
        $this->objDao = new CustomerDao();

        $custlist= $this->objDao->getCustomerList('',0,0);
        //$customerList = array();
        $result = $this->objDao->getCustomerLevelList();
        $jresult = $this->objDao->getJingbanrenList(array());
        $jingbanList = array();
        while ($row = mysql_fetch_array($jresult)) {
            $jingbanList[$row['id']]= $row;
        }
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $i = 0;
        while ($row = mysql_fetch_array($custlist)) {
            $string = $row['mobile'];
            $pattern = '/(\d{3})(\w+)(\d{3})/i';
            $replacement = '${1}*****$3';
            $str = preg_replace($pattern, $replacement, $string);
            $row['mobile'] = $str;
            $i++;
            $customer =array();
            $level = $levelList[$row['custo_level']];
            $sum_m = $this->objDao->getCurrentMonthOrderFee($row['id']);
            $customer[0] = $i;
            $customer[1] = $row['realName'];
            $customer[2] = $row['company_name'];
            $customer[3] = $level['level_name'];
            $customer[4] = (float)$sum_m ? $sum_m : 0.0;
            $customer[5] = (float)$row['total_money'];
            $customer[6] = $row['mobile'];
            $customer[7] = date("Y-m",time());
            $customerList[] = $customer;
        }
        require 'tools/php-excel.class.php';


        $time = 'customer';
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($customerList);
        $xls->generateXML($time."list");
        exit;
    }
    function orderExportForSale(){
        $where['datefrom'] = $_REQUEST['fromDate'];
        $where['dateto'] = $_REQUEST['toDate'];
        $where['jingbanren'] = $_REQUEST['jingbanren'];
        $this->objDao = new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList();

        $this->objDao = new OrderDao();
        $result = $this->objDao->searchOrderTotalListByType($where);
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val['jingbanren_name'];
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
            $sum += $data[6] = (float)$row['realChengjiaoer'];
            $data[7] = $row['mark'];
            $stuffSumList[] = $data;

        }
        $stuffSumList[] = array(1=> '合计',2=>$sum);
        $time = 'order';
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($jingbanList[$where['jingbanren']]."list");
        exit;
    }
    function orderExportForIncomeList(){
        $where['datefrom'] = $_REQUEST['fromDate'];
        $where['dateto'] = $_REQUEST['toDate'];
        $this->objDao = new CustomerDao();
        $jingliPO = $this->objDao->getJingbanrenList();

        $this->objDao = new OrderDao();
        $result = $this->objDao->searchOrderTotalListByType($where);
        $jingbanList= array();
        while ($val = mysql_fetch_array($jingliPO)) {
            $jingbanList[$val['id']] = $val['jingbanren_name'];
        }

        require 'tools/php-excel.class.php';

        $stuffSumList = array();
        $stuffSumList[]  = array('序号','订单号','订单日期','客户名称','付款方式','销售人员','合计金额','订单备注');

        //$list[] = $head_json;
        global $payType;

        $i = 1;
        $total = array();
        $total['order_money'] = 0.00;
        $total['vip_money'] = 0.00;
        $total['cash_money'] = 0.00;
        $total['free_money'] = 0.00;
        while ($row = mysql_fetch_array($result)) {
            $data =array();
            $data[0] = $i;$i++;
            $data[1] = $row['order_no'];
            $data[2] = $row['ding_date'];
            $data[4] = $row['custer_name'];
            $data[5] = $payType[$row['pay_type']];
            $data[6] = $jingbanList[$row['jingbanren']];
            $data[7] = (float)$row['realChengjiaoer'];
            $data[8] = $row['mark'];
            $stuffSumList[] = $data;
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
        $lastArr = array(
            0=> "总金额：",1=> (float)$total['order_money'],
            2=> "现金合计：",3=> (float)$total['cash_money'],
            4=> "转银行卡合计：",5=> (float)$total['toCard_money'],
            6=> "微信合计：",7=> (float)$total['weiChat_money'],
            8=> "支付宝合计：",9=> (float)$total['aliPay_money'],
            10=> "会员卡合计：",11=> (float)$total['vip_money'],
            12=> "赠送会员卡合计：",13=> (float)$total['freeVip_money'],
            14=> "赠送合计：",15=> (float)$total['free_money'],
            16=> "预收款合计：",17=> (float)$total['yuShou_money'],
            18=> "充值合计：",19=> (float)$total['recharge_money'],
            20=> "转账合计：",21=> (float)$total['zhuan_money'],
            22=> "刷卡合计：",23=> (float)$total['shuaka_money'],
            24=> "抵扣款项合计：",25=> (float)$total['dikou_money'],
            26=> "支票合计：",27=> (float)$total['zhipiao_money'],
            28=> "回款合计：",29=> (float)$total['huikuan_money'],
            30=> "退单合计：",31=> (float)$total['tuidan_money'],
            32=> "客户存酒合计：",33=> (float)$total['cunjiu_money'],
            34=> "活动赠送合计：",35=> (float)$total['huodong_money'],
            36=> "未结款合计：",37=> (float)$total['weijie_money'],
        );
        $stuffSumList[] = $lastArr;
        $time = 'order';
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML("income");
        exit;
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
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $jingliPO = $this->objDao->getJingbanrenList(array("jingbanrenType" => '2'));

        $this->objForm->setFormData("levelList",$levelList);
        $this->objForm->setFormData("jingli",$jingliPO);
        $this->objForm->setFormData("tongJiList",$tongJiList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("total_money",$total_money);
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
        $total['vip_money'] = 0.00;
        $total['cash_money'] = 0.00;
        $total['free_money'] = 0.00;
        $total['return_money'] = 0.00;
        global $payType;

        $this->objDao=new CustomerDao();
        $result_l = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result_l)) {
            $levelList[$row['id']]= $row;
        }
        /**
         * $payType=array(
        '1'=>'现金',
        '2'=>'会员卡',
        '3'=>'转账',
        '4'=>'赠送',
        '5'=>'活动赠送',
        '6'=>'转银行卡',
        '7'=>'未结账',
        '8'=>'微信',
        '9'=>'支付宝',
        '10'=>'赠送会员卡',
        '11'=>'刷卡',
        '12'=>'抵扣款项',
        '13'=>'支票',
        '14'=>'回款',
        '15'=>'退单',
        '16'=>'客户存酒',
        '17'=>'预收款',
        );
         */
        while($row = mysql_fetch_array($result)){

            $customer = $this->objDao->getCustomerById($row['custer_no']);
            $row['level_name'] = $levelList[$customer['custo_level']]['level_name'];

            $row['pay_type_name'] = $payType[$row['pay_type']];
            $orderList[] = $row;
            ++$total['order_num'];
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
            } elseif ($row['pay_type'] == 17) {//预收款
                $total['yuShou_money']+= $row['realChengjiaoer'];
            } elseif ($row['pay_type'] == 0 && $row['order_type'] == 1) {//充值
                $total['recharge_money']+= $row['realChengjiaoer'];
            }

        }

        while($row = mysql_fetch_array($reResult)){

            $customer = $this->objDao->getCustomerById($row['customer_id']);
            $row['level_name'] = $levelList[$customer['custo_level']]['level_name'];
            if (empty($row['customer_name'])) {
                $row['customer_name'] = $customer['realName'];
            }
            //print_r($row);exit;
            $returnList[] = $row;
            ++$total['return_num'];
            $total['return_money']+= $row['return_real_jin'];
            $total['return_money'] = sprintf("%.2f", $total['return_money']);
        }
        //print_r($returnList);exit;
        $total['order_money'] = sprintf("%.2f", $total['order_money']);
        $total['vip_money'] = sprintf("%.2f", $total['vip_money']);
        $total['cash_money'] = sprintf("%.2f", $total['cash_money']);
        $total['free_money'] = sprintf("%.2f", $total['free_money']);
        $this->objForm->setFormData("orderList",$orderList);
        $this->objForm->setFormData("returnList",$returnList);
        $this->objForm->setFormData("dateFrom",$dateFrom);
        $this->objForm->setFormData("dateTo",$dateTo);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("total",$total);
    }
    function modelExport () {
        //$customerList = array_merge(json_decode($_REQUEST['head']),json_decode($_REQUEST['excelData'],true));
        $head = json_decode($_REQUEST['head']);
        $excelData = json_decode($_REQUEST['excelData'],true);
        $columns = json_decode($_REQUEST['columns'],true);
        $customerList[] = $head;
        foreach ($excelData as $val) {
            $data = array();
            if (!empty($columns)) {
                $i = 0;
                foreach ($columns as $col) {

                    if (eregi("^[-+]?[0-9]*\.?[0-9]+$",$val[$col['data']])) {

                        $data[] = (float)$val[$col['data']];
                        //echo $val[$col['data']]."\n";
                    } else {

                        $data[] = $val[$col['data']];
                    }
                    $i++;
                }
            } else {
                $data = $val;
            }

            $customerList[] = $data;
        }
        //print_r($customerList);exit;
        require 'tools/php-excel.class.php';


        $time = 'customer';
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($customerList);
        $xls->generateXML($time."list");
        exit;
    }
}


$objModel = new StatAction($actionPath);
$objModel->dispatcher();



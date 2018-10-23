<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/OrderDao.class.php");
require_once("tools/Lunar.php");
require_once("tools/JPagination.php");
require_once("tools/printClass.php");
class CustomerAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function CustomerAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new CustomerForm();
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
            case "toAdd" :
                $this->toAddCustomerPage();
                break;
            case "addCustomer" :
                $this->addCustomer();
                break;
            case "getCustomerList" :
                $this->getCustomerList();
                break;
            case "getCustomerListJson" :
                $this->getCustomerListJson();
                break;
            case "getCustomer" :
                $this->getCustomer();
                break;
            case "getCustomerByIdJson" :
                $this->getCustomerByIdJson();
                break;
            case "updateCustomer" :
                $this->updateCustomer();
                break;
            case "delCustomer" :
                $this->delCustomer();
                break;
            case "getJingbanrenlist":
            	$this->getJingbanrenlist();
                break;
            case "addJingban":
            	$this->addJingban();
            	break;
            case "updateJingban":
            	$this->updateJingban();
            	break;
            case "updateZhidan":
            	$this->updateZhidan();
            	break;
            case "searchJingbanList":
            	$this->searchJingbanList();
            	break;
            case "delJingbanList":
            	$this->delJingbanList();
            	break;
            case "toCustomerExport" :
            	$this->toCustomerExport();
            	break;
            case "verifyCustomCode" :
            	$this->verifyCustomCode();
            	break;
            case "sumNongli" :
            	$this->sumNongli();
            	break;
            case "toAddCustomerLevel" :
            	$this->toAddCustomerLevel();
            	break;
            case "addCustomerLevel" :
            	$this->addCustomerLevel();
            	break;
            case "delCustomerLevel" :
            	$this->delCustomerLevel();
            	break;
            case "updateCustomerLevel" :
            	$this->updateCustomerLevel();
            	break;
            case "toCustomerStat" :
                $this->toCustomerStat();
                break;
            case "toCustomerOrderStat" :
                $this->toCustomerOrderStat();
                break;
            case "toCustomerLevelStat" :
                $this->toCustomerLevelStat();
                break;
            case "toCustomerByOrderStat" :
                $this->toCustomerByOrderStat();
                break;
            case "getCustomerLevelStatListJson" :
                $this->getCustomerLevelStatListJson();
                break;
            case "getCustomerByOrderStatListJson" :
                $this->getCustomerByOrderStatListJson();
                break;
            case "customerAddFee" :
                $this->customerAddFee();
                break;
            case "toGetCustomerBuyRecords" :
                $this->toGetCustomerBuyRecords();
                break;
            case "getCustomerBuyListJson" :
                $this->getCustomerBuyListJson();
                break;
            default :
                $this->modelInput();
                break;
        }



    }
    function toCustomerOrderStat () {
        $this->mode = "toCustomerOrderStat";
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();

        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $res = $this->objDao->getCustomerLevelSumAccount();
        $account_sum = $this->objDao->getCustomerLevelSumAccountVal();
        $account_sum_list = array();
        while ($account = mysql_fetch_array($res)) {
            $account_sum_list[] = $account;
        }
        $this->objForm->setFormData("levelList",$levelList);
        $this->objForm->setFormData("account_sum",$account_sum['sum_account_val']);
        $this->objForm->setFormData("account_sum_list",$account_sum_list);
    }
    function toCustomerLevelStat () {
        $this->mode = "toCustomerLevelStat";
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();

        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $this->objForm->setFormData("levelList",$levelList);
    }
    function toCustomerByOrderStat () {
        $this->mode = "toCustomerByOrderStat";
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();

        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $this->objForm->setFormData("levelList",$levelList);
    }
    function getCustomerLevelStatListJson () {
        $level = $_REQUEST['level'];
        $keyword = $_REQUEST['keyword'];
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $jresult = $this->objDao->getJingbanrenList(array(),false);
        $jingbanList = array();
        while ($row = mysql_fetch_array($jresult)) {
            $jingbanList[$row['id']]= $row;
        }

        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $where = ' where 1=1 and del_flag = 0';
        if ($level != 0) {
            $where .= " and custo_level = $level";
        }
        if (!empty($keyword)) {
            $where .= " and realName like '%$keyword%' ";
        }
        $result = $this->objDao->getCustomerListByLevel($where);

        $cusList = array();
        while ($row = mysql_fetch_array($result)) {
            $level = $levelList[$row['custo_level']];
            $row['jinbanren_name'] = $jingbanList[$row['customer_jingbanren']]['jingbanren_name'];
            $row['customer_level_name'] = $level['level_name'];
            $row['option'] = '<a title="消费记录" class="theme-color" target="_blank" href="index.php?action=Customer&mode=toGetCustomerBuyRecords&customer_id='.$row['id'].'&n='.$_REQUEST['n'].'">消费记录</a>';
            $cusList[]= $row;
        }
        $json['data'] = $cusList;
        echo json_encode($json);
        exit;
    }
    function getCustomerByOrderStatListJson () {
        $level = $_REQUEST['level'];
        $from = $_REQUEST['from_date'];
        $to = $_REQUEST['to_date'];
        $this->objDao=new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $jresult = $this->objDao->getJingbanrenList(array(),false);
        $jingbanList = array();
        while ($row = mysql_fetch_array($jresult)) {
            $jingbanList[$row['id']]= $row;
        }

        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $result = $this->objDao->getCustomerBuyValListByLevel   ($from,$to,$level);

        $cusList = array();
        $i = 1;
        while ($row = mysql_fetch_array($result)) {
            $row['index']  =  $i;
            $level = $levelList[$row['custo_level']];
            $row['jinbanren_name'] = $jingbanList[$row['customer_jingbanren']]['jingbanren_name'];
            $row['customer_level_name'] = $level['level_name'];
            $cusList[]= $row;
            $i++;
        }
        $json['data'] = $cusList;
        echo json_encode($json);
        exit;
    }
    function toGetCustomerBuyRecords () {
        $this->mode = "customer_buy_records";
        $customer_id = $_REQUEST['customer_id'];

        $this->objForm->setFormData("customer_id",$customer_id);

    }
    function getCustomerBuyListJson () {
        $where['customer_id'] = $_REQUEST['customer_id'];
        $where['keyword'] = $_REQUEST['keyword'];
        $where['type'] = $_REQUEST['search_type'];
        $this->objDao = new CustomerDao();
        $result = $this->objDao->getConsumeRecodesListByCustomerId($where);
        $account_type = array(
            "cutomer_account",//余额扣款
            "or_account_coupon",//优惠券扣款
            "or_customer_account",//年费扣减
            "account_change",//账户变更
        );
        $data_list = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['source_type'] == 'account_recharge' && $row['deal_val'] < 0) {
                $row['consume_type'] = '<span class="label label-success">退款后充值</span>';
            }
            elseif ($row['source_id'] == 0 || $row['source_type'] == 'account_recharge') {
                $row['consume_type'] = '<span class="label label-success">充值</span>';
            } elseif ($row['source_type'] == 'or_order_total') {
                $row['consume_type'] = '<span class="label label-success">消费订单</span>';
            } else if ($row['source_type'] == 'nian_800') {
                $row['consume_type'] = '<span class="label label-success">扣减年费</span>';
            } else if ($row['source_type'] == 'account_change') {
                $row['consume_type'] = '<span class="label label-success">账户变更</span>';
            }
            if ($row['account_type'] == "cutomer_account") {
                $row['account_type_name'] = '会员账户';
            } elseif ($row['account_type'] == "or_customer_account") {
                $row['account_type_name'] = '会员账户';
            } elseif ($row['account_type'] == "or_account_coupon") {
                $row['account_type_name'] = '优惠券账户';
            }
            $customer = $this->objDao->getCustomerById($row['customer_id']);
            $row['customer']  = $customer;
            $data_list[] = $row;
        }
        echo json_encode(array(
            'data' => $data_list
        ));
        exit;
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
/**
   * 添加客户信息
   */
  function addCustomer(){
  	$exmsg=new EC();//设置错误信息类
  	$this->objDao=new CustomerDao();
      //$result = $this->objDao->getCustomerByCardNo($_REQUEST['card_no']);
      $result = false;
      if ($result) {
          $this->objForm->setFormData("warn","该客户编号已经存在");
          $this->getCustomerList();
      } else {
          $result=$this->objDao->addCustomer($_REQUEST);
          if(!$result){
              $exmsg->setError(__FUNCTION__, "add customer   faild ");
              //事务回滚
              $this->objDao->rollback();
              $this->objForm->setFormData("warn","添加客户信息失败");
              throw new Exception ($exmsg->error());
          }else{
              $saveLastId=$this->objDao->g_db_last_insert_id();
              $suess="添加成功！客户编码：".$saveLastId;
              $this->objForm->setFormData("suess",$suess);
          }

          header('Location: http://182.92.81.13/eOrderOATest/index.php?action=Customer&mode=getCustomerList&n=3_1');
      }


  }
  /**
   * 修改客户信息
   */
  function updateCustomer(){
  	$exmsg=new EC();//设置错误信息类
  	$this->objDao=new CustomerDao();
      $admin=$_SESSION['admin'];
      $this->objDao->beginTransaction();
  	$customer = $this->objDao->getCustomerById($_REQUEST['id']);

  	$result=$this->objDao->updateCustomerById($_REQUEST);
        if(!$result){
				$exmsg->setError(__FUNCTION__, "update customer   faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","修改客户信息失败");
				throw new Exception ($exmsg->error());
            $this->objDao->rollback();
			}else{
            if ($_REQUEST['realName'] != $customer['realName']) {
                $this->objDao->updateOrderCustomerName($_REQUEST);
            }
				$suess="修改成功！";
                if ($_REQUEST['total_money'] != $customer['total_money']) {
                    $accountItem = array();
                    $accountItem['customer_id'] = $_REQUEST['id'];
                    $accountItem['before_val'] = $customer['total_money'];
                    $accountItem['after_val'] = $_REQUEST['total_money'];
                    $accountItem['deal_val'] = $customer['total_money'] - $_REQUEST['total_money'];
                    $accountItem['admin_id'] = $admin['id'];
                    $accountItem['admin_name'] = $admin['real_name'];
                    $accountItem["source_id"] = 0;
                    $accountItem["source_type"] = "account_change";
                    $accountItem["account_id"] = $_REQUEST['id'];
                    $accountItem["account_type"] = "or_customer_account";
                    $res = $this->objDao->addAccountItem4Order($accountItem);
                    if (!$res) {
                        $this->objDao->rollback();
                        $this->objForm->setFormData("warn","修改客户信息失败");
                    } else {

                        $this->objForm->setFormData("suess",$suess);
                    }
                } else {

                }
			}

	    //$this->getAdminList();

      $this->objDao->commit();
	     $this->getCustomer($_REQUEST['id']);
  	     
  }
    /**
     * 得到管理员列表
     */
  function toAddCustomerPage(){
  	    $this->mode="toAdd";
      $this->objDao=new CustomerDao();
      $result = $this->objDao->getCustomerLevelList();
      $levelList = array();
      while ($row = mysql_fetch_array($result)) {
          $levelList[$row['id']]= $row;
      }
      $jingban['jingbanrenType'] = 2;//销售经理
      $jresult = $this->objDao->getJingbanrenList($jingban);
      $jingbanList = array();
      while ($row = mysql_fetch_array($jresult)) {
          $jingbanList[$row['id']]= $row;
      }

      $this->objForm->setFormData("levelList",$levelList);
      $this->objForm->setFormData("jingbanList",$jingbanList);
  }
  /**
   * 删除管理员
   */
  function  delCustomer(){
  	$today=date("Y-m-d");//当天日期
		//查询管理员详细信息
		global $loginusername;
		$this->objDao=new CustomerDao();
		//开始事务    
		$this->objDao->beginTransaction();
		//取得管理员信息
		$admin=$_SESSION['admin'];
		$exmsg=new EC();//设置错误信息类
		$cusId=$_REQUEST["custoId"];
  	    $result=$this->objDao->delCustomerById($cusId);
        if(!$result){
				$exmsg->setError(__FUNCTION__, "delete customer   faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","删除客户信息操作失败！");
				throw new Exception ($exmsg->error());
			}
        $opLog=array();
			$opLog['who']=$admin['id'];
			$opLog['what']=$cusId;
			$opLog['Subject']=OP_LOG_DEL_CUSTOMER;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "delCustomer  add oplog  faild ");
				$this->objForm->setFormData("warn","删除客户信息操作失败！");
				//事务回滚  
				$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}else{
				$this->objForm->setFormData("succ","删除客户信息操作成功！");
			}
		//事务提交
	    $this->objDao->commit();
	    $this->getCustomerList();
  }
    function getCustomerListJson () {
        $this->objDao = new CustomerDao();

        $result = $this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }
        $keyword = $_REQUEST['keyword'];

        $result = $this->objDao->getCustomerAll($keyword);
        $customerList = array();
        while ($row = mysql_fetch_array($result)){
            if (empty($row['custo_level']))  {
                $levelName = "非会员";
                $money = "0";
                $discount = "无";
            } else {

                $levelName = $levelList[$row['custo_level']]['level_name'];
                $row['level_discount'] = $discount = $levelList[$row['custo_level']]['discount'];
                $money = $row['total_money'];
            }
            $row['name'] = $row['card_no'].' '.$row['realName']."【".$levelName."】余额：$money 折扣：{$discount}折";
            $customerList[] = $row;
        }
        echo json_encode($customerList);
        exit;
    }
  function getCustomerList(){
  	$this->mode="toList";
      $searchKey=trim($_REQUEST['searchKey']);
      if (!empty($searchKey)) {
          $custName = $searchKey;
      } else {
          $custName=$_REQUEST['keyWord'];
      }
  	/*$custName=$this->js_unescape($custName);*/
  	$custNo=$_REQUEST['custNo'];
  	$custType=$_REQUEST['custType'];
      $where = array();
      $where['customer_code'] = $custNo;
      $where['customer_type'] = $custType;
      $where['realName'] = $custName;
  	$this->objDao=new CustomerDao();
 	$whereCount="1=1";
    if($custNo){
   	    	$whereCount.=" and id  =$custNo";
   	    }else{
   	    if($custName){
   	    	$whereCount.=" and realName like '%".$custName."%'";
   	    }
   	    if($custType){
   	    	$whereCount.=" and custo_type =$custType";
   	    }
   	    }
	$sum =$this->objDao->g_db_count("or_customer","*",$whereCount);
      $pageSize=PAGE_SIZE;
      $count = intval($_GET['c']);
      $page = intval($_GET['page']);
      if ($count == 0){
          $count = $pageSize;
      }
      if ($page == 0){
          $page = 1;
      } elseif ($sum <= $pageSize) {
          $page = 1;
      }

      $startIndex = ($page-1)*$count;
      $total = $sum;
        $custlist= $this->objDao->getCustomerList($where,$startIndex,$pageSize);
      $pages = new JPagination($total);
      $pages->setPageSize($pageSize);
      $pages->setCurrent($page);
      if (!empty($searchKey)) {
          $data['key'] = 'searchKey';
          $data['val'] = $searchKey;
          $pages->setParam(array('1'=>$data));
      }
      $pages->makePages();
      $customerList = array();
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
      while ($row = mysql_fetch_array($custlist)) {



          $customer =array();
          $level = $levelList[$row['custo_level']];

          $customer['id'] = $row['id'];
          $customer['company_name'] = $row['company_name'];
          $customer['card_no'] = $row['card_no'];
          $customer['discount'] = $row['discount'];
          $customer['address'] = $row['address'];
          $customer['realName'] = $row['realName'];
          $customer['total_money'] = $row['total_money'];
          $customer['jinbanren_name'] = $jingbanList[$row['customer_jingbanren']]['jingbanren_name'];
          $customer['custo_level'] = $row['custo_level'];
          $customer['customer_level_name'] = $level['level_name'];
          $customer['discount'] = $level['discount'];
          $customer['mobile'] = $row['mobile'];
          $customerList[] = $customer;
      }
      $this->objForm->setFormData("customerList",$customerList);
      $this->objForm->setFormData("custName",$custName);
      $this->objForm->setFormData("total",$total);
      $this->objForm->setFormData("page",$pages);
  }
/**
	 * 得到操作日志列表
	 */
	function getCustomer($custId=null){
		$this->mode="getCust";
		$this->objDao=new CustomerDao();
		if(empty($custId)){
		$custId=$_REQUEST['id'];
		}
		$custPO =$this->objDao->getCustomerById($custId);
        $result =$this->objDao->getCustomerLevelList();
        $levelList = array();
        while ($row = mysql_fetch_array($result)) {
            $levelList[$row['id']]= $row;
        }


        $jingban['jingbanrenType'] = 2;//销售经理
        $jresult = $this->objDao->getJingbanrenList($jingban);
        $jingbanList = array();
        while ($row = mysql_fetch_array($jresult)) {
            $jingbanList[$row['id']]= $row;
        }

        $this->objForm->setFormData("levelList",$levelList);
        $this->objForm->setFormData("jingbanList",$jingbanList);
	 $this->objForm->setFormData("customerPo",$custPO);
	}
    function getCustomerByIdJson () {
        $this->objDao=new CustomerDao();
        $custId=$_REQUEST['id'];
        $data = array();
        if(empty($custId)){
            $data['code'] = 100001;
            $data['message'] = '参数错误，重新尝试';
        }
        $custPO =$this->objDao->getCustomerById($custId);
        $level = $this->objDao->getCustomerLevelById($custPO['custo_level']);
        $coupon = $this->objDao->getCouponByCusId($custId);
        $custPO['coupon'] =array();
        while ($row = mysql_fetch_array($coupon)) {
            $custPO['coupon'][] =$row;
        }
        $custPO['level_discount'] = $level['discount'];
        $custPO['custo_discount'] = $level['discount'];
        if (!$custPO) {
            $data['code'] = 100002;
            $data['message'] = '查询失败，重新尝试';
        } else {
            $data['data'] = $custPO;
            $data['code'] = 100000;
        }
        echo json_encode($data);
        exit;
    }
   function getJingbanrenlist(){
   	    $this->mode="tojingbanrenList";
   	    $jingban=array();
   	    $this->objDao=new CustomerDao();
   	    $jingbanrenList=$this->objDao->getJingbanrenList($jingban);
   	    $this->objForm->setFormData("jingbanList",$jingbanrenList);
   }
   function searchJingbanList(){
   	    $this->mode="tojingbanrenList";
   	    $jingban=array();
   	    $jingban['jingbanNo']=$_REQUEST['jingbanNo'];
   	    $jingban['jingbanName']=$_REQUEST['jingbanName'];
   	    $jingban['jingbanGroup']=$_REQUEST['jingbanGroup'];
   	    $this->objDao=new CustomerDao();
   	    $jingbanrenList=$this->objDao->getJingbanrenList($jingban);
   	    $this->objForm->setFormData("jingbanList",$jingbanrenList);
   }
   function addJingban(){
   	$jingban=array();
   	$jingban['jingbanName']=$_REQUEST['c_name'];
   	$jingban['jingbanGroup']=$_REQUEST['c_type'];
   	    $this->objDao=new CustomerDao();
   	    $jingbanrenList=$this->objDao->saveJingban($jingban);
       if (!$jingbanrenList){
           $data['code'] = 100001;
           $data['message'] = '添加失败';
       }
       else {
           $data['code'] = 100000;
           $data['message'] = '';
       }
       echo json_encode($data);
       exit;
   }
   function updateJingban(){
   	$jingban['jingbanName']=$_REQUEST['c_name'];
   	$jingban['jingbanGroup']=$_REQUEST['c_type'];
   	$jingban['id']=$_REQUEST['id'];
   	    $this->objDao=new CustomerDao();
   	    $jingbanrenList=$this->objDao->updateJingban($jingban);
       if (!$jingbanrenList){
           $data['code'] = 100001;
           $data['message'] = '修改失败';
       }
       else {
           $data['code'] = 100000;
           $data['message'] = '';
       }
       echo json_encode($data);
       exit;
   }
   function updateZhidan(){
   	$val=$_REQUEST['value'];
   	$id=$_REQUEST['id'];
   	    $this->objDao=new CustomerDao();
   	    $jingbanrenList=$this->objDao->updateZhidan($id,$val);
       if (!$jingbanrenList){
           $data['code'] = 100001;
           $data['message'] = '修改失败';
       }
       else {
           $data['code'] = 100000;
           $data['message'] = '';
       }
       echo json_encode($data);
       exit;
   }
   function delJingbanList(){
   	    $jingbanId=$_REQUEST['id'];
   	    $this->objDao=new CustomerDao();
   	    $jingbanrenList=$this->objDao->delJingban($jingbanId);
   	    $this->objForm->setFormData("succ","删除成功");
       if (!$jingbanrenList){
           $data['code'] = 100001;
           $data['message'] = '删除失败';
       }
       else {
           $data['code'] = 100000;
           $data['message'] = '';
       }
       echo json_encode($data);
       exit;

   }
   function toCustomerExport(){
   	    $this->mode="toCustomerExport";
   }
    function toAddCustomerLevel() {
        $this->mode="toAddCustomerLevel";
        $this->objDao = new CustomerDao();
        $result = $this->objDao->getCustomerLevelList();
        $this->objForm->setFormData("levelList",$result);
    }
    function delCustomerLevel () {
        $id = $_REQUEST['id'];
        $this->objDao = new CustomerDao();
        $result = $this->objDao->delCustomerLevel($id);
        if (!$result){
            $data['code'] = 100001;
            $data['message'] = '删除失败';
        }
        else {
            $data['code'] = 100000;
            $data['message'] = '';
        }
        echo json_encode($data);
        exit;
    }
    function updateCustomerLevel () {
        $this->objDao = new CustomerDao();
        $level = array();
        $level['id'] = $_REQUEST['id'];
        $level['level_name'] = $_REQUEST['level_name'];
        $level['discount'] = $_REQUEST['discount'];
        /*$result = $this->objDao->getCustomerLevelByName($level);
        if ($result) {
            $data['code'] = 100003;
            $data['message'] = '修改名称已经存在';
            echo json_encode($data);
            exit;
        }*/

        $result = $this->objDao->updateCustomerLevel($level);
        $data = array();
        if (!$result){
            $data['code'] = 100001;
            $data['message'] = '修改失败';
        }
        else {
            $data['code'] = 100000;
            $data['message'] = '';
        }
        echo json_encode($data);
        exit;
    }
    function addCustomerLevel (){
        $this->objDao = new CustomerDao();
        $level = array();
        $level['level_name'] = $_REQUEST['level_name'];
        $level['discount'] = $_REQUEST['discount'];
        $result = $this->objDao->getCustomerLevelByName($level);
        if ($result) {
            $data['code'] = 100003;
            $data['message'] = '添加名称已经存在';
            echo json_encode($data);
            exit;
        }
        $result = $this->objDao->addCustomerLevel($level);
        $data = array();
        if (!$result){
            $data['code'] = 100001;
            $data['message'] = '保存失败';
        }
        else {
            $data['code'] = 100000;
            $data['message'] = '';
        }
        echo json_encode($data);
        exit;
    }
    function verifyCustomCode (){
        $code=$_REQUEST['code'];
        if (empty($code)){
            echo 'false';
            exit;
        }
        $this->objDao = new CustomerDao();
        $result = $this->objDao->getCustomerByCode($code);
        if ($result) {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }
    function sumNongli (){
        $date = $_REQUEST['date'];
        $date = strtotime($date);
        $d=date("d",$date);//
        $m=date("m",$date);//
        $y=date("Y",$date);//
        $lunar = new Lunar();
        $month = $lunar->convertSolarToLunar($y,$m,$d); //将阴历转换为阳历
        //$month = $lunar->convertSolarToLunar(1987,07,07); //将阴历转换为阳历
        //print_r($month);
        $nongli = $month[0].'-'.$month[1].'-'.$month[2];
        $n['data'] = $nongli;
        echo json_encode($n);
        exit;
    }
    function customerAddFee () {
        $admin=$_SESSION['admin'];
        $add_fee = $_REQUEST["fee"];
        $customerId = $_REQUEST["customer_id"];
        $reg = "/^[0-9](.[0-9]{1,2})?$/";
        $result = array();
        if(!is_numeric($add_fee)) {
            $result["code"] = "100001";
            $result["message"] = "输入金额有误";
            echo json_encode($result);exit;
        }
        $this->objDao = new CustomerDao();
        $this->objDao->beginTransaction();
        $customer = $this->objDao->getCustomerById($customerId);
        $afterFee = bcadd($add_fee,$customer['total_money'],2);

        $orderPO = array();
        $orderPO['order_no'] = 0;
        $orderPO['custer_no'] = $customerId;
        $orderDao = new OrderDao();
        $customer = $orderDao->getCustomerById($customerId);
        $orderPO['custer_name'] = $customer['realName'];

        $orderPO['zhidanren_name'] = $admin['real_name'];
        $orderPO['op_id'] = $admin['id'];
        $orderPO['chengjiaoer'] = $add_fee;
        $orderPO['realChengjiaoer'] = $add_fee;
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
        $accountItem['before_val'] = $customer['total_money'];
        $accountItem['after_val'] = $afterFee;
        $accountItem['deal_val'] = $add_fee;
        $accountItem['admin_id'] = $admin['id'];
        $accountItem['admin_name'] = $admin['real_name'];
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
        $res = $this->objDao->updateCustomerMoneyById($customerId,$afterFee);
        if (!$res) {
            $this->objDao->rollback();
            $data['code'] = 100001;
            $data['message'] = "添加客户充值金额失败！";
            echo json_encode($data);
            exit;
        }

        $printInfo = array();
        $printInfo["before_val"] = $customer['total_money'];
        $printInfo["after_val"] = $afterFee;
        $printInfo["customer_name"] = $customer['realName'];
        $printInfo["deal_val"] = $add_fee;
        $printInfo["addId"] = $addId;
        $printInfo['admin_name'] = $admin['real_name'];

        $printInfo["orderSaveId"] = $orderSaveId;
        $this->objDao->commit();
        $this->printAddMoney($printInfo);
        $this->printAddMoney($printInfo);
        $data['code'] = 100000;
        $data['message'] = "添加客户充值成功！";
        echo json_encode($data);
        exit;
    }
    function printAddMoney ($printInfo) {
        header("Content-type: text/html; charset=utf-8");
        $printObj = new PrintClass();
        $message = $printObj->getAddMoneyTitle();
        $tmp = $printObj->generateAddMoneyFormat("充值",$printInfo['deal_val'],"");
        $message = $message.$tmp.'
';
        $adminPO = $_SESSION['admin'];
        $message .= '
--------------------------------
当前余额：';
        $message = $message.$printInfo['after_val'].'元

充值前金额：'.$printInfo['before_val'].'元
客户名称：'.$printInfo['customer_name'].'
充值Id：'.$printInfo['addId'].'
时间：'.date("Y-m-d H:i:s").'
制单人：  '.$printInfo['admin_name'].'
尚嘉品鉴欢迎您
电话：010-87722599
                               ';
        //echo $message;exit;
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
}


$objModel = new CustomerAction($actionPath);
$objModel->dispatcher();



?>

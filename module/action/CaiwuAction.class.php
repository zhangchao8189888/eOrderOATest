<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/OrderDao.class.php");
require_once("module/dao/CustomerDao.class.php");
require_once("module/dao/ProductDao.class.php");
require('tools/fpdf16/chinese-unicode.php'); 
require_once('tools/tcpdf/config/lang/eng.php');
require_once('tools/tcpdf/tcpdf.php');
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
class CaiwuAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function CaiwuAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new CaiwuForm();
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
            case "toCaiwuImportExcel" :
                $this->toCaiwuImportExcel();
                break;
            case "toCaiwuAdd" :
                $this->toCaiwuAdd();
                break;
            case"getCaiwuList":
            	$this->getCaiwuList();
                break;
            case "caiwuAdd":
            	$this->caiwuAdd();
                break;
            case "caiwuYushou":
            	$this->caiwuYushou();
                break;
            case "caiwuJiekuanByOrderNo":
            	$this->caiwuJiekuanByOrderNo();
                break;
            case "allJiekuan":
            	$this->allJiekuan();
                break;
            case "toCaiwuExcel":
            	$this->toCaiwuExcel();
                break;
            case "toCaiwuBackup":
            	$this->toCaiwuBackup();
                break;
            case "upload":
            	$this->filesUp();
                break;
            case "excelToHtml" :
				$this->excelToHtml();
				break;
		    case "del":
				$this->fileDel();
				break;
		    case "caiwuImport":
		    	$this->caiwuImport();
				break;
		    case "caiwuJiekuanCancelByOrderNo":
		    	$this->caiwuJiekuanCancelByOrderNo();
				break;
		    case "caiwuYUshouModify":
		    	$this->caiwuYUshouModify();
				break;
		    case "toCaiwuDele":
		    	$this->toCaiwuDele();
				break;
			case "allCaiwuDelete":
		    	$this->allCaiwuDelete();
				break;
			case "toCaiwuReturn":
				$this->toCaiwuReturn();
				break;
			case "caiwuReJiekuan":
				$this->caiwuReJiekuan();
				break;
            default :
                $this->toAddProductPage();
                break;
        }
    }
   function toCaiwuImportExcel(){
   	   
   	
   	
   }
   function toCaiwuAdd(){
   	  $this->mode="toCaiwu";
   	
   }
   function getCaiwuList(){
   	$this->mode="toCaiwu";
   	$orderNo=$_REQUEST['orderNo'];
   	$custoNo=$_REQUEST['custoList'];
   	$custName=$_REQUEST['custoName'];
   	$jingbanren=$_REQUEST['jingbanren'];
   	$dateFrom=$_REQUEST['dateFrom'];
   	$dateTo=$_REQUEST['dateTo'];
   	//echo $dateFrom.$dateTo."////////////";
   	$seachList=array();
   	$this->objDao=new OrderDao();
   	$weijieheji=0;
   	if($orderNo){
   		$i=0;
   		$orderTotalPO=mysql_fetch_array($this->objDao->getOrderTotal("","","",$orderNo));
   		$seachList[$i]['order']=$orderTotalPO;
   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($orderNo);
   		if($caiwuPO){
   		$seachList[$i]['caiwu']=$caiwuPO;	
   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$orderTotalPO['chengjiaoer']-$orderTotalPO['tuikuanger'];
	   	$seachList[$i]['caiwu']=$caiwuPO;	
   		}
   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
   			$this->objForm->setFormData("orderNo",$orderNo);
   	}elseif($custoNo){
   		$result=$this->objDao->getOrderTotalByCustNo($custoNo);
   		$i=0;
   		while ($row=mysql_fetch_array($result)){
   			$seachList[$i]['order']=$row;
	   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($row['order_no']);
	   		if($caiwuPO){
	   		$seachList[$i]['caiwu']=$caiwuPO;	
	   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$row['chengjiaoer']-$row['tuikuanger'];
	   		$seachList[$i]['caiwu']=$caiwuPO;
	   		}
	   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
	   		$i++;
   		}
   			$this->objForm->setFormData("custName",$custName);
   			$this->objForm->setFormData("custNo",$custoNo);
   	}elseif($jingbanren){
   	$result=$this->objDao->searchOrderTotalListByJingbanren($jingbanren);
   		$i=0;
   		while ($row=mysql_fetch_array($result)){
   			$seachList[$i]['order']=$row;
	   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($row['order_no']);
	   		if($caiwuPO){
	   		$seachList[$i]['caiwu']=$caiwuPO;	
	   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$row['chengjiaoer']-$row['tuikuanger'];
	   		$seachList[$i]['caiwu']=$caiwuPO;
	   		}
	   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
	   		$i++;
   		}
   			$this->objForm->setFormData("jingbanren",$jingbanren);
   	}elseif($dateFrom&&$dateTo){
   		$result=$this->objDao->getOrderTotalListByFanwei(0,$dateFrom,$dateTo);
   		$i=0;
   		while ($row=mysql_fetch_array($result)){
   			$seachList[$i]['order']=$row;
	   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($row['order_no']);
	   		if($caiwuPO){
	   		$seachList[$i]['caiwu']=$caiwuPO;	
	   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$row['chengjiaoer']-$row['tuikuanger'];
	   		$seachList[$i]['caiwu']=$caiwuPO;
	   		}
	   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
	   		$i++;
   		}
   			$this->objForm->setFormData("dateFrom",$dateFrom);
   		    $this->objForm->setFormData("dateTo",$dateTo);
   		
   	}
   	$this->objForm->setFormData("weijieheji",$weijieheji);
   	$this->objForm->setFormData("caiwuList",$seachList);
   
   }
   function caiwuAdd(){
   	 $orderListStr=$_REQUEST["orderNoList"];
   	$jiekuanjin=$_REQUEST["jiekuanjin"];
   	$orderList=split(",",$orderListStr);
   	$this->objDao=new OrderDao();
   	$str="";
   	$shengyujin=$jiekuanjin;//剩余金额
   	//开始事务    
		$this->objDao->beginTransaction();
		$exmsg=new EC();//设置错误信息类
   	foreach ($orderList as $orderNo){
   		if($orderNo=="")continue;
   		if($shengyujin<0){
   			$shengyujin=0;
   		}
   		$orderTotalPO=mysql_fetch_array($this->objDao->getOrderTotal("","","",$orderNo));//查询订单
   		$caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo);	//查询财务
   		$caiwuPO=array();
   		if(!$caiwusearchPO){
   		$weijie=$orderTotalPO['chengjiaoer']-$orderTotalPO['tuikuanger'];
   		$yuanyushou=0;
   		}else{
   		$weijie=$caiwusearchPO['weijiekuan'];	
   		}
   		$caiwuPO['order_no']=$orderTotalPO['order_no'];
   		if($shengyujin>$weijie){
   		$caiwuPO['yushoujine']=$weijie+$yuanyushou;
   		$caiwuPO['weijiekuan']=0;
   		$caiwuPO['cust_no']=$orderTotalPO['custer_no'];
   		$caiwuPO['cust_name']=$orderTotalPO['custer_name'];
   		$caiwuPO['isjiekuan']=1;
   		$shengyujin=$shengyujin-$weijie;
   		}else{
   		$caiwuPO['yushoujine']=$shengyujin+$yuanyushou;
   		$caiwuPO['weijiekuan']=$weijie-$shengyujin;
   		$caiwuPO['cust_no']=$orderTotalPO['custer_no'];
   		$caiwuPO['cust_name']=$orderTotalPO['custer_name'];
   		$caiwuPO['isjiekuan']=0;
   		$shengyujin=0;
   		}
   		//var_dump($caiwuPO);
   		
   		$result=$this->objDao->orderCheck($caiwuPO['order_no'],$caiwuPO['isjiekuan']);
   	    if(!$result){
				$exmsg->setError(__FUNCTION__, "update orderTotal jiekuan faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
		if($caiwusearchPO){
		$caiwuPO['yushoujine']+=$caiwusearchPO['yushoujine'];
		//$caiwuPO['weijiekuan']-=$caiwusearchPO['yushoujine'];
		$result=$this->objDao->updateCaiwuByOrderNo($caiwuPO);	
		}else{
   		$result=$this->objDao->addCaiwu($caiwuPO);
		}
   	   if(!$result){
				$exmsg->setError(__FUNCTION__, "add caiwu faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
   	}
   	
   	//事务提交
	    $this->objDao->commit();
   	$this->getCaiwuList();
   }
   function caiwuYushou(){
   	$yushou=$_REQUEST['yushoujin'];
   	$orderNo=$_REQUEST['order_no'];
   	$shengyujin=$yushou;//剩余金额
   	$this->objDao=new OrderDao();
   	//开始事务    
		$this->objDao->beginTransaction();
		$exmsg=new EC();//设置错误信息类
   	$orderTotalPO=mysql_fetch_array($this->objDao->getOrderTotal("","","",$orderNo));//查询订单
   	$caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo);	//查询财务
   $caiwuPO=array();
   		if(!$caiwusearchPO){
   		$weijie=$orderTotalPO['chengjiaoer']-$orderTotalPO['tuikuanger'];
   		$yuanyushou=0;
   		}else{
   		$weijie=$caiwusearchPO['weijiekuan'];	
   		}
   		$caiwuPO['order_no']=$orderTotalPO['order_no'];
   		if($shengyujin>$weijie){
   		$caiwuPO['yushoujine']=$weijie+$yuanyushou;
   		$caiwuPO['weijiekuan']=0;
   		$caiwuPO['cust_no']=$orderTotalPO['custer_no'];
   		$caiwuPO['cust_name']=$orderTotalPO['custer_name'];
   		$caiwuPO['isjiekuan']=1;
   		$shengyujin=$shengyujin-$weijie;
   		}else{
   		$caiwuPO['yushoujine']=$shengyujin+$yuanyushou;
   		$caiwuPO['weijiekuan']=$weijie-$shengyujin;
   		$caiwuPO['cust_no']=$orderTotalPO['custer_no'];
   		$caiwuPO['cust_name']=$orderTotalPO['custer_name'];
   		$caiwuPO['isjiekuan']=0;
   		$shengyujin=0;
   		}
   		//var_dump($caiwuPO);
   		
   		$result=$this->objDao->orderCheck($caiwuPO['order_no'],$caiwuPO['isjiekuan']);
   	    if(!$result){
				$exmsg->setError(__FUNCTION__, "update orderTotal jiekuan faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
		if($caiwusearchPO){
		$caiwuPO['yushoujine']=$yushou;
		//$caiwuPO['weijiekuan']-=$caiwusearchPO['yushoujine'];
		$result=$this->objDao->updateCaiwuByOrderNo($caiwuPO);	
		}else{
   		$result=$this->objDao->addCaiwu($caiwuPO);
		}
   	   if(!$result){
				$exmsg->setError(__FUNCTION__, "add caiwu faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
			//事务提交
	    $this->objDao->commit();
   	$this->getCaiwuList();
   }
   function caiwuJiekuanByOrderNo(){
   	$orderNo=$_REQUEST['order_No'];
   	$yushou=$_REQUEST['danjiekuanjin'];
   	$shengyujin=$yushou;
   	$this->objDao=new OrderDao();
   	//开始事务    
	$this->objDao->beginTransaction();
   	$exmsg=new EC();//设置错误信息类
   	$orderTotalPO=mysql_fetch_array($this->objDao->getOrderTotal("","","",$orderNo));//查询订单
   	$caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo);	//查询财务
   $caiwuPO=array();
   $totalJin=$orderTotalPO['chengjiaoer']-$orderTotalPO['tuikuanger'];//订单总金额
   		
   		if(!$caiwusearchPO){
   		$weijie=$totalJin;//未结金额
   		$yuanyushou=0;//原预收金额
   		}else{
   		$weijie=$caiwusearchPO['weijiekuan'];//未结金额
   		$yuanyushou=$caiwusearchPO['yushoujine'];//原预收金额
   		}
   		$yushou+=$yuanyushou;
   		$caiwuPO['order_no']=$orderTotalPO['order_no'];
   		$caiwuPO['yushoujine']=$yushou;
   		$caiwuPO['weijiekuan']=$totalJin-$yushou;
   		$caiwuPO['cust_no']=$orderTotalPO['custer_no'];
   		$caiwuPO['cust_name']=$orderTotalPO['custer_name'];
   		$caiwuPO['isjiekuan']=1;
   		
   		//var_dump($caiwuPO);
   		
   		$result=$this->objDao->orderCheck($caiwuPO['order_no'],$caiwuPO['isjiekuan']);
   	    if(!$result){
				$exmsg->setError(__FUNCTION__, "update orderTotal jiekuan faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
		if($caiwusearchPO){
		
		$result=$this->objDao->updateCaiwuByOrderNo($caiwuPO);	
		}else{
   		$result=$this->objDao->addCaiwu($caiwuPO);
		}
   	   if(!$result){
				$exmsg->setError(__FUNCTION__, "add caiwu faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
			//事务提交
	    $this->objDao->commit();
   	$this->getCaiwuList();
   }
   function caiwuJiekuanCancelByOrderNo(){
   	$orderNo=$_REQUEST['order_no'];
   	$caiwuType=$_REQUEST['caiwuType'];
   	$isJiekuan=0;
   	$this->objDao=new OrderDao();
   	if($caiwuType){
   	  $result=$this->objDao->updateCaiwuJiekuanTypeByOrderNo($isJiekuan,$orderNo,2);
   	}else{
   	$result=$this->objDao->updateCaiwuJiekuanTypeByOrderNo($isJiekuan,$orderNo);
   	$result=$this->objDao->orderCheck($orderNo,$isJiekuan);
   	}
   	if($caiwuType){
   		$this->toCaiwuReturn();
   	}else{
   	$this->getCaiwuList();
   	}
   }
   function caiwuYUshouModify(){
		$orderNo=$_REQUEST['order_NO'];
   	    $yushou=$_REQUEST['yushoujinUp'];
   	    $caiwuType=$_REQUEST['caiwuType'];
   	    $shengyujin=$yushou;
   	    $this->objDao=new OrderDao();
   	    if($caiwuType){
   	    $caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo,2);
   	    $orderTotalPO=mysql_fetch_array($this->objDao->searchReturnByOrderNo($orderNo));
   	    $caiwusearchPO['weijiekuan']=$orderTotalPO['sumJin']-$yushou;
   	    $result=$this->objDao->updateCaiwuYushouBuOrderNo($yushou,$caiwusearchPO['weijiekuan'],$orderNo,$caiwuType);
   	    $this->toCaiwuReturn();
   	    }else{
   	    $caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo);
   	    $orderTotalPO=mysql_fetch_array($this->objDao->getOrderTotal("","","",$orderNo));//查询订单
   	    $caiwusearchPO['weijiekuan']=$orderTotalPO['chengjiaoer']-$yushou;	
   	    $result=$this->objDao->updateCaiwuYushouBuOrderNo($yushou,$caiwusearchPO['weijiekuan'],$orderNo);
   	    $this->getCaiwuList();
   	    }
   	    
   	    
	}
	function toCaiwuDele(){
		
		$this->mode="toCaiwuDele";
		$dateFrom=$_REQUEST['dateFrom'];
	   	$dateTo=$_REQUEST['dateTo'];
	   	$seachList=array();
	   	$this->objDao=new OrderDao();
		if($dateFrom&&$dateTo){
   		$result=$this->objDao->getOrderTotalListByFanwei(0,$dateFrom,$dateTo);
   		$i=0;
   		while ($row=mysql_fetch_array($result)){
   			$seachList[$i]['order']=$row;
	   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($row['order_no']);
	   		if($caiwuPO){
	   		$seachList[$i]['caiwu']=$caiwuPO;	
	   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$row['chengjiaoer']-$row['tuikuanger'];
	   		$seachList[$i]['caiwu']=$caiwuPO;
	   		}
	   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
	   		$i++;
   		}
   			$this->objForm->setFormData("dateFrom",$dateFrom);
   		    $this->objForm->setFormData("dateTo",$dateTo);
   		
   	}
   	$this->objForm->setFormData("weijieheji",$weijieheji);
   	$this->objForm->setFormData("caiwuList",$seachList);
	}
	function allCaiwuDelete(){
	$orderListStr=$_REQUEST["orderNoList"];
   	$orderList=split(",",$orderListStr);
   	$this->objDao=new OrderDao();
   	$str="";
   	//开始事务    
		$this->objDao->beginTransaction();
		$exmsg=new EC();//设置错误信息类
   	foreach ($orderList as $orderNo){
   		$result=$this->objDao->deleteCaiwuByOrderNo($orderNo);
   	}
	$this->	toCaiwuDele();
		
	}
	function toCaiwuReturn(){
		$this->mode="toCaiwuReturn";
		$orderNo=$_REQUEST['orderNo'];
   	    $dateFrom=$_REQUEST['dateFrom'];
   	    $dateTo=$_REQUEST['dateTo'];
   	    $seachList=array();
   	    $this->objDao=new OrderDao();
	if($orderNo){
   	    	$i=0;
   		$orderTotalPO=mysql_fetch_array($this->objDao->searchReturnByOrderNo($orderNo));
   		$seachList[$i]['order']=$orderTotalPO;
   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($orderNo,2);
   		if($caiwuPO){
   		$seachList[$i]['caiwu']=$caiwuPO;	
   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$orderTotalPO['sumJin'];
	   	$seachList[$i]['caiwu']=$caiwuPO;	
   		}
   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
   			$this->objForm->setFormData("orderNo",$orderNo);
   	    }
   	    elseif($dateFrom&&$dateTo){
   	    $result=$this->objDao->searchReturnListByDate($dateFrom,$dateTo);
   		$i=0;
   		while ($row=mysql_fetch_array($result)){
   			$row['order_no']=$row['order_no'];
   			$seachList[$i]['order']=$row;
	   		$caiwuPO=$this->objDao->getCaiwuListByOrderNo($row['order_no'],2);
	   		//var_dump($caiwuPO);
	   		if($caiwuPO){
	   		$seachList[$i]['caiwu']=$caiwuPO;	
	   		}else{
	   			$caiwuPO['yushoujine']=0;
	   			$caiwuPO['isjiekuan']=0;
	   			$caiwuPO['weijiekuan']=$row['sumJin'];
	   		$seachList[$i]['caiwu']=$caiwuPO;
	   		}
	   		$weijieheji+=$seachList[$i]['caiwu']['weijiekuan'];
	   		$i++;
   		}
   			$this->objForm->setFormData("dateFrom",$dateFrom);
   		    $this->objForm->setFormData("dateTo",$dateTo);
   	    }
   	    $this->objForm->setFormData("weijieheji",$weijieheji);
     	$this->objForm->setFormData("caiwuList",$seachList);
	}
	function caiwuReJiekuan(){
    $orderNo=$_REQUEST['order_No'];
   	$yushou=$_REQUEST['danjiekuanjin'];
   	$shengyujin=$yushou;
   	$this->objDao=new OrderDao();
   	//开始事务    
	$this->objDao->beginTransaction();
   	$exmsg=new EC();//设置错误信息类
   	$orderReturnPO=mysql_fetch_array($this->objDao->searchReturnByOrderNo($orderNo));//查询订单
   	$caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo,2);	//查询财务
   $caiwuPO=array();
   $totalJin=$orderReturnPO['sumJin'];//订单总金额
   		
   		if(!$caiwusearchPO){
   		$weijie=$totalJin;//未结金额
   		$yuanyushou=0;//原预收金额
   		}else{
   		$weijie=$caiwusearchPO['weijiekuan'];//未结金额
   		$yuanyushou=$caiwusearchPO['yushoujine'];//原预收金额
   		}
   		$yushou+=$yuanyushou;
   		$caiwuPO['order_no']=$orderReturnPO['order_no'];
   		$caiwuPO['yushoujine']=$yushou;
   		$caiwuPO['weijiekuan']=$totalJin-$yushou;
   		$caiwuPO['cust_no']=$orderReturnPO['customer_id'];
   		$caiwuPO['cust_name']=$orderReturnPO['custo_name'];
   		$caiwuPO['isjiekuan']=1;
   		
   		//var_dump($caiwuPO);
		if($caiwusearchPO){
		
		$result=$this->objDao->updateCaiwuByOrderNo($caiwuPO,2);	
		}else{
   		$result=$this->objDao->addCaiwu($caiwuPO,2);
		}
   	   if(!$result){
				$exmsg->setError(__FUNCTION__, "add caiwu faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
			//事务提交
	    $this->objDao->commit();
   	$this->toCaiwuReturn();
		
	}
   function allJiekuan(){
   	$orderListStr=$_REQUEST["orderNoList"];
   	$orderList=split(",",$orderListStr);
   	$this->objDao=new OrderDao();
   	//开始事务    
	$this->objDao->beginTransaction();
   	$exmsg=new EC();//设置错误信息类
   	foreach ($orderList as $orderNo){
   	$orderTotalPO=mysql_fetch_array($this->objDao->getOrderTotal("","","",$orderNo));//查询订单
   	$caiwusearchPO=$this->objDao->getCaiwuListByOrderNo($orderNo);	//查询财务
   	$caiwuPO=array();
   		$weijie=$orderTotalPO['chengjiaoer']-$orderTotalPO['tuikuanger'];
   		
   		$caiwuPO['order_no']=$orderTotalPO['order_no'];
   		$caiwuPO['yushoujine']=$weijie;
   		$caiwuPO['weijiekuan']=0;
   		$caiwuPO['cust_no']=$orderTotalPO['custer_no'];
   		$caiwuPO['cust_name']=$orderTotalPO['custer_name'];
   		$caiwuPO['isjiekuan']=1;
   		$result=$this->objDao->orderCheck($caiwuPO['order_no'],$caiwuPO['isjiekuan']);
   	    if(!$result){
				$exmsg->setError(__FUNCTION__, "update orderTotal jiekuan faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
		if($caiwusearchPO){
		$result=$this->objDao->updateCaiwuByOrderNo($caiwuPO);	
		}else{
   		$result=$this->objDao->addCaiwu($caiwuPO);
		}
   	   if(!$result){
				$exmsg->setError(__FUNCTION__, "add caiwu faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","财务结款失败");
				throw new Exception ($exmsg->error());
			}
   		
   	}
   		//事务提交
	    $this->objDao->commit();
	    $this->getCaiwuList();
   }
   function toCaiwuExcel(){
   	$this->mode="toCaiwuExcel";
   }
   function toCaiwuBackup(){
   	$this->mode="toCaiwuBackup";
   	$op=new fileoperate();
	 $files=$op->list_filename("caiwu/",1);
	 $this->objForm->setFormData("files",$files);
   	
   }
function filesUp(){
        $exmsg=new EC();
		$fullfilepath = CAIWU_UPLOADPATH.$_FILES['file']['name'];
		$errorMsg="";
		//var_dump($_FILES);
		$fileArray=split("\.",$_FILES['file']['name']);
		//var_dump($fileArray);
		if(count($fileArray)!=2){
			$this->mode="toUpload";
			$errorMsg='文件名格式 不正确';
			$this->objForm->setFormData("error",$errorMsg);
			return;
		}else if($fileArray[1]!='xls'){
			$this->mode="toUpload";
			$errorMsg='文件类型不正确，必须是xls类型';
			$this->objForm->setFormData("error",$errorMsg);
			return;
		}
		if($_FILES['file']['error'] != 0){
			$error = $_FILES['file']['error'];
			switch($error)
			{
				case 1:
					$errorMsg= '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
					break;
				case 2:
					$errorMsg= '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
					break;
				case 3:
					$errorMsg= '3,文件只有部分被上传';
					break;
				case 4:
					$errorMsg= '4,文件没有被上传';
					break;
				case 6:
					$errorMsg= '找不到临文件夹';
					break;
				case 7:
					$errorMsg= '文件写入失败';
					break;
			}
		}
		if($errorMsg!=""){
			$this->mode="toUpload";
			$this->objForm->setFormData("error",$errorMsg);
			return;
		}
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $fullfilepath)){//上传文件
			//print_r($_FILES);print_r($fullfilepath);
			//$this->objDao->rollback();
			$this->objForm->setFormData("error","文件导入失败");
			throw new Exception(UPLOADPATH." is a disable dir");

			//die("UPLOAD FILE FAILED:".$_FILES['plusfile']['error']);
		}else{
			$this->mode="toUpload";
			$succMsg='文件导入成功';
			$this->objForm->setFormData("succ",$succMsg);
			 
		}
		$op=new fileoperate();
		$files=$op->list_filename("caiwu/",1);
		$this->objForm->setFormData("files",$files);
	}
 function excelToHtml(){
		$fname=$_GET['fname'];
		$err=Read_Excel_File("caiwu/".$fname,$return);
		if($err!=0){
			$this->objForm->setFormData("error",$err);
		}
		$this->objForm->setFormData("salarylist",$return);
		$this->mode="excelList";
	}
   function fileDel(){
		$this->mode="toUpload";
		$fname=$_GET['fname'];
		$op=new fileoperate();
		$mess=$op->del_file("caiwu/",$fname);
		$files=$op->list_filename("caiwu/",1);
		$this->objForm->setFormData("files",$files);
		$this->objForm->setFormData("error",$mess);
	}
	function caiwuImport(){
		$salaryList=$_SESSION['salarylist'];
   	 $this->mode="afterImportPage";
   	 
   $error=array();
   $message=array();
   $message['count']=count($salaryList['caiwuBackUp'])-1;
   //循环添加客户信息
      /**
     * CREATE TABLE `or_caiwu` (
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
  PRIMARY KEY (`id`)
     */
   for ($i=1;$i<count($salaryList['caiwuBackUp']);$i++){
    $caiwu=array();
   	$caiwu['order_no']=$salaryList['caiwuBackUp'][$i][0];
  	$caiwu['yushoujine']=$salaryList['caiwuBackUp'][$i][1];
  	$caiwu['weijiekuan']=$salaryList['caiwuBackUp'][$i][2];
  	$caiwu['cust_no']=$salaryList['caiwuBackUp'][$i][3];
  	$caiwu['cust_name']=$salaryList['caiwuBackUp'][$i][4];
  	$caiwu['shoukuan_date']=$salaryList['caiwuBackUp'][$i][6];
  	$caiwu['isjiekuan']=$salaryList['caiwuBackUp'][$i][7];
  	$this->objDao=new OrderDao();
  	$caiwuPO=$this->objDao->getCaiwuListByOrderNo($caiwu['order_no']);
  	if($caiwuPO){
  		$error[$i]["error"]="{$salaryList['caiwuBackUp'][$i][0]}:该财务订单号已经存在";
				$message['count']-=1;
				continue;
  	}
    $result=$this->objDao->addCaiwu($caiwu);
        if(!$result){
				$error[$i]["error"]="{$salaryList['caiwuBackUp'][$i][0]}:该订单财务字段内容不正确无法加入";
				$message['count']-=1;
			}
		}
/*		var_dump($error);
		exit;
*/   $message['name']="成功导入{$message["count"]}条财务信息";
		$this->objForm->setFormData("error",$error);
		$this->objForm->setFormData("message",$message);
		
	}
	
}


$objModel = new CaiwuAction($actionPath);
$objModel->dispatcher();



?>

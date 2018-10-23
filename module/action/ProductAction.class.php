<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/OrderDao.class.php");
require_once("module/dao/CustomerDao.class.php");
require_once("tools/JPagination.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
class ProductAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function ProductAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new ProductForm();
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
                $this->toAddProductPage();
                break;
            case "addProduct" :
                $this->addProduct();
                break;
            case "saveProductList" :
                $this->saveProductList();
                break;
            case "getProductList" :
                $this->getProductList();
                break;
            case "getProductListJson" :
                $this->getProductListJson();
                break;
            case "getProduct":
                $this->getProduct();
                break;
            case "getProductByIdJson":
                $this->getProductByIdJson();
                break;
            case "updateProduct":
            	$this->updateProduct();
            	break;
            case "delProduct":
                $this->delProduct();
                break;
            case "toProductExport":
                $this->toProductExport();
                break;
            case "productExport":
                $this->productExport();
                break;
            case "updateProdutList":
            	$this->updateProdutList();
                break;
            case "allProductDelete":
            	$this->allProductDelete();
                break;
            case "verifyProductCode":
            	$this->verifyProductCode();
                break;
            case "getProductNumList":
            	$this->getProductNumList();
                break;
            case "updateProductNum":
                $this->updateProductNum();
                break;
            case "productUpload" :
                $this->productUpload();
                break;
            case "filesUp" :
                $this->filesUp();
                break;
            case "excelToHtml" :
                $this->newExcelToHtml();
                break;
            case "productImport":
                $this->productImport();
                break;
            case "fileUpload":
                $this->fileUpload();
                break;
            case "picCut":
                $this->picCut();
                break;
            case "getProductExcelList":
                $this->getProductExcelList();
                break;
            case "getPandianlList":
                $this->getPandianlList();
                break;
            case "toPandianList":
                $this->toPandianList();
                break;
            case "toIntoStorage":
                $this->toIntoStorage();
                break;
            case "getProductByName":
                $this->getProductByName();
                break;
            case "saveDayStorage":
                $this->saveDayStorage();
                break;
            case "getIntoStorageList":
                $this->getIntoStorageList();
                break;
            case "delProductById":
                $this->delProductById();
                break;
            case "delDayProduceById":
                $this->delDayProduceById();
                break;
            case "productExportList":
                $this->productExportList();
                break;
            case "toProductStat":
                $this->toProductStat();
                break;
            case "toKucunMonthPage":
                $this->toKucunMonth();
                break;
            case "getKucunMonth":
                $this->getKucunMonth();
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
    function toKucunMonth () {
        $this->mode = "toKucunMonth";
    }
    function getKucunMonth () {
      $from_date = $_REQUEST['kucun_date'];
      $proName = $_REQUEST['proName'];
      $where['searchKey'] = $proName;
      //$res_date = $this->AssignTabMonth($from_date);
      $lastMonth = (date("Y-m",strtotime("-1 month",strtotime($from_date))));
      //print_r($res_date);
      $this->objDao = new ProductDao();
      $pro_res = $this->objDao->getProductListNoPage($where);
      $res_data = array();
        while($row = mysql_fetch_array($pro_res)) {
            $obj = array();
            $lastMonthRes = $this->objDao->getStorageByLastMonByProId($lastMonth,$row['id']);
            $obj['pro_name'] =  $row['pro_name'];
            $now_into_storage_Res = $this->objDao->getSumIntoStorageByMon($from_date,$row['id']);
            if (empty($lastMonthRes['new_storage'])) {

                $thisMonth = $this->objDao->getFirstStorageThisMonth($lastMonth,$row['id']);
                if (empty($thisMonth)) {

                    $obj['last_storage'] =  $row['pro_num'];
                } else {

                    $obj['last_storage'] =  $thisMonth['old_storage'];
                }
            } else {

                $obj['last_storage'] =  $lastMonthRes['new_storage'];
            }

            //退单

            $now_del_Res = $this->objDao->getSumDelStorageByMon($from_date,$row['id']);
            $now_return_Res = $this->objDao->getSumReturnStorageByMon($from_date,$row['id']);
            $obj['now_del_storage'] = $now_del_Res['sum_del'] ? $now_del_Res['sum_del'] : 0;
            if (empty($now_return_Res)) {

                $obj['now_return_storage'] = $now_return_Res['sum_return'];
            } else {
                $now_return_Res = $this->objDao->getSumReturnOrderProNumByMon($from_date,$row['id']);
                $obj['now_return_storage'] = $now_return_Res['sum_return'];
            }


            $obj['now_into_storage'] =  $now_into_storage_Res['sum_into'] ? $now_into_storage_Res['sum_into'] : 0;
            $now_out_storage_Res = $this->objDao->getSumOutStorageByMon($from_date,$row['id']);
            $obj['now_out_storage'] =  $now_out_storage_Res['sum_out'] ? $now_out_storage_Res['sum_out'] : 0;
            $obj['now_storage'] =  $row['pro_num'];
            $obj['cost_sum_all'] =  $row['cost_price'] * $row['pro_num'];

            $obj['cost_price'] =  $row['cost_price'];
            if ($obj['now_out_storage'] > 0) {
                $obj['cost_sum'] =  $row['cost_price'] * $obj['now_out_storage'];
                //sprintf("%.2f", bcsub($sumMoney[$i],$returnList[$ids[$i]],2)/$print['pro_num']);

                $obj['sum_order_jiner'] =  $this->objDao->getOrderPriceByProId($from_date,$row['id']);

                $obj['order_price'] =  sprintf("%.2f", $obj['sum_order_jiner']/$obj['now_out_storage']);
                $obj['earn_val'] = $obj['sum_order_jiner'] - $obj['cost_sum'];
            } else {
                //$obj['cost_price'] =  0.00;
                $obj['cost_sum'] =  0.00;
                //sprintf("%.2f", bcsub($sumMoney[$i],$returnList[$ids[$i]],2)/$print['pro_num']);

                $obj['sum_order_jiner'] =  0.00;

                $obj['order_price'] = 0.00;
                $obj['earn_val'] = 0.00;
            }

            $sub_val = $obj['last_storage'] + $obj['now_into_storage'] + $obj['now_del_storage'] + $obj['now_return_storage'] - $obj['now_out_storage'] - $obj['now_storage'];
            if ($sub_val != 0) {
                $add_time = $lastMonthRes['add_time'];

                $sum_sal =  $this->objDao->getSumEarlySalOrderProNumByMon($add_time,$row['id']);
                $obj['earlySal_num'] = $sum_sal['sum_sal'];
            } else {
                $obj['earlySal_num'] = 0;
            }


            $res_data[] = $obj;
        }

        echo json_encode(array('data'=>$res_data));
        exit;
    }
    function delProductById () {
        $ids = $_REQUEST['ids'];
        $bDel = $_REQUEST['bDel'];
        $this->objDao = new ProductDao();
        foreach ($ids as $id) {
            if ($bDel) {

                $result = $this->objDao->delProductById($id);
            } else {

                $result = $this->objDao->updateProductByIdToDel($id);
            }
        }
        if ($result) {
            $reData['code'] = 100000;
            $reData['mess'] = '删除成功';
        } else {
            $reData['code'] = 100001;
            $reData['mess'] = '删除失败';
        }

        echo json_encode($reData);
        exit;
    }
    function getIntoStorageList() {
        $where['produce_date'] = $_REQUEST['produce_date'];
        $where['from_date'] = $_REQUEST['from_date'];
        $where['to_date'] = $_REQUEST['to_date'];
        $where['bRang'] = $_REQUEST['bRang'];
        $this->objDao = new ProductDao();
        $result = $this->objDao->getIntoStorageList($where) ;
        $pruduceList = array();
        $pruduceList['data'] = array();
        while($row = mysql_fetch_array($result)) {
            $produce['row_id'] = $row['id'];
            $produce['pro_code'] = $row['pro_code'];
            $produce['pro_num'] = $row['pro_num'];
            $produce['pro_id'] = $row['pro_id'];//
            $product = $this->objDao->getProductById($row['pro_id']);
            $produce['price'] = $product['market_price'];
            $produce['sum_price'] = sprintf("%.2f",$product['market_price'] * $row['pro_num']);
            $produce['memo'] = $row['memo'];
            $produce['into_date'] = $row['into_date'];
            $produce['old_storage'] = $row['old_storage'];
            $produce['new_storage'] = $row['new_storage'];
            $pruduceList['data'][] = $produce;
        }
        echo json_encode($pruduceList);
        exit;
    }
    function productExportList() {
        require 'tools/php-excel.class.php';
        $where['fromDate'] = $_REQUEST['fromDate'];
        $where['toDate'] = $_REQUEST['toDate'];
        $where['pro_type'] = $_REQUEST['pro_type'];
        $this->objDao = new OrderDao();
        $result = $this->objDao->getOrderDetailListByWhereArray($where);
        //GLOBAL.admin_type == 1 || GLOBAL.admin_type == 5 || GLOBAL.admin_type == 7
        $admin = $_SESSION['admin'];
        $stuffSumList = array();
        if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5|| $admin['admin_type'] == 7) {
            $stuffSumList[]  = array('序列号','订单号','订单日期','产品名称','类型','销售数量','成本价','成本合计','销售单价','销售合计','收益额','备注');
        } else {
            $stuffSumList[]  = array('序列号','订单号','订单日期','产品名称','类型','销售数量','销售单价','销售合计','收益额','备注');
        }


        //$list[] = $head_json;
        $i = 1;
        global $productType;
        $this->objDao = new ProductDao();
        while ($row = mysql_fetch_array($result)) {
            $product = $this->objDao->getProductById($row['pro_id']);
            $data =array();
            $data[] = $i;$i++;
            $data[] = $row['order_no'];
            $data[] = $row['ding_date'];
            $data[] = $row['pro_code'];
            $data[] = $productType[$row['pro_type']];
            $data[] = (int)$row['pro_num'];
            //$obj['cost_price'] =  $row['cost_price'];
            if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5|| $admin['admin_type'] == 7) {
                $data[] = (float)$product['cost_price'];
                $data[] =  (float)$product['cost_price'] * $row['pro_num'];
            }

            //sprintf("%.2f", bcsub($sumMoney[$i],$returnList[$ids[$i]],2)/$print['pro_num']);

            $data[] =  (float)$row['real_price'];
            $data[] =  (float)$row['real_order_jiner'];

            //$obj['order_price'] =  sprintf("%.2f", $obj['sum_order_jiner']/$obj['now_out_storage']);
            $data[] = (float)($row['real_order_jiner'] - $data[7]);
            $data[] = $row['mark'];
            $stuffSumList[] = $data;
        }
        $time = 'product';
        //$produceList = array_merge($head_json,$list);print_r($produceList);exit;
        //print_r($stuffSumList);exit;
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($time);
        exit;
    }
    function delDayProduceById () {

        $productDao = new ProductDao();
        $data = $_REQUEST['ids'];
        foreach ($data as $id) {
            $into = $productDao->getIntoStorageById($id);

            $product = $productDao->getProductById($into['pro_id']);
            $result = $productDao->delIntoStorageById($id);
            $sum = $product['pro_num'] -  $into['pro_num'];
            $po = array();
            $po['pro_num'] =  $sum;
            $po['pro_id'] =  $product['id'];
            $productDao->updateProductNumByProId($po);

            if ($result) {

                $save_id = $productDao->g_db_last_insert_id();
                $row['row_id'] = $save_id;

                $success_List[] = $id;
            } else {
                $errorList[] = $id;
            }
        }
        if (count($errorList) == 0) {
            $reData['code'] = 100000;
            $reData['mess'] = '删除成功';
        } else {
            $reData['code'] = 100001;
            $reData['mess'] = '删除失败';
            $reData['errorList'] = '删除失败';
        }
        $reData['errorList'] = $errorList;
        $reData['success_List'] = $success_List;
        echo json_encode($reData);
        exit;
    }
    function saveDayStorage () {
        $data = $_REQUEST['data'];
        $produce_date = $_REQUEST['produce_date'];
        $productDao = new ProductDao();
        $u_date = strtotime($produce_date);
        $errorList = array();
        $success_List = array();
        foreach ($data as $row) {
            $product = $productDao->getProductByCName($row['pro_code']);
            if (!$product) {
                $errorList[] = $row;
                continue;
            }
            $produce = array();
            $produce['pro_id'] = $product['id'];
            $produce['pro_code'] = $row['pro_code'];
            $produce['pro_num'] = $row['pro_num'];
            $produce['into_date'] = $produce_date;
            $produce['memo'] = $row['memo'];
            $sum = $product['pro_num'] +  $row['pro_num'];
            $produce['old_storage'] = $product['pro_num'];
            $produce['new_storage'] = $sum;
            if (!empty($row['row_id']) && $row['row_id'] != "null") {
                $produce['id'] = $row['row_id'];
                $produce['old_storage'] = $row['old_storage'];
                $produce['new_storage'] = $row['new_storage'];
                $sum = $row['new_storage'];
                $result = $productDao->updateIntoStorage($produce);
                if (!$result) {
                    $errorList[] = $row;
                } else {
                    $success_List[] = $row;
                }
            } else {
                $result = $productDao->addIntoStorage($produce);
                if ($result) {

                    $save_id = $productDao->g_db_last_insert_id();
                    $row['row_id'] = $save_id;

                    $success_List[] = $row;
                } else {
                    $errorList[] = $row;
                }
            }
            /*//销售总数
            $salSum = (int)$productDao->getSalSumNum($product['id']);
            //入库总数
            $intoSum = (int)$productDao->getIntoSumNum($product['id']);
            //退货总数
            $returnSum = (int)$productDao->getReturnSumNum($product['id']);
            $sum = $intoSum - $salSum + $returnSum;
            echo $intoSum .'-'. $salSum .'+'. $returnSum."\n";*/

            $po = array();
            $po['pro_num'] =  $sum;
            $po['pro_id'] =  $product['id'];
            $productDao->updateProductNumByProId($po);
        }
        if (count($errorList) == 0) {
            $reData['code'] = 100000;
            $reData['mess'] = '添加成功';
        } else {
            $reData['code'] = 100001;
            $reData['mess'] = '添加失败';
            $reData['errorList'] = '添加失败';
        }
        $reData['errorList'] = $errorList;
        $reData['success_List'] = $success_List;
        echo json_encode($reData);
        exit;
    }
    function getProductByName () {
        $query = $_REQUEST['query'];
        $this->objDao=new ProductDao();
        $result = $this->objDao->getProductByCName($query);
        if (!empty($result)) {

            echo json_encode(array("is"=>1,"data"=>$result));
            exit;
        }
        echo json_encode(array("is"=>0));
        exit;
    }
/**
   * 添加产品信息
   */
  function addProduct(){
  	$this->mode="toAdd";
  	$product=array();
  	$product['pro_code']=$_REQUEST["product_code"];
  	$product['pro_name']=$_REQUEST["product_name"];
  	$product['pro_supplier']=$_REQUEST["product_supplier"];
  	$product['pro_type']=$_REQUEST["product_type"];
  	$product['pro_flag']=$_REQUEST["flag"];
  	$product['pro_price']=$_REQUEST["product_price"];
  	$product['market_price']=$_REQUEST["market_price"];
  	$product['channel_price']=$_REQUEST["channel_price"];
  	$product['com_channel_price']=$_REQUEST["com_channel_price"];
  	$product['after_dis_price']=$_REQUEST["after_dis_price"];
  	$product['cost_price']=$_REQUEST["cost_price"];
  	$product['vip_price']=$_REQUEST["vip_price"];
  	$product['pro_num']=$_REQUEST["product_num"];
  	$product['pro_unit']=$_REQUEST["product_unit"];
  	$product['pro_num']=$_REQUEST["product_num"];
  	$exmsg=new EC();//设置错误信息类
      //开始事务

  	$this->objDao=new ProductDao();
      $this->objDao->beginTransaction();
  	$productPo=$this->objDao->getProductByCode($product['pro_code']);
    if(!empty($productPo)){
  	   $mess="({$product['pro_code']})此产品型号已经添加过了";
  	 $this->objForm->setFormData("error",$mess);
  	   return;
  	}
  	$result=$this->objDao->addProduct($product);
        if(!$result){
				$exmsg->setError(__FUNCTION__, "add product   faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","添加产品信息失败");
				throw new Exception ($exmsg->error());
			}else{
				$saveLastId=$this->objDao->g_db_last_insert_id();
				$suess="添加成功！";
            $productNum = array();
            $productNum['pro_num']=$product['pro_num'];
            $productNum['pro_id']=$saveLastId;
            $productNum['pro_code']=$product['pro_code'];
            $productNum['pro_unit']=$product["pro_unit"];
            $result=$this->objDao->addProductNum($productNum);
            if (!$result) {
                $this->objDao->rollback();
                $this->objForm->setFormData("warn","添加产品数量失败");
            }
				$this->objForm->setFormData("succ",$suess);
			}
      $this->objDao->commit();
	    $this->getProductList();
  }
  /**
   * 修改客户信息
   */
  function updateProduct(){

      $this->objDao = new ProductDao();
      $updateData = $_REQUEST["data"];
      $success_List =array();
      global $productTypeValue;
      foreach ($updateData as $row) {
          $row['pro_type'] = $productTypeValue[$row['typeName']];
          if (empty($row['pro_type'])) {
              $row['mess'] = "类别：".$row['typeName']."不存在";
              $errorList[] = $row;
              continue;
          }
          $row['e_name'] =  mysql_real_escape_string($row['e_name'],$this->objDao->get_db_connect());
          $row['c_name'] = mysql_real_escape_string($row['c_name'],$this->objDao->get_db_connect());
          $row['pro_name'] = $row['e_name'].$row['c_name'];
          if ($row['isModifyProNum']) {
              $productDao = new ProductDao();
              $produce = array();//
              $produce['pro_id'] = $row['id'];
              $produce['pro_code'] = $row['pro_name'];
              $produce['pro_num'] = $row['pro_num'];
              $produce['into_date'] = date('Y-m-d h:i:s',time());
              $produce['memo'] = '修改库存数量';
              $produce['old_storage'] = $row['old_pro_num'];
              $produce['new_storage'] = $row['pro_num'];
              $produce['op_type'] = 5;
              $result = $productDao->addIntoStorage($produce);
          }
          $result=$this->objDao->updateProductById($row);
          if (!$result) {
              $row['mess'] = $row['pro_name']."修改失败：";
              $errorList[] = $row;
          } else {
              $success_List[] = $row;;
          }
      }

      if (count($errorList) == 0) {
          $reData['code'] = 100000;
          $reData['mess'] = '添加成功';
      } else {
          $reData['code'] = 100001;
          $reData['mess'] = '添加失败';
          $reData['errorList'] = '添加失败';
      }
      $reData['errorList'] = $errorList;
      $reData['success_List'] = $success_List;
      echo json_encode($reData);
      exit;

  }
    /**
     * 得到管理员列表
     */
  function toAddProductPage(){
  	    $this->mode="toAdd";
  }
  function allProductDelete () {
      $productList = $_REQUEST['orderNoList'];
      $productArr = explode(",",$productList);
      $this->objDao=new ProductDao();
      foreach ($productArr  as $pid) {
          if ($pid)  {
              $result=$this->objDao->delProductById($pid);
          }

      }
      $this->getProductList();
  }
    function verifyProductCode () {
        $code=$_REQUEST['code'];
        if (empty($code)){
            echo 'false';
            exit;
        }
        $this->objDao = new ProductDao();
        $result = $this->objDao->getProductByCode($code);
        if ($result) {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }
  /**
   * 删除管理员
   */
  function  delProduct(){
  	$today=date("Y-m-d");//当天日期
		//查询管理员详细信息
		global $loginusername;
		$this->objDao=new ProductDao();
		//开始事务    
		$this->objDao->beginTransaction();
		//取得管理员信息
		//$pid=$_SESSION['pid'];
		$exmsg=new EC();//设置错误信息类
		$pid=$_REQUEST["pid"];
  	    $result=$this->objDao->delProductById($pid);
        if(!$result){
				$exmsg->setError(__FUNCTION__, "delete product   faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","删除产品信息操作失败！");
				throw new Exception ($exmsg->error());
			}
		//事务提交
	    $this->objDao->commit();
	    $this->getProductList();
  }
  function saveProductList () {
      $data = $_REQUEST['data'];
      $pro_type = $_REQUEST['pro_type'];
      $this->objDao = new ProductDao();
      $error_list = array();
      $success_list = array();
      $str = array();
      foreach ($data as $row) {

          //$str[] = $row['e_name'].','.$row['c_name'].','.$row['pro_type'].','.$row['pro_address'];continue;
          //print_r($row);
          if ($row['e_name'] == 'null' && $row['c_name'] == 'null') {
              continue;
          }
          $row['pro_name'] = $row['e_name'].$row['c_name'];
          if ($row['pro_name'] == 'null' || empty($row['pro_name'])) {
              $row['message'] = "名称不能为空";
              $error_list = $row;
              continue;
          }
          //echo  $row['pro_name'] ;
        $productPO = $this->objDao->getProductByCode(mysql_real_escape_string($row['pro_name'],$this->objDao->get_db_connect()));
          if($productPO) {
              $row['message'] = "已经添加过了";
              $error_list[] = $row;
          } else {
              $product = array();
              global $productTypeValue;
              if (empty($productTypeValue[$row['pro_type']])) {
                  $row['message'] = $row['pro_type'].":类别不存在";
                  $error_list[] = $row;
                  continue;
              }
              $product['pro_type'] = $productTypeValue[$row['pro_type']];
              $product['pro_area'] = $row['pro_area'];
              $product['c_name'] = mysql_real_escape_string($row['c_name']);
              $product['e_name'] = mysql_real_escape_string($row['e_name']);
              $product['pro_name'] = mysql_real_escape_string($row['pro_name']);
              $product['pro_address'] = ($row['pro_address'] == 'null'||empty($row['pro_address'])) ? '' :trim($row['pro_address']);
              $product['pro_level'] = ($row['pro_level'] == 'null'||empty($row['pro_level']))? '' :trim($row['pro_level']);
              $product['pro_price'] = ($row['pro_price'] == 'null'||empty($row['pro_price'])) ? 0.00 :trim($row['pro_price']);
              $product['market_price'] = ($row['market_price'] == 'null'||empty($row['market_price'])) ? 0.00 :trim($row['market_price']);
              $product['channel_price'] = ($row['channel_price'] == 'null'||empty($row['channel_price'])) ? 0.00 :trim($row['channel_price']);
              $product['com_channel_price'] = ($row['com_channel_price'] == 'null'||empty($row['com_channel_price'])) ? 0.00 :trim($row['com_channel_price']);
              $product['after_dis_price'] = ($row['after_dis_price'] == 'null'||empty($row['after_dis_price'])) ? 0.00 :trim($row['after_dis_price']);
              $product['cost_price'] = ($row['cost_price'] == 'null'||empty($row['cost_price'])) ? 0.00 :trim($row['cost_price']);
              $product['vip_price'] = ($row['vip_price'] == 'null'||empty($row['vip_price'])) ? 0.00 :trim($row['vip_price']);
              $product['pro_num'] = ($row['pro_num'] == 'null'||empty($row['pro_num'])) ? 0 :trim($row['pro_num']);
              //if

              //$this->objDao->beginTransaction();
              $res = $this->objDao->addProduct($product);
              if (!$res) {
                  $row['message'] = "添加失败";
                  $error_list[] = $row;
              } else {
                  $success_list[] = $row;
              }
          }

      }
      //echo implode('|',$str);exit;
      $response['error_list'] = $error_list;
      $response['success_list'] = $success_list;
      echo json_encode($response);
      exit;
  }
    function getProductExcelList () {
        $searchKey=trim($_REQUEST['keyWord']);
        $type=$_REQUEST['pro_type'];
        $this->objDao=new ProductDao();

        $where = array();
        $where['type'] = $type;
        $where['searchKey'] = $searchKey;
        $proList= $this->objDao->getProductExcelList($where);
        $dataList = array();
        global $productType;
        $i = 1;
        while ($row = mysql_fetch_array($proList)) {
            $row['typeName'] = $productType[$row['pro_type']];
            $row['row_id'] = $row['id'];
            $row['sale_num'] = $this->objDao->getSalNumByProId($row['id']);
            $row['num'] = $i;$i++;
            $dataList[] = $row;
        }
        $data['data'] = $dataList;
        echo json_encode($data);
        exit;
    }
    function toPandianList () {

        $this->mode="toPandianList";
    }
    function toIntoStorage () {
        $this->mode="toIntoStorage";
        $this->objDao = new ProductDao();
    }
    function getPandianlList () {
        $date = $_REQUEST['produce_date'];
        $pro_type = $_REQUEST['pro_type'];
        $keyword = $_REQUEST['keyword'];
        if ($date){
            $star_date = $date."-01";
            $end_date = $date."-31";
            $proTime = $this->AssignTabMonth($star_date,0);
        } else {
            $msg['code'] = 1001;
            $msg['massage'] = "添加时间无效";
            echo json_encode($msg);
            exit;
        }
        $headJson = array();
        $headJson[0] ='商品型号';
        $headJson[1] ='上月末库存数量';
        $headJson[2] ='本月入库数量';
        $headJson[3] ='本月销售数量';
        $headJson[4] ='当前库存数量';
        /***计算天数***/
        $lastDay = $proTime['days'];
        $month = explode("-",$proTime['month']);
        $month = $month[1];

        for ($i = 1;$i <= $lastDay;$i ++) {
            $headJson[] =$month.'-'.$i;
        }
        $this->objDao = new OrderDao();
        $where['start_date'] = $star_date;
        $where['end_date'] = $end_date;
        $where['pro_type'] = $pro_type;
        $where['type'] = $pro_type;
        $where['searchKey'] = $keyword;
        $orderResult = $this->objDao->getOrderInfoListByRang($where);
        $orderList = array();
        while ($row = mysql_fetch_array($orderResult)) {
            $day = date("j",strtotime($row['ding_date']));
            if(!empty($orderList[$row['pro_id']][$day])) {
                $new = $orderList[$row['pro_id']][$day];
                $new['pro_num'] += $row['pro_num'];
                $orderList[$row['pro_id']][$day] = $new;
            } else {

                $orderList[$row['pro_id']][$day] = $row;
            }
        }
        //print_r($orderList);exit;
        $this->objDao = new ProductDao();
        $proList= $this->objDao->getProductExcelList($where);
        $list = array();
        while ($row = mysql_fetch_array($proList)) {
            $info = array();
            $info[] = $row['pro_name'];
            //$info[] = $row['pro_num'];
            $info[] = $this->objDao->getLastStorageByProId($row['id'],$star_date);
            $info[] =$this->objDao->getCurrentStorageByProId($row['id'],$star_date,$end_date);
            $info[] =$this->objDao->getCurrentSaleStorageByProId($row['id'],$star_date,$end_date);
            $info[] = $row['pro_num'];
            for($z=1; $z<=$proTime['days']; $z++) {
                if (!empty($orderList[$row['id']][$z])) {
                    $info[] = $orderList[$row['id']][$z]['pro_num'];
                } else {
                    $info[] = 0;
                }
            }
            $list[] = $info;
        }
        $data = array();
        $data['head'] = $headJson;
        $data['data'] = $list;
        echo  json_encode($data);
        exit;
    }
  function getProductList(){
  	$this->mode="toList";
  	$searchKey=trim($_REQUEST['searchKey']);
  	$proNo=$_REQUEST['proNo'];
  	$type=$_REQUEST['pro_type'];
  	$proName=$_REQUEST['proName'];
  	$this->objDao=new ProductDao();
 	$whereCount="1=1";
  /*if($proNo){
   	    	$whereCount.=" and pro_code like '%".$proNo."%'";
   	    }*/
   	    if($type){
   	    	$whereCount.=" and pro_type = $type";
   	    }
   	    if($proName){
   	    	$whereCount.=" and pro_name like '%".$proName."%' ";
   	    }
      if ($searchKey) {
          //$whereCount.=" and CONCAT(pro_code,pro_name,pro_type) LIKE '%{$searchKey}%' ";
          $whereCount.=" and pro_name like '%".$searchKey."%' ";
      }
	$sum =$this->objDao->g_db_count("or_product","*",$whereCount);
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
      $pages = new JPagination($total);
      $pages->setPageSize($pageSize);
      $pages->setCurrent($page);
      if (!empty($searchKey)) {
          $data['key'] = 'searchKey';
          $data['val'] = $searchKey;
          $pages->setParam(array('1'=>$data));
      }
      $pages->makePages();
      $where = array();
      $where['proNo'] = $proNo;
      $where['type'] = $type;
      $where['proName'] = $proName;
      $where['searchKey'] = $searchKey;
      $proList= $this->objDao->getProductList($where,$startIndex,$pageSize);
      $this->objForm->setFormData("proList",$proList);
      $this->objForm->setFormData("total",$total);
      $this->objForm->setFormData("page",$pages);
      $this->objForm->setFormData("proNo",$proNo);
      $this->objForm->setFormData("type",$type);
      $this->objForm->setFormData("proName",$proName);
      $this->objForm->setFormData("searchKey",$searchKey);
  }
    function getProductListJson(){
        $type=$_REQUEST['type'];
        $keyword=$_REQUEST['keyword'];
        $this->objDao=new ProductDao();
        if ($type == 'all'){
            $result = $this->objDao->getProductListAll();
        } else {
            $result= $this->objDao->getProductListLike($keyword);
        }
        $proList = array();
        global $productType;
        while($row = mysql_fetch_array($result)) {
            $po = array();
            $proType = $productType[$row['pro_type']];
            $proNum = $row['pro_num'];
            $po['name'] = $row['pro_code']." ".$row['pro_name']."【类型：{$proType}】"."【库存：{$proNum}】";
            $po['id'] = $row['id'];
            $proList[] = $po;
        }
        echo json_encode($proList);
        exit;
  }
/**
	 * 得到操作日志列表
	 */
	function getProduct($proId=null){
		$this->mode="getPro";
		$this->objDao=new ProductDao();
		if(empty($proId)){
		$proId=$_REQUEST['pid'];
		}
		$proPO =$this->objDao->getProductById($proId);
		$proNumPO =$this->objDao->getProductNumByProId($proId);
        $proPO['pro_num'] = $proNumPO['pro_num'];
	    $this->objForm->setFormData("productPo",$proPO);
	}
    function getProductByIdJson () {
        $this->objDao=new ProductDao();
        $proId=$_REQUEST['id'];
        $data = array();
        if(empty($proId)){
            $data['code'] = 100001;
            $data['message'] = '参数错误，重新尝试';
        }
        $proPO =$this->objDao->getProductById($proId);
        if (!$proPO) {
            $data['code'] = 100002;
            $data['message'] = '查询失败，重新尝试';
        } else {
            $data['data'] = $proPO;
            $data['code'] = 100000;
        }
        echo json_encode($data);
        exit;
    }
    function productExport(){
        require 'tools/php-excel.class.php';

        $pro_type = $_REQUEST['pro_type'];
        $this->objDao = new ProductDao();
        $result = $this->objDao->getProductListAll($pro_type);

        $stuffSumList = array();
        $admin = $_SESSION['admin'];
        if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5|| $admin['admin_type'] == 7) {
            $stuffSumList[] = array('序列号', ' Products Name', '产品名称', '产区', '类型', '库存', '成本价', '会员价', '零售价', '渠道价', '备注');
        } else {
            $stuffSumList[] = array('序列号', ' Products Name', '产品名称', '产区', '类型', '库存',  '会员价', '零售价', '渠道价', '备注');
        }
        //$list[] = $head_json;
        $i = 1;
        global $productType;
        while ($row = mysql_fetch_array($result)) {
            $data =array();
            $data[] = $i;$i++;
            $data[] = $row['c_name'];
            $data[] = $row['e_name'];
            $data[] = $row['pro_area'];
            $data[] = $productType[$row['pro_type']];
            $data[] = (int)$row['pro_num'];
            if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5|| $admin['admin_type'] == 7) {
                $data[] = (float)$row['cost_price'];
            }
            $data[] = (float)$row['vip_price'];
            $data[] = (float)$row['market_price'];
            $data[] = (float)$row['channel_price'];//cost_price
            $data[] = $row['memo'];
            $stuffSumList[] = $data;
        }
        $time = 'product';
        //$produceList = array_merge($head_json,$list);print_r($produceList);exit;
        //print_r($stuffSumList);exit;
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($stuffSumList);
        $xls->generateXML($time);
        exit;
    }
    function updateProdutList(){
    	$this->mode="duibiError";
		$proCode=($_POST['pro_code']-1);
		$proSpec=($_POST['pro_spec']-1);
		$proPrice=($_POST['pro_price']-1);
		//print_r($delArray);
		session_start();
		$salaryList=$_SESSION['salarylist'];
		$jisuan_var=array();
		$error=array();
		$this->objDao=new ProductDao();
		//根据身份证号查询出员工身份类别
		for ($i=1;$i<count($salaryList['moban']);$i++)
		{   
			if($i%200==0){
				sleep(1);
			}
			$sql=" update or_product  set ";
			$updateSal="";
			$salaryList['moban'][$i][$proCode]=trim($salaryList['moban'][$i][$proCode]);	
			if($salaryList['moban'][$i][$proCode]){
				if($proSpec!=''&&$proSpec!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="pro_spec='{$salaryList['moban'][$i][$proSpec]}'";
				}
				if($proPrice!=''&&$proPrice!=-1){
				if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="pro_price={$salaryList['moban'][$i][$proPrice]}";
				}
				$where=" where pro_code='{$salaryList['moban'][$i][$proCode]}'";
				$sql.=$updateSal.$where;
				//echo $sql."<br/>";
				$this->objDao->g_db_query($sql);
				$upRows= mysql_affected_rows();
				if($upRows==0){
				$error[$i]["error_shenfen"]=" <div  style='word-wrap:break-word; background-color:Tan;'><font color='red'>第".($i-1)."行</font>{$salaryList['moban'][$i][$proCode]}:未更新成功，（价格或型号没变，或是未找到该型号！）</font></div>";
				//echo $sql."<br/>";
				}
			}else{
				$error[$i]["error_shenfen"]=" <div  style='word-wrap:break-word; background-color:red;'><font>第".($i-1)."行</font>:商品型号无法识别！</font></div>";
				continue;
			}
		}
	    if(count($error)==0){
			$error[0]["succ"]="<font color=green>修改没有错误</font>";
		}
		$this->objForm->setFormData("errorlist",$error);
		$this->objForm->setFormData("excelList",$salaryList['moban']);
    }
    function getProductNumList () {
        $this->mode = 'toProNumList';
        $this->objDao = new ProductDao();
        $proNumList = array();
        $where =array();
        $pro_type = $_REQUEST['pro_type'];
        $pro_name = $_REQUEST['proName'];
        $whereCnt = "1=1 and pn.pro_id = op.id ";
        if (!empty($pro_name)) {
            $whereCnt .= " and op.pro_name = '{$pro_name}'";
            $where['pro_name'] =  $pro_name;
        }
        if (!empty($pro_type))  {
            $whereCnt .= " and op.pro_type = '{$pro_type}'";
            $where['pro_type'] =  $pro_type;
        }
        $sum =$this->objDao->g_db_count("or_product_num pn, or_product op","*",$whereCnt);
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
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        if (!empty($searchKey)) {
            $data['key'] = 'searchKey';
            $data['val'] = $searchKey;
            $pages->setParam(array('1'=>$data));
        }
        $pages->makePages();
        $result = $this->objDao->getProductNumList($where,$startIndex,$pageSize);
        while ($row = mysql_fetch_array($result)) {
            $proNumList[] =$row;
        }
        $this->objForm->setFormData("proNumList",$proNumList);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("total",$total);
    }
    function updateProductNum () {
        $pro['pro_id']=$_REQUEST['id'];
        $pro['pro_num']=$_REQUEST['proNum'];
        $this->objDao=new ProductDao();
        $result=$this->objDao->updateProductNumByProId($pro);
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
    }/*
    function productUpload () {
        $this->mode = "toUpload";
        $op = new fileoperate();
        $files = $op->list_filename("upload/", 1);
        $this->objForm->setFormData("files", $files);
    }*/
    function productUpload () {
        $this->mode = "toUpload";
    }
    function filesUp()
    {
        $exmsg = new EC();
        $fullfilepath = UPLOADPATH . $_FILES['file']['name'];
        $errorMsg = "";
        //var_dump($_FILES);
        $fileArray = split("\.", $_FILES['file']['name']);

        if (count($fileArray) != 2) {
            $this->mode = "toUpload";
            $errorMsg = '文件名格式 不正确';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        } else if ($fileArray[1] != 'xls') {
            $this->mode = "toUpload";
            $errorMsg = '文件类型不正确，必须是xls类型';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        if ($_FILES['file']['error'] != 0) {
            $error = $_FILES['file']['error'];
            switch ($error) {
                case 1:
                    $errorMsg = '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
                    break;
                case 2:
                    $errorMsg = '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
                    break;
                case 3:
                    $errorMsg = '3,文件只有部分被上传';
                    break;
                case 4:
                    $errorMsg = '4,文件没有被上传';
                    break;
                case 6:
                    $errorMsg = '找不到临文件夹';
                    break;
                case 7:
                    $errorMsg = '文件写入失败';
                    break;
            }
        }
        if ($errorMsg != "") {
            $this->mode = "toUpload";
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $fullfilepath)) { //上传文件
            $this->objForm->setFormData("error", "文件导入失败");
            throw new Exception(UPLOADPATH . " is a disable dir");

        } else {
            $this->mode = "toUpload";
            $succMsg = '文件导入成功';
            $this->objForm->setFormData("succ", $succMsg);

        }
        $op = new fileoperate();
        $files = $op->list_filename("upload/", 1);
        $this->objForm->setFormData("files", $files);
    }
    function productImport()
    {
        session_start();
        $salaryList = $_SESSION['excellsit'];
        $this->mode = "afterImportPage";

        $error = array();
        $message = array();
        $message['count'] = count($salaryList['moban']);
        //var_dump($salaryList['moban']);
        //供应商 商品编号 货品简称 货品类别 单位 数量 市场定价 入库时间
        //循环添加客户信息
        for ($i = 1; $i < count($salaryList['moban']); $i++) {
            $ProductPO = array();
            $ProductPO['pro_supplier'] = trim($salaryList['moban'][$i][0]);
            $ProductPO['pro_code'] = trim($salaryList['moban'][$i][1]);
            $ProductPO['pro_name'] = trim($salaryList['moban'][$i][2]);
            $ProductPO['pro_type'] = trim($salaryList['moban'][$i][3]);
            $ProductPO['pro_unit'] = trim($salaryList['moban'][$i][4]);
            $ProductPO['pro_num'] = floatval($salaryList['moban'][$i][5]);

            $ProductPO['pro_price'] = floatval($salaryList['moban'][$i][6]);
            $ProductPO['pro_spec'] = '';
            $ProductPO['pro_flag'] = 1;

            $exmsg = new EC(); //设置错误信息类
            $this->objDao = new ProductDao();
            $productPo = $this->objDao->getProductByCode($ProductPO['pro_code']);
            if ($productPo) {
                //$mess="({$product['pro_code']})此产品型号已经添加过了";
                $error[$i]["error"] = "({$productPo['pro_code']})此产品型号已经添加过了";
                //$this->objForm->setFormData("error",$mess);
                continue;
            }
            $result = $this->objDao->addProduct($ProductPO);
            if (!$result) {
                $error[$i]["error"] = "{$salaryList['moban'][$i][1]}:该产品字段内容不正确无法加入";
                $message['count'] -= 1;
            } else {
                $saveLastId=$this->objDao->g_db_last_insert_id();
                $suess="添加成功！";
                $productNum = array();
                $productNum['pro_num']=$ProductPO['pro_num'];
                $productNum['pro_id']=$saveLastId;
                $productNum['pro_code']=$ProductPO['pro_code'];
                $productNum['pro_unit']=$ProductPO["pro_unit"];
                $result=$this->objDao->addProductNum($productNum);
                if (!$result) {
                    $error[$i]["error"] = "{$salaryList['moban'][$i][1]}:该商品库存不正确无法加入，请重新设置";
                }
            }
            if ($i % 10 == 0) {
                echo "sleepstart";
                sleep(0.2);
                echo "sleepstop";
            }
        }
        /*		var_dump($error);
                exit;
        */
        $message['name'] = "成功导入{$message["count"]}条产品信息";
        $this->objForm->setFormData("error", $error);
        $this->objForm->setFormData("message", $message);
    }
}


$objModel = new ProductAction($actionPath);
$objModel->dispatcher();



?>

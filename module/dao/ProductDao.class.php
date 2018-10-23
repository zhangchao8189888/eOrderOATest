<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class ProductDao extends BaseDao
{
 
    /**
     *
     * @return ProductDao
     */
    function CustomerDao()
    {
        parent::BaseDao();
    }
    /**
     * 添加产品信息
     *
    Type	Allow Null	Default Value
    CREATE TABLE `or_product` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `pro_name` varchar(200) NOT NULL,
    `pro_address` varchar(50) NOT NULL,
    `pro_level` varchar(20) NOT NULL,
    `pro_price` decimal(11,2) unsigned NOT NULL,
    `market_price` decimal(11,2) unsigned NOT NULL,
    `vip_price` decimal(11,2) unsigned NOT NULL,
    `pro_num` int(5) unsigned NOT NULL,
    `pro_type` int(2) NOT NULL,
    `add_time` datetime NOT NULL,
    `op_date` datetime NOT NULL,
    `memo` varchar(500) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=utf8

     */

    function addProduct ($product) {
        $sql="insert into or_product
		(pro_name,c_name,e_name,pro_address,pro_level,pro_price,market_price,channel_price,com_channel_price,after_dis_price,cost_price,vip_price,pro_num,pro_area,pro_type,add_time,op_date,memo)
		values ('{$product['pro_name']}','{$product['e_name']}','{$product['c_name']}','{$product['pro_address']}',
		'{$product['pro_level']}',{$product['pro_price']},{$product['market_price']},{$product['channel_price']},{$product['com_channel_price']},{$product['after_dis_price']},{$product['cost_price']},{$product['vip_price']},
		{$product['pro_num']},
		'{$product['pro_area']}',
		{$product['pro_type']},
		now(),now(),
		'{$product['memo']}')";
        $result=$this->g_db_query($sql);
        /*if (!$result) {
            echo $sql."<br/>";print_r($product);
        }*/
        //echo $sql."<br/>";print_r($product);
        return $result;
    }
    function addProductNum ($productNum) {
        $sql="insert into or_product_num
		(pro_id,pro_num,pro_code,pro_unit,op_time)
		values ({$productNum['pro_id']},{$productNum['pro_num']},'{$productNum['pro_code']}','{$productNum['pro_unit']}',now())";
        //echo $sql;
        //exit;
        $result=$this->g_db_query($sql);
//        if (!$result) {
//            echo $sql;
//        }
        return $result;
    }
    function updateProductNumByProId ($productNum) {
        $sql="update or_product set pro_num = {$productNum['pro_num']},op_date = now()
		where id = {$productNum['pro_id']}";//echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getProductNumByProId ($productNum) {
        $sql="select * from or_product_num  where pro_id = $productNum";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getProductNumList ($where,$startIndex,$pageSize) {
        $sql="select op.pro_name,op.pro_type,opn.*  from or_product_num opn,or_product op where opn.pro_id = op.id ";
        if ($where['pro_type']) {
            $sql.= " and op.pro_type = {$where['pro_type']}";
        }
        if ($where['pro_name']) {
            $sql.= ' and op.pro_name like "%'.$where['pro_name'].'%"';
        }

        $sql.=" order by op_date  desc limit $startIndex,$pageSize";

        $result=$this->g_db_query($sql);
        return $result;
    }/*
    function addProduct($product){
		$sql="insert into or_product
		(pro_code,pro_name,pro_spec,pro_unit,pro_price,pro_flag,pro_supplier,pro_type,add_time,op_date)
		values ('{$product['pro_code']}','{$product['pro_name']}',
		'{$product['pro_spec']}','{$product['pro_unit']}',{$product['pro_price']},{$product['pro_flag']},
		'{$product['pro_supplier']}',
		'{$product['pro_type']}',now(),now())";
		$result=$this->g_db_query($sql);
		return $result;
    }*/
    /**
     * 修改产品信息
     * @param $customer
     */
    /**
     * {data: "pro_name",type: 'text'},
    {data: "typeName",type: 'text'},
    {data: "pro_num",type: 'text'},
    {data: "pro_price",type: 'text'},
    {data: "vip_price",type: 'text'},
    {data: "market_price",type: 'text'},
    {data: "memo",type: 'text'}
     */
    function updateProductById($product){
        //pro_num={$product['pro_num']},
    	$sql="update or_product 
		set pro_name='{$product['pro_name']}',pro_area='{$product['pro_area']}',e_name='{$product['e_name']}',c_name='{$product['c_name']}',pro_type={$product['pro_type']},pro_num={$product['pro_num']},
		pro_price={$product['pro_price']},vip_price={$product['vip_price']},market_price={$product['market_price']},cost_price={$product['cost_price']},channel_price={$product['channel_price']},com_channel_price={$product['com_channel_price']},after_dis_price={$product['after_dis_price']},
		memo='{$product['memo']}'
		 where id={$product['id']}";//echo $sql;
    	$result=$this->g_db_query($sql);
		return $result;
    }
    /**
     * 根据产品型号查询商品信息
     * @param $code
     */

    function getProductByCode($code){
    	$sql="select  *  from or_product where pro_name='{$code}'";//echo $sql;exit;
    	$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    function getProductByCName($code) {
        $sql="select  *  from or_product where c_name='{$code}' or e_name='{$code}'";
        //echo $sql;
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function delIntoStorageById ($id) {
        $sql = "delete from or_day_storage where id = $id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    /**
     * @return bool|resourceCREATE TABLE `or_day_storage` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `op_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `pro_code` varchar(50) NOT NULL,
    `good_num` int(5) NOT NULL DEFAULT '0',
    `memo` varchar(200) NOT NULL,
    `into_date` date NOT NULL,
    `add_time` datetime NOT NULL,
    `update_time` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
     */
    function addIntoStorage ($produce) {
        if (empty($produce['op_type'])) {
            $produce['op_type'] = 0;
        }
        global $admin_uid;
        $sql="insert into or_day_storage (op_id,pro_id,pro_code,pro_num,old_storage,new_storage,memo,into_date,add_time,update_time,op_type) values (
        {$admin_uid},{$produce['pro_id']},'{$produce['pro_code']}',
        {$produce['pro_num']},{$produce['old_storage']},{$produce['new_storage']},'{$produce['memo']}',
        '{$produce['into_date']}',now(),now(),{$produce['op_type']})";
       // echo $sql;//exit;
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getSalSumNum ($proId) {
        $sql = " select sum(pro_num) as salSum from or_order where pro_id = $proId";
        $result=$this->g_db_query($sql);
        $sum = mysql_fetch_array($result);
        $numSum = $sum? $sum['salSum'] : 0;
        return $numSum;
    }
    function getReturnSumNum ($proId) {
        $sql = " select sum(pro_num) as returnSum from or_order_return where pro_id = $proId";
        $result=$this->g_db_query($sql);
        $sum = mysql_fetch_array($result);
        $return_num = $sum? $sum['returnSum'] : 0;
        return $return_num;
    }
    function getIntoSumNum ($proId) {
        $sql = " select sum(pro_num) as returnSum from or_day_storage where pro_id = $proId";
        $result=$this->g_db_query($sql);
        $sum = mysql_fetch_array($result);
        $return_num = $sum? $sum['returnSum'] : 0;
        return $return_num;
    }
    function getIntoStorageById ($id) {
        $sql = " select *  from or_day_storage where id = $id ";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getIntoStorageList ($where,$flag = 1) {
        if ($where['bRang']) {

            $sql = " select *  from or_day_storage where into_date >= '{$where['from_date']}'
            and into_date <= '{$where['to_date']}'";
        } else {

            $sql = " select *  from or_day_storage where into_date = '{$where['produce_date']}' ";
        }
        if ($where['search_code']) {
            $sql .= " and pro_code = '{$where['search_code']}'";
        }
        if ($flag) {
            $sql .= " and op_type = 0";
        }
        if ($where['orderBy'] == 1) {

            $sql.= " order by id desc";
        } else if ($where['bRang']) {
            $sql.= " order by  into_date desc";
        }
        $result=$this->g_db_query($sql);//echo $sql;exit;
        return $result;
    }
    function updateIntoStorage ($produce) {
        global $admin_uid;
        $sql="update or_day_storage set op_id = {$admin_uid},pro_id = {$produce['pro_id']},
        pro_code = '{$produce['pro_code']}',pro_num = {$produce['pro_num']},old_storage = {$produce['old_storage']},new_storage = {$produce['new_storage']},memo = '{$produce['memo']}',
        into_date = '{$produce['into_date']}',update_time = now() where id = {$produce['id']}
        ";
        //echo $sql;exit;
        $result=$this->g_db_query($sql);
        return $result;
    }
    /**
     * 查询商品信息集合
     * @param $custName
     * @param $custNo
     * @param $custType
     * @param $startIndex
     * @param $pagesize
     */
   function getProductList($where,$startIndex,$pagesize){
   	    $sql="select *  from or_product where del_flg = 0";
       if ($where['searchKey']) {
           //$sql.=" and CONCAT(pro_code,pro_name,pro_type) LIKE '%".$where['searchKey']."%'";
           $sql.=" and pro_name like '%".$where['searchKey']."%' ";
       } else {
           if($where['proNo']){
               $sql.=" and pro_code like '%".$where['proNo']."%'";
           }
           if($where['type']){
               $sql.=" and pro_type like '%".$where['type']."%'";
           }
           if($where['proName']){
               $sql.=" and pro_name like '%".$where['proName']."%' ";
           }
       }

   	    //$sql.=" order by pro_code  limit $startIndex,$pagesize";
   	    $sql.=" order by op_date  desc limit $startIndex,$pagesize";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function getProductListNoPage ($where){
       $sql="select *  from or_product where del_flg = 0";//
       if ($where['searchKey']) {
           //$sql.=" and CONCAT(pro_code,pro_name,pro_type) LIKE '%".$where['searchKey']."%'";
           $sql.=" and pro_name like '%".$where['searchKey']."%' ";
       } else {
           if($where['proNo']){
               $sql.=" and pro_code like '%".$where['proNo']."%'";
           }
           if($where['type']){
               $sql.=" and pro_type like '%".$where['type']."%'";
           }
           if($where['proName']){
               $sql.=" and pro_name like '%".$where['proName']."%' ";
           }
       }

       //$sql.=" order by pro_code  limit $startIndex,$pagesize";
       $sql.=" order by op_date  desc ";
       $result=$this->g_db_query($sql);
       return $result;
   }
   function getProductExcelList($where){
   	    $sql="select *  from or_product where del_flg = 0";
       if ($where['searchKey']) {
           //$sql.=" and CONCAT(pro_code,pro_name,pro_type) LIKE '%".$where['searchKey']."%'";
           $sql.=" and pro_name like '%".$where['searchKey']."%' ";
       } else {
           if($where['proNo']){
               $sql.=" and pro_code like '%".$where['proNo']."%'";
           }
           if($where['type']){
               $sql.=" and pro_type = {$where['type']} ";
           }
           if($where['proName']){
               $sql.=" and pro_name like '%".$where['proName']."%' ";
           }
       }

   	    //$sql.=" order by pro_code  limit $startIndex,$pagesize";
   	    $sql.=" order by op_date  desc ";//echo $sql;exit;
   	    $result=$this->g_db_query($sql);
		return $result;
   }
    function getProductListLike($key){
        //$sql="select *  from or_product where CONCAT(pro_code,pro_name) LIKE '%{$key}%'";
        $sql="select *  from or_product where pro_name LIKE '%{$key}%' and del_flg = 0";
        $sql.=" order by op_date";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getProductListAll($pro_type = 0){

        $sql="select *  from or_product where del_flg = 0 and pro_level != 'test'";
        if ($pro_type > 0) {
            $sql .= " and pro_type = $pro_type";
        }
        $sql.=" order by op_date";
        $result=$this->g_db_query($sql);
        return $result;
    }
   /**
    * 根据id查询商品信息
    * @param $custId
    */
   function getProductById($proId){
   	    $sql="select *  from or_product where id=$proId";
   	    $result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
   }
   /**
    * 根据商品编号查询商品
    * @param $proNo
    */
   function getProductByCodeLike($proNo){
   	    $sql="select *  from or_product where pro_code like '".$proNo."%' and del_flg = 0 order by  pro_code";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   /**
    * 删除产品信息通过ID
    * @param $custId
    */
   function delProductById($pId){
   	    $sql="delete   from or_product where id=$pId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function updateProductByIdToDel($pId){
   	    $sql="update or_product set del_flg = 1 where  id=$pId";
   	    $result=$this->g_db_query($sql);
		return $result;
   }
   function getStorageByLastMonByProId ($month,$pro_id) {
        $sql = "select  *  from or_day_storage where  add_time <= '{$month}-31' and pro_id = $pro_id order by id desc limit 1;";
       $result=$this->g_db_query($sql); //echo $sql;
       return mysql_fetch_array($result);
   }
   function getFirstStorageThisMonth ($month,$pro_id) {
        $sql = "select  *  from or_day_storage where  add_time > '{$month}-31' and pro_id = $pro_id order by id  limit 1;";
       $result=$this->g_db_query($sql); //echo $sql;
       return mysql_fetch_array($result);
   }
   function getSumIntoStorageByMon ($month,$pro_id) {
       $sql = "select sum(pro_num) as sum_into from or_day_storage where add_time >=  '{$month}-01' and add_time <=  '{$month}-31' and pro_id = $pro_id and op_type = 0";
       $result=$this->g_db_query($sql);//echo $sql;//exit;
       return mysql_fetch_array($result);
   }
   function getSumOutStorageByMon ($month,$pro_id) {
       $sql = "select sum(pro_num) as sum_out from or_day_storage where add_time >=  '{$month}-01' and add_time <=  '{$month}-31' and pro_id = $pro_id and op_type = 1";
       $result=$this->g_db_query($sql);
       return mysql_fetch_array($result);
   }

   function getSumDelStorageByMon ($month,$pro_id) {
       $sql = "select sum(pro_num) as sum_del from or_day_storage where add_time >=  '{$month}-01' and add_time <=  '{$month}-31' and pro_id = $pro_id and op_type = 3";
       $result=$this->g_db_query($sql);
       return mysql_fetch_array($result);
   }

   function getSumReturnStorageByMon ($month,$pro_id) {
       $sql = "select sum(pro_num) as sum_return from or_day_storage where add_time >=  '{$month}-01' and add_time <=  '{$month}-31' and pro_id = $pro_id and op_type = 4";
       $result=$this->g_db_query($sql);    //echo $sql;
       return mysql_fetch_array($result);
   }
   function getSumReturnOrderProNumByMon ($month,$pro_id) {
       $sql = "select sum(pro_num) as sum_return from or_order_return where op_date >=  '{$month}-01' and op_date <=  '{$month}-31' and pro_id = $pro_id";
       $result=$this->g_db_query($sql);  //echo $sql;
       return mysql_fetch_array($result);
   }


   function getSumEarlySalOrderProNumByMon ($start,$pro_id) {
       $sql = "select sum(pro_num) as sum_sal from or_order where op_date >=  '{$start}' and op_date <  '2017-09-15 14:26:49' and pro_id = $pro_id";
       $result=$this->g_db_query($sql);  //echo $sql;
       return mysql_fetch_array($result);
   }


    function getOrderPriceByProId ($month,$pro_id) {
       $sql = "select sum(order_jiner) sum_order_jiner from or_order where op_date >=  '{$month}-01' and op_date <=  '{$month}-31' and pro_id = $pro_id;";

        $result=$this->g_db_query($sql); //echo $sql;
        if ($res = mysql_fetch_array($result)) {
            return $res['sum_order_jiner'] ? $res['sum_order_jiner'] : 0;
        } else {
            return 0.00;
        }
    }

    function getLastStorageByProId ($pro_id,$date) {
        $sql = "select *  from or_day_storage where op_type = 0 and add_time < '{$date}' and pro_id = $pro_id order by id desc  limit 1";
        $result=$this->g_db_query($sql);
        if ($res = mysql_fetch_array($result)) {
            return $res['new_storage'] ? $res['new_storage'] : 0;
        } else {
            return 0;
        }
        //return mysql_fetch_array($result);
    }
    function getCurrentStorageByProId ($pro_id,$start_date,$end_date) {
        $sql = "select sum(pro_num) as pro_num_sum from or_day_storage where op_type = 0 and add_time >= '{$start_date}' and  add_time <= '{$end_date}' and pro_id = $pro_id ";
        $result=$this->g_db_query($sql);
        if ($res = mysql_fetch_array($result)) {
            return $res['pro_num_sum'] ? $res['pro_num_sum'] : 0;
        } else {
            return 0;
        }
    }
    function getCurrentSaleStorageByProId ($pro_id,$start_date,$end_date) {
        $sql = "select sum(pro_num) as pro_num_sum from or_day_storage where op_type = 1 and add_time >= '{$start_date}' and  add_time <= '{$end_date}' and pro_id = $pro_id ";
        $result=$this->g_db_query($sql);
        if ($res = mysql_fetch_array($result)) {
            return $res['pro_num_sum'] ? $res['pro_num_sum'] : 0;
        } else {
            return 0;
        }
    }


}
?>

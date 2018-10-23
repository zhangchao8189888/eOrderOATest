<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 2017/11/19
 * Time: 下午2:23
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING);
$mysql_host = '182.92.81.13';
$mysql_user = 'root';
$mysql_pass = 'xxxx';
$conn = mysql_connect($mysql_host,$mysql_user,$mysql_pass);
// 执行sql查询

mysql_query('set names utf8',$conn);
mysql_select_db('product',$conn);
//$result=mysql_query($query,$conn);
//print_r(mysql_fetch_array($result));

$end_day=date("Y-m-d",strtotime("-1 year"));
//$first_day='2017-06-01';
//print_r($end_day);
$serah_time = date("Y-m-d",strtotime("+1 month"));
//$search_sql =
/*************************添加年费优惠券模块*******************/
/*
$sql  = "select *  from or_account_item where source_type = 'nian_800'";

$result=mysql_query($sql,$conn);
while ($row = mysql_fetch_array($result)) {
		//print_r($row);
				$coupon = array();
                $coupon['customer_id'] = $row['account_id'];
                $coupon['coupon_name'] = "nian_800";
                $coupon['coupon_val'] = 800;
                $coupon['coupon_type'] = 6;
                $coupon['admin_id'] = $row['admin_id'];
                $coupon['admin_name'] = $row['admin_name'];
                $coupon['end_date'] = date("Y-m-d",strtotime("+1 year",strtotime($row['add_time'])));
		$insert_sql = "insert into or_account_coupon (customer_id,coupon_val,coupon_name,coupon_type,admin_id,admin_name,end_date,add_time,up_time)
		        values({$coupon['customer_id']},{$coupon['coupon_val']},'{$coupon['coupon_name']}',{$coupon['coupon_type']},{$coupon['admin_id']},'{$coupon['admin_name']}','{$coupon['end_date']}',now(),now())";
		//echo $insert_sql."\n";
		
		$res=mysql_query($insert_sql,$conn);

	}
*/
/***********************添加end*****************************/

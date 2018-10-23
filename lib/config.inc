<?php
require_once( 'lib/db.inc' );
require_once( 'lib/functions.inc' );
require_once( 'lib/log.class.inc' );
require_once( 'lib/EC.class.inc' );
define('DB_HOST','localhost');
define('DB_NAME','product_test');//
define('DB_USER','root');
define('DB_PASS','Hello0617');
define('UPLOADPATH','upload/');
define('DUIBIUPLOADPATH','upload/duiBi/');
//define('UPLOADPATH','$(UPLOADPATH)');
define('UPLOAD_FILE_NAME','upload.');
define('DOWNLOAD_KEHU_PATH','template/kehuMoban.xls');
define('DOWNLOAD_CHANPIN_PATH','template/chanpinMoban.xls');
//分页一页长度
define('PAGE_SIZE',40);
//设置管理员类型
define('ADMIN_TYPE_SYS',1);
define('ADMIN_TYPE_MON',2);
//设置订单基本号
define('ORDER_BASE_NO',10000);
define('RETURN_BASE_NO',100000);
/**
 * 操作日志
 */

//
define('OP_LOG_LOGIN','管理员登录');
define('OP_LOG_DELETE_ADMIN','删除管理员');
//操作日志添加管理员
define('OP_LOG_ADD_ADMIN','添加管理员');
define('OP_LOG_ADD_EMPLOY','添加员工');
define('OP_LOG_IMPORT_EMPLOY','导入员工');
define('OP_LOG_UPDATE_EMPLOY','修改员工');
define('OP_LOG_DEL_EMPLOY','删除员工');
define('OP_LOG_DEL_COMPANY','删除公司');
define('OP_LOG_UPLOAD_FILE','上传文件');
define('OP_LOG_ORDER','订单日志');
define('OP_LOG_ADD_ORDER','添加订单');
define('OP_LOG_DEL_ORDER','订单作废修改库存');
define('OP_LOG_MODIFY_ORDER','修改订单');
define('OP_LOG_MODIFY_CUSTOMER_VAL','修改客户储值金额');
define('OP_LOG_REFUND_ORDER','操作退单');


define('OP_LOG_RETURN_ORDER','退单日志');
define('OP_LOG_RETURN_ADD','添加退单');
/**
 * 工资
 */
define('OP_LOG_SAVE_SALARY','保存工资');
define('OP_LOG_SAVE__NIAN_SALARY','保存年终奖');
define('OP_LOG_SAVE__ER_SALARY','保存二次工资');
define('OP_LOG_DEL_SALARY','删除工资');
define('OP_LOG_DEL_NIAN_SALARY','删除年终奖');
define('OP_LOG_UPDATE_PER_SALARY','修改个人工资');
define('OP_LOG_SEND_SALARY','工资发放');
define('OP_LOG_ADD_BILL_INVOICE','添加发票');
define('OP_LOG_ADD_BILL_ZHI','添加支票');
define('OP_LOG_ADD_BILL_ZHIDAO','添加支票到账');
define('ERROR_LOG_PATH','log/');
//设置年终奖类别
define('SALARY_TIME_TYPE',5);
//设置二次工资类别
define('ER_SALARY_TIME_TYPE',6);
global $jiezhangType;
$jiezhangType=array(
    '1'=>'已付',
    '0'=>'未付',
);
global $payTypeForVip;
$payTypeForVip = array(2,10,18,19);//会员卡,赠送会员卡
global $orderType ;
$orderType = array(
    "common" => 0,
    "recharge" => 1,
    "refund" => 2,
);
global $payType;
$payType=array(
    '0'=>'充值',
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
	//'17'=>'预收款',
	//'18'=>'赠送卡',
	'20'=>'退货',
	'21'=>'代金券',
);
global $searchPayType;
$searchPayType=array(
    'xianjin'=>'1',
	'vip'=>'2',
	'weixin'=>'3',
);
global $payTypeValue;
$payTypeValue=array(
    'xianjin'=>'1',
	'vip'=>'2',
	'weixin'=>'3',
);
global $returnType;
$returnType=array(
    '1'=>'已退款',
    '2'=>'未退款',
);

global $productType;
$productType=array(
    '1'=>'红葡萄酒',
    '2'=>'白葡萄酒',
    '3'=>'香槟',
    '4'=>'起泡',
    '5'=>'无醇起泡',
    '6'=>'白酒',
    '7'=>'加强酒',
    //'8'=>'烈酒',
    '9'=>'雪茄',
    '10'=>'酒具',
    '11'=>'其它',
    '12'=>'物料',
    '13'=>'茶叶',
    '14'=>'会员卡费',
    '15'=>'场地费',
    '16'=>'门票费',
    //'17'=>'红酒杯',
    '18'=>'干邑',
    '19'=>'雅文邑',
    '20'=>'威士忌',
    '21'=>'配餐',
    '22'=>'门票',
);
global $productTypeValue;
$productTypeValue=array(
    '红葡萄酒'=>'1',
    '白葡萄酒'=>'2',
    '香槟'=>'3',
    '起泡'=>'4',
    '无醇起泡'=>'5',
    '白酒'=>'6',
    '加强酒'=>'7',
    //'烈酒'=>'8',
    '雪茄'=>'9',
    '酒具'=>'10',
    '其它'=>'11',
    '物料'=>'12',
    '茶叶'=>'13',
    '会员卡费'=>'14',
    '场地费'=>'15',
    '门票费'=>'16',
    //'红酒杯'=>'17',
    '干邑'=>'18',
    '雅文邑'=>'19',
    '威士忌'=>'20',
    '配餐'=>'21',
    '门票'=>'22',
);
global $adminType;
$adminType = array(
    '1'=> '最高管理员',
    '2'=> '店长',
    '3'=> '财务',
    '4'=> '开单员',
    '5'=> '库管',
);
global $coupon_name;
$coupon_name = array(
    '1'=> '500元',
    '2'=> '500不找零',
    '3'=> '2500元',
    '4'=> '6000元',
    '5'=> '1280月卡',
    '6'=> '年费800',
    '7'=> '50元',
    '8'=> '2000元',
    '9'=> '450元',
    '10'=> '3500元',
);
global $coupon_val;
$coupon_val = array(
    '1'=> '500',
    '2'=> '500',
    '3'=> '2500',
    '4'=> '6000',
    '5'=> '1280',
    '7'=> '50',
    '8'=> '2000',
    '9'=> '450',
    '10'=> '3500',
);
global $room_val;
$room_val = array(
    '1'=> '卡座1',
    '2'=> '卡座2',
    '3'=> 'Testing room',
    '4'=> 'Private room',
    '5'=> 'Tea room',
);
global $access_ip;
$access_ip = array(
    '125.34.211.36','123.117.64.74','106.37.232.2','133.130.107.227','43.224.47.188','111.207.107.175','1.95.223.245','43.224.47.87','43.224.45.67','1.95.239.141',
    '101.201.34.153','43.224.45.68','1.95.239.141','101.201.34.153','118.150.14.124','43.224.45.86','106.38.14.146','221.217.163.135','61.48.54.66',
    '222.129.255.43','123.122.82.127','114.254.221.220','123.118.112.195','221.219.141.25','123.119.189.32','114.240.62.21','123.113.135.233','123.117.66.118',
    '123.117.66.118','222.129.253.33','61.48.53.202','123.119.188.18','222.129.250.0','114.254.222.7','114.244.9.174','221.217.161.72','123.122.93.195'//
);
global $menus;
$menus=array(

    '1' => array(
        'resource'   => '权限管理',
        'action' => 'Admin',
        'icon'		 =>	'edit',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Admin',
                'mode' => 'getAdminList',
                'resource'	=> '权限管理',
            ),
            '2'	=> array(
                'action' => 'Admin',
                'mode' => 'getGroupList',
                'resource'	=> '权限组',
            ),/*
            '3'	=> array(
                'action' => 'Admin',
                'mode' => 'getGroupAccess',
                'resource'	=> '个人权限',
            ),*/

            '3'	=> array(
                'action' => 'Admin',
                'mode' => 'getJingbanrenlist',
                'resource'	=> '客户经理',
            ),
        ),
    ),

    '2' => array(
        'resource'   => '订单管理',
        'action' => 'Order',
        'icon'		 =>	'edit',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Order',
                'mode' => 'toOrderPage',
                'resource'	=> '订货单',
            ),
            '2'	=> array(
                'action' => 'Order',
                'mode' => 'toOrderReturnList',
                'resource'	=> '退货单',
            ),
            '3'	=> array(
                'action' => 'Order',
                'mode' => 'productListOrder',
                'resource'	=> '快速点单',
            ),
            /*
            '3'	=> array(
                'action' => 'Order',
                'mode' => 'toOrderStatistics',
                'resource'	=> '订单统计',
            ),*//*
            '4'	=> array(
                'action' => 'Order',
                'mode' => 'toFinanceStat',
                'resource'	=> '财务统计',
            ),*/
        ),
    ),
    '3' => array(
        'resource'   => '客户管理',
        'action' => 'Customer',
        'icon'		 =>	'edit',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Customer',
                'mode' => 'getCustomerList',
                'resource'	=> '客户列表',
            ),
            '2'	=> array(
                'action' => 'Customer',
                'mode' => 'toAddCustomerLevel',
                'resource'	=> '客户级别设置',
            ),
            '3'	=> array(
                'action' => 'Customer',
                'mode' => 'toCustomerStat',
                'resource'	=> '按客户统计',
            ),
            '4'	=> array(
                'action' => 'Customer',
                'mode' => 'toCustomerOrderStat',
                'resource'	=> '客户消费明细查询',
            ),
            '5'	=> array(
                'action' => 'Customer',
                'mode' => 'toCustomerLevelStat',
                'resource'	=> '按客户级别查询余额',
            ),
            '6'	=> array(
                'action' => 'Customer',
                'mode' => 'toCustomerByOrderStat',
                'resource'	=> '客户排行',
            )
        ),
    ),
    '4' => array(
        'action' => 'Product',
        'resource'   => '产品管理',
        'icon'		 =>	'group',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Product',
                'mode' => 'getProductList',
                'resource'	=> '商品列表',
            ),
            '2'	=> array(
                'action' => 'Product',
                'mode' => 'productUpload',
                'resource'	=> '新增商品',
            ),

            '3'	=> array(
                'action' => 'Product',
                'mode' => 'toIntoStorage',
                'resource'	=> '商品入库',
            ),
            '4'	=> array(
                'action' => 'Product',
                'mode' => 'toProductStat',
                'resource'	=> '商品销售',
            ),
            '5'	=> array(
                'action' => 'Product',
                'mode' => 'toPandianList',
                'resource'	=> '商品盘点',
            ),
            '6'	=> array(
                'action' => 'Product',
                'mode' => 'toKucunMonthPage',
                'resource'	=> '每月库存',
            ),

        ),
    ),
    '5' => array(
        'resource'   => '财务管理',
        'action' => 'Stat',
        'icon'		 =>	'edit',
        'son'		 => array(/*
            '1'	=> array(
                'action' => 'Stat',
                'mode' => 'toProductStat',
                'resource'	=> '按商品统计',
            ),
            '2'	=> array(
                'action' => 'Stat',
                'mode' => 'toCustomerStat',
                'resource'	=> '按客户统计',
            ),
            '3'	=> array(
                'action' => 'Stat',
                'mode' => 'toCustomerOrderStat',
                'resource'	=> '按客户订单统计',
            ),*/
            '1'	=> array(
                'action' => 'Stat',
                'mode' => 'toOrderStatistics',
                'resource'	=> '财务统计',
            ),
        ),
    ),

);

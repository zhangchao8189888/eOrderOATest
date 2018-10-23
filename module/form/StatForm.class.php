<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class StatForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function StatForm()
    {
        //页面formData做成
        parent::BaseForm();
    }
    /**
     * 取得tpl文件
     *
     * @param $mode　模式
     * @return 页面表示文件
     */
    function getTpl($mode = false)
    {
        switch ($mode) {
            case "stat_product" :
                return "stat/stat_product.php";
            case "stat_customer" :
                return "stat/stat_customer.php";
            case "toCustomerOrderStat" :
                return "stat/stat_customer_order.php";
            case "toOrderStatistics" :
                return "stat/order_statistics.php";
            case "toOrderSearchList":
                return "stat/order_searchList.php";
            default :
                return "BaseConfig.php";
        }
    }
}
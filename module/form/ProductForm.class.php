<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class ProductForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function ProductForm()
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
            case "toAdd" :
                return "product/product_add.php";
            case "toList" :
                return "product/productInfo_list.php";
            case "getPro" :
                return "product/product_modify.php";
            case "toProductExport":
            	return "product/product_export.php";
            case "duibiError":
            	return "duibiError.php";
            case "toProNumList":
            	return "product/product_num_list.php";
            case "toUpload":
            	return "product/productTableImport.php";
            case "excelList":
            	return "product/excelHtmlList.php";
            case "afterImportPage" :
                return "product/afterImport.php";
            case "toPandianList" :
                return "product/product_pandian.php";
            case "toIntoStorage" :
                return "product/product_storage_import.php";
            case "stat_product" :
                return "product/stat_product.php";
            case "toKucunMonth" :
                return "product/kucun_month.php";
            default :
                return "BaseConfig.php";
        }
    }
    }



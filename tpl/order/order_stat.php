<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/productImport.js"></script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">财务统计</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="index.php?action=Order&mode=toOrderPage&n=2_1">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList&n=2_2">退货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderStatistics&n=2_3">订单商品统计</a></li>
                            <li class="active"><a href="index.php?action=Order&mode=toFinanceStat&n=2_4">财务统计</a></li>
                        </ul>

                    </div>
                    <div class="widget-content nopadding">
                        <form id="salForm" action="" method="post">
                            <div class="form-horizontal form-alert">
                                <label class="control-label">产品类别：</label>
                                <div class="controls">
                                    <select id="pro_type">
                                        <?php

                                        global $productType;
                                        $productTypeList = $productType;
                                        foreach($productTypeList as $k=>$v) {
                                            echo '<option value="'.$k.'">'.$v.'</option>';
                                        }?>
                                    </select>
                                </div>
                                <label class="control-label">产品类别：</label>
                                <div class="controls">
                                    <select id="pro_type">
                                        <option value="0">全部</option>
                                        <?php

                                        global $productType;
                                        $productTypeList = $productType;
                                        foreach($productTypeList as $k=>$v) {
                                            echo '<option value="'.$k.'">'.$v.'</option>';
                                        }?>
                                    </select>
                                </div>
                                <label class="control-label">时间段选择：</label>
                                <div class="controls">
                                    <input type="text" id="dateFrom" name="dateFrom" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                    至 <input type="text" id="dateTo" name="dateTo" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="span12" style="margin-left:0;">
                <div class="widget-box">
                    <div class="tab-content">
                        <div>
                            <div class="controls">
                                <!-- checked="checked"-->
                                <input type="button" value="导入" class="btn btn-success" id="produceSave" >
                                <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                &nbsp;&nbsp;选中行数：<span style="color: #049cdb" id="p_num"></span>&nbsp;&nbsp;选中合计：<span id="p_sum" style="color: #049cdb"></span>
                                <div class="alert alert-error" style="display: none">

                                </div>
                                <div class="alert alert-success" style="display: none">
                                </div>
                                <div id="intoStorageGrid" class="dataTable" style="height: 700px;overflow: hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
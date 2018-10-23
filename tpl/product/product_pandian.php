<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/product_pandian.js?2"></script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">产品列表</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="index.php?action=Product&mode=getProductList&n=4_1">商品列表</a></li>
                            <li class=""><a href="index.php?action=Product&mode=productUpload&n=4_2">新增商品</a></li>
                            <li class=""><a href="index.php?action=Product&mode=toIntoStorage&n=4_3">商品入库</a></li>
                            <li class=""><a href="index.php?action=Product&mode=toProductStat&n=4_4">商品销售</a></li>
                            <li class="active"><a href="index.php?action=Product&mode=toPandianList&n=4_5">商品盘点</a></li>
                        </ul>

                    </div>
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品列表 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form id="salForm" action="" method="post">
                            <div class="form-horizontal form-alert">
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
                                <label class="control-label">商品名称：</label>
                                <div class="controls">
                                    <input type="text" placeholder="请输入商品关键字" value="<?php echo $keyword;?>" name="keyword" id="keyword"/>
                                </div>
                                <label class="control-label">盘点月份：</label>
                                <div class="controls">
                                    <input type="text" id="produce_date" name="produce_date" value="<?php echo date("Y-m");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM',realDateFmt:'yyyy-MM'})"/>
                                    <input type="button" value="查询" class="btn btn-primary" id="searchPro" />
                                    <a href="#" class="btn btn-primary" id="exportListBtn">导出</a>
                                </div>

                            </div>

                            <input type="hidden" value="" name="excelData" id="excelData"/>
                            <input type="hidden" value="" name="head" id="head"/>
                            <input type="hidden" value="" name="columns" id="columns"/>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="span12" style="margin-left:0;">
            <div class="widget-box">
                <div class="tab-content">
                    <div>
                        <div class="controls">
                            <!-- checked="checked"-->
                            <a id="level_add" event_type="check" href="index.php?action=Product&mode=productUpload" class="btn btn-success" >导入</a>
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
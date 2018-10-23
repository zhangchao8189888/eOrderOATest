<?php
/* @var $this JController */


$admin=$_SESSION['admin'];
?>
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>


<script src="common/common-js/product_list.js?21"></script>
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
                            <li class="active"><a href="index.php?action=Product&mode=getProductList&n=4_1">商品列表</a></li>
                            <li class=""><a href="index.php?action=Product&mode=productUpload&n=4_2">新增商品</a></li>
                            <li class=""><a href="index.php?action=Product&mode=toIntoStorage&n=4_3">商品入库</a></li>
                            <li class=""><a href="index.php?action=Product&mode=toProductStat&n=4_4">商品销售</a></li>
                            <li class=""><a href="index.php?action=Product&mode=toPandianList&n=4_5">商品盘点</a></li>
                        </ul>

                    </div>
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品列表 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form id="iForm" action="" method="post">

                            </form>
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
                                <label class="control-label">产品：</label>
                                <div class="controls">
                                    <input type="text" id="keyWord" name="keyWord" value=""  />
                                    <input type="button" value="查询" class="btn btn-primary" id="searchPro" />
                                    <input type="button" value="导出" class="btn btn-primary" id="export" />
                                </div>
                                <?php if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5) { ?>

                                    <div class="controls">
                                        <input type="button" value="商品出库明细导出" class="btn btn-success" id="js-export" >
                                    </div>
                                <?php } ?>
                            </div>
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
                                <?php if($this->access_name == "modify") {
                                    ?>

                                    <a id="level_add" event_type="check" href="index.php?action=Product&mode=productUpload" class="btn btn-success" >导入</a>
                                <?php } ?>
                                <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                <input type="checkbox" id="delFlag" autocomplete="off"> <span>完全删除</span>
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
<div class="modal hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>订单日期范围</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">日期：<input type="text" id="dateFrom" name="dateFrom" value="<?php echo date("Y-m-01",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                至 <input type="text" id="dateTo" name="dateTo" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/></div>
            <label class="control-label">产品类别：</label>
            <div class="controls">
                <select id="pro_type_ex">
                    <option value="0">全部</option>
                    <?php

                    global $productType;
                    $productTypeList = $productType;
                    foreach($productTypeList as $k=>$v) {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="exportListBtn">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
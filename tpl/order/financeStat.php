<?php
$levelList=$form_data['levelList'];
$jingli=$form_data['jingli'];
$admin=$_SESSION['admin'];
global $productType;
$productTypeList = $productType;
?>
<script src="common/jquery-ui/js/jquery-ui.custom.js"></script>
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/order_stat.js?2"></script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Order&mode=toOrderPage">订单</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="index.php?action=Order&mode=toOrderPage&n=2_1">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList&n=2_2">退货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderStatistics&n=2_3">订单商品统计</a></li>
                            <li class="active"><a href="index.php?action=Order&mode=toFinanceStat&n=2_4">财务统计</a></li>
                        </ul>

                    </div>

                    <div class="widget-content nopadding">
                        <form id="salForm" action="" method="post" target="_blank">
                            <div class="form-horizontal form-alert">

                                <div class="control-group">
                                    <label class="control-label">会员类别：</label>
                                    <div class="controls">
                                        <select id="customer_level"name="customer_level">
                                            <option value='0'>请选择</option>
                                            <?php foreach($levelList as $val){
                                                $select = '';
                                                if ($val['id'] == $customerPo['custo_level']) {
                                                    $select = 'selected';
                                                }
                                                echo "<option value='{$val["id"]}' {$select}>{$val["level_name"]}</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <label class="control-label">商品种类：</label>
                                    <div class="controls">
                                        <select name="pro_type">
                                            <option value="0">全部</option>
                                            <?php foreach($productTypeList as $k=>$v) {
                                                echo '<option value="'.$k.'">'.$v.'</option>';
                                            }?>
                                        </select>
                                    </div>
                                    <label class="control-label">商品名称：</label>
                                    <div class="controls">
                                        <input type="text" placeholder="请输入商品关键字" value="<?php echo $keyword;?>" name="keyword" id="keyword"/>
                                    </div>
                                    <label class="control-label">排序：</label>
                                    <div class="controls">
                                        <select id="order_by" name="order_by">
                                            <option value='0'>请选择</option>
                                            <option value="1">按人员排序</option>
                                        </select>
                                    </div>
                                    <label class="control-label"><input type="checkbox" id="rang" autocomplete="off"> 日期范围：</label>
                                    <div class="controls">
                                        <input type="text" id="from_date" name="from_date" value="<?php echo date("Y-m-d");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM-dd'})"/>
                                        到<input type="text" id="to_date" name="to_date" value="<?php echo date("Y-m-d");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM-dd'})"/>
                                    </div>
                                </div>
                        </form>
                    </div>
                    <div class="span12" style="margin-left:0;">
                        <div class="widget-box">
                            <div class="tab-content">
                                <div>
                                    <div class="controls">
                                        <!-- checked="checked"-->
                                        <input type="button" value="保存" class="btn btn-success" id="produceSave" >
                                        <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                        &nbsp;&nbsp;选中行数：<span style="color: #049cdb" id="p_num"></span>&nbsp;&nbsp;选中合计：<span id="p_sum" style="color: #049cdb"></span>
                                    </div>
                                    <div class="alert alert-error" style="display: none">

                                    </div>
                                    <div class="alert alert-success" style="display: none">
                                    </div>
                                    <div id="dayProduceGrid" class="dataTable" style="height: 800px; overflow: auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script language="javascript" type="text/javascript">
    var pageAccess = "<?php echo $this->access_name;?>";
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
    });
    function searchByType () {
        $("#iForm").submit();
    }
    function checkSubmit () {
        if ($("#dateFrom").val() == '' || $("#dateTo").val() == '') {
            alert('填写完整日期');
            return false;
        }
    }
</script>



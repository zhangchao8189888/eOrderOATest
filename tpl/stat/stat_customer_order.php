<?php
$levelList=$form_data['levelList'];
$account_sum_list=$form_data['account_sum_list'];
$account_sum=$form_data['account_sum'];
$admin=$_SESSION['admin'];
$jingliList=$form_data['jingli'];
$first_day=date("Y-m",time()).'-01';
$last_day=date("Y-m-d",strtotime("last day"));
?>
<!--<script src="common/jquery-ui/js/jquery-ui.custom.js"></script>-->
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/stat_customer_order.js?8"></script>
<style type="text/css">
    .td-text-right {
        text-align: right;
    }
    .table{
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }
    .clearfix:after {
        clear: both;
        content: "";
        display: block;
        height: 0;
        visibility: hidden;
    }
    .order-sum {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #7fbbc5 #f0f0f0 #f0f0f0;
        border-image: none;
        border-right: 1px solid #f0f0f0;
        border-style: solid;
        border-width: 3px 1px 1px;
        height: 46px;
        line-height: 46px;
        overflow: hidden;
    }
    .order-sum-1,.order-sum-2 {
        border-left: 1px solid #f0f0f0;
        float: left;
        margin-left: -1px;
        padding-left: 5%;
        width: 100%;
    }
    .order-sum-money {
        font-family: Arial;
        font-weight: 900;
        margin: 0 5px;
    }
</style>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Order&mode=toOrderPage&n=<?php echo $this->pageId;?>">订单</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="index.php?action=Stat&mode=toCustomerOrderStat">客户消费明细查询</a></li>
                        </ul>

                    </div>

                    <div class="widget-content nopadding">
                        <form id="salForm" action="" method="post">
                            <div class="form-horizontal form-alert">

                                <div class="control-group">
                                    <label class="control-label">客户级别：</label>
                                    <div class="controls">
                                        <select name="custo_level" id="custo_level">
                                            <option value="0">全部</option>
                                            <?php foreach($levelList as $val){
                                                $select = '';
                                                if ($val['id'] == $customerPo['custo_level']) {
                                                    $select = 'selected';
                                                }
                                                echo "<option value='{$val["id"]}' {$select}>{$val["level_name"]}</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <label class="control-label">客户名称：</label>
                                    <div class="controls">
                                        <input type="text" placeholder="请输入客户关键字" value="<?php echo $keyword;?>" name="keyword" id="keyword"/>
                                    </div>
                                    <label class="control-label"><!--<input type="checkbox" id="rang" autocomplete="off"> -->日期范围：</label>
                                    <div class="controls">
                                        <input type="text" id="from_date" name="from_date" value="<?php echo $first_day;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM-dd'})"/>
                                        到<input type="text" id="to_date" name="to_date" value="<?php echo date("Y-m-d");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM-dd'})"/>

                                        <input type="button" value="查询" class="btn btn-success" id="searchPro" />
                                        <a href="#" class="btn btn-primary" id="exportListBtn">导出</a>
                                    </div>
                                </div>
                                <input type="hidden" value="" name="excelData" id="excelData"/>
                                <input type="hidden" value="" name="head" id="head"/>
                                <input type="hidden" value="" name="columns" id="columns"/>
                        </form>
                    </div>
                    <div class="order-sum clearfix">
                        <div class="order-sum-1">
                            总金额： <span class="order-sum-money">￥<span class="order-sum-money-detail"><?echo $account_sum;?></span></span><span></span>
                            <?php
                                $i = 1;
                                $length = count($account_sum_list);
                                //$half = intval($length/2);
                                foreach ($account_sum_list as $row) {
                                    if(empty($levelList[$row['custo_level']]['level_name'])) {
                                       continue;
                                    }

                                    ?>
                                    <span class="order-sum-money">【<?php echo $levelList[$row['custo_level']]['level_name']?>】：￥<span class="order-sum-money-detail"><?php echo $row['sum_account'];?></span></span>
                                <?php
                                    $i++;
                                    if ($i%9 == 0) {
                                        echo '</div></div>';
                                        echo '<div class="order-sum clearfix"><div class="order-sum-1">';
                                    }

                                }?>
                        </div><!--
                        <div class="order-sum-2">
                            退货单 <span class="order-sum-money">￥ <span class="order-sum-money-detail"><?/*echo $total['return_money'];*/?></span></span><span><?/*echo $total['return_num'];*/?>笔</span>
                        </div>-->
                    </div>
                    <div class="span12" style="margin-left:0;">
                        <div class="widget-box">
                            <div class="tab-content">
                                <div>
                                    <div class="controls">
                                        <!-- checked="checked"-->
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

<div class="modal hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>订单日期范围</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">日期：<input type="text" id="dateFrom" name="dateFrom" value="<?php echo date("Y-m-01",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                至 <input type="text" id="dateTo" name="dateTo" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/></div>
            <label class="control-label">销售人员：</label>
            <div class="controls">
                <select name="jingbanren" id="jingbanren">
                    <option value="0">默认客户销售</option>
                    <?php foreach($jingbanrenList as $val){

                        echo "<option value='{$val["id"]}' >{$val["jingbanren_name"]}</option>";
                    }?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
        /*$("#exportListBtn").click(function () {
            var keyword = $('#keyword').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var url = "index.php?action=Stat&mode=orderExportForCustomer&keyword="+keyword+"&from_date="+from_date+"&to_date="+to_date;
            location.href = url;
            $('#modal-event1').modal("hide");
        });*/



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



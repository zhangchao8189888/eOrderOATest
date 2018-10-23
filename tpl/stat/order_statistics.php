<?php
$tongJiList=$form_data['tongJiList'];
$total=$form_data['total'];
$total_money=$form_data['total_money'];
$admin=$_SESSION['admin'];
$levelList=$form_data['levelList'];
$admin=$_SESSION['admin'];
$jingliList=$form_data['jingli'];
$first_day=date("Y-m",time()).'-01';
$last_day=date("Y-m-d",strtotime("last day"));
?>
<style type="text/css">
    .td-text-right {
        text-align: right;
    }
    .table{
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }
</style>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">财务</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="index.php?action=Order&mode=toFinanceStat&n=2_4">财务统计</a></li>
                        </ul>

                    </div>

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Stat&mode=toOrderSearchList" method="post" onsubmit="checkSubmit()">
                                    时间段选择 ：<input type="text" id="dateFrom" name="dateFrom" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                   至 <input type="text" id="dateTo" name="dateTo" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                    <input type="submit" class="btn btn-success" value="查询"/>
                                </form>
                            </div>
                            <?php if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5|| $admin['admin_type'] == 7) { ?>

                                <div class="controls">
                                    <input type="button" value="业绩统计表导出" class="btn btn-success" id="js-export" >
                                </div>
                                <div class="controls">
                                    <input type="button" value="收入汇总表" class="btn btn-success" id="js-export-income" >
                                </div>
                                <div class="controls">
                                    <input type="button" class="btn btn-success js-export-order" value="订单列表导出"/>
                                </div>
                            <?php } ?>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!--时间 	订货金额 	订单笔数 	退货金额 	退单笔数 	操作-->
                                    <th class="tl"><div>时间</div></th>
                                    <th class="tl"><div>订货金额 </div></th>
                                    <th class="tl"><div>订单笔数</div></th>
                                    <th class="tl"><div>退货金额</div></th>
                                    <th class="tl"><div>退单笔数</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">
                                <?php
                                foreach ($tongJiList as $row){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10"><?php echo $row['order']['month'];?>月</td>
                                        <td class="pr10">￥<?php echo $row['order']['order_money'];?></td>
                                        <td class="td-text-right order-logistics-status">
                                            <span><?php echo $row['order']['order_count'];?>笔</span><br>
                                        </td>
                                        <td class="td-text-right">
                                            <div class="orange">￥<?php echo empty($row['return']['return_money']) ? 0.00:$row['return']['return_money'];?></div>
                                        </td>
                                        <td class="td-text-right order-logistics-status">
                                            <span><?php echo empty($row['return']['return_count']) ? 0:$row['return']['return_count'];?>笔</span><br>
                                        </td>
                                        <td class="tr">
                                            <a title="查看明细" class="theme-color" href="index.php?action=Stat&mode=toOrderSearchList&orderDateMonth=<?php echo $row['order']['month'];?>">查看明细</a>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tfoot>
                                <tr class="">
                                    <td>合计：</td>
                                    <td class="tr">￥<i id="ordersummoney"><?php echo number_format($total_money['order_money'],2);?></i>元</td>
                                    <td class="tl"><i id="ordersumnumber"><?php echo $total['order_count'];?></i>笔</td>
                                    <td class="tr">￥<i id="returnsummoney"><?php echo number_format($total_money['return_money'],2);?></i>元</td>
                                    <td class="tl pl10"><i id="returnsumnumber"><?php echo $total['return_count'];?></i>笔</td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
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
            <div class="tips">日期：<input type="text" class="dateFrom" name="dateFrom" value="<?php echo date("Y-m-01",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                至 <input type="text" class="dateTo" name="dateTo" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/></div>
            <label class="control-label">销售人员：</label>
            <div class="controls">
                <select name="jingbanren" id="jingbanren">
                    <option value="0">默认客户销售</option>
                    <?php while($val = mysql_fetch_array($jingliList)){

                        echo "<option value='{$val["id"]}' >{$val["jingbanren_name"]}</option>";
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

<div class="modal hide" id="modal-event2">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>订单日期范围</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">日期：<input type="text" id="dateFrom1" name="dateFrom1" value="<?php echo date("Y-m-01",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                至 <input type="text" id="dateTo1" name="dateTo1" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/></div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="exportListBtn1">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event3">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>订单日期范围</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">日期：<input type="text" id="dateFrom_order" name="dateFrom" value="<?php echo date("Y-m-01",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                至 <input type="text" id="dateTo" name="dateTo_order" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/></div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="exportListBtn3">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
        $("#js-export").click(function () {//
            $('#modal-event1').modal({show:true});
        });
        $("#exportListBtn").click(function () {
            var fromDate = $(".dateFrom").val();
            var toDate = $(".dateTo").val();
            var jingbanren = $("#jingbanren").val();
            var url = "index.php?action=Stat&mode=orderExportForSale&jingbanren="+jingbanren+"&fromDate="+fromDate+"&toDate="+toDate;
            location.href = url;
            $('#modal-event1').modal("hide");
        });
        $("#js-export-income").click(function () {//
            $('#modal-event2').modal({show:true});
        });
        $("#exportListBtn1").click(function () {
            var fromDate = $("#dateFrom1").val();
            var toDate = $("#dateTo1").val();
            var url = "index.php?action=Stat&mode=orderExportForIncomeList&fromDate="+fromDate+"&toDate="+toDate;
            location.href = url;
            $('#modal-event1').modal("hide");
        });

        $(".js-export-order").click(function () {
            $('#modal-event3').modal({show:true});
        });
        $("#exportListBtn3").click(function () {
            var fromDate = $("#dateFrom_order").val();
            var toDate = $("#dateTo_order").val();
            var url = "index.php?action=Order&mode=orderExport&fromDate="+fromDate+"&toDate="+toDate+"&n=<?php echo $this->pageId;?>";
            location.href = url;
            $('#modal-event1').modal("hide");
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



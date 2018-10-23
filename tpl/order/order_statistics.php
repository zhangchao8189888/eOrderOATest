<?php
$tongJiList=$form_data['tongJiList'];
$total=$form_data['total'];
$total_money=$form_data['total_money'];
$admin=$_SESSION['admin'];
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
            <a href="index.php?action=Order&mode=toOrderPage&n=<?php echo $this->pageId;?>">订单</a>
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

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Order&mode=toOrderSearchList" method="post" onsubmit="checkSubmit()">
                                    时间段选择 ：<input type="text" id="dateFrom" name="dateFrom" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                   至 <input type="text" id="dateTo" name="dateTo" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                    <input type="submit" class="btn btn-success" value="查询"/>
                                </form>
                            </div>
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
                                            <a title="查看明细" class="theme-color" href="index.php?action=Order&mode=toOrderSearchList&orderDateMonth=<?php echo $row['order']['month'];?>">查看明细</a>
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
<script language="javascript" type="text/javascript">
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



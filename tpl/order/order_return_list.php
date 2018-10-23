<?php
$orderList=$form_data['orderList'];
$total=$form_data['total'];
$searchType=$form_data['searchType'];
$by=$form_data['by'];
$up=$form_data['up'];
$admin=$_SESSION['admin'];
?>
<style type="text/css">
    .ui-slide-gray-a {
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #555;
        background-color: #808080;
        display: inline-block;
    }
    .step {
        height: 40px;
    }

    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }
    .step .step-item em {
        margin-right: 10px;
        font-style: normal;
    }

    .step.step1 .step-item-1 {
        background-color: #e0f0f5;
        background-position: right -40px;
    }

    .step.step1 .step-item-2 {
        background-color: #f3f5f6;
        background-position: right 0;
    }
    .step.step1 .step-item-3 {
        background: #f3f5f6;
    }
    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }

    .step .step-item {
        float: left;
        width: 33.3%;
        text-align: center;
        font-size: 16px;
        color: #666;
        line-height: 40px;
        height: 40px;
        background: #f3f5f6 url(common/img/step.png) no-repeat;
    }
    .step .step-item em {
        margin-right: 10px;
        font-style: normal;
    }
    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }
    .ui-slide-gray-a {
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #555;
        background: url(common/img/step.png) -148px -79px no-repeat;
        display: inline-block;
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
                            <li class="active"><a href="index.php?action=Order&mode=toOrderReturnList&n=2_2">退货单</a></li>
                        </ul>

                    </div>

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <?php if($this->access_name == "modify") {
                                    ?>

                                    <!--<div style="float: right;margin-right: 20px"><a href="index.php?action=Order&mode=toOrderReturn" class="btn btn-success" >新增退订单</a></div>-->

                                <?php } ?>
                                <div style="float: right;margin-right: 20px"><input type="button" class="btn btn-success js-export" value="导出"/></div>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="tl"><div>单号/下单时间</div></th>
                                    <th class="tl"><div>客户名称</div></th>
                                    <th class="tl"><div>退单金额</div></th>
                                    <th class="tl"><div>状态</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                foreach ($orderList as $row){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10">
                                            <div><a href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>" class="serial"><?php echo $row['order_no'];?>
                                                 </a>
                                                <input type="hidden" value="<?php echo $row['order_no'];?>" class="order-num" autocomplete="off">
                                            </div>
                                            <span class="lite-gray"><?php echo $row['add_time'];?></span>
                                        </td>
                                        <td class="tl pl10"><a href="/customer/customer?action=load&amp;id=25762928" target="_blank" class="company"><?php echo $row['custer_name'];?></a></td>
                                        <!--<td class="tr pr10">￥<?php /*echo $row['chengjiaoer'];*/?></td>-->
                                        <td class="tr pr10">￥<?php echo $row['return_real_jin'];?></td>
                                        <td class="tc order-logistics-status">
                                            <span><?php echo $row['order_type'];?></span><br>
                                        </td>
                                        <td class="tr">
                                            <a title="订单查详情" class="theme-color" href="index.php?action=Order&mode=getOrderReturnDetail&orderId=<?php echo $row['order_no'];?>">订单详情</a>



                                            <div class="cb"></div>

                                            <!--<a title="添加收款记录" class="theme-color order-pay" href="javascript:void(0)">添加收款记录</a>-->

                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                            <?php require_once("tpl/page.php"); ?>
                            <div class="total_page">共 <span class="redtitle"><?php echo $total ;?></span> 条记录</div>
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
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="exportListBtn">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });

        $(".js-export").click(function () {
            $('#modal-event1').modal({show:true});
        });
        $("#exportListBtn").click(function () {
            var fromDate = $("#dateFrom").val();
            var toDate = $("#dateTo").val();
            var url = "index.php?action=Order&mode=orderReturnExport&fromDate="+fromDate+"&toDate="+toDate+"&n=<?php echo $this->pageId;?>";
            location.href = url;
            $('#modal-event1').modal("hide");
        });
    });
    function searchByType () {
        $("#iForm").submit();
    }
</script>



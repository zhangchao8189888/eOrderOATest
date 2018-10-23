<?php
$orderList=$form_data['orderList'];
$total=$form_data['total'];
$searchType=$form_data['searchType'];
$by=$form_data['by'];
$up=$form_data['up'];
$payType_s=$form_data['payType_s'];
$admin=$_SESSION['admin'];

$dateFrom=$form_data['dateFrom'];
$dateTo=$form_data['dateTo'];
$customer_name=$form_data['customer_name'];
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
                            <li class="active"><a href="index.php?action=Order&mode=toOrderPage&n=2_1">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList&n=2_2">退货单</a></li>
                        </ul>

                    </div>

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Order&mode=toOrderPage&n=<?php echo $this->pageId;?>" method="get">
                                时间段选择 ：<input type="text"  name="dateFrom_s" value="<?php echo $dateFrom;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                至 <input type="text"  name="dateTo_s" value="<?php echo $dateTo;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                    <input type="submit" class="btn btn-success" value="查询"/><br/>

                                    <input type="hidden" id="action" name="action" value="Order"/>
                                    <input type="hidden" id="mode" name="mode" value="toOrderPage"/>
                                    <input type="hidden" id="n" name="n" value="2_1"/>
                                客户名称： <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer_name;?>"/>
                                筛选 ：<select id="searchType" name="searchType"   onchange="searchByType()" >
                                      <option value="all" <?php if ($searchType == 'all') echo 'selected'; ?>>全部订单</option>
                                      <option value="yifu" <?php if ($searchType == 'yifu') echo 'selected'; ?>>已付</option>
                                      <option value="weifu" <?php if ($searchType == 'weifu') echo 'selected'; ?>>未付</option>
                                      <!--<option value="xianjin" <?php /*if ($searchType == 'xianjin') echo 'selected'; */?>>现金</option>
                                      <option value="shuaka" <?php /*if ($searchType == 'shuaka') echo 'selected'; */?>>刷卡</option>
                                      <option value="dianhui" <?php /*if ($searchType == 'dianhui') echo 'selected'; */?>>电汇</option>-->
                                        </select>
                                付款方式 ：<select id="payType_s" name="payType_s"   onchange="payStatusChange()" >
                                        <option value="0" <?php if ($payType_s == 0) echo 'selected'; ?>>全部</option>
                                        <?php
                                        global $payType;
                                        foreach ($payType as $key => $val) {
                                            if($key == 0) {$key = 'recharge';};
                                            $selected = '';
                                            if ($payType_s == $key) {
                                                $selected = 'selected';
                                            }
                                            echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                                        }
                                        ?>
                                    </select>
                                排序 ：<select id="by" name ='by'  onchange="searchByType()" >
                                    <option value="order_no" <?php if ($by == 'order_no') echo 'selected'; ?>>订单号</option>
                                    <option value="ding_date" <?php if ($by == 'ding_date') echo 'selected'; ?>>订货时间</option>
                                </select>
                                升降 ：<select id="up" name ='up'   onchange="searchByType()" >
                                    <option value="asc" <?php if ($up == 'asc') echo 'selected'; ?>>升序</option>
                                    <option value="desc" <?php if ($up == 'desc') echo 'selected'; ?>>降序</option>
                                </select>

                                <input type="hidden" value="" id="pro_id"/>
                                <input type="hidden" value="" id="pro_code"/>
                                    <?php if($this->access_name == "modify") {
                                        ?>
                                        <div style="float: right;margin-right: 20px"><a href="index.php?action=Order&mode=toAdd&n=<?php echo $this->pageId;?>" class="btn btn-success" >新增订单</a></div>
                                   <?php } ?>
                                    <div style="float: right;margin-right: 20px"><input type="button" class="btn btn-success js-export" value="导出"/></div>
                                </form>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!--单号/下单时间 	代理商名称 	金额 	出库/发货 	状态 	操作-->
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>单号/下单时间</div></th>
                                    <th class="tl"><div>客户名称</div></th>
                                    <!--<th class="tl"><div>金额</div></th>-->
                                    <th class="tl"><div>实际金额</div></th>
                                    <th class="tl"><div>付款方式</div></th>
                                    <th class="tl"><div>付款状态</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                foreach ($orderList as $row){
                                    ?>
                                    <tr class="">
                                        <td>    </td>
                                        <td class="tl pl10">
                                            <div><a href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>&n=<?php echo $_REQUEST['n']?>" class="serial"><?php echo $row['order_no'] ? $row['order_no'] : '';?>
                                                    <?php if ($row['isOff']) {echo '<span class="label label-success">特价</span>';}
                                                    if ($row['order_type'] == 1) {
                                                        echo '<span class="label label-success">充值</span>';
                                                    }elseif ($row['is_refund'] == 1) {
                                                        echo '<span class="label label-warning">已退单</span>';
                                                    }?>

                                                </a>
                                                <input type="hidden" value="<?php echo $row['order_no'];?>" class="order-num" autocomplete="off">
                                            </div>
                                            <span class="lite-gray"><?php echo $row['ding_date'];?></span>
                                        </td>
                                        <td class="tl pl10"><!--<a href="/customer/customer?action=load&amp;id=25762928" target="_blank" class="company"></a>--><?php echo $row['custer_name'];?></td>
                                        <!--<td class="tr pr10">￥<?php /*echo $row['chengjiaoer'];*/?></td>-->
                                        <td class="tr pr10">￥<?php echo $row['realChengjiaoer'];?></td>
                                        <td class="tc order-logistics-status">
                                            <span><?php echo $row['pay_type'];?></span><br>
                                        </td>
                                        <td class="tc">
                                            <div class="orange"><?php echo $row['pay_status'];?></div>
                                        </td>
                                        <td class="tr">
                                            <?php
                                            if ($row['order_type'] == 0) {?>
                                                <a title="订单查详情" class="theme-color" href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>&n=<?php echo $_REQUEST['n']?>">订单详情</a>
                                            <?php } else if ($row['order_type'] == 1) {?>
                                                <a title="重新打印" class="theme-color rePrint" style="cursor:pointer" data-id="<?php echo $row['id'];?>" >重新打印</a>
                                            <?php }
                                            ?>




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
            var url = "index.php?action=Order&mode=orderExportForOrder&fromDate="+fromDate+"&toDate="+toDate+"&n=<?php echo $this->pageId;?>";
            location.href = url;
            $('#modal-event1').modal("hide");
        });
        $(".rePrint ").click(function () {
            //var personID=$("#personID").val();
            var orderId=$(this).attr("data-id");
            $.ajax({
                type: "post",
                url:"index.php?action=Order&mode=ajaxPrintAddMoney",
                data:{
                    orderId:orderId
                },
                dataType: "json",
                success: function(data){
                    if (data.code && data.code == 100000) {
                        alert(data.message);
                        return;
                    }
                }
            });
        });
    });
    function searchByType () {
        $("#iForm").submit();
    }

</script>



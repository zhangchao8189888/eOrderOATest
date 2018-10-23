<?php
$orderNo=$form_data['orderNo'];
$customer=$form_data['customer'];
$orderTotal=$form_data['orderTotal'];
$orderList=$form_data['orderList'];
$logList=$form_data['logList'];
$admin=$_SESSION['admin'];
$jingliList=$form_data['jingliList'];
if ($orderTotal['is_refund'] == 1) {
    $this->access_name = 'read';
}
?>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
    });
</script>
<style type="text/css">
    .search_suggest{margin-left: 185px;}
    /* mailBox */
    .gover_search_key{
        border: 1px solid #0591aa;
        font-family: "Microsoft Yahei",Tahoma,Arial;
        height: 40px;
        line-height: 50px;
        outline: 0 none;
        width: 100%;

    }

    .search_suggest{background:#fff;border:1px solid #ddd;padding:3px 5px 0px;position:absolute;z-index:9999;display:none;-webkit-box-shadow:0px 2px 7px rgba(0, 0, 0, 0.35);-moz-box-shadow:0px 2px 7px rgba(0, 0, 0, 0.35);}
    .search_suggest p{width:100%;margin:0;padding:0;height:20px;line-height:20px;clear:both;font-size:12px;color:#ccc;cursor:default;}
    .search_suggest ul{padding:0;margin:0;}
    .search_suggest li{font-size:12px;height:22px;line-height:22px;color:#939393;font-family:'Tahoma';list-style:none;cursor:pointer;overflow:hidden;}
    .search_suggest .cmail{color:#000;background:#e8f4fc;}
    .search_ul{
        position: relative;
        overflow: auto;
        height: 150px;
    }
    .ui-icon-ellipsis {
        border: medium none;
        right: 0;
        top: 65%;
        width: 16px;
        height: 16px;
        margin-top: -8px;
        cursor: pointer;
        overflow: hidden;
        position: absolute;
        /*#background: url(../../images/icon.png) 0 -70px no-repeat;*/
    }
    /*li{
        padding-left:10px;
    }*/
    .ui-combo-wrap {
        position: relative;
        display: inline-block;
        height: 30px;
        vertical-align: middle;
        background-color: #fff;
        border: 1px solid #d6dee3;
        color: #555;
        overflow: hidden;
        zoom: 1;
    }
    .extra-list-ctn {
        border-top: 1px solid #d6dee3;
        padding-left: 10px;
        line-height: 26px;
        background-color: #f5f5f5;
    }
    .quick-add-link {
        line-height: 26px;
        position: relative;
        cursor: pointer;
        outline: medium none;
        text-decoration: none;
        color: #0591aa;
    }
    .order-total {
        min-height: 40px;
        border: 1px solid #e5e8ea;
        margin-top: -1px;
        overflow: hidden;
    }
    .order-total .total-r {
        width: 260px;
        float: right;
        padding: 10px 20px;
    }
    .total-r .total-group {
        line-height: 28px;
    }
    .total-group label {
        margin-top: 3px;
        width: 135px;
        text-align: right;
        float: left;
        font-size : 11px;
    }
    .order-total .total-l {
        margin-right: 303px!important;
        margin-right: 300px;
        padding: 0px;
    }
    .control-group {
        margin-top: 10px;
        overflow: hidden;
        min-height: 32px;
        line-height: 32px;
    }
    .ui-chk {
        padding-left: 20px;
        height: 20px;
        display: inline-block;
        cursor: pointer;
        line-height: 6px;
        vertical-align: middle;
    }
    .fl {
        float: left!important;
    }
    .w80 {
        width: 80px!important;
    }
    .tr {
        text-align: right!important;
    }
    .ui-input-line-dis {
        background: #fcfcfc;
        cursor: not-allowed;
    }
    .ui-input-line {
        outline: 0;
        border: 0;
        height: 14px;
        line-height: 18px;
        border-bottom: 1px solid #d6dee3;
        color: #555;
        padding: 2px 12px;
        font-size: 12px;
    }
    .total-r .total-group .total {
        width: 110px;
        text-align: right;
        margin-left: 130px;
    }
    .red, a.red:hover {
        color: red;
    }
    .order .remark .inp-remark {
        font-size: 12px;
        padding-right: 20px;
        width: 100%;
    }
    .ui-textarea-line {
        color: rgb(255,0,0);
        border-left: medium none;
        border-right: medium none;
        border-top: medium none;
        border-bottom: 1px solid rgb(192,192,192);
        color: #555;
        font-family: verdana,"宋体","Microsoft Yahei",Tahoma,Arial;
        font-size: 12px;
        height: 32px;
        line-height: 32px;
        outline: 0 none;
        padding: 0 5px;
        resize: none;
    }
    .remark {
        margin-top: 30px;
    }
    .control-group .control-label {
        float: left;
        height: 32px;
        line-height: 32px;
        text-align: right;
        width: 110px;
        font-size: 12px;
    }
    .remark .control-input {
        float: none;
        margin-left: 110px;
    }
    .remark .inp-remark {
        width: 80%;
        padding-right: 20px;
        font-size: 12px;
    }
    .search {
        float: left;
    }
    .modify-a {
        float: right;
        margin: 4px 50px 0;
        font-size: 14px;
        width: 100px;
    }
    .theme-color, a.theme-color:hover {
        color: #1596ad;
    }
    .theme-color-fee, a.theme-color-fee:hover {
        color: #ad1727;
    }
    .left {
        float: left;
    }
    .right {
        float: right;
    }
    .dingModify {
        margin: 10px 190px;
    }
    .markModify {
        margin: -24px 190px;
    }
    .fuModify {
        margin: 0px 190px;
    }
</style>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Order&mode=toOrderPage&n=<?php echo $this->pageId;?>">订单</a>
            <a href="#" class="current">订单详情</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">

                    <div class="widget-content tab-content">
                        <div class="search-form">

                            <div class="row-fluid1">
                                <div class="search">
                                        <span style="font-size: 16px;"><?php echo $orderTotal['custer_name']?>【<?php echo $customer['custo_level_name']?>】</span>
                                        单号：<span style="font-size: 14px;"><?php echo $orderTotal['order_no']?></span>
                                    <?php if($orderTotal['isOff']) {?> <span class="label label-success">特价</span><?php }?>
                                    <?php if($orderTotal['is_refund']) {?> <span class="label label-warning">已退单</span><?php }?>
                                    <input type="hidden" id="oId" name="oId" value="<?php echo $orderTotal['order_no']?>">
                                </div>

                            </div>

                        </div>
                        <div class="modify-a ">
                            <?php if($this->access_name == "modify") {
                                ?>

                                <a href="index.php?action=Order&mode=modifyOrder&orderId=<?php echo $orderNo;?>" class="icon-edit theme-color">修改</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="widget-content tab-content">

                        <div class="tab-pane active" id="tab1">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <!--商品 数量 单位 单价 小计 备注-->
                                    <th class="tl" style="width: 25px;"><div></div></th>
                                    <!--<th class="tl" style="width: 50px;"><div></div></th>-->
                                    <th class="tl" style="width: 200px;"><div>商品编码</div></th>
                                    <th class="tl" style="width: 605px;"><div>商品</div></th>
                                    <th class="tl" style="width: 110px;"><div>数量</div></th>
                                    <th class="tl" style="width: 69px;"><div>单位</div></th>
                                    <th class="tl" style="width: 138px;"><div>零售价</div></th>
                                    <th class="tl" style="width: 138px;"><div>会员单价</div></th>
                                    <th class="tl" style="width: 138px;"><div>单价</div></th>
                                    <th class="tl" style="width: 206px;"><div>小计</div></th>
                                    <th class="tl" style="width: 80px;"><div>备注</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodys">
                                <?php $rowNum = 1;foreach ($orderList['data'] as $val) {
                                    ?>
                                    <tr >
                                        <td style="text-align: center;"><?php echo $rowNum;?></td>
                                        <td style="text-align: center;"><?php echo $val['pro_code'];?></td>
                                        <td class="product_add" style="width: 300px;height:20px;"><?php echo $val['pro_name'];?></td>
                                        <td class="product_num" style="text-align:center;"><?php echo $val['pro_num'];?></td>
                                        <td style="text-align:center;"><?php echo $val['pro_unit'];?></td>
                                        <td style="text-align:right;"><?php echo $val['market_price'];?></td>
                                        <td style="text-align:right;"><?php echo $val['vip_price'];?></td>
                                        <td style="text-align:right;"><?php echo $val['price'];?></td>
                                        <td style="text-align:right;"><?php echo $val['order_jiner'];?></td>
                                        <td></td>
                                    </tr>
                                <?php $rowNum++; }?>
                                </tbody>
                            </table>
                            <div class="product-promotion">
                                <ul>
                                    <li style="display:none;" class="template">【赠品】<input type="hidden" class="productId" autocomplete="off"><span class="code"></span> <span class="name"></span><span class="spec"></span>&#12288;<span class="count"></span><span class="unit"></span></li>
                                </ul>
                            </div>
                            <div class="order-total">
                                <div class="total-r">

                                    <div class="total-group">
                                        <label class="total-label total-money-label">合计：</label>
                                        <div class="total">￥<span class="total-money"><?php echo $orderTotal['chengjiaoer'];?></span></div>
                                    </div>

                                    <div class="total-group" style="display:none;">
                                        <label class="total-label total-order-priviledges-label">订单促销优惠：</label>
                                        <div class="total red">-￥<span class="total-order-priviledges-money">0.00</span></div>
                                    </div>

                                    <div class="total-group" style="">
                                        <label class="total-label total-rel-money-label" >实付总额：</label>
                                        <div class="total red">￥<span class="total-rel-money"><?php echo $orderTotal['realChengjiaoer'];?></span></div>
                                        <?php if($this->access_name == "modify"  && $orderTotal['pay_status'] == 0) {
                                            ?>

                                            <a href="#" id="dingFeeModifyOpen" class="icon-edit theme-color-fee">修改订单总额</a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="total-l chks">
                                    <!-- <div class="control-group">
                                        <label class="ui-chk chk fl"><input type="checkbox" name="isUseBackPoint">使用返点</label><span class="is-use-back-point-con fl" style="display: none;">：返点帐户余额￥<span class="back-point-account">0.00</span>元，本次使用￥（<input type="text" class="ui-input-line ui-input-line-dis" value="0" disabled id="inp-back-point-account" />）元</span>
                                    </div> -->
                                    <?php if($orderTotal['isOff']) {?>
                                        <div class="control-group">
                                            <label class="ui-chk chk fl"><div class="controls">
                                                    <input type="checkbox" name="isUseBackPoint" checked disabled>
                                                </div></label><span class="is-discount-order-con fl">已申请特价，请输入获批订单金额：￥（<input type="text" id="inp-discount-order" style="border: 0;height: 18px;margin-bottom: 8px;" disabled class="ui-input-line w80 tr" value="<?php echo $orderTotal['realChengjiaoer'];?>" autocomplete="off">）</span><a href="javascript:void(0)" class="tip-orange ui-icon-info discount-tip" data-hasqtip="3"></a>
                                        </div>
                                    <?php }?>
                                    <?php if($orderTotal['cash_val'] > 0) {?>
                                        <div class="control-group">
                                            <label class="ui-chk chk fl"><div class="controls">
                                                    <input type="checkbox" name="isUseBackPoint" checked disabled>
                                                </div></label><span class="is-discount-order-con fl">现金支付：￥（<?php echo $orderTotal['cash_val'];?>）</span><a href="javascript:void(0)" class="tip-orange ui-icon-info discount-tip" data-hasqtip="3"></a>
                                        </div>
                                    <?php }?>
                                    <?php if($orderTotal['coupon_val'] > 0) {?>
                                        <div class="control-group">
                                            <label class="ui-chk chk fl"><div class="controls">
                                                    <input type="checkbox" name="isUseBackPoint" checked disabled>
                                                </div></label><span class="is-discount-order-con fl">优惠券抵扣：￥（<?php echo $orderTotal['coupon_val'];?>）</span><a href="javascript:void(0)" class="tip-orange ui-icon-info discount-tip" data-hasqtip="3"></a>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="control-group remark">
                                <label class="control-label">填写备注：</label>
                                <div class="control-input left">
                                    <textarea id="remark" class="ui-textarea-line ui-textarea-auto inp-remark" placeholder="在此填写备注信息..."readonly> <?php echo $orderTotal['mark'];?></textarea>
                                </div>
                                <div class="markModify right">

                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">交货日期：</label>
                                <div class="control-input delivery-info left">
                                    <input type="text" id="dingDate" name="dingDate" value="<?php echo $orderTotal['ding_date'];?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})" readonly/>
                                </div>
                                <div class="dingModify right">
                                    <a href="#" id="markAdd" class="icon-plus theme-color">添加备注</a><br/>
                                    <?php if($this->access_name == "modify") {
                                        ?>

                                        <a href="#" id="dingDateModify" class="icon-edit theme-color">修改交货日期</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">制单人：</label>
                                <div class="control-input delivery-info">
                                    <div class="control-input delivery-info left"><?php echo $orderTotal['zhidanren_name'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">经办人：</label>
                                <div class="control-input delivery-info">
                                    <div class="control-input delivery-info left"><?php echo $jingliList[$orderTotal['jingbanren']];?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">付款方式：</label>
                                <div class="control-input delivery-info left"><?php global $payType; echo $payType[$orderTotal['pay_type']];?>
                                </div>
                                <div class="fuModify right">
                                    <?php if($this->access_name == "modify") {
                                        ?>
                                        <a href="#" id="payModify" class="icon-edit theme-color">修改付款方式</a>
                                    <?php } ?>

                                </div>

                            </div>

                            <div class="control-group delivery-date">
                                <label class="control-label">是否付款：</label>
                                    <div class="control-input delivery-info left"><?php global $jiezhangType; echo $jiezhangType[$orderTotal['pay_status']];?>
                                </div>
                                <div class="fuModify right">
                                    <?php if( $this->access_name == "modify" && $orderTotal['pay_status'] == 0) {//$admin['admin_type'] == 1 &&
                                        ?>
                                        <a href="#" id="payStatusModify" class="icon-edit theme-color-fee">修改付款状态</a>
                                    <?php } ?>

                                </div>
                            </div>
                            <?php if ($orderTotal['is_refund'] == 0) { ?>
                            <div class="fuModify right">
                                <a href="javascript:return false;" id="getPdf"  data-id="<?php echo $orderTotal['order_no'];?>" class="icon-edit theme-color">生成发货清单</a>
                            </div>
                            <div class="control-group delivery-date">
                                <a href="javascript:return false;" id="printOrder" data-id="<?php echo $orderTotal['order_no'];?>" class="icon-edit theme-color">重新打印订单</a>
                            </div>
                            <div class="control-group delivery-date">
                                <a href="javascript:return false;" id="smsOrder" data-id="<?php echo $orderTotal['order_no'];?>" class="icon-edit theme-color">发送短信提醒</a>
                            </div>
                            <?php } ?>
                            <?php if ($admin['admin_type'] == 1 && $orderTotal['is_refund'] == 0) { ?>
                                <div class="control-group delivery-date">
                                    <a href="javascript:return false;" id="printDel" data-id="<?php echo $orderTotal['id'];?>" class="icon-edit theme-color">订单作废</a>
                                </div>

                            <?php } ?>
                            <?php if($this->access_name == "modify" && ($admin['name'] == 'luju' || $admin['admin_type'] == 1)) {
                                ?>
                                <div class="control-group delivery-date">
                                    <a href="javascript:return false;" id="orderRefund" data-id="<?php echo $orderTotal['order_no'];?>" class="icon-edit theme-color">退单</a>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="form-actions">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse" class="collapsed"> <span class="icon"><i class="icon-circle-arrow-right"></i></span>
                                        <h5>展开日志</h5>
                                    </a> </div>
                            </div>
                            <div class="accordion-body collapse" id="collapseGTwo" style="height: 0px;">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr><th>操作人</th><th>时间</th><th>操作类别</th><th>操作日志</th></tr>
                                    </thead>
                                    <?php foreach($logList as $row) {?>
                                        <tr><td><?php echo $row['who_name']?></td>
                                            <td><?php echo $row['time']?></td>
                                            <td><?php echo $row['subject']?></td>
                                            <td><?php echo $row['memo']?></td></tr>
                                    <?php }?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<div class="search_suggest" id="gov_search_suggest">
    <ul class="search_ul">

    </ul>
    <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择商品</a></div>
</div>
<div class="modal hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>添加备注</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <textarea id="markValue"><?php echo $orderTotal['mark'];?></textarea>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary detail_add" id="detail_add">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event2">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改订货日期</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <input type="text" id="newDingDate" name="newDingDate" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary detail_add" id="ding_add">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event3">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改付款方式</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <select id="payType" name="payType"   onchange="" >
                <?php
                global $payType;
                foreach ($payType as $key => $val) {
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="pay_add">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event5">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改付款状态</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <select id="payStatus" name="payStatus"   onchange="" >
                <option value="1">已付</option>
                <option value="0">未付</option>
            </select>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="pay_status_add">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event6">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改订单总额</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
        </div>
        <div class="control-group">
            <label class="control-label"><em class="red-star">*</em>订单总额 :</label>
            <div class="controls">

                <input type="text" value="" name="realChengjiaoer"  id="realChengjiaoer" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><em class="red-star">*</em>备注 :</label>
            <div class="controls">
                <textarea id="markValue4fee"><?php echo $orderTotal['mark'];?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="dingFeeModify">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event7">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>退款订单总额</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
        </div>
        <div class="control-group">
            <label class="control-label"><em class="red-star">*</em>备注 :</label>
            <div class="controls">
                <textarea id="markRefund"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <input type="hidden" value="" name="refund_order_id"  id="refund_order_id" />
        <a href="#" class="btn btn-primary" id=returnOrderFee>确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event4">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>生成发货单</h3>
    </div>
    <div class="modal-body">
        <form enctype="multipart/form-data" id="printform" action="index.php?action=Order&mode=orderPrint" method="post"target="_blank">
            <div class="control-group">
                <label class="control-label"><em class="red-star">*</em>购货单位 :</label>
                <div class="controls">
                    <input id="danwei" name="danwei" placeholder="购货单位">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><em class="red-star">*</em>联系人 :</label>
                <div class="controls">
                    <input id="lianxiren" name="lianxiren" placeholder="联系人">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><em class="red-star">*</em>联系方式 :</label>
                <div class="controls">
                    <input id="lianxifangshi" name="lianxifangshi" placeholder="联系方式">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><em class="red-star">*</em>送货地址 :</label>
                <div class="controls">
                    <input id="songdizhi" name="songdizhi" placeholder="送货地址">
                    <input type="hidden" id="orderId" name="orderId" placeholder="送货地址">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><em class="red-star">*</em>日期 :</label>
                <div class="controls">
                    <input type="text" id="songDate" name="songDate" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                </div>
            </div>
        <form/>
    </div>
    <div class="modal-footer modal_operate">
        <input type="submit" class="btn btn-primary" value="打印" class="btn_submit">
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="search_suggest" id="custor_search_suggest">
    <ul class="search_ul">

    </ul>
    <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
</div>
<script language="javascript" type="text/javascript" charset="utf-8">
    $(function(){
        $("#markAdd").click(function () {
            $('#modal-event1').modal({show:true});
        });
        $("#dingDateModify").click(function () {
            $('#modal-event2').modal({show:true});
        });
        $("#payModify").click(function () {
            $('#modal-event3').modal({show:true});
        });
        $("#payStatusModify").click(function () {
            $('#modal-event5').modal({show:true});
        });
        $("#getPdf").click(function () {
            $('#modal-event4').modal({show:true});
            var orderId = $(this).attr("data-id");
            $("#orderId").val(orderId);
        });
        $("#dingFeeModifyOpen").click(function () {
            $('#modal-event6').modal({show:true});
            var orderId = $(this).attr("data-id");
            $("#orderId").val(orderId);
            //$("#realChengjiaoer").val(orderId);
        });
        $("#orderRefund").click(function () {
            $('#modal-event7').modal({show:true});
            var orderId = $(this).attr("data-id");
            $("#refund_order_id").val(orderId);
            //$("#realChengjiaoer").val(orderId);
        });
        $("#printOrder").click(function () {
            var order_id = $(this).attr("data-id");
            var obj = {};
            obj.order_id = order_id;
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=printOrderAjax",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        alert(data.message);
                        return;
                    }
                }
            );
        });
        $("#smsOrder").click(function () {
            var order_id = $(this).attr("data-id");
            var obj = {};
            obj.order_id = order_id;
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=ajaxSendSms",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        alert(data.message);
                    }
                }
            );
        });
        $("#printDel").click(function () {
            var order_id = $(this).attr("data-id");
            var obj = {};
            obj.order_id = order_id;
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=orderDel",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            alert(data.message);
                           //location.href = "index.php?action=Customer&mode=getCustomerList&n=3_1";
                        }
                    }
                }
            );
        });
        $("#returnOrderFee").click(function () {

            var obj = {};
            obj.remark = $("#markRefund").val();
            obj.orderId = $("#refund_order_id").val();
            if (obj.remark == '') {
                alert("填写备注！");
                return;
            }
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=orderRefundAjax",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            alert(data.message);
                           //location.href = "index.php?action=Customer&mode=getCustomerList&n=3_1";
                        }
                    }
                }
            );
        });
        $(".detail_add").click(function () {
            var obj = {};
            obj.oId = $("#oId").val();
            obj.mark = $("#markValue").val();
            obj.ding = $("#newDingDate").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=updateOrderDetail",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            );
        });
        $("#pay_add").click(function () {
            var obj = {};
            obj.oId = $("#oId").val();
            obj.payType = $("#payType").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=updateOrderDetail",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            );
        });
        $("#pay_status_add").click(function () {
            var obj = {};
            obj.oId = $("#oId").val();
            obj.payStatus = $("#payStatus").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=updateOrderDetail",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            );
        });
        $("#dingFeeModify").click(function () {
            var obj = {};
            obj.oId = $("#oId").val();
            obj.realChengjiaoer = $("#realChengjiaoer").val();
            obj.mark = $("#markValue4fee").val();
            var mark_old = "<?php echo $orderTotal['mark']; ?>";
            if (obj.mark == '' || obj.mark == mark_old) {
                alert('需要添加备注，或是不能和原来一致');
                return;
            }
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=updateOrderDetail",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            );
        });
    });
</script>
<!--<script language="javascript" type="text/javascript" src="common/order/orderAdd.js" charset="utf-8"></script>-->




<?php
$orderNo=$form_data['orderNo'];
$levelList=$form_data['levelList'];
$jingliList=$form_data['jingli'];
$jingbanList=$form_data['jingliList'];

$admin=$_SESSION['admin'];
?>
<script language="javascript" type="text/javascript">
    var canShu = "<?php echo $this->pageId;?>";
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
        /*JQuery 限制文本框只能输入数字和小数点*/
        $(".NumDecText").keyup(function(){
            $(this).val($(this).val().replace(/[^0-9.]/g,''));
        }).bind("paste",function(){  //CTR+V事件处理
                $(this).val($(this).val().replace(/[^0-9.]/g,''));
            }).css("ime-mode", "disabled"); //CSS设置输入法不可用
    });
</script>
<link rel="stylesheet" href="common/css/order-add.css" />
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Order&mode=toOrderPage&n=<?php echo $this->pageId;?>">订单</a>
            <a href="#" class="current">新增订货单</a>
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

                                    客户名称 ：
                                    <span class="ui-combo-wrap" id="agentAuto">
                                        <input type="text" id="customer_add" name="customer_add" style="width: 420px;height: 20px;margin-bottom: 0px;">
                                        <span class="icon icon-user ui-icon-ellipsis" id="pro_add"></span>
                                    </span>
                                    <input type="checkbox" id="isCommonPrice">用零售价
                                    <input type="hidden" id="cId" name="cId" value="">
                                    <input type="hidden" id="custo_discount" name="custo_discount" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content tab-content">

                        <div class="tab-pane active" id="tab1">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <!--商品 数量 单位 单价 小计 备注-->
                                    <th class="tl" style="width: 25px;"><div></div></th>
                                    <th class="tl" style="width: 50px;"><div></div></th>
                                    <th class="tl" style="width: 605px;"><div>商品</div></th>
                                    <th class="tl" style="width: 110px;"><div>数量</div></th>
                                    <th class="tl" style="width: 69px;"><div>单位</div></th>
                                    <th class="tl" style="width: 138px;"><div>零售价</div></th>
                                    <th class="tl" style="width: 138px;"><div>会员单价</div></th>
                                    <th class="tl" style="width: 138px;"><div>折后实际单价</div></th>
                                    <th class="tl" style="width: 206px;"><div>小计</div></th>
                                    <th class="tl" style="width: 80px;"><div>备注</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodys">
                                <tr id="1">
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;"><a style="cursor: pointer;" class="icon-plus" title="新增行"></a>
                                        <a class="icon-minus" style="cursor: pointer;" title="删除行"></a></td>
                                    <td class="product_add" style="width: 300px;height:20px;"></td>
                                    <td class="product_num" style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:right;"></td>
                                    <td style="text-align:right;"></td>
                                    <td></td>
                                </tr><tr id="2">
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;"><a style="cursor: pointer;" class="icon-plus" title="新增行"></a>
                                        <a class="icon-minus" style="cursor: pointer;" title="删除行"></a></td>
                                    <td class="product_add"style="width: 300px;height:20px;"></td>
                                    <td class="product_num" style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:right;"></td>
                                    <td style="text-align:right;"></td>
                                    <td></td>
                                </tr><tr id="3">
                                    <td style="text-align: center;">3</td>
                                    <td style="text-align: center;"><a style="cursor: pointer;" class="icon-plus" title="新增行"></a>
                                        <a class="icon-minus" style="cursor: pointer;" title="删除行"></a></td>
                                    <td class="product_add"style="width: 300px;height:20px;"></td>
                                    <td class="product_num" style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:right;"></td>
                                    <td style="text-align:right;"></td>
                                    <td></td>
                                </tr>
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
                                        <div class="total">￥<span class="total-money">0.00</span></div>
                                    </div>

                                    <div class="total-group-coupon" style="display: none">
                                        <label class="total-label total-money-label">优惠扣减：</label>
                                        <div class="total">￥<span class="coupon-money">0.00</span></div>
                                    </div>

                                    <div class="total-group" style="display:none;">
                                        <label class="total-label total-order-priviledges-label">订单促销优惠：</label>
                                        <div class="total red">-￥<span class="total-order-priviledges-money">0.00</span></div>
                                    </div>

                                    <div class="total-group" >
                                        <label class="total-label total-rel-money-label" >应付总额：</label>
                                        <div class="total red">￥<span class="total-rel-money">0.00</span></div>
                                    </div>
                                </div>
                                <div class="total-l chks">
                                     <div class="control-group">
                                        <label class="ui-chk chk fl"><span class="is-use-coupon-con fl" style=""><input type="checkbox" id="isUseCoupon">使用返点：优惠券帐户余额￥<span class="back-point-account">0.00</span>元，本次使用￥（<input type="text" class="ui-input-line ui-input-line-dis" value="0" disabled id="inp-back-point-account" />）元</span>

                                    </div>

                                    <div class="control-group">
                                        <label class="ui-chk chk fl"></label><span class="is-use-back-point-con fl"><input type="checkbox" id = "monthCard">使用月卡：月卡余额￥<span class="mon_val">0.00</span>元，扣除月卡金额￥（<input type="text" class="ui-input-line ui-input-line-dis NumDecText" value="0" disabled id="month_val" value="0.00"/>）元</span>
                                    </div>
                                     <div class="control-group-coupon" style="display: none">
                                         <input type="checkbox" id="val_500" >500不找零
                                    </div>
                                    <div class="control-group">
                                        <label class="ui-chk chk fl"></label><span class="is-use-back-point-con fl"><input type="checkbox" id = "useCash">部分付现金：客户账户余额￥<span class="customer_val">0.00</span>元，额外付现金￥（<input type="text" class="ui-input-line ui-input-line-dis NumDecText" value="0" disabled id="user_cash_val" value="0.00"/>）元</span>
                                    </div>
                                    <div class="control-group">
                                        <label class="ui-chk chk fl"><div class="controls">

                                            </div></label><span class="is-discount-order-con fl"><input type="checkbox" name="isUseBackPoint" id = "discounts">已申请特价，请输入获批订单金额：￥（<input type="text" id="inp-discount-order" style="border: 0;height: 18px;margin-bottom: 8px;" class="ui-input-line ui-input-line-dis w80 tr" value="" disabled autocomplete="off">）</span><a href="javascript:void(0)" class="tip-orange ui-icon-info discount-tip" data-hasqtip="3"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group remark">
                                <label class="control-label">填写备注：</label>
                                <div class="control-input">
                                    <textarea id="remark" class="ui-textarea-line ui-textarea-auto inp-remark" placeholder="在此填写备注信息..."></textarea>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">交货日期：</label>
                                <div class="control-input delivery-info">
                                    <input type="text" id="dingDate" name="dingDate" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">制单人：</label>
                                <div class="control-input delivery-info"><!--
                                    <select name="zhidanren" id="zhidanren">
                                        </select>--><?php /*while($val = mysql_fetch_array($jingbanList)){
                                            if ($val['is_zhidan_type'] == 1) {
                                                echo "<option value='{$val["id"]}' >{$val["jingbanren_name"]}</option>";
                                            }

                                        }*/?>

                                    <input type="text" name="zhidanren_name" id="zhidanren_name" value="<?php echo $admin['real_name']?>" disabled />
                                    <input type="hidden" name="zhidanren" id="zhidanren" value="<?php echo $admin['id']?>" disabled />
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">销售人员：</label>
                                <div class="control-input delivery-info">
                                    <select name="customer_jingbanren" id="customer_jingbanren">
                                        <option value="0">默认客户销售</option>
                                        <?php while($val = mysql_fetch_array($jingliList)){
                                            echo "<option value='{$val["id"]}' >{$val["jingbanren_name"]}</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">付款方式：</label>
                                <div class="control-input delivery-info">
                                    <select id="payType" name="payType"   onchange="payStatusChange()" >
                                        <?php
                                        global $payType;
                                        foreach ($payType as $key => $val) {
                                            if($key == 0) continue;
                                            echo '<option value="'.$key.'">'.$val.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">消费房间：</label>
                                <div class="control-input delivery-info">
                                    <select id="roomId" name="roomId"   onchange="" >
                                        <option value="0">无</option>
                                        <?php
                                        global $room_val;
                                        foreach ($room_val as $key => $val) {
                                            echo '<option value="'.$key.'">'.$val.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group delivery-date">
                                <label class="control-label">是否付款：</label>
                                <div class="control-input delivery-info">
                                    <select id="payStatus" name="payStatus"   onchange="" >
                                        <option value="1">已付</option>
                                        <option value="0">未付</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="control-group delivery-date">
                            打印双份：<input type="checkbox" id="print" checked />
                        </div>
                        <div class="control-group delivery-date">
                            打印不保存：<input type="checkbox" id="un_save"/>
                        </div>
                        <div class="control-group delivery-date">
                            是否发送会员通知短信：<input type="checkbox" id="sms" /><!--checked disabled-->

                            <input id="transFile" class="btn btn-success" type="button" value="生成发货清单">
                        </div>
                    </div>
                    <div class="form-actions">
                        <input id="submit" class="btn btn-primary" type="submit" value="提交">
                        <input id="cancel" class="btn" type="button" value="取消">

                        <div id="status"></div>
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
        <h3>添加客户</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">客户名称：<input type="text" maxlength="20" id="custor_name"  ></div>
            <div class="tips">客户级别：<select name="custo_level" id="custo_level">
                    <?php foreach($levelList as $val){
                        $select = '';
                        if ($val['id'] == $customerPo['custo_level']) {
                            $select = 'selected';
                        }
                        echo "<option value='{$val["id"]}' {$select}>{$val["level_name"]}</option>";
                    }?>
                </select></div>
            <div class="tips">联系方式：<input type="text" maxlength="20" id="customer_moveTel"  ></div>
            <div class="tips">会员卡号：<input type="text" maxlength="20" id="car_no"  ></div>
            <div class="tips">储值金额：<input type="text" maxlength="20" id="money_val" value="0.00" ></div>
            <!--<div class="tips">1280月卡：<input type="checkbox" id="monCard"></div>-->
            <div class="tips">500不找零：<input type="checkbox" id="val500"></div>
            <div class="tips">800年费：<input type="checkbox" id="nian800"></div>
            <div class="tips">代金券<select id="coupon">
                    <option value="0">无</option>
                    <option value="7">50元</option>
                    <option value="1">500元</option>
                    <option value="9">450元</option>
                    <option value="3">2500元</option>
                    <option value="8">2000元</option>
                    <option value="4">6000元</option>
                    <option value="10">3500元</option>
                </select></div>

        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="cust_add">添加</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="search_suggest" id="custor_search_suggest">
    <ul class="search_ul">

    </ul>
    <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
</div>
<div class="modal hide" id="modal-event4">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>生成发货单</h3>
    </div>
    <div class="modal-body">
        <form enctype="multipart/form-data" id="printform" action="index.php?action=Order&mode=getOrderPDF" method="post"target="_blank">
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
            <input type="hidden" id="order_data" name="order_data" value=""/>
            <form/>
    </div>
    <div class="modal-footer modal_operate">
        <input type="button" class="btn btn-primary" id="getPdf" value="打印" class="btn_submit">
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script language="javascript" type="text/javascript" src="common/order/order.js?21" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/order/orderAdd.js?21" charset="utf-8"></script>

<script language="javascript" type="text/javascript" charset="utf-8">
    $(function(){
        $("#transFile").click(function () {
            $('#modal-event4').modal({show:true});
            var orderId = $(this).attr("data-id");
            $("#orderId").val(orderId);
        });
    })
    function payStatusChange() {
        if ($("#payType").val() == 7) {//未结账
            $("#payStatus").val(0)
        }
    }
</script>


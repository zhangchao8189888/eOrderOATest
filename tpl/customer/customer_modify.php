<?php
/* @var $this JController */

$customer=$form_data['customerPo'];
$levelList=$form_data['levelList'];
$jingbanList=$form_data['jingbanList'];

$admin=$_SESSION['admin'];
?>
<style>
    .control-group .control-input {
        float: left;
        line-height: 32px;
        padding-left: 10px;
    }
    .control-group-line .control-label-2 {
        width: 50px;
    }
</style>
<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Customer&mode=getCustomerList">客户</a>
            <a href="#" class="current">修改客户  </a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span6">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>基础资料</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form action="index.php?action=Customer&mode=updateCustomer&n=<?php echo $_REQUEST['n'];?>" id="customer_validate" method="post" class="form-horizontal" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>公司名称:</label>
                                <div class="controls">
                                    <input type="text" name="company_name" id="company_name" class="span11" value="<?php echo $customer['company_name'];?>" placeholder="公司名称">
                                    <input type="hidden" name="id" id="customer_id"  value="<?php echo $customer['id'];?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>卡号 :</label>
                                <div class="controls">
                                    <input type="text" name="card_no" id="card_no" class="span11" value="<?php echo $customer['card_no'];?>" placeholder="客户编码">
                                    <!--<span class="help-block">（唯一标识客户，不能重复，必填。）</span>-->
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">客户折扣 :</label>
                                <div class="controls">
                                    <input type="text" name="discount" id="discount" class="span11" value="<?php echo $customer['discount'];?>" placeholder="客户折扣">
                                    <span class="help-block">（可以不填。）</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">详细地址</label>
                                <div class="controls">
                                    <input type="text" name="address" id="address" class="span11"  value="<?php echo $customer['address'];?>" placeholder="详细地址">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">姓名</label>
                                <div class="controls">
                                    <input type="text" name="realName" id="realName" value="<?php echo $customer['realName'];?>" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">客户储值金额</label>
                                <div class="controls">

                                    <input type="text" name="total_money" id="total_money" value="<?php echo $customer['total_money'];?>" <?php if ($admin['admin_type'] != 1) { echo "readonly";}?>  class="span6">
                                    <a href="javascript:return false;" id="addMoney" data-val="<?php echo $customer['total_money'];?>">充值</a>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">客户级别:</label>
                                <div class="controls">
                                    <select name="custo_level" id="custo_level" <?php if ($admin['admin_type'] != 1) { echo "readonly";}?>>
                                        <?php foreach($levelList as $val){
                                            $select = '';
                                            if ($val['id'] == $customer['custo_level']) {
                                                $select = 'selected';
                                            }
                                            echo "<option value='{$val["id"]}' {$select}>{$val["level_name"]}</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">客户经理:</label>
                                <div class="controls">
                                    <select name="customer_jingbanren" id="customer_jingbanren">
                                        <option val="0">请选择</option>
                                        <?php foreach($jingbanList as $val){
                                            $select = '';
                                            if ($val['id'] == $customer['customer_jingbanren']) {
                                                $select = 'selected';
                                            }

                                            echo "<option value='{$val["id"]}' {$select}>{$val["jingbanren_name"]}</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">座机</label>
                                <div class="controls">
                                    <input type="text" name="phone" id="phone" value="<?php echo $customer['phone'];?>" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">手机</label>
                                <div class="controls">
                                    <input type="text" name="mobile" id="mobile" value="<?php
                                    if (!$this->checkPhoneNum) {
                                        $string = $customer['mobile'];
                                        $pattern = '/(\d{3})(\w+)(\d{3})/i';
                                        $replacement = '${1}*****$3';
                                        $str = preg_replace($pattern, $replacement, $string);
                                        $customer['mobile'] = $str;
                                    }

                                    echo $customer['mobile'];
                                    ?>" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">QQ</label>
                                <div class="controls">
                                    <input type="text" name="qq" id="qq" value="<?php echo $customer['qq'];?>" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">email</label>
                                <div class="controls">
                                    <input type="text" name="email" id="email" value="<?php echo $customer['email'];?>" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">微信</label>
                                <div class="controls">
                                    <input type="text" name="weixin" id="weixin" value="<?php echo $customer['weixin'];?>" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">联名备注</label>
                                <div class="controls">
                                    <textarea name="custo_info" id="custo_info" class="span11"><?php echo $customer['custo_info'];?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">备注信息</label>
                                <div class="controls">
                                    <textarea name="remarks" id="remarks" class="span11"><?php echo $customer['remarks'];?></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if($this->access_name == "modify") {
                                    ?>
                                    <button type="submit" class="btn btn-success">修改</button>
                                <?php } ?>
                                <!---->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>充值</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">现余额：<span id="cus_fee"></span></div>
            <div class="tips">充值金额：<input type="text" maxlength="20" id="add_fee"  ></div>
            <div class="tips">1280月卡：<input type="checkbox" id="monCard"></div>
            <div class="tips">500不找零：<input type="checkbox" id="val500"></div>
            <div class="tips">800年费：<input type="checkbox" id="nian800"></div>
            <div class="tips">代金券<select id="coupon">
                    <option value="0">无</option>
                    <option value="7">50元</option>
                    <option value="9">450元</option>
                    <option value="1">500元</option>
                    <option value="3">2500元</option>
                    <option value="8">2000元</option>
                    <option value="4">6000元</option>
                    <option value="10">3500元</option>
                </select></div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="fee_add">添加</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {
        $("#addMoney").click(function (){
            $("#cus_fee").text($(this).attr("data-val"));
            $('#modal-event1').modal({show:true});
            return false;
        });
        $("#fee_add").click(function(){
            var obj = {
                coupon : $("#coupon").val(),
                card_no : $("#card_no").val(),
                customer_tel : $("#customer_moveTel").val()
            }
            if ($("#monCard").attr("checked") == 'checked') {
                obj.monCard = 1;
            }
            if ($("#val500").attr("checked") == 'checked') {
                obj.val_500 = 1;
            }
            if ($("#nian800").attr("checked") == 'checked') {
                obj.nian_800 = 1;
            }
            obj.fee = $("#add_fee").val();
            obj.customer_id = $("#customer_id").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Order&mode=updateCustomerJson",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code == 100000){
                            alert("充值成功！");
                            location.reload();
                        } else {
                            alert(data.message);
                            return;
                        }

                    }
                }
            );
        });
        $("#customer_validate").validate({
            rules: {
                customer_name: { required: true },

                txbNewPwd2: { required: true, rangelength: [8, 15], equalTo: "#txbNewPwd1" }
            },
            messages: {
                customer_name:
                {
                    required: '必填'
                },
                customer_code:
                {
                    required: '（唯一标识客户，不能重复，必填。）',
                    remote : '客户编码已经存在'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight:function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('success');
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }


        });

    });



    jQuery.extend(jQuery.validator.messages, {
        required: "必选字段",
        remote: "请修正该字段",
        email: "请输入正确格式的电子邮件",
        url: "请输入合法的网址",
        date: "请输入合法的日期",
        dateISO: "请输入合法的日期 (ISO).",
        number: "请输入合法的数字",
        digits: "只能输入整数",
        creditcard: "请输入合法的信用卡号",
        equalTo: "请再次输入相同的值",
        accept: "请输入拥有合法后缀名的字符串",
        maxlength: jQuery.validator.format("请输入一个 长度最多是 {0} 的字符串"),
        minlength: jQuery.validator.format("请输入一个 长度最少是 {0} 的字符串"),
        rangelength: jQuery.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符串"),
        range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
        max: jQuery.validator.format("请输入一个最大为{0} 的值"),
        min: jQuery.validator.format("请输入一个最小为{0} 的值")
    });
    function pack(dp){
        if(!confirm('日期框原来的值为: '+dp.cal.getDateStr()+', 要用新选择的值:' + dp.cal.getNewDateStr() + '覆盖吗?'))
            return true;
    }
    function changeDate(dp) {

        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Customer&mode=sumNongli",
                async:false,
                data: {
                    date: dp.cal.getNewDateStr()
                },
                dataType: "json",
                success: function(data){
                    if (data.data){
                        $("#nongli").val(data.data);
                    }

                }
            }
        );
    }

</script>
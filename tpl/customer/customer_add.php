<?php
$levelList=$form_data['levelList'];
$jingbanList=$form_data['jingbanList'];
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
            <a href="#" class="current">新增客户</a>
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
                <form action="index.php?action=Customer&mode=addCustomer" id="customer_validate" method="post" class="form-horizontal" novalidate="novalidate">
                    <div class="control-group">
                        <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>公司名称:</label>
                        <div class="controls">
                            <input type="text" name="company_name" id="company_name" class="span11" placeholder="公司名称">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>卡号 :</label>
                        <div class="controls">
                            <input type="text" name="card_no" id="card_no"value="" class="span11" placeholder="客户编码">
                            <!--<span class="help-block">（唯一标识客户，不能重复，必填。）</span>-->
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">客户折扣 :</label>
                        <div class="controls">
                            <input type="text" name="discount" id="discount" value="0.00" class="span11" placeholder="客户折扣">
                            <span class="help-block">（可以不填。）</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">详细地址</label>
                        <div class="controls">
                            <input type="text" name="address" id="address" class="span11" placeholder="详细地址">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">姓名</label>
                        <div class="controls">
                            <input type="text" name="realName" id="realName" class="span6">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">客户储值金额</label>
                        <div class="controls">

                            <input type="text" name="total_money" id="total_money" value="0.00" class="span6">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">客户级别:</label>
                        <div class="controls">
                            <select name="custo_level" id="custo_level">
                                <?php foreach($levelList as $val){
                                    $select = '';
                                    if ($val['id'] == $customerPo['custo_level']) {
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
                                <?php foreach($jingbanList as $val){
                                    echo "<option value='{$val["id"]}' {$select}>{$val["jingbanren_name"]}</option>";
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">座机</label>
                        <div class="controls">
                            <input type="text" name="phone" id="phone" class="span6">
                            </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">手机</label>
                        <div class="controls">
                            <input type="text" name="mobile" id="mobile" class="span6">
                            </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">QQ</label>
                        <div class="controls">
                            <input type="text" name="qq" id="qq" class="span6">
                            </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">email</label>
                        <div class="controls">
                            <input type="text" name="email" id="email" class="span6">
                            </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">微信</label>
                        <div class="controls">
                            <input type="text" name="weixin" id="weixin" class="span6">
                            </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">联名备注</label>
                        <div class="controls">
                            <textarea name="custo_info" id="custo_info" class="span11"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">备注信息</label>
                        <div class="controls">
                            <textarea name="remarks" id="remarks" class="span11"></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">保存</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {
        $("#customer_validate").validate({
            rules: {
                customer_name: { required: true }
                /*customer_code:
                {
                    required: true
                }*/
            },
            messages: {
                customer_name:
                {
                    required: '必填'
                },
                customer_code:
                {
                    required: '（唯一标识客户，不能重复，必填。）'
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
<?php
$levelList=$form_data['levelList'];
$admin=$_SESSION['admin'];
$jingliList=$form_data['jingli'];
$first_day=date("Y-m",time()).'-01';
$last_day=date("Y-m-d",strtotime("last day"));
?>
<script src="common/jquery-ui/js/jquery-ui.custom.js"></script>
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/stat_customer.js?5"></script>
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
                            <li class="active"><a href="index.php?action=Stat&mode=toCustomerStat">按客户统计</a></li>
                        </ul>

                    </div>

                    <div class="widget-content nopadding">
                        <form id="salForm" action="" method="post" target="_blank">
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
                                    <label class="control-label">销售人员：</label>
                                    <div class="controls">
                                        <select name="customer_jingbanren" id="customer_jingbanren">
                                            <option value="0">默认客户销售</option>
                                            <?php $jingbanrenList = array(); while($val = mysql_fetch_array($jingliList)){
                                                $jingbanrenList[] = $val;
                                                echo "<option value='{$val["id"]}' >{$val["jingbanren_name"]}</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <label class="control-label">客户名称：</label>
                                    <div class="controls">
                                        <input type="text" placeholder="请输入客户关键字" value="<?php echo $keyword;?>" name="keyword" id="keyword"/>
                                    </div>
                                    <label class="control-label">排序：</label>
                                    <div class="controls">
                                        <select id="order_by" name="order_by">
                                            <option value='0'>请选择</option>
                                            <option value="1">按人员排序</option>
                                        </select>
                                    </div>
                                    <label class="control-label"><!--<input type="checkbox" id="rang" autocomplete="off"> -->日期范围：</label>
                                    <div class="controls">
                                        <input type="text" id="from_date" name="from_date" value="<?php echo $first_day;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM-dd'})"/>
                                        到<input type="text" id="to_date" name="to_date" value="<?php echo date("Y-m-d");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM-dd'})"/>

                                        <input type="button" value="查询" class="btn btn-success" id="searchPro" >
                                    </div>
                                    <?php if ($admin['admin_type'] == 1 || $admin['admin_type'] == 5) { ?>

                                        <div class="controls">
                                            <input type="button" value="业绩统计表导出" class="btn btn-success" id="js-export" >
                                        </div>
                                        <div class="controls">
                                            <input type="button" value="收入汇总表" class="btn btn-success" id="js-export-income" >
                                        </div>
                                    <?php } ?>
                                </div>
                        </form>
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
                至 <input type="text" id="dateTo" name="dateTo" value="<?php echo date("Y-m-d",time());?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/></div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="exportListBtn1">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
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



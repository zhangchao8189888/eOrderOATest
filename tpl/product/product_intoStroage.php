<?php
$workerList=$form_data['workerList'];
$workName=$form_data['workName'];
$assistName=$form_data['assistName'];

?>
<script src="common/jquery-ui/js/jquery-ui.custom.js"></script>
<!--<script src="common/pikaday/css/pikaday.css"></script>-->
<!--<script src="common/moment/moment.js"></script>-->
<!--<script src="common/pikaday/pikaday.js"></script>-->
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/productCheck.js"></script>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">产品生产</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品生产 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form id="salForm" action="" method="post" target="_blank">
                            <div class="form-horizontal form-alert">

                                <div class="control-group">
                                    <label class="control-label">主品检员：</label>
                                    <div class="controls">
                                        <select id="p_worker"name="p_worker">
                                            <option value='0'>请选择</option>
                                            <?php foreach($workerList as $row){
                                                if($row['work_type'] == 2)
                                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <label class="control-label">副品检员：</label>
                                    <div class="controls">
                                        <select id="g_worker" name="g_worker">
                                            <option value='0'>请选择</option>
                                            <?php foreach($workerList as $row){
                                                if($row['work_type'] == 3)
                                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                            }?>
                                        </select>
                                    </div>
                                    <label class="control-label">排序：</label>
                                    <div class="controls">
                                        <select id="paixu" name="paixu">
                                            <option value='0'>请选择</option>
                                            <option value="1">按人员排序</option>
                                        </select>
                                    </div>
                                    <label class="control-label">产品型号：</label>
                                    <div class="controls">
                                        <input name="proNo" id="proNo_search" type="text" value="<?php echo $proNo;?>" style="width: 100px"/>
                                    </div>
                                    <label class="control-label"><em class="red-star">*</em>品检日期：</label>
                                    <div class="controls">
                                        <input type="text" id="check_date" name="check_date" value="<?php echo date("Y-m-d");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                        <input type="button" value="查询" class="btn btn-primary" id="searchPro" />
                                        <input type="button" value="保存导出" class="btn btn-success" id="export" />

                                    </div>
                                </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="span12" style="margin-left:0;">
                <div class="widget-box">
                    <div class="tab-content">
                        <div>
                            <div class="controls">
                                <!-- checked="checked"-->
                                <input type="button" value="保存" class="btn btn-success" id="produceSave" >
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
<div class="search_suggest" id="custor_search_suggest">
    <ul class="search_ul">

    </ul>
    <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
</div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript">
    var work_mame_list = <?php echo json_encode($workName);?>;
    var assist_mame_list = <?php echo json_encode($assistName);?>;
</script>
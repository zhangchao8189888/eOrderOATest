<?php
$errorMsg=$form_data['error'];
$message=$form_data['message'];
?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Admin&mode=filesUpload">产品导入</a>
            <a href="#" class="current">导入结果  </a>
        </div>
    </div>
    <div class="step step1">
        <div class="step-item step-item-1"><em class="ui-slide-gray-a ">一</em>导入文件</div>
        <div class="step-item step-item-2"><em class="ui-slide-gray-a ">二</em>导入预览</div>
        <div class="step-item step-item-3"><em class="ui-slide-gray-a current">三</em>导入完成</div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品导入 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="index.php?action=Admin&mode=upload" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group" id="createError" style="display:none;">
                                <label class="control-label">&nbsp;</label>
                                <div class="controls">
                                    <span class="colorRem"></span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <font color="green"><?php if($message)echo $message['name']?></font>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>错误信息</th>
                        </tr>
                        </thead>
                        <tbody  id="tbodays">

                        <?php foreach ($errorMsg as $row){  ?>
                            <tr >

                                <td><div><?php echo $row['error'];?></div></td>

                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
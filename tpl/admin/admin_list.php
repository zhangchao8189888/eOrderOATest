<?php
$adminList=$form_data['adminList'];
$adminType=$form_data['adminType'];
global $admin_uid;
?>
<script language="javascript" type="text/javascript">
    $(function(){

        $("#com_add").click(function(){
            $('#modal-event1').modal({show:true});
        });
        $(".rowUpdate").click(function(){
            var id = $(this).attr("data-id");
            $.ajax(
                {
                    type: "post",
                    url: "index.php?action=Admin&mode=getAdminById",
                    data: {aid:id},
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.mess);
                            return;
                        }
                        var admin = data.admin;
                        $("#name").val(admin.name);
                        $("#real_name").val(admin.real_name);
                        $("#admin_id").val(admin.id);
                        $("#adminType").val(admin.admin_type);
                        $("#password").val(admin.password);
                        $("#memo").val(admin.memo);
                        $('#modal-event1').modal({show:true});
                    }
                }
            )

        });
        $(".rowDelete").click(function(){
            var id = $(this).attr("data-id");
            $.ajax(
                {
                    type: "post",
                    url: "index.php?action=Admin&mode=delete",
                    data: {aid:id},
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.mess);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        });

    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">权限管理</a>
            <a href="#">管理员管理</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="controls">
                    <?php if($this->access_name == "modify" || $admin_uid == 20) { ?>
                    <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" >新增管理员</a></div>
                    <?php }?>
                </div>
            </div>
            <div class="span12"><div class="widget-box">
                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">


                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>登录名</div></th>
                                    <th class="tl"><div>用户名</div></th>
                                    <th class="tl"><div>管理员类别</div></th>
                                    <th class="tl"><div>上传登录时间</div></th>
                                    <th class="tl"><div>备注</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                while ($row = mysql_fetch_array($adminList)){
                                          if ($this->access_name != "modify") {

                                              if ($admin_uid != $row['id']) {
                                                  continue;
                                              }
                                          }
                                    ?>
                                    <tr >
                                        <td><div></div></td>
                                        <td><div><?php echo $row['name'];?></div></td>
                                        <td><div><?php echo $row['real_name'];?></div></td>
                                        <td><div><?php echo $adminType[$row['admin_type']];?></div></td>
                                        <td><div><?php echo $row['last_login_time'];?></div></td>
                                        <td><div><?php echo $row['memo'];?></div></td>
                                        <td class="tr">
                                            <?php if($this->access_name == "modify") {
                                            ?>
                                            <a title="排序修改" href="#" data-group-id="<?php echo $row['admin_type'];?>"  data-id="<?php echo $row['id'];?>"   class="edit_btn pointer theme-color btn btn-small">修改权限组</a>
                                            <a title="删除" data-id="<?php echo $row['id'];?>"  class="rowDelete pointer theme-color">删除</a>
                                            <a title="修改" data-id="<?php echo $row['id'];?>"  class="rowUpdate pointer theme-color">修改</a>
                                            <div class="cb"></div>
                                            <?php } else {
                                                ?>
                                                <a title="修改" data-id="<?php echo $row['id'];?>"  class="rowUpdate pointer theme-color">修改</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
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
        var type = $("#searchType").val();
        if (type == 'name') {
            $("#search_name").show();
            $("#com_status").hide();
        } else if (type == 'status') {
            $("#search_name").hide();
            $("#com_status").show();
        }
    }
    function searchByStatus() {

    }
</script>
<script language="javascript" type="text/javascript" src="common/common-js/admin_list.js" charset="utf-8"></script>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>管理员管理</h3>
    </div>
    <form action="" id="admin_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="modal-body">
                <div class="form-horizontal form-alert">
                    <div class="control-group">
                        <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>登录用户名：</label>
                        <div class="controls">
                            <input type="text" maxlength="20" id="name"name="name" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>真实姓名：</label>
                        <div class="controls">
                            <input type="text" maxlength="20" id="real_name"name="real_name" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>密码：</label>
                        <div class="controls">
                            <input type="password" maxlength="20" id="password" name="password" />
                            <input type="hidden" id="admin_id" name="admin_id" value=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>类别：</label>
                        <div class="controls">
                            <select id="adminType" name="adminType"  <?php if($this->access_name != "modify" && $admin_uid != 20) {  echo "disabled" ;}?>>
                                <?php foreach ($adminType as $k => $val) {
                                    echo "<option value = '{$k}'>{$val} </option>";
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">备注：</label>
                        <div class="controls">
                            <textarea id="memo">

                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="submit" class="btn btn-primary">添加</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>



<!--修改--START---->
<div class="modal hide" id="modal-edit-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改</h3>
    </div>
    <div class="modal-body">
        <form id="admin_form">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>分组名称 :</label>
                    <div class="controls ">
                        <textarea  placeholder="行业名称"></textarea><br />
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer modal_operate">
        <button type="button" class="btn_edit btn btn-primary">保存</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--修改--END---->

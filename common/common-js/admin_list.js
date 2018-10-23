/**
 * Created by zhangchao8189888 on 15-12-11.
 */
$(function(){
    $(".edit_btn").click(function(){
        var id = $(this).attr('data-id');
        var group_id = $(this).attr('data-group-id');
        $.ajax({
            type: "post",
            url:"index.php?action=Admin&mode=ajaxGetGroup",
            data:{
                id:id
            },
            dataType: "json",
            success: function(data){
                if (data.status && data.status == 100001) {
                    alert(data.content);
                    return;
                }else{
                    var group=data.content;
                    var r="<select id='checkGroup'>";
                    $.each(group,function (i,row) {
                        var select="";
                        if(group_id==row['id']){
                            select="selected";
                        }
                        r+="<option "+select+" value="+row["id"]+">";
                        r+=row["title"];
                        r+='</option>';
                    });
                    r+="</select>";
                    r+='<input type="hidden" id="personID" value="'+id+'">';
                    $("#modal-edit-event .controls").html(r);
                }
            }
        });

        $("#modal-edit-event").modal({show:true});
    });
    //保存
    $(".btn_edit ").click(function () {
        var personID=$("#personID").val();
        var groupID=$("#checkGroup").val();
        $.ajax({
            type: "post",
            url:"index.php?action=Admin&mode=saveGroup",
            data:{
                personID:personID,
                groupID:groupID
            },
            dataType: "json",
            success: function(data){
                if (data.status && data.status == 100001) {
                    alert(data.content);
                    return;
                }else{
                    alert(data.content);
                    window.location.reload();
                }
            }
        });
    });
    $("#admin_validate").validate({
        onsubmit:true,
        submitHandler:function(form){
            var obj = {};
            obj.name = $("#name").val();
            obj.real_name = $("#real_name").val();
            obj.pass = $("#password").val();
            obj.admin_type = $("#adminType").val();
            obj.memo = $("#memo").val();
            if ($("#admin_id").val()) {
                obj.id = $("#admin_id").val();
            }
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Admin&mode=addOrUpdateAdmin",
                    data: obj,
                    dataType:'json',
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.mess);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        },
        rules: {
            name: { required: true }
        },
        messages: {
            name:
            {
                required: '必填'
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
$(function() {
    var access_list = {};
    $('input[name="rule_add"]').change(
        function () {
            $('#access_tb').html("");
            if($(this).attr("checked")){
                var menu_id = $(this).val();
                var sonList = menu_list[menu_id].son;
                var menu_name = menu_list[menu_id].resource;
                access_list[menu_id] = {};
                for (var k in sonList) {

                    access_list[menu_id][k] = "modify";
                    var html = '<tr class="odd">' +
                        '<td>' + menu_name+'</td>' +
                        '<td>' + sonList[k].resource+'</td>' +
                        '<td><input type="radio" class="access_set" name="'+menu_id+'_'+k+'" class="access_json" value="read" />查看' +
                        '<input type="radio"  class="access_set" name="'+menu_id+'_'+k+'" class="access_json" value="modify" checked/>修改</td>'
                        '</tr>'
                    $('#access_tb').append(html);
                    $("#access_add").show();
                }
            }

        }
    );

    $('input[name="rule_edit"]').change(
        function () {
            $('#access_tb_up').html("");
            if($(this).attr("checked")){
                var menu_id = $(this).val();
                var sonList = menu_list[menu_id].son;
                var menu_name = menu_list[menu_id].resource;

                var checked_r = "";
                var checked_m = "";
                for (var k in sonList) {

                    if (access_list[menu_id]) {
                        if (access_list[menu_id][k] == "modify") {
                            checked_m = "checked";
                        } else if (access_list[menu_id][k] == "read") {
                            checked_r = "checked";
                        } else {
                            access_list[menu_id][k] = "modify";
                            checked_r = "checked";
                        }
                    } else {
                        access_list[menu_id] = {};
                        access_list[menu_id][k] = "modify";
                        checked_m = "checked";
                    }
                    var html = '<tr class="odd">' +
                        '<td>' + menu_name+'</td>' +
                        '<td>' + sonList[k].resource+'</td>' +
                        '<td><input type="radio" class="access_set" name="'+menu_id+'_'+k+'" class="access_json" value="read" '+checked_r+' />查看' +
                        '<input type="radio"  class="access_set" name="'+menu_id+'_'+k+'" class="access_json" value="modify" '+checked_m+' />修改</td>' +
                        '</tr>'
                    $('#access_tb_up').append(html);
                    $("#access_up").show();
                }
            }

        }
    );
    $(".access_set").live("click",function () {
        var num = $(this).attr("name");
        var attr = num.split("_");
        if (!access_list[attr[0]]) {
            access_list[attr[0]] = {};
        }
        access_list[attr[0]][attr[1]] = $(this).val();
    });
    // 添加
    $("#add_btn").click(function(){
        $('#group_form_add')[0].reset();
        $('input[name="rule_add"]').each(function () {
            var one = $(this).removeAttr('checked');
            $.uniform.update(one);
        });
        $("#modal-add-event").modal({show:true});
        $("#access_add").hide();
        access_list = {};
    });

    $('.save_add').click(function () {

        var name = $('#group_name_add').val();
        var status = $('.status_add').val();
        var rules = new  Array();
        var access_obj = {};
        $('input[name="rule_add"]:checked').each(function(){
            rules.push($(this).val());
            if (access_list[$(this).val()]) {
                access_obj[$(this).val()] = access_list[$(this).val()];
            }
        });
        var describe = $('#describe_add').val();
        //console.log();
        $.ajax({
            url: 'index.php?action=Admin&mode=groupAdd',
            data: {
                name: name,
                status: status,
                rule: rules,
                access_obj: access_obj,
                describe: describe
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.status == 100000) {
                    alert(data.content);
                    window.location.reload();
                }
            }
        });
    });

    $('.save_edit').click(function () {
        var id = $('#group_id').val();
        var name = $('#group_name_edit').val();
        var status = $('input[name="status_edit"]:checked ').val();
        var rules = new  Array();
        var access_obj = {};
        $('input[name="rule_edit"]:checked').each(function(){
            rules.push($(this).val());
            if (access_list[$(this).val()]) {
                access_obj[$(this).val()] = access_list[$(this).val()];
            }
        });
        var describe = $('#describe_edit').val();
        $.ajax({
            url: 'index.php?action=Admin&mode=updateGroup',
            data: {
                id: id,
                name: name,
                status: status,
                rule: rules,
                access_obj: access_obj,
                describe: describe
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.status == 100000) {
                    alert(data.content);
                    window.location.reload();
                }
            }
        });
    });
    // 编辑
    $('.edit_btn').click(function () {
        $('#group_form_edit')[0].reset();
        $('input[name="rule_edit"]').each(function () {
            var one = $(this).removeAttr('checked');
            $.uniform.update(one);
        });
        $('#modal-edit-event').modal({show:true});
        var id = $(this).attr('data-id');
        $('#group_id').val(id);
        $.ajax({
            url: 'index.php?action=Admin&mode=getGroupListJson',
            type: 'post',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if (data.status == 100000) {
                    var group = data.content;
                    $('#group_name_edit').val(group.title);
                    var two = $('input[name="status_edit"][value='+group.status+']').attr('checked', true);
                    $.uniform.update(two);
                    for (var i=0;i<group.rules.length;i++) {
                        var values = group.rules[i];
                        var one = $('input[name="rule_edit"][value='+values+']').attr('checked', true);
                        $.uniform.update(one);
                    }
                    access_list = group.access_list;
                    $('#describe_edit').val(data.content.describe);
                } else {
                    alert(data.content);
                }
            }
        });
        $("#access_up").hide();
        access_list = {};
    });
    $('.rowDelete').click(function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: 'index.php?action=Admin&mode=delGroup',
            data: {
                id: id
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.status == 100000) {
                    alert(data.content);
                    window.location.reload();
                } else {
                    alert(data.content);
                }
            }
        });
    });

});
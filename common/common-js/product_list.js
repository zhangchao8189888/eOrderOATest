/**
 * Created by zhangchao8189888 on 16-6-5.
 */
$(function(){
    $("#export").click(function () {

        location.href = 'index.php?action=Product&mode=productExport&pro_type='+$("#pro_type").val();
    });
    function createBigData() {
        var keyWord = $('#keyWord').val();
        var pro_type = $('#pro_type').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Product&mode=getProductExcelList",
                data: {
                    keyWord : keyWord,
                    pro_type : pro_type

                },
                dataType: "json",
                success: function(data){
                    var jData = data.data;
                    productGrid.loadData(jData);
                    if (GLOBAL.access_name == "read") {
                        productGrid.updateSettings({
                            //columns :columns,
                            contextMenu:false,
                            readOnly:true
                        });
                    }

                }
            }
        );
    }

    $("#searchPro").click(function (){
        createBigData();
    });

    var changeList = {};
    var productGrid = document.getElementById('intoStorageGrid');
    var table_header = ['序列号',' Products Name','产品名称','产区','类型','库存','销售量','会员价','零售价','渠道价（合伙人）','餐饮渠道价','渠道价','备注'];

    var columns = [
        {data: "row_id",type: 'text'},
        {data: "e_name",colWidths:200,type: 'text'},
        {data: "c_name",colWidths:200,type: 'text'},
        {data: "pro_area",type: 'text'},
        {data: "typeName",type: 'text'},
        //{data: "pro_num",type: 'text',readOnly: true},
        {data: "pro_num",type: 'text',readOnly: true},
        {data: "sale_num",type: 'text'},
        //{data: "pro_price",type: 'text'},
        {data: "vip_price",type: 'text'},
        {data: "market_price",type: 'text'},
        {data: "channel_price",type: 'text'},
        {data: "com_channel_price",type: 'text'},
        {data: "after_dis_price",type: 'text'},
        {data: "memo",type: 'text'}
    ];
    var menu = [];
    if (GLOBAL.admin_type == 1) {
        menu = ['row_above', 'row_below', 'remove_row'];
    } else {
        menu = ['row_above', 'row_below'];
    }
    var modify_num = true;
    if (GLOBAL.admin_type == 1 ) {
        modify_num = false;
    }
    if (GLOBAL.admin_type == 1 || GLOBAL.admin_type == 5 || GLOBAL.admin_type == 7) {//
        table_header = ['序列号',' Products Name','产品名称','产区','类型','库存','销售量','会员价','零售价','渠道价（合伙人）','餐饮渠道价','渠道价','成本价','备注'];
        columns = [
            {data: "row_id",type: 'text'},
            {data: "e_name",colWidths:200,type: 'text'},
            {data: "c_name",colWidths:200,type: 'text'},
            {data: "pro_area",type: 'text'},
            {data: "typeName",type: 'text'},
            //{data: "pro_num",type: 'text',readOnly: true},
            {data: "pro_num",type: 'text',readOnly: modify_num},
            {data: "sale_num",type: 'text'},
            //{data: "pro_price",type: 'text'},
            {data: "vip_price",type: 'text'},
            {data: "market_price",type: 'text'},
            {data: "channel_price",type: 'text'},
            {data: "com_channel_price",type: 'text'},
            {data: "after_dis_price",type: 'text'},
            {data: "cost_price",type: 'text'},
            {data: "memo",type: 'text'}
        ];

    }
    productGrid = new Handsontable(productGrid, {
        data: createBigData(),
        //stretchH: 'last',
        contextMenu: menu,
        startRows: 5,
        minSpareRows: 1,
        colHeaders: table_header,//'单价',
        manualColumnResize: true,
        manualRowResize: true,
        rowHeights: function(){return 25;},
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
            } else if (p == p2 && r== r2) {
                var rowDate =this.getSourceDataAtRow(r);
                if (rowDate.message) {
                    $(".alert-error").show();

                    $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>' +
                        '<strong>导入失败</strong>'+rowDate.c_name+'：'+rowDate.message);
                }

            } else {
                var sum = 0;
                var hang = 0;
                for (var i = r; i <= r2; i++) {
                    var cellDate =this.getDataAtCell(i,p);
                    if (!parseInt(cellDate)) cellDate = 0;
                    sum += parseInt(cellDate);
                    hang ++;
                }
                $("#p_num").text(hang);
                $("#p_sum").text(sum);
            }

        },
        beforeRemoveRow:function (index,amount) {
            if(!window.confirm('你确定要修改此数据吗？')){
                return false;
            }

            var delId = [];
            for (var i = 0; i < amount; i++) {
                var rowData = this.getSourceDataAtRow(index);
                if (!rowData.row_id) continue;
                delId.push(rowData.row_id);
                index++;
            }
            var bDel = 0;
            if ($("#delFlag").attr('checked')) {
                bDel = 1;
            }
            //删除
            var url = 'index.php?action=Product&mode=delProductById';
            var formData = {
                ids: delId,
                bDel: bDel
            }
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if (res.code > 100000) {
                        console.log(res.mess);
                    }
                    else {
                        console.log(res.mess);
                        createBigData();
                    }
                },
                error: function () {
                    console.text('Save error');
                }
            });
        },
        afterChange: function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }
            for(var val = 0; val < change.length; val++) {
                if (change[val][2] != change[val][3]) {

                    var row = parseInt(change[val][0]);
                    var col = change[val][1];
                    if(window.confirm('你确定要修改此数据吗？')){
                        //alert("确定");
                    }else{
                        //alert("取消");

                        productGrid.setDataAtRowProp( row, col, change[val][2], "updateData");
                        return false;
                    }

                    var rowData = this.getSourceDataAtRow(row);//&& rowData.pro_price && rowData.vip_price&& rowData.market_price
                    if ((rowData.c_name || rowData.e_name) && rowData.typeName && rowData.pro_num >= 0 ) {
                        //if (rowData.worker && rowData.product ) {
                        rowData.c_name = rowData.c_name ? rowData.c_name: '';
                        rowData.e_name = rowData.e_name ? rowData.e_name: '';
                        rowData.pro_area = rowData.pro_area ?rowData.pro_area: '';
                        rowData.typeName = rowData.typeName ?rowData.typeName: '';
                        rowData.pro_num = rowData.pro_num ?rowData.pro_num: 0;
                        rowData.pro_price = rowData.pro_price ?rowData.pro_price: 0.00;
                        rowData.vip_price = rowData.vip_price ? rowData.vip_price : 0.00;
                        rowData.market_price = rowData.market_price ? rowData.market_price : 0.00;
                        rowData.channel_price = rowData.channel_price ? rowData.channel_price : 0.00;
                        rowData.com_channel_price = rowData.com_channel_price ? rowData.com_channel_price : 0.00;
                        rowData.after_dis_price = rowData.after_dis_price ? rowData.after_dis_price : 0.00;
                        rowData.row = row;
                        rowData.col = col;
                        if (col == 'pro_num') {
                            rowData.isModifyProNum = 1;
                            rowData.old_pro_num = change[val][2];
                        } else {
                            rowData.isModifyProNum = 0;
                        }
                        if (rowData.id) {
                            changeList[row] = rowData;
                        }

                        if (!changeList.length) {
                            changeList.length = 0;
                        }
                        changeList.length++;
                    }
                }
            }
            if(!changeList.length || changeList.length < 1) {
                //alert("没有要保存的");
                return;
            } else {
                delete changeList.length;
            }
            var formData = {
                "data": changeList
            }
            var url = 'index.php?action=Product&mode=updateProduct';
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if (res.code > 100000) {
                        changeList = {};
                        if (res.errorList && res.errorList.length > 0) {
                            $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>');
                            for(var val = 0; val < res.errorList.length; val++) {
                                var error = res.errorList[val];
                                var row = parseInt(error.row);
                                var rowData = productGrid.getSourceDataAtRow(row);
                                rowData.zp = rowData.zp ? rowData.zp: 0;
                                rowData.cp = rowData.cp ?rowData.cp: 0;
                                rowData.qt = rowData.qt ?rowData.qt: 0;
                                rowData.sh = rowData.sh ?rowData.sh: 0;
                                rowData.memo = rowData.memo ? rowData.memo : '';
                                rowData.row = row;
                                rowData.col = col;
                                changeList[row] = rowData;
                                row += 1;
                                $(".alert-error").show();
                                $(".alert-error").append('<strong>第'+row+'行'+error.mess+'</strong>');

                            }
                        }
                    } else {
                        $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>');
                        changeList = {};
                        if (res.success_List && res.success_List.length > 0) {
                            for(var val = 0; val < res.success_List.length; val++) {
                                $(".alert-success").show();
                                var row = parseInt(res.success_List[val].row);
                                var rowData = productGrid.getSourceDataAtRow(row);
                                var row_text = row +1;
                                $(".alert-success").append('<strong>第'+row_text+'行'+rowData.c_name+'保存成功</strong>');

                            }
                        }
                        //createBigData();
                    }
                    //createBigData();
                },
                error: function () {
                    alert('保存失败，请重试');
                }
            });

        },
        columns: columns
    });

    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };
    $("#produceSave").click(function () {


        var formData = {
            "data": productGrid.getData(),
            pro_type: $("#pro_type").val()
        }
        var url = 'index.php?action=Product&mode=saveProductList';
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                var errorData = res.error_list;
                var count = res.success_list.length;
                $(".alert-success").show();
                $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>' +
                    '<strong>成功导入'+count+'个商品</strong></div>');
                if (errorData.length > 0) {

                    productGrid.loadData(errorData);
                    productGrid.updateSettings({
                        cells: function (row, col, prop) {
                            var cellProperties = {};
                            if (col == 0){
                                cellProperties.renderer = redRenderer;
                            }
                            return cellProperties;
                        }
                    })
                }
            },
            error: function () {
                console.text('Save error');
            }
        });
    });
    $("#js-export").click(function () {//
        $('#modal-event1').modal({show:true});
    });
    $("#exportListBtn").click(function () {
        var fromDate = $("#dateFrom").val();
        var toDate = $("#dateTo").val();
        var pro_type = $("#pro_type_ex").val();
        var url = "index.php?action=Product&mode=productExportList&pro_type="+pro_type+"&fromDate="+fromDate+"&toDate="+toDate;
        location.href = url;
        $('#modal-event1').modal("hide");
    });
});
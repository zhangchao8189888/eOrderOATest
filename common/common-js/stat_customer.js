/**
 * Created by zhangchao8189888 on 16/7/10.
 */
$(function(){
    $("#searchPro").click(function () {
        createBigData();
    });
    var nowKucunGrid = document.getElementById('dayProduceGrid');
    nowKucunGrid = new Handsontable(nowKucunGrid, {
        data: [],
        //stretchH: 'last',
        startRows: 5,
        minSpareRows: 1,
        colHeaders: ['销售时间',' Products Name产品名称','销售数','客户类别','销售价格','客户名称','销售人员','制单人','备注'],
        rowHeights: function(){return 25;},
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
            } else if (p == p2 && r== r2) {

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

        columns: [
            {data: "add_date",type: 'text'},
            {data: "pro_code",type: 'text'},
            {data: "pro_num", type: 'text'},
            {data: "pro_type_name",type: 'text'},
            {data: "order_jiner",type: 'text'},
            {data: "custo_name",type: 'text'},
            {data: "jinban_name",type: 'text'},
            {data: "zhidanren_name",type: 'text'},
            {data: "mark",type: 'text'}
        ]
    });

    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };
    $("#produceSave").click(function () {


        var formData = {
            "data": nowKucunGrid.getData(),
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

                    nowKucunGrid.loadData(errorData);
                    nowKucunGrid.updateSettings({
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
    function createBigData() {
        var customer_jingbanren = $('#customer_jingbanren').val();
        var keyword = $('#keyword').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var order_by = $('#order_by').val();
        var pro_type = $('#pro_type').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Stat&mode=getCustomerStatList",
                data: {
                    customer_jingbanren : customer_jingbanren,
                    from_date : from_date,
                    to_date : to_date,
                    sort_val : order_by,
                    pro_type : pro_type,
                    keyword : keyword

                },
                dataType: "json",
                success: function(data){
                    var jData = data.data;
                    //var sum = data.zp_sum;

                    nowKucunGrid.loadData(jData);
                    //$("#p_sum").text(sum);

                }
            }
        );
    }
    $("#js-export").click(function () {//
        $('#modal-event1').modal({show:true});
    });
    $("#exportListBtn").click(function () {
        var fromDate = $("#dateFrom").val();
        var toDate = $("#dateTo").val();
        var jingbanren = $("#jingbanren").val();
            var url = "index.php?action=Stat&mode=orderExportForSale&jingbanren="+jingbanren+"&fromDate="+fromDate+"&toDate="+toDate;
        location.href = url;
        $('#modal-event1').modal("hide");
    });
    $("#js-export-income").click(function () {//
        $('#modal-event2').modal({show:true});
    });
    $("#exportListBtn1").click(function () {
        var fromDate = $("#dateFrom1").val();
        var toDate = $("#dateTo1").val();
            var url = "index.php?action=Stat&mode=orderExportForIncomeList&fromDate="+fromDate+"&toDate="+toDate;
        location.href = url;
        $('#modal-event1').modal("hide");
    });
});

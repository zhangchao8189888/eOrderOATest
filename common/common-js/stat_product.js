/**
 * Created by zhangchao8189888 on 16-6-25.
 */

$(function(){
    $("#searchPro").click(function () {
        createBigData();
    });

    function safeHtmlRenderer(instance, td, row, col, prop, value, cellProperties) {
        var escaped = Handsontable.helper.stringify(value);
        td.innerHTML = escaped;

        return td;
    }
    var table_header = ['订单号','销售时间',' Products Name产品名称','销售数','客户类别','销售单价','销售合计','收益额','客户名称','销售人员','备注','付款方式','操作'];

    var columns = [
        {data: "order_no",type: 'text'},
        {data: "add_date",type: 'text'},
        {data: "pro_code",type: 'text'},
        {data: "pro_num", type: 'text'},
        {data: "pro_type_name",type: 'text'},
        {data: "real_per_price",type: 'text'},
        {data: "real_order_jiner",type: 'text'},
        {data: "earn",type: 'text'},
        {data: "custo_name",type: 'text'},
        {data: "jinban_name",type: 'text'},
        {data: "mark",type: 'text'},
        {data: "pay_type_name",type: 'text'},
        {data: "option",renderer: safeHtmlRenderer}
    ];
    if (GLOBAL.admin_type == 1 || GLOBAL.admin_type == 5 || GLOBAL.admin_type == 7) {//
        table_header = ['订单号','销售时间',' Products Name产品名称','销售数','客户类别','销售单价','成本单价','销售合计','成本合计','收益额','客户名称','销售人员','备注','付款方式','操作'];
        columns = [
            {data: "order_no",type: 'text'},
            {data: "add_date",type: 'text'},
            {data: "pro_code",type: 'text'},
            {data: "pro_num", type: 'text'},
            {data: "pro_type_name",type: 'text'},
            {data: "real_per_price",type: 'text'},
            {data: "cost_price",type: 'text'},
            {data: "real_order_jiner",type: 'text'},
            {data: "sum_cost",type: 'text'},
            {data: "earn",type: 'text'},
            {data: "custo_name",type: 'text'},
            {data: "jinban_name",type: 'text'},
            {data: "mark",type: 'text'},
            {data: "pay_type_name",type: 'text'},
            {data: "option",renderer: safeHtmlRenderer}
        ];

    }
    var nowKucunGrid = document.getElementById('dayProduceGrid');
    nowKucunGrid = new Handsontable(nowKucunGrid, {
        data: [],
        //stretchH: 'last',
        startRows: 5,
        minSpareRows: 1,
        colHeaders: table_header,
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

        columns: columns
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
                url: "index.php?action=Stat&mode=getProductStatList",
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
    $("#exportListBtn").click(function () {
        /*if (!$("#columns").val()) {
            $("#salForm").append('<input type="hidden" value="" name="excelData" id="excelData"/>' +
                '<input type="hidden" value="" name="head" id="head"/>' +
                '<input type="hidden" value="" name="columns" id="columns"/>')
        }
*/
        $("#excelData").val(JSON.stringify(nowKucunGrid.getData()));
        $("#head").val(JSON.stringify(nowKucunGrid.getColHeader()));
        $("#columns").val(JSON.stringify(nowKucunGrid.getSettings().columns));
        $("#salForm").attr("action","index.php?action=Stat&mode=modelExport");
        $("#salForm").submit();
        //$('#modal-event1').modal("hide");
    });
});
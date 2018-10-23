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
        minSpareRows: 1,//'当前余额',
        colHeaders: ['订单号','下单时间','客户名称','客户级别','支付前余额','实际金额','支付后余额','付款方式','付款状态','备注','操作'],
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
            {data: "order_no",type: 'text'},
            {data: "ding_date",type: 'text'},
            {data: "custer_name", type: 'text'},
            {data: "level_name", type: 'text'},
            {data: "before_val",type: 'text'},
            {data: "realChengjiaoer",type: 'text'},
            {data: "after_val",type: 'text'},
            {data: "pay_type",type: 'text'},
            {data: "pay_status",type: 'text'},
            //{data: "total_money",type: 'text'},
            {data: "mark",type: 'text'},
            {data: "option",renderer: safeHtmlRenderer}
        ]
    });

    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };
    function safeHtmlRenderer(instance, td, row, col, prop, value, cellProperties) {
        var escaped = Handsontable.helper.stringify(value);
        td.innerHTML = escaped;

        return td;
    }
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
        var keyword = $('#keyword').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var custo_level = $('#custo_level').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Stat&mode=getCustomerOrderStatList",
                data: {
                    from_date : from_date,
                    to_date : to_date,
                    custo_level : custo_level,
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

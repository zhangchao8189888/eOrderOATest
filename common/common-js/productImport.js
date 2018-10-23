/**
 * Created by zhangchao8189888 on 16-4-18.
 */
/**
 * Created by zhangchao8189888 on 15-10-2.
 */
$(function(){

    var nowKucunGrid = document.getElementById('intoStorageGrid');
    nowKucunGrid = new Handsontable(nowKucunGrid, {
        data: [],
        //stretchH: 'last',
        startRows: 5,
        minSpareRows: 1,
        colHeaders: ['序列号',' Products Name','产品名称','产区','类别','产地','级别','数量','单价','会员价','零售价','渠道价','成本价','备注'],
        rowHeights: function(){return 25;},
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
            } else if (p == p2 && r== r2) {
                var rowDate =this.getSourceDataAtRow(r);
                if (rowDate.message) {
                    $(".alert-error").show();

                    $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>' +
                        '<strong>导入失败</strong>'+rowDate.pro_name+'：'+rowDate.message);
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

        columns: [
            {data: "num",type: 'text'},
            {data: "e_name",type: 'text'},
            {data: "c_name",type: 'text'},
            {data: "pro_area",type: 'text'},
            {data: "pro_type",type: 'text'},
            {data: "pro_address", type: 'text'},
            {data: "pro_level",type: 'text'},
            {data: "pro_num",type: 'text'},
            {data: "pro_price",type: 'text'},
            {data: "vip_price",type: 'text'},
            {data: "market_price",type: 'text'},
            {data: "channel_price",type: 'text'},
            {data: "cost_price",type: 'text'},
            {data: "memo",type: 'text'}
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
                            if (col == 1){
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
});
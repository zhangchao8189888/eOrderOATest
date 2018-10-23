/**
 * Created by zhangchao on 2017/10/21.
 */
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
        colHeaders: ['客户名称','客户级别','会员卡余额','经办人','备注','操作'],
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
                    if (!parseFloat(cellDate)) cellDate = 0;
                    sum += parseFloat(cellDate);
                    hang ++;
                }
                sum = Math.round(sum*100)/100;
                $("#p_num").text(hang);
                $("#p_sum").text(sum);
            }

        },

        columns: [
            {data: "realName",type: 'text'},
            {data: "customer_level_name", type: 'text'},
            {data: "total_money", type: 'text'},
            {data: "jinbanren_name",type: 'text'},
            {data: "remarks",type: 'text'},
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

    function createBigData() {
        var keyword = $('#keyword').val();
        var custo_level = $('#custo_level').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Customer&mode=getCustomerLevelStatListJson",
                data: {
                    level : custo_level,
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

        $("#excelData").val(JSON.stringify(nowKucunGrid.getData()));
        $("#head").val(JSON.stringify(nowKucunGrid.getColHeader()));
        $("#columns").val(JSON.stringify(nowKucunGrid.getSettings().columns));
        $("#salForm").attr("action","index.php?action=Stat&mode=modelExport");
        $("#salForm").submit();
        //$('#modal-event1').modal("hide");
    });
});

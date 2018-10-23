/**
 * Created by zhangchao8189888 on 16/7/10.
 */
$(function(){
    $("#searchPro").click(function () {
        createBigData();
    });

    var table_header = ['商品名称','上月库存','当月入库数','当月删单数','当月退货数','当月销售数','当前剩余库存','销售单价','销售合计','收益额','库存总成本合计','前期销售数'];

    var columns = [
        {data: "pro_name",type: 'text'},
        {data: "last_storage",type: 'text'},
        {data: "now_into_storage", type: 'text'},
        {data: "now_del_storage", type: 'text'},
        {data: "now_return_storage", type: 'text'},
        {data: "now_out_storage", type: 'text'},
        {data: "now_storage",type: 'text'},
        {data: "order_price",type: 'text'},
        {data: "sum_order_jiner",type: 'text'},
        {data: "earn_val",type: 'text'},
        {data: "cost_sum_all",type: 'text'},
        {data: "earlySal_num",type: 'text'},
    ];
    if (GLOBAL.admin_type == 1 || GLOBAL.admin_type == 5 || GLOBAL.admin_type == 7) {//
        table_header = ['商品名称','上月库存','当月入库数','当月删单数','当月退货数','当月销售数','当前剩余库存','成本单价','成本合计','销售单价','销售合计','收益额','库存总成本合计','前期销售数'];
        columns = [
            {data: "pro_name",type: 'text'},
            {data: "last_storage",type: 'text'},
            {data: "now_into_storage", type: 'text'},
            {data: "now_del_storage", type: 'text'},
            {data: "now_return_storage", type: 'text'},
            {data: "now_out_storage", type: 'text'},
            {data: "now_storage",type: 'text'},
            {data: "cost_price",type: 'text'},
            {data: "cost_sum",type: 'text'},
            {data: "order_price",type: 'text'},
            {data: "sum_order_jiner",type: 'text'},
            {data: "earn_val",type: 'text'},
            {data: "cost_sum_all",type: 'text'},
            {data: "earlySal_num",type: 'text'},
        ];

    }

    var nowKucunGrid = document.getElementById('intoStorageGrid');
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
    function safeHtmlRenderer(instance, td, row, col, prop, value, cellProperties) {
        var escaped = Handsontable.helper.stringify(value);
        td.innerHTML = escaped;

        return td;
    }

    function createBigData() {
        var kucun_date = $('#kucun_date').val();
        var proName = $('#proName').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Product&mode=getKucunMonth",
                data: {
                    kucun_date : kucun_date,
                    proName : proName,

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

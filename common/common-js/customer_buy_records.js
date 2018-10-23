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
        colHeaders: ['客户名称','账户类别','消费类别','消费前余额','消费金额','消费后余额','消费订单号','操作人','交易时间'],//,'备注','操作'
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
            {data: "customer.realName",type: 'text'},
            {data: "account_type_name", type: 'text'},
            {data: "consume_type", type: 'text',renderer: safeHtmlRenderer},
            {data: "before_val",type: 'text'},
            {data: "deal_val",type: 'text'},
            {data: "after_val",type: 'text'},
            {data: "source_id",type: 'text'},
            {data: "admin_name",type: 'text'},
            {data: "add_time",type: 'text'},
            //{data: "remarks",type: 'text'},
            //{data: "option",renderer: safeHtmlRenderer}
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
        if(!buy_page_data.customer_id) {
            return;
        }
        var keyword = $('#keyword').val();
        var search_type = $('#search_type').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Customer&mode=getCustomerBuyListJson",
                data: {
                    search_type : search_type,
                    customer_id : buy_page_data.customer_id

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
    createBigData();
});

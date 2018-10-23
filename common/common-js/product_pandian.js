/**
 * Created by zhangchao8189888 on 16-6-12.
 */
/**
 * Created by zhangchao8189888 on 16-6-5.
 */
$(function(){

    var huiRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = '#FFFFFF';

    };
    var fontWeight = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = '#000000';

    };
    var excel_header ={};
    function createBigData() {
        var produce_date = $('#produce_date').val();
        var pro_type = $('#pro_type').val();
        var keyword = $('#keyword').val();
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Product&mode=getPandianlList",
                data: {
                    produce_date : produce_date,
                    keyword : keyword,
                    pro_type : pro_type

                },
                dataType: "json",
                success: function(data){
                    var jData = data.data;
                    productGrid.loadData(jData);
                    var head  = excel_header = data.head;
                    productGrid.updateSettings({
                        colHeaders: head
                    });

                    /*productGrid.updateSettings({
                        fixedColumnsLeft: 2
                    });*/
                    productGrid.updateSettings({
                        cells: function (row, col, prop) {
                            var cellProperties = {};
                            if (col === 0) {
                                cellProperties.width = 300;
                                cellProperties.renderer = fontWeight;
                            }else {

                                if (productGrid.getData()[row][col] > 0){
                                    //cellProperties.readOnly = true;
                                    cellProperties.renderer = fontWeight;
                                } else if(productGrid.getData()[row][col] == 0) {
                                    cellProperties.renderer = huiRenderer;
                                }
                            }
                            return cellProperties;
                        }
                    });

                }
            }
        );
    }

    $("#searchPro").click(function (){
        createBigData();
    });

    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            productGrid.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            productGrid.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    var changeList = {};
    var productGrid = document.getElementById('intoStorageGrid');
    productGrid = new Handsontable(productGrid, {
        data: createBigData(),
        startRows: 5,
        minSpareRows: 1,
        colHeaders: [],
        readOnly : true,
        manualColumnResize: true,
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
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

        }
    });
    $("#exportListBtn").click(function () {
        /*if (!$("#columns").val()) {
            $("#salForm").append('<input type="hidden" value="" name="excelData" id="excelData"/>' +
                '<input type="hidden" value="" name="head" id="head"/>' +
                '<input type="hidden" value="" name="columns" id="columns"/>')
        }
*/
        $("#excelData").val(JSON.stringify(productGrid.getData()));
        $("#head").val(JSON.stringify(productGrid.getColHeader()));
        $("#columns").val(0);
        $("#salForm").attr("action","index.php?action=Stat&mode=modelExport");
        $("#salForm").submit();
        //$('#modal-event1').modal("hide");
    });

});
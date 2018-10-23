/**
 * Created by zhangchao8189888 on 16/7/14.
 */
/**
 * Created by zhangchao8189888 on 15-4-22.
 */
$(function(){
    var colorRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = '#000000';
        var rowData = instance.getSourceDataAtRow(row);
        if (rowData.color_type == 1) {

            td.style.background = '#FF3030';
        } else if (rowData.color_type == 2) {
            td.style.background = '#838B8B';
        } else if (rowData.color_type == 3) {
            td.style.background = '#87CEFA';
        }

    };


    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'red';

    };
    var noRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = '';

    };

    var fontWeight = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = '#000000';

    };
    $("#searchPro").click(function () {
        createBigData();
    });
    function sumHang () {
        var emptyRowNum = dayProduce.countEmptyRows();
        var sumRowNum = dayProduce.countRows();
        return (sumRowNum-emptyRowNum);
    }

    var huiRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = '#FFFFFF';

    };
    function createBigData() {
        var produce_date = $('#produce_date').val();
        var bRang = 0;
        if ($("#rang").attr('checked')) {
            bRang = 1;
        }

        $.ajax(
            {
                type: "post",
                url: "index.php?action=Product&mode=getIntoStorageList",
                data: {
                    produce_date : produce_date,
                    bRang : bRang,
                    from_date : $("#from_date").val(),
                    to_date : $("#to_date").val()

                },
                dataType: "json",
                success: function(data){
                    var jData = data.data;
                    var sum = data.zp_sum;

                    dayProduce.loadData(jData);
                    $("#p_num").text(sumHang());
                    $("#p_sum").text(sum);
                    if (GLOBAL.access_name == "read") {
                        dayProduce.updateSettings({
                            //columns :columns,
                            contextMenu:false,
                            readOnly:true
                        });
                    }

                }
            }
        );
    }
    //$().change(function(){});
    //$("#dayProduceGrid").scrollTop($("#dayProduceGrid")[0].scrollHeight);
    var today = '';
    var changeList = {},saveDataList = [];
    var dayProduceGrid = document.getElementById('intoStorageGrid'),
        dayProduce;
    dayProduce = new Handsontable(dayProduceGrid, {
        data: createBigData(),
        startRows: 24,
        minSpareRows: 24,
        colHeaders: ['日期','型号', '原有库存','入库数量','入库后库存', '备   注', '市场单价', '合计'],
        rowHeaders: true,
        rowHeights: function(){return 25;},
        manualColumnResize: true,
        manualRowResize: true,
        currentRowClassName: 'currentRow',
        currentColClassName: 'currentCol',
        autoWrapRow: true,
        isEmptyRow: function (row) {
            var col, colLen, value, meta;

            for (col = 1, colLen = this.countCols(); col < colLen; col ++) {
                if(col == 1 ||col == 2 ||col == 3 ){
                    continue;
                }
                value = this.getDataAtCell(row, col);

                if (value !== '' && value !== null && typeof value !== 'undefined') {
                    if (typeof value === 'object') {
                        meta = this.getCellMeta(row, col);

                        return Handsontable.helper.isObjectEquals(this.getSchema()[meta.prop], value);
                    }
                    return false;
                }
            }

            return true;
        },
        afterCreateRow : function (index, amount) {
            var rowIndex = index-4;
            //this.selectCell(index,0);
            var coords = {};
            coords.row = dayProduce.countRows() - 1;
            coords.col = 0;
            dayProduce.view.scrollViewport(coords);
        },
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
            }
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
        },
        contextMenu: ['row_above', 'row_below', 'remove_row'],
        beforeRemoveRow:function (index,amount) {
            var delId = [];
            for (var i = 0; i < amount; i++) {
                var rowData = this.getSourceDataAtRow(index);
                if (!rowData.row_id) continue;
                delId.push(rowData.row_id);
                index++;
            }
            //删除
            var url = 'index.php?action=Produce&mode=delDayProduceById';
            var formData = {
                ids: delId,
                produce_date: $("#produce_date").val()
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
                    var rowData = this.getSourceDataAtRow(row);
                    if (col == 'pro_code') {
                        var bError = false;
                        $.ajax({
                            //url: 'php/cars.php', // commented out because our website is hosted on static GitHub Pages
                            url: 'index.php?action=Product&mode=getProductByName',
                            dataType: 'json',
                            data: {
                                query: change[val][3]
                            },
                            success: function (response) {
                                //process(JSON.parse(response.data)); // JSON.parse takes string as a argument
                                if (response.is && response.is == 1) {
                                    dayProduce.updateSettings({
                                        cell: [
                                            {row: row, col: 1, renderer: noRenderer}
                                        ]
                                    })
                                    var pro_data = response.data;
                                    if(pro_data)dayProduce.setDataAtRowProp( row, "old_storage", pro_data.pro_num, "updateData");
                                } else {
                                    dayProduce.updateSettings({
                                        cell: [
                                            {row: row, col: 1, renderer: redRenderer}
                                        ]
                                    })
                                    bError = true;
                                }

                            }
                        });
                        if (bError) {
                            return;
                        }
                    } else if (col == 'pro_num') {
                        if (rowData.row_id != null && rowData.row_id > 0) {
                            alert("原库存数据不可修改");
                            dayProduce.setDataAtRowProp( row, "pro_num", change[val][2], "updateData");
                            return;
                        } else {

                            var sum_pro_num = rowData.old_storage + change[val][3];
                            dayProduce.setDataAtRowProp( row, "new_storage", sum_pro_num, "updateData");
                        }
                    }
                    if (rowData.pro_num == null) {
                        return;
                    }
                    if (rowData.pro_code && rowData.pro_num != 0) {
                        rowData.memo = rowData.memo ? rowData.memo : '';
                        rowData.row = row;
                        rowData.col = col;
                        if (rowData.col == 'pro_code' && rowData.row_id) {
                            rowData.bChangeCode = 1;
                        }
                        changeList[row] = rowData;
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

            if(window.confirm('你确定要修改此数据吗？')){
                //alert("确定");
            }else{
                //alert("取消");

                createBigData();
                return false;
            }
            var formData = {
                "data": changeList,
                produce_date: $("#produce_date").val()
            }
            var url = 'index.php?action=Product&mode=saveDayStorage';
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
                                var rowData = dayProduce.getSourceDataAtRow(row);
                                rowData.memo = rowData.memo ? rowData.memo : '';
                                rowData.row = row;
                                rowData.col = col;
                                changeList[row] = rowData;
                                row += 1;
                                $(".alert-error").show();
                                $(".alert-error").append('<strong>第'+row+'行'+rowData.pro_code+'入库'+rowData.pro_num+'保存失败请点击保存</strong>');

                            }
                        }
                    } else {
                        $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>');
                        changeList = {};

                        if (res.success_List && res.success_List.length > 0) {
                            for(var val = 0; val < res.success_List.length; val++) {
                                var succ = res.success_List[val];
                                var  row = parseInt(succ.row);
                                var rowData = dayProduce.getSourceDataAtRow(row);
                                //dayProduce.setDataAtRowProp(row,'row_id',succ.row_id,"updateData");
                                row += 1;
                                $(".alert-success").show();
                                $(".alert-success").append('<strong>第'+row+'行'+rowData.pro_code+'入库'+rowData.pro_num+'保存成功</strong>');

                            }
                        }
                        //createBigData();
                    }
                    createBigData();
                },
                error: function () {
                    alert('保存失败，请重试');
                }
            });

        },
        columns: [
            {
                data: function(row){

                    var date = new Date();
                    var todayDate  = date.getFullYear() + "-" + date.getMonth()+ "-" + date.getDate();
                    today = $("#produce_date").val()? $("#produce_date").val() : todayDate;
                    if ($("#rang").attr('checked')) {
                        if (row['into_date']) {
                            return row['into_date'];
                        }
                    }
                    return today;
                },
                renderer : fontWeight,
                readOnly: true
            },
            {data: "pro_code",type: 'text'},
            {data: "old_storage",type: 'numeric',readOnly:true},
            {data: "pro_num",type: 'numeric'},
            {data: "new_storage",type: 'numeric',readOnly:true},
            {data: "memo", type: 'text'},
            {data: "price", type: 'text'},
            {data: "sum_price", type: 'text'}
        ]
    });
    $("#produceSave").click(function () {

        if(!changeList.length || changeList.length < 1) {
            //alert("没有要保存的");
            return;
        } else {
            delete changeList.length;
        }
        var formData = {
            "data": changeList,
            produce_date: $("#produce_date").val()
        }
        var url = 'index.php?action=Produce&mode=saveDayProduce';
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
                            var rowData = dayProduce.getSourceDataAtRow(row);
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
                            $(".alert-error").append('<strong>第'+row+'行'+rowData.worker+'生产'+rowData.product+'保存失败请点击保存</strong>');

                        }
                    }
                } else {
                    $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>');
                    changeList = {};
                    if (res.success_List && res.success_List.length > 0) {
                        for(var val = 0; val < res.success_List.length; val++) {
                            var succ = res.success_List[val];
                            var  row = parseInt(succ.row);
                            var rowData = dayProduce.getSourceDataAtRow(row);

                            dayProduce.setDataAtRowProp(row,'row_id',succ.row_id,"updateData");
                            row +=1;
                            $(".alert-success").show();
                            $(".alert-success").append('<strong>第'+row+'行'+rowData.worker+'生产'+rowData.product+'保存成功</strong>');

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
    });

    $("#export").click(function(){
        $("#data").val(dayProduce.getData());
        $("#salForm").attr("action","index.php?action=Produce&mode=test");
        $("#salForm").submit();
    });
    var checkProduct = function () {

    }
});
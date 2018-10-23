/**
 * Created by chaozhang204017 on 14-10-30.
 */
Array.prototype.remove=function(obj){
    for(var i =0;i <this.length;i++){
        var temp = this[i];
        if(!isNaN(obj)){
            temp=i;
        }
        if(temp == obj){
            for(var j = i;j <this.length;j++){
                this[j]=this[j+1];
            }
            this.length = this.length-1;
        }
    }
}
var util = {
    extend : function(oTarget, oSource, fOverwrite) {
        if (!oTarget) {
            oTarget = {};
        }

        if (!oSource) {
            return oTarget;
        }

        for (var k in oSource) {
            v = oSource[k];

            if (util.isDef(v) && (fOverwrite || !util.isDef(oTarget[k]))) {
                oTarget[k] = v;
            }
        }

        return oTarget;
    },
    isNumReg : function (o) {
        var reg = new RegExp("^[0-9]*$");
        return reg.test(o);
    },
    isDef : function(o) {
        return typeof o != 'undefined';
    },
    isNum : function(o) {
        return typeof o == 'number' && o != null;
    },
    isArray : function(o) {
        return o && (typeof(o) == 'object') && (o instanceof Array);
    },
    isStr : function(o) {
        return o && (typeof o == 'string' || o.substring);
    },
    isWinActive : function() {
        return util.STORE.__bWinActive;
    },
    wait : function(fnCond, fnCb, nTime) {
        function waitFn() {
            if (fnCond()) {
                fnCb();
            } else {
                W.setTimeout(waitFn, util.isNum(nTime) ? nTime : 100);
            }
        };

        waitFn();
    },
    delay : function(iTime) {
        var t, arg;

        if ($.isFunction(iTime)) {
            arg = [].slice.call(arguments, 0);
            t = 10;
        } else {
            arg = [].slice.call(arguments, 1);
            t = iTime;
        }

        if (arg.length > 0) {
            var fn = arg[0], obj = arg.length > 1 ? arg[1] : null, inputArg = arg.length > 2 ? [].slice.call(arg, 2) : [];

            return W.setTimeout(function() {
                fn.apply(obj || W, inputArg);
            }, t);
        }
    },
    clearDelay : function(n) {
        W.clearTimeout(n);
    }
};
var Order ={};
Order.orderList = [];
Order.tableList = [];
Order.galobal= {};
var cOrderData = function () {
    var totalMoney;
    this.getTotalMoney = function() {
        return totalMoney;
    }
    //setter
    this.setTotalMoney = function(t)
    {
        totalMoney = t;
    }

};
Order.galobal.OrderData =  new cOrderData();
var Customer = {};
Customer.oCustomer = {
    fnGetCustomerInfo : function (obj) {
        var cusObj = obj;
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Customer&mode=getCustomerByIdJson",
                async:false,
                data: cusObj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    }
                    var custoData = data.data;
                    Customer.oCustomer.info = custoData;
                    $("#customer_add").val(obj.name);
                    $("#cId").val(obj.id);
                    if (custoData.custo_level > 0 ) {

                        $("#custo_discount").val(custoData.level_discount);
                    } else {

                        $("#custo_discount").val(0);
                    }
                    if ($("#isReturn").val()) {
                        $(".refund-info").show();
                        $(".contact").html(custoData.realName);
                        $(".mobile").html(custoData.mobile);
                    }
                }
            }
        );
    }
};
Order.Table={
    fnSort : function() {
        var rowId = 0;
        $("table tr").each(function(){
            var tdIndex =$(this).children("td").eq(0);
            if (rowId > 0){
                tdIndex.html(rowId);
            }
            rowId ++;
        });
    },
    fnSumOrderTotalMoney : function () {
        var rowId = 0;
        var totalMoney = 0.00;
        $("table tr").each(function(){

            var tdMoney =$(this).children("td").eq(8);
            var money = tdMoney.html();
            if (rowId > 0) {
                if (money && money > 0){
                    totalMoney += parseFloat(money);
                }
            }

            rowId ++;
        });
        totalMoney = (totalMoney).toFixed(2);
        Order.galobal.OrderData.setTotalMoney(totalMoney);
        $(".total-money").html(totalMoney);
        if($("#discounts").attr("checked") != 'checked') {
            $(".total-rel-money").html(totalMoney);
        }

    },
    fnAddNewTr :function () {
        var trLn = $("table tr").length -1;
        var rowId = $("table tr").eq(trLn).attr("id");

        var oData = Order.tableList[rowId];
        var tr = $("table tr:last");
        if (oData) {
            tr.after('<tr id="'+Order.Table.row+'"><td style="text-align: center;"></td>' +
                '<td style="text-align: center;">' +
                '<a style="cursor: pointer;" class="icon-plus" title="新增行"></a>&nbsp' +
                '<a class="icon-minus" style="cursor: pointer;" title="删除行"></a>' +
                '</td>' +
                '<td class="product_add" style="width: 300px;height:20px;"></td>' +
                '<td class="product_num" style="text-align:center;"></td>' +
                '<td style="text-align:center;"></td>' +
                '<td style="text-align:center;"></td>' +
                '<td style="text-align:center;"></td>' +
                '<td style="text-align:right;"></td>' +
                '<td style="text-align:right;"></td>' +
                '<td></td></tr>');
            Order.Table.row++;
        }
        this.fnSort();
    },
    fnAddOrderByCode:function (codeId) {
        if (!Customer.oCustomer.info) {
            layer.msg('请先选择客户！');
            return false;
        }
        var nVal = this.fnOrderCombine(codeId,1);
        if (false === nVal) {
            return nVal;
        }
        if (nVal > 0){//is combine
            //this._fnClearTableTr(rowId);
            this.fnSumOrderTotalMoney();
            return true;
        }
        //oInput.val(obj.name);
        var tr = $("table tr:last");
        var nRowIndex = tr.attr('id');
        var tdName = tr.find('td').eq(2);
        var tdNum = tr.find('td').eq(3);
        var tdUnit = tr.find('td').eq(4);
        var tdMarketPrice = tr.find('td').eq(5);
        var tdVipPrice = tr.find('td').eq(6);
        var tdPrice = tr.find('td').eq(7);
        var tdSum = tr.find('td').eq(8);
        var obj = {};
        obj.id = codeId;
        var ajaxThis = this;
        var result = true;
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Product&mode=getProductByIdJson",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        result = false;
                        return;
                    }
                    var proData = data.data;
                    var returnFlag = $("#orderReturn").val();
                    if (proData.pro_num == 0 && !returnFlag) {
                        layer.msg(proData.pro_name+"库存为0，不能购买！",{time:1000});
                        result = false;
                        return false;
                    }
                    tdUnit.html(proData.pro_unit);
                    if (Customer.oCustomer.info.custo_level > 0) {

                        if (Customer.oCustomer.info.custo_level == 16 || Customer.oCustomer.info.custo_level == 18 || Customer.oCustomer.info.custo_level == 32|| Customer.oCustomer.info.custo_level == 25) {
                            proData.pro_price = proData.market_price;
                        } else if (Customer.oCustomer.info.custo_level == 19) {
                            proData.pro_price = proData.channel_price;
                        }  else if (Customer.oCustomer.info.custo_level == 26) {
                            proData.pro_price = proData.after_dis_price;
                        }  else if (Customer.oCustomer.info.custo_level == 27) {
                            proData.pro_price = proData.com_channel_price;
                        } else {
                            proData.pro_price = proData.vip_price;
                        }
                    } else {
                        proData.pro_price = proData.market_price;
                    }
                    if (Customer.oCustomer.info.custo_discount && Customer.oCustomer.info.custo_discount > 0) {
                        proData.pro_price = proData.pro_price * (Customer.oCustomer.info.custo_discount/100)
                    }
                    tdName.html(proData.pro_name);
                    tdMarketPrice.html(proData.market_price);
                    tdVipPrice.html(proData.vip_price);
                    tdPrice.html(proData.pro_price);
                    tdSum.html(proData.pro_price);
                    proData.inputText = obj.name;
                    proData.pro_id = proData.id;
                    proData.bay_num = tdNum;
                    Order.tableList[nRowIndex] = proData;

                    tdNum.html(1);//添加数量
                    ajaxThis.fnSumOrderTotalMoney();
                    ajaxThis.fnAddNewTr();

                }
            }
        );
        return result;
    },
    fnUserCommonPrice : function () {
        var i = 0;
        var ajaxThis = this;
        $("#tab1 tr").each(function(){
            if (i == 0) {
                i ++ ;
                return;
            }
             //console.log($(this).find("td").eq(5).text())
            var nRowIndex = $(this).attr('id');
            var price = $(this).find("td").eq(5).text();
            var nRowIndex = $(this).attr('id');
            var proData = Order.tableList[nRowIndex];
            if (price > 0 && proData) {
                $(this).find("td").eq(7).text(price);
                proData.pro_price = price;
                Order.tableList[nRowIndex] = proData;
                var proNum = $(this).find("td").eq(3).text();
                ajaxThis.fnModifyForNum(nRowIndex,proNum);
            }
        })
        //this.fnSumOrderTotalMoney();
    },
    fnBulkUsePrice : function () {
        var i = 0;
        var ajaxThis = this;
        $("#tab1 tr").each(function(){
            if (i == 0) {
                i ++ ;
                return;
            }
            var nRowIndex = $(this).attr('id');
            var proData = Order.tableList[nRowIndex];
            if (!proData) {
                return;
            }
            if (Customer.oCustomer.info.custo_level > 0) {

                if (Customer.oCustomer.info.custo_level == 16 || Customer.oCustomer.info.custo_level == 18 || Customer.oCustomer.info.custo_level == 32|| Customer.oCustomer.info.custo_level == 25) {
                    proData.pro_price = proData.market_price;
                } else if (Customer.oCustomer.info.custo_level == 19) {
                    proData.pro_price = proData.channel_price;
                }  else if (Customer.oCustomer.info.custo_level == 26) {
                    proData.pro_price = proData.after_dis_price;
                }  else if (Customer.oCustomer.info.custo_level == 27) {
                    proData.pro_price = proData.com_channel_price;
                } else {
                    proData.pro_price = proData.vip_price;
                }
            } else {
                proData.pro_price = proData.market_price;
            }
            if (proData.pro_price > 0) {
                $(this).find("td").eq(7).text(proData.pro_price);

                Order.tableList[nRowIndex] = proData;
            }
            var proNum = $(this).find("td").eq(3).text();
            ajaxThis.fnModifyForNum(nRowIndex,proNum);
        })
        //this.fnSumOrderTotalMoney();
    },
    fnAddOrder : function (obj,oInput,rowId) {
        if (!Customer.oCustomer.info) {
            layer.msg('请先选择客户！');
            return;
        }
        var nVal = this.fnOrderCombine(obj.id,1);
        if (false === nVal){
            return nVal;
        }
        if (nVal > 0){//is combine
            this._fnClearTableTr(rowId);
            this.fnSumOrderTotalMoney();
            return true;
        }
        oInput.val(obj.name);
        var tr = oInput.parent().parent();
        var nRowIndex = tr.attr('id');
        var tdNum = tr.find('td').eq(3);
        var tdUnit = tr.find('td').eq(4);
        var tdMarketPrice = tr.find('td').eq(5);
        var tdVipPrice = tr.find('td').eq(6);
        var tdPrice = tr.find('td').eq(7);
        var tdSum = tr.find('td').eq(8);
        var ajaxThis = this;
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Product&mode=getProductByIdJson",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return false;
                    }
                    var proData = data.data;
                    var returnFlag = $("#orderReturn").val();
                    if (proData.pro_num == 0 && !returnFlag) {
                        alert(proData.pro_name+"库存为0，不能购买！");
                        return false;
                    }
                    tdUnit.html(proData.pro_unit);
                    if (Customer.oCustomer.info.custo_level > 0) {

                        if (Customer.oCustomer.info.custo_level == 12 ||Customer.oCustomer.info.custo_level == 16 || Customer.oCustomer.info.custo_level == 18||Customer.oCustomer.info.custo_level == 32|| Customer.oCustomer.info.custo_level == 25|| Customer.oCustomer.info.custo_level == 34|| Customer.oCustomer.info.custo_level == 38) {
                            proData.pro_price = proData.market_price;
                        } else if (Customer.oCustomer.info.custo_level == 19) {
                            proData.pro_price = proData.channel_price;
                        }  else if (Customer.oCustomer.info.custo_level == 26) {
                            proData.pro_price = proData.after_dis_price;
                        }  else if (Customer.oCustomer.info.custo_level == 27) {
                            proData.pro_price = proData.com_channel_price;
                        } else {
                            proData.pro_price = proData.vip_price;
                        }
                    } else {
                        proData.pro_price = proData.market_price;
                    }
                    if (Customer.oCustomer.info.custo_discount && Customer.oCustomer.info.custo_discount > 0) {
                        proData.pro_price = proData.pro_price * (Customer.oCustomer.info.custo_discount/100)
                    }
                    tdMarketPrice.html(proData.market_price);
                    tdVipPrice.html(proData.vip_price);
                    tdPrice.html(proData.pro_price);
                    tdSum.html(proData.pro_price);
                    proData.inputText = obj.name;
                    proData.pro_id = proData.id;
                    proData.bay_num = tdNum;
                    Order.tableList[nRowIndex] = proData;

                    tdNum.html(1);//添加数量
                    ajaxThis.fnSumOrderTotalMoney();
                    ajaxThis.fnAddNewTr();

                }
            }
        );

    },
    fnOrderCombine : function (pid,pNum) {
        var rowIndex = this.fnFindSameOrder(pid);
        if (rowIndex && rowIndex > 0) {
            var oData = Order.tableList[rowIndex];
            var oTr = $("#"+rowIndex);
            var oTdNum = oTr.find('td').eq(3);
            var fPrice = oData.pro_price;
            var oTdSum = oTr.find('td').eq(8);
            var fSumNum = parseFloat(pNum) + parseFloat(oTdNum.html());
            var fSumJin = fSumNum * parseFloat(fPrice);
            var returnFlag = $("#orderReturn").val();
            if (parseFloat(fSumNum) > parseFloat(oData.pro_num) && !returnFlag) {
                layer.msg(oData.pro_name+"购买数量大于库存数:"+oData.pro_num+"，请调整数量！",{time:1000});
                return false;
            }
            oTdSum.html(fSumJin);
            oTdNum.html(fSumNum);

            layer.msg("列表中已经存在该商品，已经合并",{time:500});

            return 1;
        } else {
            return 0;
        }
    },
    fnFindSameOrder : function (pid) {
        for(var i=0; i< Order.tableList.length; i++) {
            var oOrderData = Order.tableList[i];
            if (oOrderData && pid == oOrderData.id) {
                return i;
            }
        }
        return 0;
    },
    fnDelTr : function (oTr) {
        var tbody = $(".tbodys");
        var tr = oTr;
        var sId = tr.attr('id');
        if (tbody.children('tr').length < 2){
            layer.msg("至少保留一行数据",{time:500});
            return false;
        }
        tr.remove();
        if (Order.tableList[sId])  {
            delete Order.tableList[sId];
            Order.Table.fnSumOrderTotalMoney();
        }

        Order.Table.fnSort();
    },
    fnModifyForNum : function (rid,fNum) {
        if (!Customer.oCustomer.info) {
            alert('请先选择客户！');
            return;
        }

        var oData = Order.tableList[rid];
        var oTr = $("#"+rid);//tr
        var oTdNum = oTr.find('td').eq(3);//td
        if (fNum < 1) {
            this.fnDelTr(oTr);
        }
        var returnFlag = $("#orderReturn").val();
        if (parseFloat(fNum) > parseFloat(oData.pro_num) && !returnFlag) {
            alert(oData.pro_name+"购买数量大于库存数:"+oData.pro_num+"，请调整数量！");
            return false;
        }
        if (Order.tableList[rid]) {

            var fPrice = oData.pro_price;
            /*if (Customer.oCustomer.info.custo_discount && Customer.oCustomer.info.custo_discount > 0) {
                fPrice = fPrice * (Customer.oCustomer.info.custo_discount/100)
            }*/
            var oTdSum = oTr.find('td').eq(8);//total money td
            var fSumNum = parseFloat(fNum);
            var fSumJin = fSumNum * parseFloat(fPrice);
            fSumJin = fSumJin.toFixed(2);
            oTdSum.html(fSumJin);//total money td
            oTdNum.html(fSumNum);//total product num
        } else {
            oTdNum.html("");
        }
        this.fnSumOrderTotalMoney();
        return true;
    },
    _fnClearTableTr : function(rowId) {
        var oTr = $("#"+rowId);//tr
        var oProductTd = oTr.find('td').eq(2);
        var oProNumTd = oTr.find('td').eq(3);
        var oUnitTd = oTr.find('td').eq(4);
        var oMarketPriceTd = oTr.find('td').eq(5);
        var oVipPriceTd = oTr.find('td').eq(6);
        var oPriceTd = oTr.find('td').eq(7);
        var oTotalJinTd = oTr.find('td').eq(8);
        oProductTd.html('');
        oProNumTd.html('');
        oUnitTd.html('');
        oMarketPriceTd.html('');
        oVipPriceTd.html('');
        oPriceTd.html('');
        oTotalJinTd.html('');
        delete Order.tableList[rowId];
    }
};
Order.UI = {};
Order.UI.SearchSelect = {

    fnInt : function () {
        this.targetTd ={};
        this.targetSuggestWrap ={};
        this.targetInput ={};
        this.left =0;
        this.top =0;
        this.key ='';
        this.inputVal ='';
        this.url ='';
        this.leftPlus ='';
        this.topPlus ='';
        this.inputWith ='';
        this.fnHideSuggest = function(){};
        this.fnMousedown = function (that,obj){};
    },

    fnSendKeyWord : function(event){
        var that = this;
        var input = that.targetInput;
        var inputOffset = input.offset();
        var suggestWrap = that.targetSuggestWrap;
        //input = $(this);
        that.left = inputOffset.left+that.leftPlus;
        that.top = inputOffset.top+that.topPlus;
        if(suggestWrap.css('display')=='block' && event.keyCode == 38 || event.keyCode == 40 || event.keyCode == 13){
            var current = suggestWrap.find('li.cmail');
            if(event.keyCode == 38){
                if(current.length>0){
                    var prevLi = current.removeClass('cmail').prev();
                    if(prevLi.length>0){
                        prevLi.addClass('cmail');
                        input.val(prevLi.html());
                    }
                }else{
                    var last = suggestWrap.find('li:last');
                    last.addClass('cmail');
                    input.val(last.html());
                }

            }else if(event.keyCode == 40){
                if(current.length>0){
                    var nextLi = current.removeClass('cmail').next();
                    if(nextLi.length>0){
                        nextLi.addClass('cmail');
                        input.val(nextLi.html());
                    }
                }else{
                    var first = suggestWrap.find('li:first');
                    first.addClass('cmail');
                    input.val(first.html());
                }
            }else if(event.keyCode == 13){
                input.val(current.html());
                that.fnHideSuggest();
            }else{
                suggestWrap.hide();
            }

            //输入字符
        }else{
            var valText = $.trim(input.val());
            if(valText ==''||valText==that.key){
                that._fnSendKeyWordToBack(valText);
            } else {
                that._fnSendKeyWordToBack(valText);
                that.key = valText;
            }

        }

    },
    _fnSendKeyWordToBack : function(keyword){
        var that = this;
        var  obj = {};
        if (!keyword) {
            obj.type= 'all';
        } else {
            obj.keyword = keyword;
        }
        $.ajax(
            {
                type: "POST",
                url: that.url,
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    var aData = [];
                    for(var i=0;i<data.length;i++){
                        var objData = {};
                        if(data[i]){
                            objData.name = data[i].name;
                            objData.id = data[i].id;
                            aData.push(objData);
                        }
                    }
                    that._fnDataDisplay(aData);
                }
            }
        );
    },
    _fnDataDisplay : function(data){
        var that = this;
        var suggestWrap = that.targetSuggestWrap;
        if(data.length<=0){
            suggestWrap.hide();
            return;
        }

        //往搜索框下拉建议显示栏中添加条目并显示
        var li;
        var tmpFrag = document.createDocumentFragment();
        suggestWrap.find('ul').html('');
        for(var i=0; i<data.length; i++){
            li = document.createElement('LI');
            li.setAttribute("data-id",data[i].id);
            li.innerHTML = data[i].name;
            tmpFrag.appendChild(li);
        }
        suggestWrap.find('ul').append(tmpFrag);
        /**display: block; width: 683px; left: 236.567px; position: absolute; top: 144px; z-index: 958;*/
        suggestWrap.attr("style","width: "+that.inputWith+"px; left: "+that.left+"px; position: absolute; top: "+that.top+"px; z-index: 958;");
        suggestWrap.show();

        //为下拉选项绑定鼠标事件
        suggestWrap.find('li').hover(function(){
            suggestWrap.find('li').removeClass('cmail');
            $(this).addClass('cmail');

        },function(){
            $(this).removeClass('cmail');
        }).mousedown(function(){
                var oProObj = {};
                oProObj.name = this.innerHTML;
                oProObj.id = this.getAttribute('data-id');
                that.fnMousedown && that.fnMousedown(that,oProObj);
            }).keydown(function (e) {
                alert(1);
            });
    }

};

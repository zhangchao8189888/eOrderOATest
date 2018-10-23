$(function(){

    Order.Table.row = 4;

    $(".icon-minus").live("click",function (){
        var tbody = $(".tbodys");
        var tr = $(this).parent().parent();
        var sId = tr.attr('id');
        if (tbody.children('tr').length < 2){
            alert('至少保留一行数据');
            return false;
        }
        tr.remove();
        if (Order.tableList[sId])  {
            delete Order.tableList[sId];
            Order.Table.fnSumOrderTotalMoney();
        }

        Order.Table.fnSort();
    });
    $(".icon-plus").live("click",function (){
        var tr = $(this).parent().parent();
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
        Order.Table.fnSort();
        Order.Table.row++;
    });
    $(".product_num").live("click",function(){

        var td=$(this);
        if(td.children('input').length>0)
            return false;
        var td_text=td.html();
        td.html("");
        var numInput=$('<input type="text">');
        numInput.width(100);
        numInput.css({"border":"none"}).val(td_text).appendTo(td);
        numInput.trigger("focus").trigger("select");
        numInput.click(function(){
            return false;
        })
        numInput.keyup(my_keyup=function(e){
            var keyCode=e.which;
            if(keyCode==27){
                numInput.val(td_text);
                td.html(td_text);
            }else if(keyCode==13){
                var new_val=numInput.val();
                td.html(new_val);
            }
        });// keyup end
        numInput.blur(function(){
            if($.trim($(this).val())==""){
                td.html(td_text);

            }else{
                var tr = td.parent();
                var rowId = tr.attr("id");
                var proNum = $(this).val();
                td.html($(this).val());
                if (proNum != td_text) {
                    var res = Order.Table.fnModifyForNum(rowId,proNum);
                    if (!res) {
                        td.html(td_text);
                    }
                }
            }


        });

    })
    /*$(".product_add").click(function (){
        alert(111);
    });*/
    $(".product_add").live("click",function(){
        //alert(11);
        var td;
        var input;
        var inputVal;
        var suggestWrap = $('#gov_search_suggest');
        var oSearchSelect = Order.UI.SearchSelect;
        /*oSearchSelect.fnInt();*/
        oSearchSelect.leftPlus = -188;
        oSearchSelect.topPlus = 50;
        oSearchSelect.inputWith = 683;
        oSearchSelect.url = 'index.php?action=Product&mode=getProductListJson';
        var fnHideSuggest = function(){
            var that = Order.UI.SearchSelect;
            var td = that.targetTd;
            var tr = td.parent();
            var sTrId = tr.attr("id");
            var oData = Order.tableList[sTrId];
            if (oData){
                td.html(oData.inputText);

            } else {
                td.html("");
            }
            that.inputVal = '';
            that.targetSuggestWrap.hide();
        }
        oSearchSelect.targetSuggestWrap = suggestWrap;
        oSearchSelect.fnHideSuggest = fnHideSuggest;
        oSearchSelect.fnMousedown = function (that,obj) {
            if (that.inputVal == obj.name) {
                that.fnHideSuggest();
            } else {
                var input = that.targetInput;
                var oTr = input.parent().parent();
                var rowId = oTr.attr('id')
                Order.Table.fnAddOrder(obj,input,rowId);
                that.fnHideSuggest();
            }
        }
        td=$(this);
        oSearchSelect.targetTd = td;
        if(td.children('input').length>0)
            return false;
        var td_text=td.html();
        td.html("");
        input=$('<input type="text" class="input_search_key gover_search_key" style="height: 40px;margin-bottom: 0px;">');
        input.css({"border":"none"}).val(td_text).appendTo(td);
        input.trigger("focus").trigger("select");
        oSearchSelect.targetInput = input;
        input.click(function(e){
            oSearchSelect.fnSendKeyWord(e);
        });
        input.keyup(
            function (e) {
                oSearchSelect.fnSendKeyWord(e);
            }
        );
        input.blur(fnHideSuggest);
        if (input.val() == '') {
            input.click();
        } else {
            inputVal = input.val();
        }
        oSearchSelect.inputVal = inputVal;


    });

    $("#customer_add").on("click",function(){
        var input;
        var inputVal;
        var suggestWrap = $('#custor_search_suggest');
        var oSearchSelect = Order.UI.SearchSelect;
        oSearchSelect.fnInt();
        oSearchSelect.leftPlus = -188;
        oSearchSelect.topPlus = 30;
        oSearchSelect.inputWith = 314;
        oSearchSelect.url = 'index.php?action=Customer&mode=getCustomerListJson';
        var fnHideSuggest = function(){
            var that = Order.UI.SearchSelect;
            that.inputVal = '';
            that.targetSuggestWrap.hide();
        }
        oSearchSelect.targetSuggestWrap = suggestWrap;
        oSearchSelect.fnHideSuggest = fnHideSuggest;
        oSearchSelect.fnMousedown = function (that,obj) {
            if (that.inputVal == obj.name) {
                that.fnHideSuggest();
            } else {
                Customer.oCustomer.fnGetCustomerInfo(obj);
                var customerData = Customer.oCustomer.info;
                customerData.coupon.mustVal = 0.00;
                customerData.coupon.monCardVal = 0.00;
                customerData.coupon.anyVal = 0.00;
                if (customerData.coupon && customerData.coupon.length > 0) {
                    $(".control-group-coupon").hide();
                    $(".back-point-account").text("0.00");
                    for (var i = 0;i < customerData.coupon.length; i ++) {
                        if (customerData.coupon[i] && customerData.coupon[i].coupon_type == 2) {
                            customerData.coupon.mustVal = customerData.coupon[i].coupon_val;
                            $(".control-group-coupon").show();
                        } else if(customerData.coupon[i] && customerData.coupon[i].coupon_type == 5){
                            customerData.coupon.monCardVal = customerData.coupon[i].coupon_val;
                            customerData.coupon[i] && $(".mon_val").text(customerData.coupon[i].coupon_val);
                        }else {
                            //customerData.coupon[i] && $(".back-point-account").text(customerData.coupon[i].coupon_val);
                            customerData.coupon.anyVal += parseFloat(customerData.coupon[i].coupon_val);
                        }
                    }
                    $(".back-point-account").text(customerData.coupon.anyVal)
                } else {
                    $(".control-group-coupon").hide();
                    $(".back-point-account").text("0.00");
                }
            }
        }
        input = $(this);
        oSearchSelect.targetInput = input;
        input.click(function(e){
            oSearchSelect.fnSendKeyWord(e);
        });
        input.keyup(
            function (e) {
                oSearchSelect.fnSendKeyWord(e);
            }
        );
        input.blur(oSearchSelect.fnHideSuggest);
        if (input.val() == '') {
            oSearchSelect.fnSendKeyWord({});
        } else {
            inputVal = input.val();
        }
        oSearchSelect.inputVal = inputVal;
    });
    $("#submit").click(function (){
        var obj = {
            ids : [],
            price : [],
            proNum : [],
            sumMoney:[],
            customerId:0,
            totalMoney:0.00,
            val_500:0,
            isUseCoupon:0,
            subVal:0,
            isOff : 0,
            realTotalMoney:0.00
        };
        obj.ids = [];
        obj.customerId = $("#cId").val();
        obj.remark = $("#remark").val();
        obj.dingDate = $("#dingDate").val();
        obj.payType = $("#payType").val();
        obj.payStatus = $("#payStatus").val();
        obj.custo_discount = $("#custo_discount").val();
        for (var i =0;i< Order.tableList.length;i++) {
            var oData = Order.tableList[i];
            if (oData) {
                obj.ids.push(oData.id);
            }
        }
        var rowId = 0;
        $("table tr").each(function(){

            var tdMoney =$(this).children("td").eq(8);
            var tdPrice =$(this).children("td").eq(7);
            var tdNum =$(this).children("td").eq(3);
            var money = tdMoney.html();
            var price = tdPrice.html();
            var proNum = tdNum.html();
            if (rowId > 0) {
                if (money != ""){
                   obj.sumMoney.push(money);
                   obj.price.push(price);
                   obj.proNum.push(proNum);
                }
            }

            rowId ++;
        });
        var totalMoney = Order.galobal.OrderData.getTotalMoney();
        obj.totalMoney = totalMoney;
        if ($("#discounts").attr("checked") == 'checked') {
            obj.isOff = 1;
        }
        if ($("#val_500").attr("checked") == 'checked') {
            obj.val_500 = 1;
            //obj.subVal = 500;
        }
        if ($("#isUseCoupon").attr("checked") == 'checked') {
            obj.isUseCoupon = 1;
            obj.subVal = $("#inp-back-point-account").val();
        }
        if ($("#isCommonPrice").attr("checked") == 'checked') {
            obj.isCommonPrice = 1;
        }
        if ($("#monthCard").attr("checked") == 'checked') {
            obj.monthCard = 1;
            obj.monthVal = $("#month_val").val();
            obj.subVal += $("#month_val").val();
        }
        if ($("#print").attr("checked") == 'checked') {
            obj.printTow = 1;
        } else {
            obj.printTow = 0;
        }
        if ($("#un_save").attr("checked") == 'checked') {
            obj.un_save = 1;
        } else {
            obj.un_save = 0;
        }
        if ($("#sms").attr("checked") == 'checked') {
            obj.sms = 1;
        } else {
            obj.sms = 0;
        }
        obj.realTotalMoney = $(".total-rel-money").html();
        if (!obj.realTotalMoney) {
            obj.realTotalMoney = totalMoney;
        }
        if ($("#useCash").attr("checked") == 'checked') {
            obj.cash_val = $("#user_cash_val").val();
            obj.addCash = 1;
        } else {
            obj.addCash = 0;
            obj.cash_val = 0;
        }
        obj.jingbanren = $("#customer_jingbanren").val();
        obj.zhidanren = $("#zhidanren").val();
        obj.roomId = $("#roomId").val();
        if ($("#discounts").attr("checked") == 'checked' && $("#remark").val() == ''){
            alert('申请特价建议填写备注信息');
            return;
        }
        if ($("#isCommonPrice").attr("checked") == 'checked' && $("#remark").val() == ''){
            alert('用零售价建议填写备注信息');
            return;
        }
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Order&mode=saveOrderListJson",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    } else {
                        window.location.href = 'index.php?action=Order&mode=toOrderPage&n='+canShu;
                        //window.location.reload();
                    }



                }
            }
        );
    });
    $("#getPdf").click(function (){
        var obj = {
            ids : [],
            price : [],
            proNum : [],
            sumMoney:[],
            customerId:0,
            totalMoney:0.00,
            isOff : 0,
            realTotalMoney:0.00
        };
        obj.ids = [];
        obj.customerId = $("#cId").val();
        obj.remark = $("#remark").val();
        obj.dingDate = $("#dingDate").val();
        obj.payType = $("#payType").val();
        obj.payStatus = $("#payStatus").val();
        obj.custo_discount = $("#custo_discount").val();
        for (var i =0;i< Order.tableList.length;i++) {
            var oData = Order.tableList[i];
            if (oData) {
                obj.ids.push(oData.id);
            }
        }
        var rowId = 0;
        $("table tr").each(function(){

            var tdMoney =$(this).children("td").eq(8);
            var tdPrice =$(this).children("td").eq(7);
            var tdNum =$(this).children("td").eq(3);
            var money = tdMoney.html();
            var price = tdPrice.html();
            var proNum = tdNum.html();
            if (rowId > 0) {
                if (money != ""){
                   obj.sumMoney.push(money);
                   obj.price.push(price);
                   obj.proNum.push(proNum);
                }
            }

            rowId ++;
        });
        var totalMoney = Order.galobal.OrderData.getTotalMoney();
        obj.totalMoney = totalMoney;
        if ($("#discounts").attr("checked") == 'checked') {
            obj.isOff = 1;
        }
        if ($("#print").attr("checked") == 'checked') {
            obj.printTow = 1;
        } else {
            obj.printTow = 0;
        }
        if ($("#sms").attr("checked") == 'checked') {
            obj.sms = 1;
        } else {
            obj.sms = 0;
        }
        obj.realTotalMoney = $(".total-rel-money").html();
        if (!obj.realTotalMoney) {
            obj.realTotalMoney = totalMoney;
        }
        if ($("#useCash").attr("checked") == 'checked') {
            obj.cash_val = $("#user_cash_val").val();
            obj.addCash = 1;
        } else {
            obj.addCash = 0;
            obj.cash_val = 0;
        }
        obj.jingbanren = $("#customer_jingbanren").val();
        obj.zhidanren = $("#zhidanren").val();
        if ($("#discounts").attr("checked") == 'checked' && $("#remark").val() == ''){
            alert('申请特价建议填写备注信息');
            return;
        }
        var data = JSON.stringify(obj);
        $("#order_data").val(data);
        $("#printform").submit();
    });
    $("#orderModify").click(function (){
        var obj = {
            ids : [],
            orderIds :[],
            price : [],
            proNum : [],
            sumMoney:[],
            customerId:0,
            totalMoney:0.00,
            isOff : 0,
            realTotalMoney:0.00
        };
        obj.ids = [];
        obj.customerId = $("#cId").val();
        obj.orderNo = $("#orderNo").val();
        obj.dingDate = $("#dingDate").val();
        obj.custo_discount = $("#custo_discount").val();
        for (var i in Order.tableList) {
            var oData = Order.tableList[i];
            if (oData.id) {
                obj.ids.push(oData.pro_id);
                oData.orderId ? oData.orderId : 0;
                obj.orderIds.push(oData.orderId);
            }
        }
        var rowId = 0;
        $("table tr").each(function(){

            var tdMoney =$(this).children("td").eq(8);
            var tdPrice =$(this).children("td").eq(7);
            var tdNum =$(this).children("td").eq(3);
            var money = tdMoney.html();
            var price = tdPrice.html();
            var proNum = tdNum.html();
            if (rowId > 0) {
                if (money != ""){
                    obj.sumMoney.push(money);
                    obj.price.push(price);
                    obj.proNum.push(proNum);
                }
            }

            rowId ++;
        });
        var totalMoney = Order.galobal.OrderData.getTotalMoney();
        obj.totalMoney = totalMoney;
        if ($("#discounts").attr("checked") == 'checked') {
            obj.isOff = 1;
        }
        obj.realTotalMoney = $(".total-rel-money").html();
        if (!obj.realTotalMoney) {
            obj.realTotalMoney = totalMoney;
        }
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Order&mode=updateOrder",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    } else {
                        window.location.href = 'index.php?action=Order&mode=toOrderPage&n='+canShu;
                        //window.location.reload();
                    }



                }
            }
        );
    });
    $("#cust_add").click(function () {
        var obj = {
            customer_name : $("#custor_name").val(),
            level_name : $("#custo_level").val(),
            money_val : $("#money_val").val(),
            coupon : $("#coupon").val(),
            card_no : $("#card_no").val(),
            customer_tel : $("#customer_moveTel").val()
        }
        if ($("#monCard").attr("checked") == 'checked') {
            obj.monCard = 1;
        }
        if ($("#val500").attr("checked") == 'checked') {
            obj.val_500 = 1;
        }
        if ($("#nian800").attr("checked") == 'checked') {
            obj.nian_800 = 1;
        }
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Order&mode=addCustomerJson",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    } else {
                        alert('添加客户成功');
                        $('#modal-event1').modal('hide');
                    }



                }
            }
        );
    });
    $("#discounts").click(function () {
        console.log($("#discounts").attr("checked"));
        if ($("#discounts").attr("checked") == 'checked') {
            $("#inp-discount-order").removeClass("ui-input-line-dis");
            $("#inp-discount-order").removeAttr("disabled");
            $("#inp-discount-order").focus();
        } else {
            $("#inp-discount-order").addClass("ui-input-line-dis");
            $("#inp-discount-order").attr("disabled","disabled");
            $(".total-rel-money").html($(".total-money").html());
            $("#inp-discount-order").val('');

        }

    });
    $("#isUseCoupon").click(function () {

        if ($("#isUseCoupon").attr("checked") == 'checked') {
            //$(".is-use-coupon-con").show();
            $("#inp-back-point-account").removeClass("ui-input-line-dis");
            $("#inp-back-point-account").removeAttr("disabled");
            $("#inp-back-point-account").focus();
        } else {
            $("#inp-back-point-account").addClass("ui-input-line-dis");
            $("#inp-back-point-account").attr("disabled","disabled");
            $("#inp-back-point-account").val("");
            //$(".is-use-coupon-con").hide();

        }

    });
    $("#isCommonPrice").click(function () {

        if ($("#isCommonPrice").attr("checked") == 'checked') {
            //$(".is-use-coupon-con").show();
            Order.Table.fnUserCommonPrice();

        } else {
            Order.Table.fnBulkUsePrice();
        }

    });
    $("#useCash").click(function () {

        var customerData = Customer.oCustomer.info;

        $(".customer_val").html(customerData.total_money);
        if ($("#useCash").attr("checked") == 'checked') {
            //$("#inp-discount-order").removeClass("ui-input-line-dis");

            $("#user_cash_val").removeAttr("disabled");
            $("#user_cash_val").focus();
        } else {
            //$("#user_cash_val").addClass("ui-input-line-dis");
            $("#user_cash_val").attr("disabled","disabled");
            $("#user_cash_val").val('');

        }

    });
    $("#monthCard").click(function () {


        if ($("#monthCard").attr("checked") == 'checked') {

            $("#month_val").removeClass("ui-input-line-dis");
            $("#month_val").removeAttr("disabled");
            $("#month_val").focus();
        } else {
            $("#month_val").removeClass("ui-input-line-dis");
            $("#month_val").attr("disabled","disabled");
            $("#month_val").val('');

        }

    });
    var getSumVal = function () {
        var totalMoney = $(".total-rel-money").text();
        var val500 = 0.00;
        if ($("#val_500").attr("checked") == 'checked') {
            val500 = 500.00;
        }
        var couponVal = $("#inp-back-point-account").val() ? $("#inp-back-point-account").val() : 0;
        var monthVal = $("#month_val").val() ? $("#month_val").val() : 0;

        totalMoney = parseFloat(totalMoney).toFixed(2);
        var realTotalMoney = totalMoney - parseFloat(val500).toFixed(2) - parseFloat(couponVal).toFixed(2) - parseFloat(monthVal).toFixed(2);
        if(realTotalMoney < 0) {
            realTotalMoney = 0.00;
        }
        $(".total-rel-money").html(realTotalMoney.toFixed(2));
    }
    $("#val_500").click(function () {

        var customerData = Customer.oCustomer.info;
        if (customerData.coupon.mustVal < 1) {
            return;
        }

        if ($("#val_500").attr("checked") == 'checked') {
            //$("#inp-discount-order").removeClass("ui-input-line-dis");
            getSumVal();

        } else {
            //$("#user_cash_val").addClass("ui-input-line-dis");
            getSumVal();
        }
        //$(".total-rel-money").html(realTotalMoney.toFixed(2));

    });
    $("#inp-discount-order").blur(function () {
        var realTotalMoney = $("#inp-discount-order").val();
        realTotalMoney = parseFloat(realTotalMoney) ? parseFloat(realTotalMoney) : 0.00;
        realTotalMoney = realTotalMoney.toFixed(2);
        $("#inp-discount-order").val(realTotalMoney);
        $(".total-rel-money").html(realTotalMoney);
        var subMoney = $("#inp-back-point-account").val();
        if (subMoney > 0.00) {
            $("#inp-back-point-account").val(0.00);
            alert("优惠券金额请重新输入！");
        }
    });
    $("#inp-back-point-account").blur(function () {
        var customerData = Customer.oCustomer.info;
        var subMoney = $("#inp-back-point-account").val();

        if (parseFloat(subMoney) > parseFloat(customerData.coupon.anyVal)) {
            layer.msg('输入金额大于优惠券'+customerData.coupon.anyVal+'！');
            $("#inp-back-point-account").val(customerData.coupon.anyVal);

        }
        getSumVal();
    });
    $("#month_val").blur(function () {
        var customerData = Customer.oCustomer.info;
        var subMoney = $("#month_val").val();

        if (parseFloat(subMoney) > parseFloat(customerData.coupon.monCardVal)) {
            layer.msg('输入金额大于优惠券'+customerData.coupon.monCardVal+'！');
            $("#month_val").val(customerData.coupon.monCardVal);
        }
        getSumVal();
    });

});

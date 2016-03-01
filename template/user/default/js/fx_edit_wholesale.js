/**
 * Created by Administrator on 2015/9/22.
 */
$(function(){
    var min_cost_price = [];
    min_cost_price[0] = [];
    min_cost_price[1] = [];
    min_cost_price[2] = [];
    min_cost_price[3] = [];
    var min_price = [];
    var max_price = [];
    min_price[0] = [];
    min_price[1] = [];
    min_price[2] = [];
    min_price[3] = [];
    max_price[0] = [];

    load_page('.app__content',load_url,{page:'edit_wholesale_content', 'id': product_id},function(){


    });

    //批量设置

    var js_batch_type2 = '';
    $('.js-batch-cost2').live('click', function() {
        js_batch_type2 = 'cost';
        $(this).parent('span').next('.js-batch-form2').html('<input type="text" class="fx-cost-price input-mini" placeholder="批发价" /> <a class="js-batch-save2" href="javascript:;">保存</a> <a class="js-batch-cancel2" href="javascript:;">取消</a><p class="help-desc"></p>');
        $(this).parent('span').next('.js-batch-form2').show();
        $(this).parent('span').hide();
    })


    $('.js-batch-price2').live('click',function(){
        js_batch_type2 = 'price';
        $(this).parent('span').next('.js-batch-form2').html('<input type="text" class="fx-min-price input-mini" placeholder="零售最低价" /> - <input type="text" class="fx-max-price input-mini" placeholder="零售最高价" /> <a class="js-batch-save2" href="javascript:;">保存</a> <a class="js-batch-cancel2" href="javascript:;">取消</a><p class="help-desc"></p>');
        $(this).parent('span').next('.js-batch-form2').show();
        $(this).parent('span').hide();
    });


    $('.js-batch-save2').live('click', function() {
        var level = $(this).closest('table').data('level'); //分销商等级
        $(this).closest('td').removeClass('manual-valid-error');
        $('.help-desc').next('.error-message').remove();
        if (js_batch_type2 == 'price') {
            var sale_min_price = $(this).closest('.js-batch-form2').children('.fx-min-price').val();
            var sale_max_price = $(this).closest('.js-batch-form2').children('.fx-max-price').val();
            var fx_cost_price = $(this).closest('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-cost2').data('batch-cost-price'); //成本价
            if (sale_min_price == '' || sale_min_price == 0 || sale_max_price == '' || sale_max_price == 0) {
                return false;
            } else if (parseFloat(sale_min_price) < 0 || !/^\d+(\.\d+)?$/.test(sale_min_price) || parseFloat(sale_max_price) < 0 || !/^\d+(\.\d+)?$/.test(sale_max_price)) {
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">零售价只能填写大于零数字</div>');
                return false;
            } else if (fx_cost_price != undefined && sale_min_price < fx_cost_price) { //零售价不能低于批发价
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">零售价不能低于批发价</div>');
                return false;
            }
            $(this).closest('table').find("input[name='sale_min_price']").val(sale_min_price);
            $(this).closest('table').find("input[name='sale_max_price']").val(sale_max_price);
            $(this).parents('.js-goods-stock').siblings('.control-group').children('.price-1').children('.controls').children('.input-prepend').children('.fx-min-price').val(sale_min_price);
            $(this).parents('.js-goods-stock').siblings('.control-group').children('.price-1').children('.controls').children('.input-prepend').children('.fx-max-price').val(sale_max_price);
            $('.fx-price-' + level).val(fx_price);
            $(this).parent('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-price2').attr('data-batch-price', fx_price);
        } else if (js_batch_type2 == 'cost') {
            var fx_price = $(this).closest('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-price2').data('batch-price');
            var cost_price = $.trim($(this).prev('.fx-cost-price').val());
            //console.log(cost_price);
            if (cost_price == '' || cost_price == 0) {
                return false;
            } else if (parseFloat(cost_price) < 0 || !/^\d+(\.\d+)?$/.test(cost_price)) {
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">批发价只能填写大于零数字</div>');
                return false;
            } else if (fx_price != undefined && cost_price > fx_price) {
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">批发价不能高于零售价</div>');
                return false;
            }
            $('.cost-price-' + level).val(cost_price);
            $(this).closest('table').find('.js-cost-price-one').val(cost_price);
            $(this).parents('.js-goods-stock').siblings('.control-group').children('.cost-price-1').children('.controls').children('.input-prepend').children('.wholesale-price-one').val(cost_price);
            $(this).parent('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-cost2').attr('data-batch-cost-price', cost_price);
        }
        $(this).parent('.js-batch-form2').hide();
        $(this).parent('.js-batch-form2').prev('.js-batch-type2').show();
        $(this).parent('.js-batch-form2').html('');
    })

    $('.js-cost-price-one').live('blur', function(){
        var fx_min_price = $(this).closest('td').next('td').find('.js-fx-min-price').val();
        if (fx_min_price != '') {
            fx_min_price = parseFloat(fx_min_price);
        } else {
            fx_min_price = 0;
        }
        $(this).parent('td').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
        if ($(this).val() != '' && !/^\d+(\.\d+)?$/.test($(this).val())) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).after('<div class="error-message">价格有误</div>');

            return false;
        } else if (fx_min_price > 0 && parseFloat($(this).val()) > fx_min_price) {

        }
        min_cost_price[0].push($(this).val());
        $("input[name='min_wholesale_price']").val(Math.min.apply(null, min_cost_price[0]));
    })

    $('.js-fx-price').live('blur', function() {
        var price = $(this).val();
        $(this).parent('td').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
        if ($(this).val() != '' && !/^\d+(\.\d+)?$/.test($(this).val())) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).after('<div class="error-message">价格有误</div>');
            return false;
        }

        var level = $(this).closest('table').data('level');
        if (level != undefined && $(this).val() != '') {
            min_price[level].push($(this).val());
            $("input[name='fx-min-price']").val(Math.min.apply(null, min_price[level]));
            $("input[name='fx-max-price']").val(Math.max.apply(null, min_price[level]));
        }
    })

    $('.js-batch-cancel').live('click',function(){
        $(this).closest('td').removeClass('manual-valid-error');
        $('.help-desc').next('.error-message').remove();
        $('.js-batch-form').hide();
        $('.js-batch-form').html('');
        $('.js-batch-type').show();
    });

    $('.js-batch-cancel2').live('click', function() {
        $(this).closest('td').removeClass('manual-valid-error');
        $(this).next('.help-desc').next('.error-message').remove();
        $(this).parent('.js-batch-form2').hide();
        $(this).parent('.js-batch-form2').prev('.js-batch-type2').show();
        $(this).parent('.js-batch-form2').html('');
    })

    $('.js-btn-save').live('click', function(){
        $('td').removeClass('manual-valid-error');
        $('.error-message').remove();
        var unified_price_setting = 1; // 供货商统一定价
        if(unified_price_setting == 1) {
            if ($('.cost-price-1 .cost-price-1:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.cost-price-1 .cost-price-1').val())) {
                layer_tips(1,'一级分销商成本价格输入有误');
                $('.cost-price-1 .cost-price-1').focus();
                return false;
            }
            if ($('.cost-price-1 .cost-price-2:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.cost-price-1 .cost-price-2').val()) || parseFloat($('.cost-price-1 .cost-price-1').val()) > parseFloat($('.cost-price-1 .cost-price-2').val())) {
                layer_tips(1,'二级分销商成本价格输入有误/不能小于一级分销商成本价');
                $('.cost-price-1 .cost-price-2').focus();

                return false;
            }
            if ($('.cost-price-1 .cost-price-3:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.cost-price-1 .cost-price-3').val()) || parseFloat($('.cost-price-1 .cost-price-2').val()) > parseFloat($('.cost-price-1 .cost-price-3').val())) {
                layer_tips(1,'三级分销商成本价格输入有误/不能小于二级分销商成本价');
                $('.cost-price-1 .cost-price-3').focus();
                return false;
            }
            $('.js-cost-price:visible').each(function(i) {
                if (!/^\d+(\.\d+)?$/.test($(this).val())) {
                    $(this).parent('td').addClass('manual-valid-error');
                    $(this).after('<div class="error-message">价格有误</div>');
                }
                if (!/^\d+(\.\d+)?$/.test($('.js-fx-price').eq(i).val())) {
                    $('.js-fx-price').eq(i).parent('td').addClass('manual-valid-error');
                    $('.js-fx-price').eq(i).after('<div class="error-message">价格有误</div>');
                }
                if (parseFloat($('.js-fx-price').eq(i).val()) < parseFloat($(this).val())) {
                    $('.js-fx-price').eq(i).parent('td').addClass('manual-valid-error');
                    $('.js-fx-price').eq(i).after('<div class="error-message">分销价不能低于成本价</div>');
                }
            });
            if ($('.price-1 .fx-price-1:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.price-1 .fx-price-1').val()) || parseFloat($('.price-1 .fx-price-1').val()) < parseFloat($('.cost-price-1 .cost-price-1').val())) {
                layer_tips(1,'一级分销商分销价格输入有误/不能低于一级分销商成本价');
                $('.price-1 .fx-price-1').focus();
                return false;
            }
            if ($('.price-1 .fx-price-2:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.price-1 .fx-price-2').val()) || parseFloat($('.price-1 .fx-price-2').val()) < parseFloat($('.cost-price-1 .cost-price-2').val())) {
                layer_tips(1,'二级分销商分销价格输入有误/不能低于二级分销商成本价');
                $('.price-1 .fx-price-2').focus();
                return false;
            }
            if ($('.price-1 .fx-price-3:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.price-1 .fx-price-3').val()) || parseFloat($('.price-1 .fx-price-3').val()) < parseFloat($('.cost-price-1 .cost-price-3').val())) {
                layer_tips(1,'三级分销商分销价格输入有误/不能低于三级分销商成本价');
                $('.price-1 .fx-price-3').focus();
                return false;
            }
        }
        if ($('.error-message').length > 0) {
            layer_tips(1,'信息填写有误，请检查');
            return false;
        }

        //库存信息
        var skus = [];
        if ($('.sku-price-1 > .table-sku-stock > tbody > .sku').length > 0) {
            $('.sku-price-1 > .table-sku-stock > tbody > .sku').each(function(i){
                var sku_id = $(this).attr('sku-id');
                var wholesale_price = $(this).find("input[name='wholesale_price']").val();
                var min_fx_price = $(this).find("input[name='sale_min_price']").val();
                var max_fx_price = $(this).find("input[name='sale_max_price']").val();
                var properties = $(this).attr('properties');
                skus[i] = {'sku_id': sku_id, 'wholesale_price': wholesale_price, 'sale_min_price': min_fx_price, 'sale_max_price': max_fx_price, 'properties': properties};
            })
        }

        var min_wholesale_price = $("input[name='min_wholesale_price']").val(); //批发价格
        var min_fx_price = $("input[name='fx-min-price']").val(); // 零售最低
        var max_fx_price = $("input[name='fx-max-price']").val(); // 零售最高

        if ($("input[name='is_recommend']:checked").length) {
            var is_recommend = $("input[name='is_recommend']:checked").val();
        }

        var product_id = $(this).attr('data-product-id');
        $.post(edit_whole_sale_url, {'product_id': product_id, 'wholesale_price': min_wholesale_price, 'sale_min_price': min_fx_price, 'sale_max_price': max_fx_price, 'is_recommend': is_recommend, 'skus': skus,  'unified_price_setting': unified_price_setting}, function(data) {
            if (data.err_code == 0) {
                $('.notifications').html('<div class="alert in fade alert-success">批发商品修改成功</div>');
                t = setTimeout('msg_hide(true, "' + data.err_msg + '")', 1000);
            } else {
                $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
                t = setTimeout('msg_hide(false, "")', 3000);
            }
        })
    })

    //库存价格
    $(".js-fx-min-price").live('focus', function(){
        $(this).parent('td').removeClass('manual-valid-error');
        $(this).next('.js-fx-max-price').next('.error-message').remove();
    })
    $(".js-fx-min-price").live('blur', function(){
        var min_fx_price = parseFloat($(this).data('min-price'));
        var max_fx_price = parseFloat($(this).next('.js-fx-max-price').data('max-price'));
        var tmp_price = parseFloat($(this).val());
        var cost_price = parseFloat($(this).closest('td').prev('td').find('.js-cost-price').val());
        var flag = false;
        if ($('.error-message').length > 0) {
            flag = true;
        }
        $(this).parent('td').removeClass('manual-valid-error');
        $(this).next('.js-fx-max-price').next('.error-message').remove();
        if (isNaN(tmp_price)) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).next('input').after('<div class="error-message">价格有误</div>');
            return false;
        } else if (cost_price > tmp_price) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).next('input').after('<div class="error-message">分销价不能低于成本价</div>');
            return false
        } else if (parseFloat(tmp_price) > parseFloat($(this).next('.js-fx-max-price').val())) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).next('input').after('<div class="error-message">无效的价格区间</div>');
            return false;
        }
        if (flag) {
            $(this).next('.js-fx-max-price').trigger('blur');
        } else {
            min_price[0].push($(this).val());
            $("input[name='fx-min-price']").val(Math.min.apply(null, min_price[0]));
        }
    })
    $(".js-fx-max-price").live('focus', function(){
        $(this).parent('td').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
    })
    $(".js-fx-max-price").live('blur', function(){
        var min_fx_price = parseFloat($(this).prev('.js-fx-min-price').data('min-price'));
        var max_fx_price = parseFloat($(this).data('max-price'));
        var tmp_price = $(this).val();
        var flag = false;
        if ($('.error-message').length > 0) {
            flag = true;
        }
        $(this).parent('td').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
        if (isNaN(tmp_price)) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).after('<div class="error-message">价格有误</div>');
            return false;
        } else if (parseFloat(tmp_price) < parseFloat($(this).prev('.js-fx-min-price').val())) {
            $(this).parent('td').addClass('manual-valid-error');
            $(this).after('<div class="error-message">无效的价格区间</div>');
            return false;
        }
        if (flag) {
            $(this).prev('.js-fx-min-price').trigger('blur');
        } else {
            max_price[0].push($(this).val());
            $("input[name='fx-max-price']").val(Math.max.apply(null, max_price[0]));
        }
    })

    $("input[name='cost_price']").live('blur', function(){
        $(this).closest('.control-group').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
        var cost_price = $(this).val();
        var fx_min_price = $("input[name='fx-min-price']").val();
        if (fx_min_price != '' && fx_min_price != undefined) {
            fx_min_price = parseFloat(fx_min_price);
        } else {
            fx_min_price = 0;
        }
        if (isNaN(cost_price)) {
            $(this).closest('.control-group').addClass('manual-valid-error');
            $(this).after('<div class="error-message">价格有误</div>');
            return false;
        } else if (fx_min_price > 0 && parseFloat(cost_price) > fx_min_price) {
            $(this).closest('.control-group').addClass('manual-valid-error');
            $(this).after('<div class="error-message">分销价不能低于成本价</div>');
            return false;
        }
    })

    $("input[name='fx-min-price']").live('focus', function(){
        $(this).closest('.control-group').removeClass('manual-valid-error');
        $(this).nextAll('input').next('.error-message').remove();
    })
    $("input[name='fx-min-price']").live('blur', function(){
        var min_fx_price = parseFloat($(this).data('min-price'));
        var max_fx_price = parseFloat($(this).nextAll("input").data('max-price'));
        var tmp_price = $(this).val();
        var cost_price = $("input[name='cost_price']").val();
        if (cost_price != '' && cost_price != undefined) {
            cost_price = parseFloat(cost_price);
        } else {
            cost_price = 0;
        }
        var flag = false;
        if ($('.error-message').length > 0) {
            flag = true;
        }
        $(this).closest('.control-group').removeClass('manual-valid-error');
        $(this).nextAll('input').next('.error-message').remove();
        if (isNaN(tmp_price)) {
            $(this).closest('.control-group').addClass('manual-valid-error');
            $(this).nextAll('input').after('<div class="error-message">价格有误</div>');
        } else if (parseFloat(tmp_price) > parseFloat($(this).nextAll('input').val())) {
            $(this).closest('.control-group').addClass('manual-valid-error');
            $(this).nextAll('input').after('<div class="error-message">无效的价格区间</div>');
        }
        if (flag) {
            $(this).nextAll("input").trigger('blur');
        }
    })
    $("input[name='fx-max-price']").live('focus', function(){
        $(this).closest('.control-group').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
    })
    $("input[name='fx-max-price']").live('blur', function(){
        var min_fx_price = parseFloat($(this).prevAll('input').data('min-price'));
        var max_fx_price = parseFloat($(this).data('max-price'));
        var tmp_price = $(this).val();
        var flag = false;
        if ($('.error-message').length > 0) {
            flag = true;
        }
        $(this).closest('.control-group').removeClass('manual-valid-error');
        $(this).next('.error-message').remove();
        if (isNaN(tmp_price)) {
            $(this).closest('.control-group').addClass('manual-valid-error');
            $(this).after('<div class="error-message">价格有误</div>');
        } else if (parseFloat(tmp_price) < parseFloat($(this).prevAll('input').val())) {
            $(this).closest('.control-group').addClass('manual-valid-error');
            $(this).after('<div class="error-message">无效的价格区间</div>');
        }
        if (flag) {
            $(this).prevAll("input").trigger('blur');
        }
    })
    $('.js-btn-cancel').live('click', function(){
        window.history.go(-1);
    })

    $('.unified-price-setting').live('click', function() {
        if ($(this).val() == 1) {
            $('.cost-price-1').removeClass('hide');
            $('.cost-price-0').addClass('hide');

            $('.sku-price-1').removeClass('hide');
            $('.sku-price-0').addClass('hide');

            $('.price-1').removeClass('hide');
            $('.price-0').addClass('hide');
        } else {
            $('.cost-price-0').removeClass('hide');
            $('.cost-price-1').addClass('hide');

            $('.sku-price-0').removeClass('hide');
            $('.sku-price-1').addClass('hide');

            $('.price-0').removeClass('hide');
            $('.price-1').addClass('hide');
        }
    })


});

function msg_hide(redirect, url) {
    if (redirect) {
        window.location.href = url;
    }
    $('.notifications').html('');
    clearTimeout(t);
}

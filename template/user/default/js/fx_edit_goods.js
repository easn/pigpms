$(function(){
	load_page('.app__content',load_url,{page:'edit_goods_content', 'id': product_id},function(){});


    //批量设置
    var js_batch_type = '';
    $('.js-batch-cost').live('click',function(){
        js_batch_type = 'cost';
        $(this).parent('span').next('.js-batch-form').html('<input type="text" class="fx-cost-price input-mini" placeholder="成本价" /> <a class="js-batch-save2" href="javascript:;">保存</a> <a class="js-batch-cancel2" href="javascript:;">取消</a><p class="help-desc"></p>');
        $(this).parent('span').next('.js-batch-form').show();
        $(this).parent('span').hide();
    });

    $('.js-batch-price').live('click',function(){
        js_batch_type = 'price';
        $(this).parent('span').next('.js-batch-form').html('<input type="text" class="fx-price input-mini" placeholder="分销价" /> <a class="js-batch-save2" href="javascript:;">保存</a> <a class="js-batch-cancel2" href="javascript:;">取消</a><p class="help-desc"></p>');
        $(this).parent('span').next('.js-batch-form').show();
        $(this).parent('span').hide();
    });

    $('.js-batch-cancel2').live('click', function() {
        $(this).closest('td').removeClass('manual-valid-error');
        $(this).next('.help-desc').next('.error-message').remove();
        $(this).parent('.js-batch-form').hide();
        $(this).parent('.js-batch-form').prev('.js-batch-type').show();
        $(this).parent('.js-batch-form').html('');
    })

    $('.js-batch-save2').live('click', function() {

        var level = $(this).closest('table').data('level'); //分销商等级
        $(this).closest('td').removeClass('manual-valid-error');
        $('.help-desc').next('.error-message').remove();
        if (js_batch_type == 'price') { //分销价
            var cost_price = parseFloat($('.cost-price-' + level).val());
            var fx_price = parseFloat($.trim($(this).prev('.fx-price').val()));

            if (fx_price == '' || fx_price == 0) {
                return false;
            } else if (parseFloat(fx_price) < 0 || !/^\d+(\.\d+)?$/.test(fx_price)) {
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">分销价只能填写大于零数字</div>');
                return false;
            } else if (cost_price != undefined && fx_price < cost_price) { //分销价低于成本价
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">分销价不能低于成本价</div>');
                return false;
            }


            $(this).closest('table').find('.js-price').val(fx_price);
            $('.fx-price-' + level).val(fx_price);
            $(this).parent('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-price2').attr('data-batch-price', fx_price);
        } else if (js_batch_type == 'cost') { //成本价
            //var fx_price = $(this).closest('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-price2').data('batch-price');
            var cost_price = $.trim($(this).prev('.fx-cost-price').val());
            //alert(cost_price);
            if (cost_price == '' || cost_price == 0) {
                return false;
            } else if (parseFloat(cost_price) < 0 || !/^\d+(\.\d+)?$/.test(cost_price)) {
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">成本价只能填写大于零数字</div>');
                return false;
            } else if (fx_price != undefined && cost_price > fx_price) {
                $(this).closest('td').addClass('manual-valid-error');
                $('.help-desc').after('<div class="error-message" style="margin-left: 60px">成本价不能高于分销价</div>');
                return false;
            }
            $('.cost-price-' + level).val(cost_price);
            $(this).closest('table').find('.js-cost-price').val(cost_price);
            $(this).parent('.js-batch-form2').prev('.js-batch-type2').children('.js-batch-cost2').attr('data-batch-cost-price', cost_price);
        }
        $(this).parent('span').hide();
        $(this).parent('.js-batch-form').prev('.js-batch-type').show();
        $(this).parent('span').next('.js-batch-form').html('');
    })


    $('.js-batch-cancel').live('click',function(){
        $('.js-batch-form').hide();
        $('.js-batch-form').html('');
        $('.js-batch-type').show();
    });

    $('.js-btn-save').live('click', function(){

        if ($('.cost-price-0 .cost-price-1:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.cost-price-0 .cost-price-1').val())) {
            layer_tips(1,'一级分销商成本价格输入有误');
            $('.cost-price-1 .cost-price-1').focus();
            return false;
        }
        if ($('.cost-price-0 .cost-price-2:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.cost-price-0 .cost-price-2').val()) || parseFloat($('.cost-price-0 .cost-price-1').val()) > parseFloat($('.cost-price-0 .cost-price-2').val())) {
            layer_tips(1,'二级分销商成本价格输入有误/不能小于一级分销商成本价');
            $('.cost-price-0 .cost-price-2').focus();

            return false;
        }
        if ($('.cost-price-0 .cost-price-3:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.cost-price-0 .cost-price-3').val()) || parseFloat($('.cost-price-0 .cost-price-2').val()) > parseFloat($('.cost-price-0 .cost-price-3').val())) {
            layer_tips(1,'三级分销商成本价格输入有误/不能小于二级分销商成本价');
            $('.cost-price-0 .cost-price-3').focus();
            return false;
        }

        if ($('.price-0 .fx-price-1:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.price-0 .fx-price-1').val()) || parseFloat($('.price-0 .fx-price-1').val()) < parseFloat($('.cost-price-0 .cost-price-1').val())) {
            layer_tips(1,'一级分销商分销价格输入有误/不能低于一级分销商成本价');
            $('.price-0 .fx-price-1').focus();
            return false;
        }
        if ($('.price-0.fx-price-2:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.price-0 .fx-price-2').val()) || parseFloat($('.price-0 .fx-price-2').val()) < parseFloat($('.cost-price-0 .cost-price-2').val())) {
            layer_tips(1,'二级分销商分销价格输入有误/不能低于二级分销商成本价');
            $('.price-0 .fx-price-2').focus();
            return false;
        }
        if ($('.price-0 .fx-price-3:visible').length > 0 && !/^\d+(\.\d+)?$/.test($('.price-0 .fx-price-3').val()) || parseFloat($('.price-0 .fx-price-3').val()) < parseFloat($('.cost-price-0 .cost-price-3').val())) {
            layer_tips(1,'三级分销商分销价格输入有误/不能低于三级分销商成本价');
            $('.price-0 .fx-price-3').focus();
            return false;
        }

        var unified_price_setting = 1; // 供货商统一定价
        //库存信息
        var skus = [];
        if ($('.table-sku-stock:eq(1) > tbody > .sku').length > 0) {
            $('.table-sku-stock:eq(1) > tbody > .sku').each(function(i){
                var drp_level = $(this).closest('table').data('level');
                var sku_id = $(this).attr('sku-id');
                var cost_price = 0;
                var min_fx_price = 0;
                var max_fx_price = 0;

                var drp_level_1_cost_price = $('.table-sku-stock-1 > tbody > .sku').eq(i).find('.js-cost-price').val();
                var drp_level_1_price      = $('.table-sku-stock-1 > tbody > .sku').eq(i).find('.js-price').val();
                var drp_level_2_cost_price = $('.table-sku-stock-2 > tbody > .sku').eq(i).find('.js-cost-price').val();
                var drp_level_2_price      = $('.table-sku-stock-2 > tbody > .sku').eq(i).find('.js-price').val();
                var drp_level_3_cost_price = $('.table-sku-stock-3 > tbody > .sku').eq(i).find('.js-cost-price').val();
                var drp_level_3_price      = $('.table-sku-stock-3 > tbody > .sku').eq(i).find('.js-price').val();

                var properties = $(this).attr('properties');
                skus[i] = {'sku_id': sku_id, 'cost_price': cost_price, 'min_fx_price': min_fx_price, 'max_fx_price': max_fx_price, 'properties': properties, 'drp_level_1_cost_price': drp_level_1_cost_price, 'drp_level_2_cost_price': drp_level_2_cost_price, 'drp_level_3_cost_price': drp_level_3_cost_price, 'drp_level_1_price': drp_level_1_price, 'drp_level_2_price': drp_level_2_price, 'drp_level_3_price': drp_level_3_price};
            });
        }


        var drp_level_1_cost_price = $('.cost-price-0 .cost-price-1').val();
        var drp_level_2_cost_price = $('.cost-price-0 .cost-price-2').val();
        var drp_level_3_cost_price = $('.cost-price-0 .cost-price-3').val();
        var drp_level_1_price = $('.price-0 .fx-price-1').val();
        var drp_level_2_price = $('.price-0 .fx-price-2').val();
        var drp_level_3_price = $('.price-0 .fx-price-3').val();


        var min_fx_price = $('.price-0 .fx-price-1').val();
        var max_fx_price = $('.price-0 .fx-price-1').val();
        var is_recommend = 0;

        if ($("input[name='is_recommend']:checked").length) {
            var is_recommend = $("input[name='is_recommend']:checked").val();
        }
        var cost_price = $('.cost_price_hidden').val();
        var product_id = $(this).attr('data-product-id');
        $.post(edit_fx_url, {'product_id': product_id,'cost_price':cost_price, 'min_fx_price': min_fx_price, 'max_fx_price': max_fx_price, 'is_recommend': is_recommend, 'skus': skus,'drp_level_1_cost_price': drp_level_1_cost_price, 'drp_level_2_cost_price': drp_level_2_cost_price, 'drp_level_3_cost_price': drp_level_3_cost_price, 'drp_level_1_price': drp_level_1_price, 'drp_level_2_price': drp_level_2_price, 'drp_level_3_price': drp_level_3_price,'unified_price_setting': unified_price_setting}, function(data) {
            if (data.err_code == 0) {
                $('.notifications').html('<div class="alert in fade alert-success">分销商品修改成功</div>');
                t = setTimeout('msg_hide(true, "' + data.err_msg + '")', 1000);
            } else {
                $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
                t = setTimeout('msg_hide(false, "")', 3000);
            }
        })
    })

    $('.js-btn-cancel').live('click', function(){
        window.history.go(-1);
    })
});

function msg_hide(redirect, url) {
    if (redirect) {
        window.location.href = url;
    }
    $('.notifications').html('');
    clearTimeout(t);
}
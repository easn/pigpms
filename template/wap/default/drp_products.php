<?php if(!defined('PIGCMS_PATH')) exit('deny access!');?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title>分销商品 - <?php echo $store['name']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
    <script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
    <script src="<?php echo TPL_URL; ?>js/drp_foundation.js"></script>
    <meta class="foundation-data-attribute-namespace"/>
    <meta class="foundation-mq-xxlarge"/>
    <meta class="foundation-mq-xlarge"/>
    <meta class="foundation-mq-large"/>
    <meta class="foundation-mq-medium"/>
    <meta class="foundation-mq-small"/>
    <script src="<?php echo TPL_URL; ?>js/drp_func.js"></script>
    <script src="<?php echo TPL_URL; ?>js/drp_common.js"></script>
</head>

<body class="body-gray">
<div class="fixed">
    <nav class="tab-bar">
        <section class="left-small"><a class="menu-icon" href="./drp_ucenter.php"><span></span></a></section>
        <section class="middle tab-bar-section"><h1 class="title">分销商品</h1></section>
    </nav>
</div>

<div class="storeedit mlr-15">
        <div class="row">
            <div class="row">
                <div class="large-12 columns">
                    <label>&nbsp;</label>
                </div>
            </div>

            <div class="tip-means mb-20 <!--mr-15-->">
                <h2 class="tip-means-title"><i class="icon-light"></i><span>温馨提示</span><i class="icon-close" onclick="tip_means_close(this)"></i></h2>
                <div class="tip-means-c">
                    <p>您的店铺下的所有分销商品，总计 <span style="color: red"><?php echo $product_count; ?></span> 件</p>
                </div>
            </div>
            <div class="row"></div>
            <div id="device" class="category gridalicious"></div>
            <div style="text-align: center">
                <a class="more" id="show_more" page="2" style="display: none;" href="javascript:void(0);">加载更多</a>
                <input type="hidden" id="canScroll" value="1"/>
            </div>
        </div>
</div>


<script src="<?php echo TPL_URL; ?>js/jquery.grid-a-licious.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#device").gridalicious({
            gutter: 10,
            width: 150,
            animationOptions: {
                speed: 150,
                duration: 400,
                complete: null
            }
        });

        //获取可以分销的商品
        $.post("./drp_products.php", {'store_id': '<?php echo $store['store_id']; ?>', 'type': 'get'}, function (data) {
            $('#device').html(data);
            $("#device").gridalicious({
                gutter: 10,
                width: 150,
                animationOptions: {
                    speed: 150,
                    duration: 400,
                    complete: null
                }
            });
        })
    });
</script>


<script type="text/javascript">
    /*---------------------加载更多--------------------*/
    var total = parseInt(<?php echo $product_count; ?>), pagesize = 20, pages = Math.ceil(total / pagesize);
    if (pages > 1) {
        var _page = $('#show_more').attr('page');
        var flag =  false;
        $(window).bind("scroll", function () {
            if ($(document).scrollTop() + $(window).height() >= $(document).height() && flag == false) {
                $('#show_more').show().html('加载中...');
                if (_page > pages) {
                    $('#show_more').show().html('没有更多了').delay(2300).slideUp(1600);
                    flag = true;
                    return;
                }
                if ($('#canScroll').val() == 0) {//不要重复加载
                    return;
                }
                $('#canScroll').attr('value', 0);
                $.post("./drp_products.php", {
                    'store_id': '<?php echo $store['store_id']; ?>',
                    'p': _page,
                    'pagesize': pagesize,
                    'type': 'get'
                }, function (data) {
                    $('#canScroll').attr('value', 1);
                    $('#show_more').hide().html('加载更多');
                    if (data) {
                        $('#show_more').attr('page', parseInt(_page) + 1);
                    }
                    _page = $('#show_more').attr('page');
                    $('#device').append(data);
                    $("#device").gridalicious({
                        gutter: 10,
                        width: 150,
                        animationOptions: {
                            speed: 150,
                            duration: 400,
                            complete: null
                        }
                    });
                })
            } else {
                flag = false;
            }
        })
    }
</script>

<?php echo $shareData;?>
</body>
</html>
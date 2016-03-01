<?php if(!defined('PIGCMS_PATH')) exit('deny access!');?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/> 
		<title>首页 - <?php echo $store_session['name']; ?> | <?php if (empty($_SESSION['sync_store'])) { ?><?php echo $config['site_name'];?><?php } else { ?>微店系统<?php } ?></title>
        <meta name="copyright" content="<?php echo $config['site_url'];?>"/>
		<link href="<?php echo TPL_URL;?>css/base.css" type="text/css" rel="stylesheet"/>
		<link href="<?php echo TPL_URL;?>css/store.css" type="text/css" rel="stylesheet"/>
        <link href="./static/css/jquery.ui.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css">
		<script type="text/javascript" src="./static/js/jquery.min.js"></script>
		<script type="text/javascript" src="./static/js/jquery.validate.js"></script>
		<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
		<script type="text/javascript" src="<?php echo TPL_URL;?>js/base.js"></script>
		<script type="text/javascript">var load_url="<?php dourl('load');?>";</script>
		<script type="text/javascript" src="<?php echo TPL_URL;?>js/store_certification.js"></script>
		<script type="text/javascript">var certification_url="<?php dourl('certification')?>"</script>
		<script src="./static/kindeditor/kindeditor.js"></script>
		<script src="./static/kindeditor/lang/zh_CN.js"></script>

	</head>
	<body class="font14 usercenter">
		<?php include display('public:header');?>
		<div class="wrap_1000 clearfix container">
			<?php if (!empty($_SESSION['drp_diy_store'])) { ?>
			<?php include display('sidebar');?>
			<?php } ?>
			<div class="app" <?php if (empty($_SESSION['drp_diy_store'])) { ?>style="width: 100%;"<?php } ?>>
				<div class="app-inner clearfix">
					<div class="app-init-container">
						<div class="nav-wrapper--app"></div>
						<div class="app__content page-showcase-dashboard" <?php if (empty($_SESSION['drp_diy_store'])) { ?>style="width: 100%;"<?php } ?>></div>
					</div>
				</div>
			</div>
		</div>
		<?php include display('public:footer');?>
		<div id="nprogress"><div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div></div>
		
	</body>
</html>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
<?php
if (count($css) > 0) {
	foreach ($css as $file) {
?>
		<link rel="stylesheet" href="<?php echo $file; ?>">
<?php
	} 
}

if (count($headerJS) > 0) {
	foreach ($headerJS as $file) {
?>
		<script src="<?php echo $file; ?>" type="text/javascript"></script>
<?php
	}
}
?>

    </head>

    <body class="<?php echo $this->router->fetch_class() . ' ' . $this->router->fetch_method(); ?>">

<?php if ($this->router->fetch_class() != 'auth' &&  $this->router->fetch_method() != 'login') { ?>

		<nav class="topmenu topmenu-horizontal topmenu-top" id="topmenu">

			<h3>Menu</h3>

<?php if ($manageContentTypes) { ?>

            <a href="/admin/ctypes">Manage Content Types</a>

<?php } ?>

<?php if ($manageContentCategories) { ?>

            <a href="/admin/categories">Manage Content Categories</a>

<?php } ?>

<?php if ($manageContentFields) { ?>

            <a href="/admin/cfields">Manage Content Fields</a>

<?php } ?>

<?php if ($manageUsers) { ?>

            <a href="/admin/users/">Manage Users</a>
            <a href="/admin/userRoles/">Manage User Roles &amp; Capabilities</a>

<?php } ?>

			<a href="#" id="close_settings_menu"><img src="/assets/admin/img/close_32.png" alt="close menu"></a>

		</nav>

<?php } ?>

    	<div id="wrapper">

<?php if ($logged) { ?>
            <header class="clearfix">

                <a href="/admin/" class="logo">
                	<img src="<?php echo $baseUrl; ?>assets/admin/img/logo.png" alt="Comune Grezzana">Comune Grezzana
                </a>

				<div class="buttons_hello clearfix">

<?php if ($role == 'admin') { ?>
					<span class="user_icon"><img src="<?php echo $baseUrl; ?>assets/admin/img/admin.png" alt="admin"></span>
<?php } else { ?>
					<span class="user_icon"><img src="<?php echo $baseUrl; ?>assets/admin/img/user.png" alt="user"></span>
<?php } ?>

					<h1>Hello <em><span class="blue"><?php echo $username; ?></span></em></h1>

					<a href="#" id="show_settings_menu" title="CMS Settings"><img src="<?php echo $baseUrl; ?>assets/admin/img/gear.png" alt="CMS settings"></a>
					<a href="/admin/auth/logout" title="Logout"><img src="<?php echo $baseUrl; ?>assets/admin/img/disconnect.png" alt="Logout"></a>
				</div>

            </header>
<?php
}
?>
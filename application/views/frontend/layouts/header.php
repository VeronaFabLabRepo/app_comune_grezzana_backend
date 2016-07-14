<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<?php if (isset($content) && $content) { ?>

			<title><?php echo $content['title'] . ' - ' . $page['title'] . ' - ' . $websiteName; ?></title>

		<?php } else if (isset($page) && $page) { ?>

			<title><?php echo (isset($page) ? $page['title'] : '') . ' - ' . $websiteName; ?></title>

		<?php } ?>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
<?php if (count($css) > 0) { ?>

	<?php foreach ($css as $file) { ?>

			<link rel="stylesheet" href="<?php echo $file; ?>">

	<?php } ?>

<?php } ?>

<?php if (count($headerJS) > 0) { ?>

	<?php foreach ($headerJS as $file) { ?>

		<script src="<?php echo $file; ?>" type="text/javascript"></script>

	<?php } ?>

<?php } ?>

    </head>

    <body class="<?php echo $this->router->fetch_class() . ' ' . $this->router->fetch_method(); ?>">

        <div id="wrapper">

            <header class="clearfix">

                <a href="/" class="logo">
                	<img src="<?php echo $baseUrl; ?>assets/admin/img/logo.png" alt="<?php echo $websiteName; ?>">Factotum
                </a>

                <nav>

<?php if ($logged) { ?>

					<a href="/logout">Log out</a>
					<a href="/change-password">Change Password</a>
					<a href="/change-email">Change Email</a>
					<a href="/unregister">Delete Account</a>

<?php } else { ?>

					<a href="/register">Registration</a>
					<a href="/login" id="login">Log in</a>

<?php } ?>

<?php if ($logged) { ?>

                    <span class="username">Hello <?php echo $username; ?></span>

<?php } ?>

                </nav>

            </header>

            <nav class="mainnav clearfix">

<?php

echo menu($menu, array('logged' => $logged));

?>
            </nav>
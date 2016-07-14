<?php if (isset($error) && $error != '') { ?>

	<h2 class="error">Uh-oh! Something went wrong.</h2>
	<p class="error"><?php echo $error; ?></p>

<?php } elseif (isset($message) && $message != '') { ?>

	<h2>User activate!</h2>
	<p><?php echo $message; ?></p>

<?php } ?>

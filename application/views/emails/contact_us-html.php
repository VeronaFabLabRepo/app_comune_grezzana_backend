<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>Contact from <?php echo $siteName; ?>!</title></head>
	<body>
		<div style="max-width: 800px; margin: 0; padding: 30px 0;">
			<table width="80%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"></td>
					<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
							You receive a contact request from <?php echo $siteName; ?>.<br />
							These are the contacts details:<br /><br />
							Email: <?php echo $email; ?><br />
							Firstname: <?php echo $firstname; ?><br />
							Lastname: <?php echo $lastname; ?><br /><br />
							Comment: <?php echo $comment; ?><br />
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
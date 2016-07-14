	</div>

	<footer>
		<p>Backend Comune di Grezzana © 2015 / A project by <a href="http://www.veronafablab.it">Verona FabLab</a></p>
	</footer>

<?php if (count($footerJS) > 0) { ?>
	
	<?php foreach ($footerJS as $file) { ?>

			<script src="<?php echo $file; ?>" type="text/javascript"></script>
	<?php } ?>

<?php } ?>

	</body>

</html>
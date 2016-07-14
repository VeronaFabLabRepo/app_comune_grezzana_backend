		</div>

		<footer>
			<p>
				Backend Comune di Grezzana © 2015 / A project by <a href="http://www.veronafablab.it">Verona FabLab</a><br>
				<!-- <a href="/admin/cms/version">Version Log - <?php echo $factotumVersion; ?></a> -->
			</p>
		</footer>

<?php
if ($this->router->fetch_class() != 'auth' &&  $this->router->fetch_method() != 'login') {

	if (count($footerJS) > 0) {
	
		foreach ($footerJS as $file) {
?>
		<script src="<?php echo $file; ?>" type="text/javascript"></script>
<?php
		}

	}

}
?>
	</body>
</html>
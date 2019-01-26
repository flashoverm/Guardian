	<footer id="footer" class="footer bg-dark">
		<div class="container text-light">
			<a class='text-light' href="<?=$config["urls"]["html"] ?>/impressum">Impressum</a> | Bei Anmerkungen oder Problemen: Email an <a class='text-white' href="mailto:guardian@thral.de?subject=Fehlerbericht%20Guardian" >guardian@thral.de</a>
		</div>
	</footer>
	<div id="overlay" style="display:none;">
 		<div class="loader"></div>
 	</div>
</body>
</html>

<script>
	function showLoader(){
		  document.getElementById("overlay").style.display = "inline";		
	}

	function hideLoader(){
		  document.getElementById("overlay").style.display = "none";		
	}
</script>
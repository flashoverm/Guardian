<?php
require_once 'inc/page_head.php';

session_start();
session_destroy();

echo "Logout erfolgreich. Zurück zum <a id='login' href='login.php'>Login</a>";
?>
<script>
	document.getElementById('login').click();
</script>
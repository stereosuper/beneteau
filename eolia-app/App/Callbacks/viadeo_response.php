<?php
@session_start();
if (isset($_GET['code'])) {
	if ($_SESSION['viadeo_state'] == $_GET['state']) {
		$_SESSION['viadeo_code'] = $_GET['code'];
	}
}
echo '<script>window.close();</script>';
?>
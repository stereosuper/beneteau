<?php
@session_start();
if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) {
	if ( $_SESSION['linkedin_state'] == $_GET['state'] ) {
		$_SESSION['linkedin_code'] = $_GET['code'];
	}
}
echo '<script>window.close();</script>';
?>
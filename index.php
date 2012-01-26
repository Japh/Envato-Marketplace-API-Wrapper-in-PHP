<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require 'Envato_marketplaces.php';
$Envato = new Envato_marketplaces();

$account_info = $Envato->new_files('codecanyon', 'plugins');

# See what we got back....
$Envato->prettyPrint($account_info);

?>
<!doctype html>
<html>
<head>
	<meta charset=utf-8>
	<title>Popular Items Last Week</title>
</head>
<body>



</body>
</html>

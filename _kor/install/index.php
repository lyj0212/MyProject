<?php
/**
 * Install i-Smart Code
 *
 * @todo 2013-02-20, constructed
 * @keyword description
 *
 * @author Shin dong-uk <uks@plani.co.kr>
 * @copyright Copyright (c) 2009, Plani
**/

define('APPPATH', '../apps/');
define('BASEPATH', FALSE);
$required_php_version = '5.1.6';
$required_mysql_version = '5.0.7';

if( ! file_exists($file_path = APPPATH.'config/database.php'))
{
	exit('The configuration file database.php does not exist.');
}

include($file_path);

if( ! isset($db) OR count($db) == 0)
{
	exit('No database connection settings were found in the database config file.');
}

$connenct_id = @mysqli_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if( ! $connenct_id)
{
	exit('Unable to connect to the database');
}

if( ! @mysqli_select_db($connenct_id, $db['default']['database']))
{
	exit('Unable to select database: '.$db['default']['database']);
}

mysqli_query($connenct_id, sprintf('SET NAMES %s COLLATE %s', $db['default']['char_set'], $db['default']['dbcollat']));

$php_version = phpversion();
$mysql_version = preg_replace('/[^0-9.].*/', '', mysqli_get_server_info($connenct_id));
$php_compat = version_compare($php_version, $required_php_version, '>=');
$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=');

if( ! $php_compat AND ! $mysql_compat)
{
	$compat = sprintf('You cannot install because i-Smart Code requires PHP version %1$s or higher and MySQL version %2$s or higher. You are running PHP version %3$s and MySQL version %4$s.', $required_php_version, $required_mysql_version, $php_version, $mysql_version);
}
elseif( ! $php_compat)
{
	$compat = sprintf('You cannot install because i-Smart Code requires PHP version %1$s or higher. You are running version %2$s.', $required_php_version, $php_version);
}
elseif ( !$mysql_compat )
{
	$compat = sprintf('You cannot install because i-Smart Code requires MySQL version %1$s or higher. You are running version %2$s.', $required_mysql_version, $mysql_version);
}

if( ! empty($compat))
{
	exit($compat);
}

if(isset($_GET['mode']))
{
	$mode = $_GET['mode'];
}
else
{
	$mode = 'init';
}

if($mode == 'install')
{
	$dbprefix = $db['default']['dbprefix'];
	$dbcharset = $db['default']['char_set'];

	include('schema.php');

	if(empty($schema))
	{
		exit('Configuration Error - Schema');
	}

	foreach($schema as $q)
	{
		mysqli_query($connenct_id, $q);
	}

	function get_sequence()
	{
		global $connenct_id, $dbprefix;
		mysqli_query($connenct_id, sprintf('INSERT INTO `%ssequence` (seq) VALUES (\'0\')', $dbprefix));
		return mysqli_insert_id($connenct_id);
	}

	$sequence = array();
	function set_match($matches)
	{
		global $sequence, $key;
		switch($matches[1])
		{
			case '{SEQ}' :
				$sequence[$key] = get_sequence();
				return $sequence[$key];
				break;
			case '{NOW}' :
				return date('Y-m-d H:i:s');
				break;
			default :
				return preg_replace_callback('/\{([a-z\-_]+)\-SEQ\}/i', create_function(
					'$sub_matches',
					'global $sequence;
					if( ! empty($sequence[$sub_matches[1]]))
					{
						return $sequence[$sub_matches[1]];
					}
					'
				)
				, $matches[1]);
				break;
		}
	}

	if( ! empty($query))
	{
		foreach($query as $key=>$array)
		{
			foreach($array as $q)
			{
				$q = preg_replace_callback('/(\{[a-z\-_]+\})/i', 'set_match', $q);
				mysqli_query($connenct_id, $q) or die(mysqli_error($connenct_id));
			}
		}
	}

	header('Location: ?mode=completed');
}
else if($mode == 'completed')
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>i-Smart Code Install</title>
	<link rel="stylesheet" href="/_res/libs/bootstrap/css/bootstrap.min.css">
	<style>
	.container-body { margin-top:50px; text-align:center; }
	.container-body p { margin-top:30px; }
	</style>
</head>
<body>
	<div class="container-body">
		<h2><a href="http://www.plani.co.kr/" target="_blank">i-Smart Code has been installed.</a></h2>
		<p><a href="/webAdmin" class="btn btn-large btn-primary">GO ADMIN SITE</a></p>
	</div>
</body>
</html>
<?php
}
else
{
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>i-Smart Code Install</title>
	<link rel="stylesheet" href="/_res/libs/bootstrap/css/bootstrap.min.css">
	<style>
	.container-body { margin-top:50px; text-align:center; }
	.container-body p { margin-top:30px; }
	</style>
</head>
<body>
	<div class="container-body">
		<h2><a href="http://www.plani.co.kr/" target="_blank">i-Smart Code</a></h2>
		<p><a href="?mode=install" class="btn btn-large btn-primary">INSTALL</a></p>
	</div>
</body>
</html>
<?php
}
?>

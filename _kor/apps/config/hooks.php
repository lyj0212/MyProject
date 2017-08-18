<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'][] = array(
	'class' => 'initialize',
	'function' => 'set_header',
	'filename' => 'initialize.php',
	'filepath' => 'hooks'
);

$hook['pre_system'][] = array(
	'class' => 'initialize',
	'function' => 'set_router',
	'filename' => 'initialize.php',
	'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
  'class'    => 'Log',
  'function' => 'setup',
  'filename' => 'log.php',
  'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
	'class' => 'initialize',
	'function' => 'setup',
	'filename' => 'initialize.php',
	'filepath' => 'hooks'
);

$hook['display_override'][] = array(
	'class' => 'layout',
	'function' => 'set_layout',
	'filename' => 'layout.php',
	'filepath' => 'hooks'
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */

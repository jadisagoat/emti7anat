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

// Back-office authentication + privilege guard: every back_office/* request (except home/index, login_validate,
// lang, logout) needs a session; level 0 sub-users are checked against `priviledge`. See application/hooks/bo_guard.php.
$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'bo_guard',
    'filename' => 'bo_guard.php',
    'filepath' => 'hooks',
    'params'   => array(),
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
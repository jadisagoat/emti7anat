<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| Practice test defaults (pre-fill the "Add practice test" form)
|--------------------------------------------------------------------------
*/
define('PT_DEFAULT_QUESTIONS',     5);
define('PT_DEFAULT_MAX_ATTEMPTS',  2);
define('PT_DEFAULT_GRADE_STYLE',   'letter');
define('PT_DEFAULT_LABEL_FIRST',   'A');
define('PT_DEFAULT_LABEL_SECOND',  'B');
define('PT_DEFAULT_LABEL_FAIL',    'F');
define('PT_DEFAULT_POINTS_FIRST',  2);
define('PT_DEFAULT_POINTS_SECOND', 1);
define('PT_DEFAULT_POINTS_FAIL',   0);
define('PT_DEFAULT_REVEAL',        'button');
define('PT_DEFAULT_PASS_PERCENT',  50);   // a result is "passed" when percent >= the test's pass mark
define('PT_DEFAULT_TIME_LIMIT',    0);    // minutes per test; 0 = no time limit
define('PT_DEFAULT_MAX_TRIES',     0);    // tries per student and test; 0 = unlimited
define('PT_DEFAULT_DISPLAY',       'all'); // 'all' = every question on one page, 'one' = one question at a time
define('PT_TIME_GRACE_SEC',        5);    // server-side tolerance after the time limit (network latency)
define('PT_DEFAULT_HINT_TEXT',     'Not quite. Read the question again and try once more.');
define('PT_ACCESS_YEARS',          1); // "Renew" in the members list adds this many years

/* End of file constants.php */
/* Location: ./application/config/constants.php */
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['officialsession'] = "home/officialsession";
$route['officialsession/(:any)'] = "home/officialsession/$1";

$route['default_controller'] = "home"; 
$route['cms'] = "back_office/home";
//$route['(:any)'] = "pages/$1";

$route['about'] = "home/about";
$route['services'] = "home/services";
$route['contactus'] = "home/contactus";
$route['news'] = "home/news";
$route['orderonline'] = "home/orderonline";
// student (members) login for the practice tests
$route['login'] = "home/login";
$route['logout'] = "home/logout";
$route['tests'] = "home/tests";
$route['profile'] = "home/profile";

$route['back_office/home/dashboard'] = "back_office/home/dashboard";
//$route['back_office/nopage'] = 'nopage';
$route['back_office/manage_project'] = "back_office/manage_project";
$route['404_override'] = 'error404';
//$route['_404'] = '';
/* End of file routes.php */
/* Location: ./application/config/routes.php */

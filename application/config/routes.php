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

$route['admin'] = "admin/index";
$route['admin/(:any)'] = "admin/$1";


// Keep the "external" controllers here, to keep the MVC standard aligned
$route['register']                     = "frontend/auth/register";
$route['registration-complete']        = "frontend/auth/registrationComplete";
$route['login']                        = "frontend/auth/login";
$route['logout']                       = "frontend/auth/logout";
$route['forgot-password']              = "frontend/auth/forgotPassword";
$route['reset-password/(:num)/(:any)'] = "frontend/auth/resetPassword/$1/$2";
$route['change-password']              = "frontend/auth/changePassword";
$route['activate-user/(:num)/(:any)']  = "frontend/auth/activateUser/$1/$2";
$route['change-email']                 = "frontend/auth/changeEmail";
$route['reset-email/(:num)/(:any)']    = "frontend/auth/resetEmail/$1/$2";
$route['send-again']                   = "frontend/auth/sendAgain";
$route['unregister']                   = "frontend/auth/unregister";


$route['([a-zA-Z0-9-_/]+)'] = "frontend/index/index/$1";

$route['default_controller'] = "frontend/index";

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
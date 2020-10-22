<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;





/***** Test Func **/
$route['test'] = "test";
$route['user_authentication'] = "User_Authentication";
$route['user_authentication/logout'] = "User_Authentication/logout";
$route['privacy-policy'] = "welcome";
$route['apple_authentication'] = "Apple_Authentication";



/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8

$route['api'] = "Rest_server";

/***** User API ****/
//$route['api/user'] ="api/user/user/";
//$route['api/user'] ="api/user/user/";

$route['api/user/users/(:num)'] ="api/user/users/id/$1";
$route['api/user/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] ="api/user/users/id/$1/format/$3$4";

/**API Auth***/
$route['api/auth/login/([a-zA-Z0-9_-]+)(.*)'] = "api/auth/login/format/$3$4";
$route['api/auth/register'] = "api/auth/register";

/** API Technician**/

//$route['api/technician/user/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] ="api/technician/users/id/$1/format/$3$4";





/**** E-Service ***/
$route['api/eservice/tech/(:num)'] = "api/eservice/tech/id/$1";
$route['api/eservice/technicians'] = "api/eservice/technicians";
$route['api/eservice/technicians/(:num)'] = "api/eservice/technicians/id/$1";
$route['api/eservice/service/(:num)'] = "api/eservice/service/id/$1";
$route['api/eservice/job/(:num)'] = "api/eservice/job/id/$1";
/***** Jobs Event *****/
$route['api/jobs/technician/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = "api/jobs/technician/id/$1/action/$3$4";
$route['api/jobs/detail/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = "api/jobs/detail/id/$1/action/$3$4";



/***** Technician ****/
$route['api/technician/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] ="api/technician/users/id/$1/format/$3$4";
$route['api/technician/all/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = "api/technician/all/id/$1/action/$3$4";
$route['api/technician/acceptjob/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = "api/technician/acceptjob/job_id/$1/action/$3$4";


/**** E-Warranty --***/
$route['api/e-warranty/validate-product'] = "api/ewarranty/valid_product";
$route['api/e-warranty/products-warranty'] = "api/ewarranty/products_warranty";




/*** Push Notification ***/

$route['push-msg'] = "send_notification/pushnotification";
$route['push-msg-2'] = "send_notification/send_notifi";
$route['test-push'] = "send_notification/index";


/*** Booking  ***/
$route['api/books/upcoming/(:num)'] ="api/books/upcoming/id/$1";
$route['api/books/upcoming_info/(:num)'] ="api/books/upcoming_info/id/$1";
$route['api/books/call/(:num)'] ="api/books/call/tech_id/$1";

/*** Core Auth***/
$route['v1/auth'] = "v1/core/auth/register";
$route['v1/auth/login'] = "v1/core/auth/login";
$route['v1/auth/login_facebook'] = "v1/core/auth/login_facebook";
$route['v1/auth/login_apple'] = "v1/core/auth/login_apple";

/*** Core User***/
$route['v1/user'] = "v1/core/user/users";
$route['v1/rating'] = "v1/core/user/rating";
$route['v1/cus_tech'] = "v1/core/user/cus_tech";
$route['v1/user/technician_online'] = "v1/core/user/technician_online";
$route['v1/user/technician_info/(:num)'] = "v1/core/user/technician_info/id/$1";
$route['v1/user/certification_info/(:num)'] = "v1/club/user/certification_info/id/$1";
$route['v1/user/check_core_account_validate'] = "v1/core/user/check_account_validate";

/*** Core Jobs ***/
$route['v1/jobs/job'] = "v1/core/jobs/job_create";
$route['v1/jobs/upload_img'] = "v1/core/jobs/upload_img";
$route['v1/jobs/cus_job_info/(:num)'] = "v1/core/jobs/job_info/id/$1";
$route['v1/jobs/cus_job_list'] = "v1/core/jobs/job_list";
$route['v1/jobs/cus_history'] = "v1/core/jobs/history";
$route['v1/jobs/job_area'] = "v1/core/jobs/job_area_tech";
$route['v1/jobs/select_tech/(:num)'] = "v1/core/jobs/job_select_tech/tech_id/$1";
$route['v1/jobs/job_cancel/(:num)'] = "v1/core/jobs/job_cancel/id/$1";
$route['v1/jobs/tech_around'] = "v1/core/jobs/tech_around";
$route['v1/jobs/tech_province'] = "v1/core/jobs/tech_province";
$route['v1/jobs/symptoms'] = "v1/core/jobs/symptoms";
$route['v1/jobs/service_list'] = "v1/core/jobs/service_list";
$route['v1/jobs/cancel_create_job/(:num)'] = "v1/core/jobs/cancel_create_job/id/$1";
$route['v1/jobs/cleaning_cost_list/(:num)'] = "v1/core/jobs/cleaning_cost_list/btu/$1";
$route['v1/jobs/service_cost_history/(:num)'] = "v1/core/jobs/service_cost_history/id/$1";
$route['v1/jobs/notify_operator/(:num)'] = "v1/core/jobs/notify_operator/id/$1";

/*** Core Device ***/
$route['v1/product/device'] = "v1/core/product/device";
$route['v1/product/ac_management'] = "v1/core/product/ac_management";
$route['v1/product/air_type/(:any)'] = "v1/core/product/air_type/serial/$1";

$route['v1/device'] = "v1/core/device";
$route['v1/device/air_type/(:any)'] = "v1/core/device/air_type/serial/$1";
$route['v1/device/rename_device/(:any)'] = "v1/core/device/rename_device/serial/$1";
$route['v1/device/family_list/(:any)'] = "v1/core/device/family_list/serial/$1";

$route['v1/device/air_info_test/(:any)'] = "v1/core/device/air_info_test/serial/$1";


/*** Core Widget***/
$route['v1/widget/widget_list/(:any)'] = "v1/core/widget/widget_list/serial/$1";
$route['v1/widget/energy_day/(:any)'] = "v1/core/widget/energy_day/serial/$1";
$route['v1/widget/energy_month/(:any)'] = "v1/core/widget/energy_month/serial/$1";
$route['v1/widget/schedule/(:any)'] = "v1/core/widget/schedule/serial/$1";
$route['v1/widget/schedule_info/(:any)/(:num)'] = "v1/core/widget/schedule_info/serial/$1/mid/$2";
$route['v1/widget/location/(:any)'] = "v1/core/widget/location/serial/$1";
$route['v1/widget/all_location'] = "v1/core/widget/all_location";
$route['v1/widget/error/(:num)'] = "v1/core/widget/error_code/id/$1";

/*** Core Manual***/
$route['v1/manual/manual_list'] = "v1/core/manual/manual_list";
$route['v1/manual/manual_info/(:num)'] = "v1/core/manual/manual_info/id/$1";

/*** Core Payment***/
$route['v1/payment/payment_token/(:num)'] = "v1/core/payment/payment_token/id/$1";
$route['v1/payment/inquiry_payment/(:num)'] = "v1/core/payment/inquiry_payment/id/$1";

/*** Club Auth***/
$route['v1/technician'] = "v1/club/auth/register";
$route['v1/technician/login'] = "v1/club/auth/login";
$route['v1/technician/login_facebook'] = "v1/club/auth/login_facebook";
$route['v1/check_version'] = "v1/club/auth/check_version";


/*** Club User***/
$route['v1/technician/info'] = "v1/club/user/index";
$route['v1/technicians'] = "v1/club/user/technician_list";
$route['v1/user/check_account_validate'] = "v1/club/user/check_account_validate";
$route['v1/user/check_saijo_certification'] = "v1/club/user/check_saijo_certification";
$route['v1/user/certification_info'] = "v1/club/user/certification_info";


/*** Club Jobs***/
$route['v1/jobs'] = "v1/club/jobs/index";
$route['v1/jobs/recommend'] = "v1/club/jobs/recommend";
$route['v1/jobs/history'] = "v1/club/jobs/history";
$route['v1/jobs/job_info/(:num)'] = "v1/club/jobs/job_info/id/$1";
$route['v1/jobs/job_accept/(:num)'] = "v1/club/jobs/job_accept/id/$1";
$route['v1/jobs/job_tech_cancel/(:num)'] = "v1/club/jobs/job_tech_cancel/id/$1";
$route['v1/jobs/job_complete/(:num)'] = "v1/club/jobs/job_complete/id/$1";
$route['v1/jobs/deny/(:num)'] = "v1/club/jobs/deny_job/id/$1";
$route['v1/jobs/deny_recommend/(:num)'] = "v1/club/jobs/deny_recommend/id/$1";
$route['v1/jobs/status_log/(:num)'] = "v1/club/jobs/status_log/id/$1";

/*** Club Notification***/
$route['v1/technician/notifications'] = "v1/club/notification";
$route['v1/technician/notification/(:num)'] = "v1/club/notification/all/id/$1";
$route['v1/technician/notification/remove/(:num)'] = "v1/club/notification/remove/id/$1";
$route['v1/technician/notification/read/(:num)'] = "v1/club/notification/read/id/$1";

/*** Club Error Code***/
$route['v1/technician/errors'] = "v1/club/error_code/errors_list";
$route['v1/technician/error/(:num)'] = "v1/club/error_code/error_code/id/$1";

/*** Password ***/
$route['v1/password'] = "v1/club/user/password";
$route['v1/forgot_password_club'] = "v1/club/user/forgot_password";
$route['v1/forgot_password_core'] = "v1/core/user/forgot_password";

/*** E-Warranty ***/
$route['v1/technician/ewarranty/validate'] = "v1/club/ewarranty/tech_valid_product";
$route['v1/technician/ewarranty/warranty_info'] = "v1/club/ewarranty/tech_warranty_info";

/*** Summary ***/
$route['v1/summary/(:num)'] = "v1/club/summary/index/id/$1";
$route['v1/summary/form_old/(:num)'] = "v1/club/summary/form_old/id/$1";
$route['v1/summary/upload_pic/(:num)/(:any)'] = "v1/club/summary/upload_pic/id/$1/serial/$2";
$route['v1/summary/upload_pic_old/(:num)'] = "v1/club/summary/upload_pic_old/id/$1";

/*** Logout ***/
$route['v1/logout'] = "v1/club/auth/logout";


/* Test */
$route['test_msg'] = "test_msg";
$route['firebase_fb'] = "Firebase_Fb";

$route['login'] = 'login';
$route['logout'] = 'login/logout';
$route['job-list'] = 'dashboard/job/job_list';
$route['job-list/form/(:num)'] = 'dashboard/job/job_list/job_form/$id/$1';
$route['job-install'] = 'dashboard/job/job_install';
$route['job-install/form/(:num)'] = 'dashboard/job/job_install/install_form/$id/$1';
$route['user-list'] = 'dashboard/system/user_list';
$route['user-list/form/(:num)'] = 'dashboard/system/user_list/user_form/$id/$1';
$route['user-list/form'] = 'dashboard/system/user_list/user_form';
$route['technician-list'] = 'dashboard/technician/technician_list';
$route['technician-list/form/(:num)'] = 'dashboard/technician/technician_list/technician_form/$id/$1';
$route['technician-verify'] = 'dashboard/technician/technician_list/technician_verify';
$route['technician-verify/form'] = 'dashboard/technician/technician_list/technician_verify_form';
$route['technician-verify/form/(:num)'] = 'dashboard/technician/technician_list/technician_verify_form/$id/$1';
$route['dealer-list'] = 'dashboard/technician/technician_list/dealer_list';
$route['dealer-list/form'] = 'dashboard/technician/technician_list/dealer_form';
$route['dealer-list/form/(:num)'] = 'dashboard/technician/technician_list/dealer_form/$id/$1';
$route['financial-list'] = 'dashboard/financial/financial_list';
$route['invoice-form/(:num)'] = 'dashboard/financial/financial_list/invoice_form/$id/$1';
$route['financial-report'] = 'dashboard/financial/financial_list/financial_report';
$route['financial-export'] = 'dashboard/financial/financial_list/financial_export';
$route['customer-list'] = 'dashboard/customer/customer_list';
$route['customer-list/form/(:num)'] = 'dashboard/customer/customer_list/customer_form/$id/$1';
$route['api/service-cost'] = 'dashboard/system/api/service_cost';
$route['api/service-cost/form'] = 'dashboard/system/api/service_cost_form';
$route['api/service-cost/form/(:num)'] = 'dashboard/system/api/service_cost_form/$id/$1';
$route['token'] = 'dashboard/token';
$route['api/service-type'] = 'dashboard/system/api/service_type';
$route['api/service-type/form'] = 'dashboard/system/api/service_type_form';
$route['api/service-type/form/(:num)'] = 'dashboard/system/api/service_type_form/$id/$1';
$route['api/problems'] = 'dashboard/system/api/problems';
$route['api/problems/form'] = 'dashboard/system/api/problems_form';
$route['api/problems/form/(:num)'] = 'dashboard/system/api/problems_form/$id/$1';
$route['claim'] = 'dashboard/claim/claim';
$route['claim/summary'] = 'dashboard/claim/claim/summary';
$route['claim/check'] = 'dashboard/claim/claim/check';
$route['claim/add_form'] = 'dashboard/claim/claim/add_form';
$route['line_login'] = 'dashboard/line_login/login';
$route['line_login/line_login_callback'] = 'dashboard/line_login/login/line_login_callback';
$route['job-claim'] = 'dashboard/job/job_claim';
$route['job-claim/form/(:num)'] = 'dashboard/job/job_claim/claim_form/$id/$1';
$route['job-claim-export'] = 'dashboard/job/job_claim/export';
$route['job-claim-print/(:num)'] = 'dashboard/job/job_claim/claim_form_print/$id/$1';
$route['job-claim-manual'] = 'dashboard/job/job_claim/manual';

$route['warranty'] = 'dashboard/warranty/warranty';
$route['warranty/warranty_info'] = 'dashboard/warranty/warranty/warranty_info';

$route['phpinfo'] = 'phpinfo';


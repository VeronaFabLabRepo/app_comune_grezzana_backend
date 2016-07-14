<?php
/*
|--------------------------------------------------------------------------
| Website details and basic configuration
|
| These details are used in emails sent by authentication library.
|--------------------------------------------------------------------------
*/
$config['version']         = '1.3.0';
$config['website_name']    = 'Comune Grezzana';
$config['webmaster_email'] = 'info@veronafablab.it';

/*
|--------------------------------------------------------------------------
| Security settings
|
| The library uses PasswordHash library for operating with hashed passwords.
| 'phpass_hash_portable' = Can passwords be dumped and exported to another server. If set to FALSE then you won't be able to use this database on another server.
| 'phpass_hash_strength' = Password hash strength.
|--------------------------------------------------------------------------
*/
$config['phpass_hash_portable'] = FALSE;
$config['phpass_hash_strength'] = 8;
$config['salt']   = 'nU9a=QraPNPt';
$config['pepper'] = 'BEQ?HhESsDWL';

/*
|--------------------------------------------------------------------------
| Registration settings
|
| 'allow_registration' = Registration is enabled or not
| 'captcha_registration' = Registration uses CAPTCHA
| 'email_activation' = Requires user to activate their account using email after registration.
| 'email_activation_expire' = Time before users who don't activate their account getting deleted from database. Default is 48 hours (60*60*24*2).
| 'email_account_details' = Email with account details is sent after registration (only when 'email_activation' is FALSE).
| 'use_username' = Username is required or not.
|
| 'username_min_length' = Min length of user's username.
| 'username_max_length' = Max length of user's username.
| 'password_min_length' = Min length of user's password.
| 'password_max_length' = Max length of user's password.
|--------------------------------------------------------------------------
*/
$config['allow_registration']      = TRUE;
$config['captcha_registration']    = FALSE;
$config['email_activation']        = TRUE;
$config['email_activation_expire'] = 60 * 60 * 24 * 2;
$config['email_account_details']   = TRUE;
$config['use_username']            = TRUE;

$config['username_min_length'] = 4;
$config['username_max_length'] = 20;
$config['password_min_length'] = 6;
$config['password_max_length'] = 20;

/*
|--------------------------------------------------------------------------
| Login settings
|
| 'login_by_username' = Username can be used to login.
| 'login_by_email' = Email can be used to login.
| You have to set at least one of 2 settings above to TRUE.
| 'login_by_username' makes sense only when 'use_username' is TRUE.
|
| 'login_record_ip' = Save in database user IP address on user login.
| 'login_record_time' = Save in database current time on user login.
|
| 'login_count_attempts' = Count failed login attempts.
| 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
| 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60*60*24).
|--------------------------------------------------------------------------
*/
$config['login_by_username']    = TRUE;
$config['login_by_email']       = TRUE;
$config['login_record_ip']      = TRUE;
$config['login_record_time']    = TRUE;
$config['login_count_attempts'] = TRUE;
$config['login_max_attempts']   = 5;
$config['login_attempt_expire'] = 60 * 60 * 24;

/*
|--------------------------------------------------------------------------
| Auto login settings
|
| 'autologin_cookie_name' = Auto login cookie name.
| 'autologin_cookie_life' = Auto login cookie life before expired. Default is 2 months (60*60*24*31*2).
|--------------------------------------------------------------------------
*/
$config['autologin_cookie_name'] = 'autologin';
$config['autologin_cookie_life'] = 60 * 60 * 24 * 31 * 2;

/*
|--------------------------------------------------------------------------
| Forgot password settings
|
| 'forgot_password_expire' = Time before forgot password key become invalid. Default is 15 minutes (60*15).
|--------------------------------------------------------------------------
*/
$config['forgot_password_expire'] = 60 * 15;

/*
|--------------------------------------------------------------------------
| Captcha
|
| You can set captcha that created by Auth library in here.
| 'captcha_path' = Directory where the catpcha will be created.
| 'captcha_fonts_path' = Font in this directory will be used when creating captcha.
| 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_grid' = Show grid in created captcha.
| 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_case_sensitive' = Captcha case sensitive or not.
|--------------------------------------------------------------------------
*/
$config['captcha_path']           = '/captcha/';
$config['captcha_fonts_path']     = '/assets/admin/fonts/Corbert_Regular_Regular.ttf';
$config['captcha_width']          = 325;
$config['captcha_height']         = 100;
$config['captcha_font_size']      = 22;
$config['captcha_grid']           = FALSE;
$config['captcha_expire']         = 180;
$config['captcha_case_sensitive'] = FALSE;

/*
|--------------------------------------------------------------------------
| reCAPTCHA
|
| 'use_recaptcha' = Use reCAPTCHA instead of common captcha
| You can get reCAPTCHA keys by registering at http://recaptcha.net
|--------------------------------------------------------------------------
*/
$config['use_recaptcha']         = FALSE;
$config['recaptcha_public_key']  = '6LfdouYSAAAAAGscYNDfoWpPedpTr_QWVsXDfja1';
$config['recaptcha_private_key'] = '6LfdouYSAAAAAC-hammO07ywUz7nZjnZMYpTKU4e';

/*
|--------------------------------------------------------------------------
| Database settings
|
| 'db_table_prefix' = Table prefix that will be prepended to every table name used by the library
| (except 'ci_sessions' table).
|--------------------------------------------------------------------------
*/
$config['db_table_prefix'] = '';

/*
|--------------------------------------------------------------------------
| Debug settings
|
| 'profiler' = Activate the codeigniter profiler
|--------------------------------------------------------------------------
*/
$config['profiler'] = FALSE;

/*
|--------------------------------------------------------------------------
| CMS settings
|
| 'content_attachment_tbl' = The content attachment table name
| 'content_category_tbl'   = The content category table name
| 'content_field_tbl'      = The content field table name
| 'content_tbl'            = The content table name
| 'content_type_tbl'       = The content type table name
| 'content_value_tbl'      = The content value table name
| 'login_attempt_tbl'      = The login attempt table name
| 'user_tbl'               = The user table name
| 'user_autologin_tbl'     = The user autologin table name
| 'user_profile_tbl'       = The user profile type table name
| 'user_role_tbl'          = The user role value table name
| 'upload_path'             = The path where to store the files
| 'tmp_upload_path'        = The path to the temporary folder where storing files
| 'images_url'             = The public URL to reach the images
| 'field_types'            = The list of possible fields
| 'image_operations'       = The list of possible image operation
| 'image_quality'          = The image quality value
|--------------------------------------------------------------------------
*/
$config['content_attachments_tbl'] = 'fm_content_attachments';
$config['categories_tbl']          = 'fm_categories';
$config['content_categories_tbl']  = 'fm_content_categories';
$config['content_fields_tbl']      = 'fm_content_fields';
$config['contents_tbl']            = 'fm_contents';
$config['content_types_tbl']       = 'fm_content_types';
$config['content_values_tbl']      = 'fm_content_values';
$config['login_attempts_tbl']      = 'fm_login_attempts';
$config['users_tbl']               = 'fm_users';
$config['user_autologins_tbl']     = 'fm_user_autologins';
$config['user_capabilities_tbl']   = 'fm_user_capabilities';
$config['user_profiles_tbl']       = 'fm_user_profiles';
$config['user_roles_tbl']          = 'fm_user_roles';

$config['content_columns'] = array(
	  'id'
	, 'id_content_type'
	, 'id_user'
	, 'status'
	, 'title'
	, 'relative_path'
	, 'absolute_path'
	, 'lang'
	, 'data_insert'
	, 'data_last_update'
	, 'order_no'
);

$config['upload_path']     = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
$config['tmp_upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/uploads/tmp';
$config['images_url']      = '/uploads/';

$config['field_types'] = array(
	  'text'                     => 'Text'
	, 'textarea'                 => 'Textarea'
	, 'xhtml_textarea'           => 'XHTML Textarea'
	, 'select'                   => 'Select'
	, 'multiselect'              => 'MultiSelect'
	, 'radio'                    => 'Radio'
	, 'checkbox'                 => 'Checkbox'
	, 'multicheckbox'            => 'Multi Checkbox'
	, 'date'                     => 'Date'
	, 'datetime'                 => 'Date And Time'
	, 'image_upload'             => 'Image Upload'
	, 'file_upload'              => 'File Upload'
	, 'gallery'                  => 'Gallery'
	, 'linked_content'           => 'Linked Content'
	, 'multiple_linked_content'  => 'Multiple Linked Content'
);

$config['image_operations'] = array(
	  'resize'       => 'Resize'
	, 'crop'         => 'Crop'
	, 'resize_crop'  => 'Resize And Crop'
);

$config['image_quality'] = 90;


$config['langs'] = array(
	'en' => 'English',
	// 'it' => 'Italiano',
);

$config['default_lang'] = key($config['langs']);

/*
|--------------------------------------------------------------------------
| Textarea Uploader Settings
|
| 'txt_upl_img_path'      = The list of possible fields ==> RELATIVE TO THE URL
| 'txt_upl_allowed_types' = The allowed image types
| 'txt_upl_max_size'      = The max file size  in kilobytes of the uploaded file, if 0, use the php.ini default value
| 'txt_upl_max_width'     = Maximum image width. Set to 0 for no limit
| 'txt_upl_max_height'    = Maximum image height. Set to 0 for no limit
| 'txt_upl_allow_resize'  = Maximum image height. Set to 0 for no limit
| 'txt_upl_encrypt_name'  = If set to `TRUE`, image file name will be encrypted in something like 7fdd57742f0f7b02288feb62570c7813.jpg
| 'txt_upl_overwrite'     = How to behave if 2 or more files with the same name are uploaded:, If TRUE` overwrite, if FALSE a number to the new file name will be added
|
|--------------------------------------------------------------------------
*/

$config['txt_upl_img_path']      = '/uploads/tmp';
$config['txt_upl_allowed_types'] = 'gif|jpg|png';
$config['txt_upl_max_size']      = 0;
$config['txt_upl_max_width']     = 0;
$config['txt_upl_max_height']    = 0;
$config['txt_upl_allow_resize']  = TRUE;
$config['txt_upl_encrypt_name']  = TRUE;
$config['txt_upl_overwrite']     = TRUE;



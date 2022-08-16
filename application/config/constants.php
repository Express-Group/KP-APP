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
		
define('source_base_path', '/images/kp_images/');
define('destination_base_path', '/images/kp_images/');
			
define('gallery_temp_image_path', 'uploads/imagegallery/temp/');
define('article_temp_image_path', 'uploads/article/temp/');
define('video_temp_image_path', 'uploads/video/temp/');
define('audio_temp_image_path', 'uploads/audio/temp/');
define('audio_temp_file_path', 'uploads/audio/audio_file_temp/');
define('resource_temp_image_path', 'uploads/resource_image/temp/');
define('author_temp_image_path', 'uploads/author/temp/');
define('imagelibrary_temp_image_path', 'uploads/imagelibrary/temp/');
define('section_article_image_path', 'uploads/jumbo_menu_articles/');
define('poll_temp_image_path', 'uploads/poll/temp/');

define('resource_worddocument_path', 'worddocument/');
define('resource_pdf_path', 'pdf/');
define('resource_excel_path', 'excel/');
define('resource_ppt_path', 'ppt/');

define('image_resolution', 65);

define('audio_source_path', 'uploads/user/audio/');

define('resource_path', 'uploads/user/resources/');
define('imagelibrary_image_path', 'uploads/user/imagelibrary/');
define('ckeditor_image_path', 'uploads/user/ckeditor_images/article/');

define('columinst_image_path', 'uploads/user/imagelibrary/author/');

define('folder_name', 'niecpan');		
define('image_url', 'https://media.kannadaprabha.com/');
define('image_url_no', 'http://media.kannadaprabha.com/');
define('no_articles', 'Please add articles to show');	
define('BASEURL', 'https://www.kannadaprabha.com/'); 
define('HOMEURL', 'https://www.kannadaprabha.com/');
define('MOBILEURL', 'https://m.kannadaprabha.com/');
define('version','1.3');
//define('HOMEURL', 'http://launch.newindianexpress.com/');
define('SHOWADS' , true);			
/* End of file constants.php */
/* Location: ./application/config/constants.php */
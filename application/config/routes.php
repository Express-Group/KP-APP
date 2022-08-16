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
//$route['default_controller'] = folder_name."/common";

$route['default_controller']        = 'user/home_controller';
$route['user/scroll_data/render_news'] = 'user/scroll_data/render_news';

$route['user/commonwidget/(:any)']  = "user/commonwidget/$1";
$route['404_override']              = '';

//  Live site Routing  //
$route['embed/(:any)'] 		  = 'embed/$1';
$route['(:any)/rssfeed']            = 'user/rss_controller';
$route['readwhere/(:num)/sitemap.xml'] = "user/readwhere_controller";
$route['news/(:any)/sitemap.xml'] = "user/rss_controller/magzterfeed";
$route['articleApi/(:num)/sitemap.xml'] = "user/readwhereApi_controller/article_by";
$route['app/(:num)/sitemap.xml'] = "user/readwhere_controller/app";
$route['live/(:num)/news']  = 'user/readwhere_controller/livecontent'; 
$route['(:any)/news/feed'] = "user/rss_controller/uc_news";
$route['sitemap.xml'] 		        = "user/rss_controller/sitemap";
$route['home.xml'] 		        = "user/rss_controller/homefeed";
$route['latest-news.xml'] 		= "user/rss_controller/latest_sitemap";
$route['latest-feed.xml'] 		= "user/rss_controller/latest_feed";
$route['news-sitemap.xml'] 		= "user/rss_controller/new_sitemap";
$route['editorial_pick.xml'] 		= "user/rss_controller/editors_pick";
$route['new-sitemap.xml'] 		= "user/home_controller";
$route['sitemap-index.xml'] 		= "user/rss_controller/sitemap_list";
$route['sitemap-main.xml'] 		= "user/rss_controller/sitemap_main";
$route['(:any).xml'] 		        = "user/rss_controller/section_year_sitemap";
$route['(:any)/article(:any).ece']      = 'user/article_controller';
$route['(:any)/article(:any).ece(:any)']      = 'user/article_controller';

/* old url to new url redirection */
$route['photo-gallery/(:any)/(:num).html'] = 'user/redirect_controller/gallery';
$route['video/player/(:any)/(:num).html'] = 'user/redirect_controller/video';
$route['(:any)/(:any)/(:num).html'] = 'user/redirect_controller';
$route['(:any)/(:any)/(:any)/(:num).html'] = 'user/redirect_controller';

$route['(:any).html']               = 'user/article_controller';
$route['(:any).amp']               = 'user/amp_controller';
$route['special-page/(:any)'] = 'user/special_controller/$1'; 
$route['(:any)'] 		            = 'user/section_controller';





/* End of file routes.php */
/* Location: ./application/config/routes.php */
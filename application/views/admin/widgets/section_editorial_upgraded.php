<style>

.FieldTopic1 {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #ccc;
    border-image: none;
    border-style: solid none none;
    border-width: 1px medium medium;
}
legend.topic1 {
    border: none;
    color: #010101;
    font-size: 21px;
    line-height: 1.1;
    margin: -2px 0 6px;
    padding: 0 5px 0 0;
    width: auto;
}


.section_editorial_date date
{
	font-size:15px;
	font-family: Droid regular !important;
}

.section_editorial_list
{
	width:50%;float:left; 
	height: auto;
    padding: 7px 0px;
	margin-bottom: 4px;
	min-height:60px;
		
}

.section_editorial_list i {
    float: left;
    margin-top: 2px;
}

.section_editorial_list p{
	font-family: Droid Serif !important;
    font-size: 16px;
    margin-left: 13px;
    margin-bottom: 5px;	
	font-weight:normal;
}

@media only screen and (max-width:767px) {
.section_editorial_list
{
	width:100%;
	float:left; 
	height: auto;
    padding: 7px 0px;
	margin-bottom: 4px;
		
}

}

</style>


<?php
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id      = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article           = $content['show_max_article'];
$render_mode           = $content['RenderingMode'];

// widget config block ends
$domain_name         = base_url();
$show_simple_tab     = "";
$show_simple_tab .= '<div class="row">
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <div class="all_column" id="columns_' . $widget_instance_id . '" ' . $widget_bg_color . '>';
//getting content block - getting content list based on rendering mode
//getting content block starts here . Do not change anything

$widget_auto_count = $this->widget_model->select_setting($view_mode);
//$article_count       = 5;
$article_count       = $widget_auto_count['subsection_otherstories_count_perpage'];

if($render_mode == "manual")
{
	$content_type = $content['content_type_id'];  // manual article content type
	$widget_instance_contents_pageination = $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id, " ", $view_mode, $max_article);
}
else
{
	$content_type = $content['content_type_id'];  // auto article content type
	$widget_instance_contents_pageination = $this->widget_model->get_all_available_articles_auto_totalcount($max_article, $content['sectionID'] , $content_type ,  $view_mode);
}

if (function_exists('array_column')) 
{
	$get_content_ids = array_column($widget_instance_contents_pageination, 'content_id'); 
}
else
{
	$get_content_ids = array_map( function($element) { return $element['content_id']; }, $widget_instance_contents);
}
$get_content_ids = implode("," ,$get_content_ids);

$TotalCount= count($widget_instance_contents_pageination); 

$last_content_id = @$widget_instance_contents_pageination[$TotalCount-1]['content_id'];

$this->load->library('pagination');
$config['total_rows']           = $TotalCount;
$config['per_page']             = $article_count;

$config['custom_num_links'] = 5;

$config['page_query_string']    = TRUE;
$config['enable_query_strings'] = TRUE;
$config['cur_tag_open']         = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close']        = "</a>";
$article_limit                  = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : 0;
$start                          = $article_limit;
$page_number                    = $this->input->get('per_page') / $config['per_page'] + 1;
$limit                          = $article_count;
$config['use_page_numbers']     = TRUE;
$this->pagination->initialize($config);
//$PaginationLink = $this->pagination->create_links();
$PaginationLink = $this->pagination->custom_create_links();
// pagination end	

$load_more_url = $domain_name.'topic/?sid='.$content['page_param'].'&cid=1';
$archive = '';
if ($get_content_ids != '')
{
	$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database_per_page($get_content_ids, $content_type, $is_home, $view_mode, $start,$limit, '');	
	$widget_contents           = array();
	foreach ($widget_instance_contents_pageination as $key => $value)
	{
		foreach ($widget_instance_contents1 as $key1 => $value1)
		{
			if ($value['content_id'] == $value1['content_id'])
			{
				$widget_contents[] = array_merge($value, $value1);
			}
		}
	}

	$i                   = 1;
	$lastpublishonformat = '';
	if (count($widget_contents) > 0)
	{
		foreach ($widget_contents as $get_content)
		{
			$custom_title     = "";
			// Assign block - assigning values required for opening the article in light box
			// Assign block starts here
			$content_url      = $get_content['url'];
			$param            = $content['close_param'];
			$live_article_url = $domain_name . $content_url . $param;
			
			if($render_mode == "manual")
			{
				$custom_title = $get_content['CustomTitle'];
			}
			else
				$custom_title = $get_content['title'];
				
			$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $custom_title); //to remove first<p> and last</p>  tag
			$display_title = '<a  href="' . $live_article_url . '" class="article_click" >' . $display_title . '</a>';
			//  Assign article links block ends hers
			
			// display title and summary block starts here
			if ($lastpublishonformat != date('d.m.Y', strtotime($get_content['publish_start_date'])))
			{
				$lastpublishonformat = date('d.m.Y', strtotime($get_content['publish_start_date']));
				$show_simple_tab .= "<div class='section_editorial_date' style='width:100%;float:left;'><fieldset class='FieldTopic1'><legend class='topic1'><date>" . date('d.m.Y', strtotime($get_content['publish_start_date'])) . "</date></legend></fieldset></div>";
			}
			$show_simple_tab .= '<div class="section_editorial_list">';
			$show_simple_tab .= '<i class="fa fa-caret-right" aria-hidden="true"></i><p>' . $display_title . '</p>';
			
			$post_time = $this->widget_model->time2string($get_content['publish_start_date']);
			//$show_simple_tab .= '<p class="post_time">' . $post_time . '&nbsp;</p>';
			//$show_simple_tab .= '</br>';
			$show_simple_tab .= '</div>';
			
			if(($TotalCount < $article_count) && ($i == count($widget_contents))  || ($last_content_id == $get_content['content_id']))
			{
				//$show_simple_tab.= '<div class="col-sm-12"><p class="load_more_archive" style="margin-bottom:10px;"><a href="'.$load_more_url.'">More from Archieve</a></p></div>';
				$archive .= '<a class="load_more_archive" href="'.$load_more_url.'">More</a>';
			}
				
			//display title and summary block ends here					
			//Widget design code block 1 starts here																
			//Widget design code block 1 starts here			
			$i = $i + 1;
		}
		
	}
}
elseif ($view_mode == "adminview")
{
	$show_simple_tab .= '<div class="margin-bottom-10">' . no_articles . '</div>';
}
$show_simple_tab .= '</div>';
$show_simple_tab .= '</div></div>';
echo $show_simple_tab;
echo '<div class="pagina">';
echo $PaginationLink;
echo $archive.'</div>';
?>

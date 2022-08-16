 <?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
$widget_instance_id  = $content['widget_values']['data-widgetinstanceid'];
$main_sction_id 	 = "";
$is_home             = $content['is_home_page'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$secid = $content['widget_values']['cdata-widgetCategory'];
// widget config block ends
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name     =  base_url();
$show_simple_tab = "";

if($widget_custom_title == "")
	$widget_custom_title = "Videos";

$show_simple_tab .='<div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-15">
            <div class="gallery-subsection-wrapper SundaySecond" '.$widget_bg_color.'>
                <fieldset class="FieldTopic">';
					   	
			 if($content['widget_title_link'] == 1)
			{
				$show_simple_tab.=	'<legend class="topic"><a href="'.$widget_section_url.'">'.$widget_custom_title.'</a></legend>';
			}
			else
			{
				$show_simple_tab.=	'<legend class="topic">'.$widget_custom_title.'</legend>';
			} 
			$show_simple_tab .= '</fieldset>';
				
				$content_type 		= $content['content_type_id'];  // manual article content type
				$secname = $this->uri->segment(1);
				if($secid!=''){
					$CI = &get_instance();
					$this->live_db = $CI->load->database('live_db',TRUE);
					$sectiondetails = $this->live_db->query("CALL get_section_by_id('".$secid."')")->result_array();
					$spliturl = explode('/',$sectiondetails[0]['URLSectionStructure']);
					$spliturl[0] = strtolower($spliturl[0]);
					if($spliturl[0]=='ಫೋಟೊ ಗ್ಯಾಲರಿ' || $spliturl[0]=='photogallery'){
						$content_type =3;
					}elseif($spliturl[0]=='videos' || $spliturl[0]=='ವಿಡಿಯೋ'){
						$content_type =4;
					}else{
						$content_type ='';
					}
				}else{
					$content_type ='';
				}
				$widget_contents 	= array();
				
				//getting content block - getting content list based on rendering mode
				//getting content block starts here . Do not change anything
				if($render_mode == "manual")
				{
					
					$widget_instance_contents 	= $this->widget_model->get_widgetInstancearticles_rendering($widget_instance_id , " " ,$content['mode'], $max_article); 	

					$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
					$get_content_ids = implode("," ,$get_content_ids); 
					
					if($get_content_ids!='')
					{
						$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
						
						foreach ($widget_instance_contents as $key => $value) {
							foreach ($widget_instance_contents1 as $key1 => $value1) {
								if($value['content_id']==$value1['content_id']){
									$widget_contents[] = array_merge($value, $value1);
								}
							}
						}
					}						
						
				}
				else
				{
					$widget_contents = $this->widget_model->get_all_available_articles_auto($max_article, $content['sectionID'] , $content_type ,  $view_mode, $is_home);
				}
				
				/*
				if (function_exists('array_column')) 
				{
			$get_content_ids = array_column($widget_instance_contents, 'content_id'); 
				}else
				{
			$get_content_ids = array_map( function($element) { return $element['content_id']; }, $widget_instance_contents);
				} 
				$get_content_ids = implode("," ,$get_content_ids);
			if($get_content_ids!='')
			{
				$widget_instance_contents1 = $this->widget_model->get_contentdetails_from_database($get_content_ids, $content_type, $is_home, $view_mode);	
				//print_r($widget_instance_contents1);exit;
				$widget_contents = array();
					foreach ($widget_instance_contents as $key => $value) {
						foreach ($widget_instance_contents1 as $key1 => $value1) {
							if($value['content_id']==$value1['content_id']){
							   $widget_contents[] = array_merge($value, $value1);
							}
						}
					}
					
					*/
				$i =1;
		if(count($widget_contents)>0)
		     {				
				foreach($widget_contents as $get_content)
				{
				
					$custom_title        = "";
					$original_image_path = "";
					$imagealt            = "";
					$imagetitle          = "";
					$Image600X300        = "";
					if($render_mode == "manual")            // from widgetinstancecontent table    
					{
						if($get_content['custom_image_path'] != '')
						{
							$original_image_path = $get_content['custom_image_path'];
							$imagealt            = $get_content['custom_image_title'];	
							$imagetitle          = $get_content['custom_image_alt'];												
						}
							$custom_title        = stripslashes($get_content['CustomTitle']);
					}
						if($original_image_path =="")                         // from cms imagemaster table    
						{
								
							   $original_image_path  = $get_content['ImagePhysicalPath'];
							   $imagealt             = $get_content['ImageCaption'];	
							   $imagetitle           = $get_content['ImageAlt'];	
						}
					
					$show_image="";

						if($original_image_path !='' && get_image_source($original_image_path, 1))
						{
							$imagedetails = get_image_source($original_image_path, 2);
							$imagewidth = $imagedetails[0];
							$imageheight = $imagedetails[1];	
						
							if ($imageheight > $imagewidth)
							{
								$Image600X300 	= $original_image_path;
							}
							else
							{
							    $Image600X300  = str_replace("original","w600X300", $original_image_path);
							}
								if (get_image_source($Image600X300, 1) && $Image600X300 != '')
								{
									$show_image = image_url. imagelibrary_image_path . $Image600X300;
								}
								else {
									$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
								}
								$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
						}
						else {

									$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
								}
								$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X300.jpg';
						
						
						$content_url = $get_content['url'];
						$param = $content['close_param']; //page parameter
						$live_article_url = $domain_name. $content_url.$param;
					
						if( $custom_title == '')
						{
							$custom_title = stripslashes($get_content['title']);
						}	
						$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
						$display_title = '<a  href="'.$live_article_url.'" class="article_click" >'.$display_title.'</a>';
						if($i==1){
							$show_simple_tab .='<div class="WidthFloat_L">';
						}
						$show_simple_tab .='<div class="col-lg-4 col-md-4 col-sm-12 SundaySecondSplit">';
						$show_simple_tab .='<a href="'.$live_article_url.'" class="article_click gallery-article-img" >';
						$show_simple_tab .='<img src="'.$dummy_image.'" data-src="'.$show_image.'" title = "'.$imagetitle.'" alt = "'.$imagealt.'" class="play">';
						if($content_type==3){
							$show_simple_tab .='<span class="icon-gl-vd icon-gl"></span>';
						}
						if($content_type==4){
							$show_simple_tab .='<span class="icon-gl-vd icon-vd"></span>';
						}
						
						$show_simple_tab .='</a><p class="gallery_subsection_title">'.$display_title .'</p>';
						$show_simple_tab .='</div>';
						if($i==3){
							$show_simple_tab .='</div>';
							$i=1;
						}else{
							$i =$i+1;
						}
					
					
				}
				 
				// content list iteration block ends here
				//}
			}
			
			if($i==2 || $i==3){
				$show_simple_tab .='</div>';
			}
			 elseif($view_mode=="adminview")
			{
			$show_simple_tab .='<div class="margin-bottom-10">'.no_articles.'</div>';
			}
              $show_simple_tab .='</div>
          </div>
          </div>';
																			  
												
																			  
echo $show_simple_tab;
?>

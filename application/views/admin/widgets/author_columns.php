<?php 
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$max_article         = 10;
$domain_name         =  base_url();
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];


if($this->uri->segment(2) != '') {
	$author_name = str_replace('_',' ',urldecode($this->uri->segment(2)));

$author_details = get_author_by_name($author_name);
$author_id = @$author_details['Author_id'];
}

$GetArchiveDetails=$this->widget_model->GetAuthorArchiveDetails($author_name,$content['page_param'],1);


// Newly add for paging  start 
$autho_articles_count	= $this->widget_model->get_Stories_For_Author_all($content['mode'],$author_name);

$TotalCount = count($autho_articles_count);


$last_content_id = @$autho_articles_count[$TotalCount-1]['content_id'];

$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : 0;

//$perpage				=	15;
if($this->uri->segment(3)=='archive'){
	$TotalCount=$this->widget_model->GetAuthorArchiveDetails($author_name,$content['page_param'],2);
	$config['total_rows'] = $TotalCount;
	$config['base_url']=$domain_name.'Author/'.$this->uri->segment(2).'/archive';
	$config['per_page'] = 20; 
	$config['custom_num_links'] = 5;
	$config['page_query_string'] = TRUE;
	$config['enable_query_strings']=TRUE;
	$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
	$config['cur_tag_close'] = "</a>";
        $config['first_url']=$domain_name.'Author/'.$this->uri->segment(2);
	$this->pagination->initialize($config);
	$PaginationLink = $this->pagination->create_links();
	$article_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : 0;
	$columnnist_articles_list=$this->widget_model->GetContentBasedOnArchive($article_limit,$author_name,$config['per_page']);

	
}else{
$config['total_rows'] = $TotalCount;
$config['per_page'] = 15; 
$config['custom_num_links'] = 5;
$config['page_query_string'] = TRUE;
$config['enable_query_strings']=TRUE;
$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close'] = "</a>";
$this->pagination->initialize($config);
//$PaginationLink = $this->pagination->create_links();
$PaginationLink = $this->pagination->custom_create_links();
$manual_instance = "&instance=archive";
$load_more_url = $domain_name.'topic/?sid='.$content['page_param'].'&cid=1'.$manual_instance;


// newly add for paging end 

	$columnnist_articles_list = $this->widget_model->get_Stories_For_Author($article_limit, $author_id, $content['mode'],$author_name);
	
	$author_det       = $this->widget_model->get_author($author_id);
	$author_name      = @$author_det[0]['AuthorName'];
	$ShortBiography   = @$author_det[0]['ShortBiography'];
	$author_image_id  = @$author_det[0]['image_id'];
	
	}

	?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="main_column column-article-list">
	  <?php 
	  if(count($author_details) > 0):
		$dummyImage = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
		$image = ($author_details['image_path']!='') ? image_url.$author_details['image_path'] : $dummyImage;
		$imgAlt = ($author_details['image_path']!='') ? $author_details['image_alt'] : 'author_alt';
		$imgTitle = ($author_details['image_path']!='') ? $author_details['image_caption'] : 'author_caption';
	  ?>
	  <style>
	  .column-flex{float: left;width: 100%;padding: 10px;background: #92000b;margin-bottom: 2rem;display: flex;   align-content: center;color:#fff;box-shadow: 1px 3px 6px 1px #0000003b;align-items: center;}
	  .column-flex img{width: 150px;height: 150px;object-fit: cover;border-radius: 50%;border: 4px solid #fff;}
	  .column-flex div h4{padding-left: 2%;font-weight: 700;}
	  .column-flex div p{padding-left: 2%;}
	  .sub_column{display: flex;width: 100%;flex: 1;}
	  .sub_column img{width:150px;}
	  .sub_column .ColumnList{margin-left:0;width: calc(100% - 150px);padding-left: 10px;}
	  .sub_column .ColumnList h5{font-weight: 700;font-size: 17px;margin-bottom: 5px;}
	  @media only screen and (max-width: 768px){.column-flex img{width: 100px;height: 100px;}.sub_column .ColumnList .summary{display:none;}}
	  </style>
	  <div class="column-flex">
		<img src="<?php echo $dummyImage; ?>" data-src="<?php echo $image; ?>" width="150" height="150" alt="<?php echo $imgAlt; ?>" title="<?php echo $imgTitle; ?>">
		<div style="width:100%;">
			<h4><?php echo $author_name;?></h4>
			<?php if($author_details['ShortBiography']!=''){ echo '<p>'.$author_details['ShortBiography'].'</p>';} ?>
		</div>
	  </div>
	  <?php endif; ?>
      <div class="current all_column">
        <?php
		  if(count($autho_articles_count)>0 && $author_id!=''){
		 foreach($autho_articles_count as $article_list){ 
		 
			if($this->uri->segment(3)=='archive'){
				$content_url = $article_list->url;
				$param = $content['close_param']; //page parameter
				$live_article_url = $domain_name. $content_url.$param;
				$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$article_list->title); 
				$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$article_list->summary_html); 
				
			}else{
			  $content_type =1 ;
              $content_details = $this->widget_model->get_contentdetails_from_database($article_list['content_id'], $content_type, $is_home, $view_mode);
				$custom_title        = "";
				$custom_summary      = "";															
				$original_image_path  = $content_details[0]['ImagePhysicalPath'];
			    $imagealt             =  $content_details[0]['ImageCaption'];	
			    $imagetitle           =  $content_details[0]['ImageAlt'];
				$content_url = $content_details[0]['url'];
				$param = $content['close_param']; //page parameter
				$live_article_url = $domain_name. $content_url.$param;
			
				if( $custom_title == '')
				{
					$custom_title = stripslashes($content_details[0]['title']);
				}	
				$display_title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_title);   //to remove first<p> and last</p>  tag
								
				if( $custom_summary == '')
				{
					$custom_summary =  $content_details[0]['summary_html'];
				}
				$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$custom_summary); 
				//to remove first<p> and last</p>  tag
				if ($original_image_path!='' && get_image_source($original_image_path, 1)){
					$imagedetails = get_image_source($original_image_path, 2);
					$imagewidth = $imagedetails[0];
					$imageheight = $imagedetails[1];	
					$Image600X390 	= str_replace('original/','w600X390/',$original_image_path);
					if ($Image600X390 != '' && get_image_source($Image600X390, 1)){
						$show_image = image_url. imagelibrary_image_path . $Image600X390;
					}else{
						$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
					}
				}else{
					$show_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}	
				$dummy_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
				}
			?>
        <div class="sub_column">
		  <a class="article_click" href="<?php echo $live_article_url; ?>" ><img src="<?php echo $dummy_image; ?>" data-src="<?php echo $show_image; ?>" class="img-responsive" width="600" height="390"></a>
          <div class="ColumnList">
            <h5><a class="article_click" href="<?php echo $live_article_url; ?>" ><?php echo $display_title;?></a></h5>
            <p class="column_det summary"><?php echo $summary;?></p>
            <p class="post_time">
              <?php 
				 if($this->uri->segment(3)=='archive'):
					$time=$article_list->last_updated_on;
				 else:
					$time= $content_details[0]['last_updated_on'];
				 endif;
				 $post_time= $this->widget_model->time2string($time); echo $post_time;
				?>
              </p>
          </div>
        </div>
        <?php } 
		  }else{?>
          <h4>Sorry Author Not Found</h4>
          <?php } ?>
		  <?php
			$archive='';
			if(($GetArchiveDetails > 0)  && $this->uri->segment(3)==''){
				$load_more_url = $domain_name.'Author/'.$this->uri->segment(2).'/archive';
				$archive = '<a class="load_more_archive" href="'.$load_more_url.'">More from archive</a>';
			}
		  ?>
		   <div class="pagina"> <?php echo $PaginationLink;?> </div>
      </div>
    </div>
  </div>
</div>


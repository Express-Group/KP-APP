<style>
@media only screen and (max-width: 768px){
	.result-section p{display:none;}
}
</style>
<?php
$_GET['term'] = htmlspecialchars($_GET['term']);
$_GET['term'] = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS); 
$_GET['term'] = urldecode($_GET['term']);
if(isset($_GET['search_term']) && $_GET['search_term']!=''){
	$_GET['search_term'] = htmlspecialchars($_GET['search_term']);
	$_GET['search_term'] = filter_input(INPUT_GET, 'search_term', FILTER_SANITIZE_SPECIAL_CHARS); 
	$_GET['term'] = urldecode($_GET['search_term']);
	$search='short';
}
extract($_GET);
$searchtype =(isset($search) && $search!='')?$search:'';
function pager($parameter=[]){
	$config=['base_url'=>$parameter['base_url'],'total_rows'=>$parameter['total_rows'],'per_page'=>$parameter['per_page'],'num_links'=>5,'page_query_string'=>TRUE,'reuse_query_string'=>FALSE,'suffix'=>$parameter['suffix'],'cur_tag_open'=>'<a class="active">','cur_tag_close'=>'</a>','use_page_numbers'=>TRUE,'first_url'=>$parameter['first_url'],'first_link'=>FALSE,'last_link'=>FALSE];
	return $config;
}
if($searchtype=='short'){
	if(mb_strlen($term) <3){
		redirect(base_url(),'location',301);
	}
	$this->live_db = $this->load->database('live_db', TRUE);
	$term=(isset($term) && $term!='')?$term:'';
	$widget_url=$content['widget_section_url'];
	$pattern="SELECT title,url,article_page_image_path,summary_html,publish_start_date FROM article WHERE (title LIKE '%".$term."%' OR summary_html LIKE '%".$term."%' OR author_name LIKE '%".$term."%' OR agency_name LIKE '%".$term."%' OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%') AND publish_start_date <=NOW() AND status='P' ORDER BY publish_start_date DESC";
	$check_query=@$_COOKIE['shortend'];
	if($check_query==base64_encode($pattern)){
		$query_count=@$_COOKIE['shortend_count'];
	}else{
		$query=$this->live_db->query($pattern." LIMIT 100");
		$query_count=$query->num_rows();
		setcookie('shortend', base64_encode($pattern),time() + (60 * 15));
		setcookie('shortend_count',$query_count,time() + (60 * 15));
	}
	if($query_count >=100){ $query_count = 100; }else{ $query_count = $query_count;}
	$this->pagination->initialize(pager(['total_rows'=>$query_count,'per_page'=>10,'base_url'=>$widget_url,'suffix'=>'&term='.$term.'&request=ALL&search=short','first_url'=>$widget_url.'?term='.$term.'&request=ALL&search=short']));
	$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
	$pagination=$this->pagination->create_links();
	$Result=$this->live_db->query($pattern." LIMIT ".$row.", 10")->result();
?>
	<style>
		#shortsearch{width:65%;padding: 5px 6px 7px;font-family: Droid regular !important;}
	</style>
	<div class="col-md-12">
		<div class="form-group">
			<input class="adv_inputs" type="text" value="<?php print $term ?>" id="shortsearch" name="shortsearch">
			<button class="btn btn-primary adv_search_btn" onclick="short_search(0);">Search</button>
			<button class="btn btn-primary adv_search_btn" onclick="short_search(1);">Advance Search</button>
		</div>
		<ul class="ascending" id="table_sorter"><li style="float:left;">Search results for <span class="active"><?php echo $term ?></span></li></ul>
		<?php
			print '<table id="example" class="display result-section" cellspacing="0" width="100%">';
			foreach($Result as $fetched):
					$publisheddate    = date('jS F Y', strtotime($fetched->publish_start_date));
					$u=image_url;
					$fetched->article_page_image_path=($fetched->article_page_image_path=='')?'logo/nie_logo_600X390.jpg':$fetched->article_page_image_path;
					$low='logo/nie_logo_600X390.jpg';
					
					print '<tr>';
					print '<td><figure class="result-section-figure"><img src="'.$u.imagelibrary_image_path.$low.'" data-src="'.$u.imagelibrary_image_path.$fetched->article_page_image_path.'" class=""></figure></td>';
					print '<td><div class="search-row_type"><h4><a class="article_click" href="'.BASEURL.$fetched->url.'">'.strip_tags($fetched->title).'</a></h4><p>'.strip_tags($fetched->summary_html).'</p><date>published on : '.$publisheddate.'</date></div></td>';
					print '</tr>';
			endforeach;
			print '</table>';
			if(count($Result) == 0):
				print '<div class="search_count text-center"><p>Your search did not match any content</p></div>';
				
			endif;
		?>
		<div class="pagina"><?php echo $pagination; ?></div>
		<div class="pagina"><?php print '<button class="btn btn-primary adv_search_btn" onclick="short_search(1);">More from archive</button>'; ?></div>
	</div>
	<script>
	function short_search(type){
		if($('#shortsearch').val()!=''){
			if(type==0){
				if($('#shortsearch').val().length < 3 ){
					alert('Please Enter more than 2 letters');
				}else{
					window.location.href=base_url+'topic?term='+$('#shortsearch').val()+'&request=ALL&search=short';
				}
				
			}
			if(type==1){
				if($('#shortsearch').val().length < 3 ){
					alert('Please Enter more than 2 letters');
				}else{
					window.location.href=base_url+'topic?term='+$('#shortsearch').val()+'&request=ALL&type=title&row_type=A&request=MIN';
				}
				
			}
		}
	}
	</script>
	
	
<?php
}else{
$term=(isset($term) && $term!='')?$term:'';
$type=(isset($type) && $type!='')?$type:'';
$row_type=(isset($row_type) && $row_type!='')?$row_type:'';
$button='';
$widget_url=$content['widget_section_url'];

$tags=urldecode($this->uri->segment(2));
$tags =str_replace('_',' ',$tags);
$tag_type=$this->uri->segment(3);
$tag_type=($tag_type!='')?$tag_type:'article';
?>
<style>
	.advance_search{
		margin-top:2%;
		text-align:center;
		font-family: Droid regular !important;
	}
	.advance_search label{
		width:20%;
	}
	.advance_search .adv_inputs{
		width:60%;
		padding:3px;
	}
	.adv_search_result{
		width:100%;
		float:left;
	}
	.error_search{
		padding-bottom: 7px;
		width: 100%;
		float: left;
		display:none;
		color:#f00;
	}
	.load_more_url{
		padding-right:4px;
		color:#666666;
	}
	
	.search-row_type p{
		margin-bottom:0px;
		font-size:13px;
		padding-top:3px;
	}
	.result-section-figure{
		width:130px;
		border:none;
	}
</style>
<?php if($tags==''): ?>
<div class="well advance_search">
	<div class="col-md-12 text-center">
		<span class="error_search"> * Enter a valid keyword</span>
	</div>
	<div class="form-group">
		<label>Key Words : </label>
		<input class="adv_inputs" type="text" value="<?php print $term; ?>" id="keyword" name="keyword" onkeypress="trigger_event(event)">
	</div>
	<div class="form-group">
		<label>Search By : </label>
		<select  class="adv_inputs" id="field" name="field">
			<option value="">Please Select</option>
			<option value="title" <?php if($type=='title'): print 'selected'; endif; ?> >Title</option>
			<option value="summary_html" <?php if($type=='summary_html'): print 'selected'; endif; ?> >Short Description</option>
			<option value="author_name" <?php if($type=='author_name'): print 'selected'; endif; ?>  >Author</option>
			<option value="agency_name" <?php if($type=='agency_name'): print 'selected'; endif; ?> >Agency</option>
		</select>
	</div>
	<div class="form-group">
		<label>Content Type : </label>
		<select  class="adv_inputs" id="type" name="type">
			<option value="">Please Select</option>
			<option value="A" <?php if($row_type=='A'): print 'selected'; endif; ?>>Article</option>
			<option value="V" <?php if($row_type=='V'): print 'selected'; endif; ?>>Video</option>
			<option value="G" <?php if($row_type=='G'): print 'selected'; endif; ?>>Gallery</option>
		</select>
	</div>
	<div class="form-group">
		<button class="btn btn-primary adv_search_btn" onclick="adv_search();">Search</button>
	</div>
</div>
<?php endif; ?>
<div class="adv_search_result">
	
	<?php
	$this->live_db = $this->load->database('live_db', TRUE);
	$CI=&get_instance();
	$this->archive_db = $CI->load->database('archive_db', TRUE);
	
	
	/*for keyword search*/
	if(isset($_GET['request']) && $_GET['request']=='ALL'):
		if(mb_strlen($term) <3){
			redirect(base_url(),'location',301);
		}
		$pattern="SELECT title,url,article_page_image_path,summary_html,publish_start_date FROM article WHERE (title LIKE '%".$term."%' OR summary_html LIKE '%".$term."%' OR author_name LIKE '%".$term."%' OR agency_name LIKE '%".$term."%' OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%') AND publish_start_date <=NOW() AND status='P' ORDER BY publish_start_date DESC";
		$check_query=@$_COOKIE['end'];
		if($check_query==base64_encode($pattern)){
			$query_count=@$_COOKIE['end_count'];
		}else{
			$query=$this->live_db->query($pattern);
			$query_count=$query->num_rows();
			setcookie('end', base64_encode($pattern),time() + (60 * 15));
			setcookie('end_count',$query_count,time() + (60 * 15));
		}
		
		$check_archive=@$_COOKIE['archive_'.base64_encode($term)];
		if($check_archive==''){
			$hasarchive['archive_result']=[];
			$range=range(2009,date('Y')-1);
			foreach($range as $ranger):
				$table='article_'.$ranger;
				if($this->archive_db->table_exists($table)){
					$archive_pattern="SELECT title,url,article_page_image_path,summary_html FROM ".$table." WHERE (title LIKE '%".$term."%' OR summary_html LIKE '%".$term."%' OR author_name LIKE '%".$term."%' OR agency_name LIKE '%".$term."%' OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%') AND publish_start_date <=NOW() AND status='P'";
					$temp_query=$this->archive_db->query($archive_pattern);
					$data['table']=$table;
					$data['count']=$temp_query->num_rows();
					if($temp_query->num_rows() !=0):
						$hasarchive['archive_result'][]=$data;
					endif;
				}
			endforeach;
			setcookie('archive_'.base64_encode($term),json_encode($hasarchive),time() + (60 * 15));
			$archivelist=$hasarchive;
		}else{
			$archivelist=json_decode($check_archive,true);
		}
		
		if(isset($_GET['archive']) && $_GET['archive']==true && $_GET['year']!=''){
			$archive_count=array_reverse($archivelist['archive_result']);
			for($i=0;$i<count($archive_count);$i++){
				$y="article_".$_GET['year'];
				if(in_array($y,$archive_count[$i])){
					$archive_total=$archive_count[$i]['count'];
					$archive_tbl=$archive_count[$i]['table'];
					if($i < count($archive_count)-1){
						$nxt='<a href="'.$widget_url.'?term='.$term.'&request=ALL&archive=true&year='.str_replace('article_','',$archive_count[$i+1]['table']).'">More from '.str_replace('article_','',$archive_count[$i+1]['table']).'</a>';
					}
				}
			}
			$this->pagination->initialize(pager(['total_rows'=>$archive_total,'per_page'=>15,'base_url'=>$widget_url,'suffix'=>'&term='.$term.'&request=ALL&archive=true&year='.$_GET['year'],'first_url'=>$widget_url.'?term='.$term.'&request=ALL&archive=true&year='.$_GET['year']]));
			$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
			$pagination=$this->pagination->create_links();
			
			$pattern_archive="SELECT title,url,article_page_image_path,summary_html,publish_start_date FROM ".$archive_tbl." WHERE (title LIKE '%".$term."%' OR summary_html LIKE '%".$term."%' OR author_name LIKE '%".$term."%' OR agency_name LIKE '%".$term."%'v OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%') AND publish_start_date <=NOW() AND status='P'";
			$Result=$this->archive_db->query($pattern_archive." LIMIT ".$row.", 15")->result();
			$LastPage = floor(($_GET['per_page']/15) + 1);
			$queryLastPage=ceil($archive_total/15);
			if($LastPage==$queryLastPage):
				$button=$nxt;
			endif;
			$loadmore='';
			$arc=array_reverse($archivelist['archive_result']);
			if(count($arc) !=0){
					$loadmore .=' <a class ="load_more_url" href="'.$widget_url.'?term='.$term.'&request=ALL">Latest</a>';
					for($a=0;$a<count($arc);$a++){
						$load_year=str_replace('article_','',$arc[$a]['table']);
						if($load_year!=$_GET['year']):
							$load_url=$widget_url.'?term='.$term.'&request=ALL&archive=true&year='.$load_year;
							$loadmore .=' <a class="load_more_url" href="'.$load_url.'">'.$load_year.'</a>';
						else:
							$loadmore .=' <a class ="load_more_url" style="color:#EA8E1A;">'.$load_year.'</a>';
						endif;
					}
			}
			
			
		}else{
			$this->pagination->initialize(pager(['total_rows'=>$query_count,'per_page'=>15,'base_url'=>$widget_url,'suffix'=>'&term='.$term.'&request=ALL','first_url'=>$widget_url.'?term='.$term.'&request=ALL']));
			$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
			$pagination=$this->pagination->create_links();
			$cookie_result=@$_COOKIE['NORMAL_'.$row.'_'.$term];
			$Result=$this->live_db->query($pattern." LIMIT ".$row.", 15")->result();
			$LastPage = floor(($_GET['per_page']/15) + 1);
			$queryLastPage=ceil($query_count/15);
			$arc=array_reverse($archivelist['archive_result']);
			if($LastPage==$queryLastPage):
				if(count($arc) !=0){
					$button='<a href="'.$widget_url.'?term='.$term.'&request=ALL&archive=true&year='.str_replace('article_','',$arc[0]['table']).'">More from '.str_replace('article_','',$arc[0]['table']).'</a>';
				}
			endif;
			$loadmore='';
			if(count($arc) !=0){
					$loadmore .=' <a class ="load_more_url" style="color:#EA8E1A;">Latest</a>';
					for($a=0;$a<count($arc);$a++){
						$load_year=str_replace('article_','',$arc[$a]['table']);
						$load_url=$widget_url.'?term='.$term.'&request=ALL&archive=true&year='.$load_year;
						$loadmore .=' <a class="load_more_url" href="'.$load_url.'">'.$load_year.'</a>';
					}
			}
		}
	endif;
	/*end*/
	
	if(isset($_GET['request']) && $_GET['request']=='MIN'):
		if(mb_strlen($term) <3){
			redirect(base_url(),'location',301);
		}
		if($type==''){
			if($row_type=='G'):
				$type_query=" (title LIKE '%".$term."%' OR summary_html LIKE '%".$term."%' OR agency_name LIKE '%".$term."%' OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%')";
			else:
				$type_query=" (title LIKE '%".$term."%' OR summary_html LIKE '%".$term."%' OR author_name LIKE '%".$term."%' OR agency_name LIKE '%".$term."%' OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%')";
			endif;
		}else{
			$type_query=" (".$type." LIKE '%".$term."%' OR url LIKE '%".$term."%' OR url LIKE '%".str_replace(' ','-',$term)."%')";
		}
		switch($row_type){
			case 'A':
				$live_db='article';
				$archive_db_new='article_'.$_GET['year'];
				$image_path='article_page_image_path';
			break;
			case 'V':
				$live_db='video';
				$archive_db_new='video_'.$_GET['year'];
				$image_path='video_image_path';
			break;
			case 'G':
				$live_db='gallery';
				$archive_db_new='gallery_'.$_GET['year'];
				$image_path='first_image_path';
			break;
			default:
				$live_db='article';
				$archive_db_new='article_'.$_GET['year'];
				$image_path='article_page_image_path';
			break;
		}
		
		$pattern="SELECT title,url,".$image_path." as article_page_image_path,summary_html,publish_start_date FROM ".$live_db." WHERE ".$type_query." AND publish_start_date <=NOW() AND status='P' ORDER BY publish_start_date DESC";
		$check_query=@$_COOKIE['end_min'];
		if($check_query==base64_encode($pattern)){
			$query_count=@$_COOKIE['end_count_min'];
		}else{
			$query=$this->live_db->query($pattern);
			$query_count=$query->num_rows();
			setcookie('end_min', base64_encode($pattern),time() + (60 * 15));
			setcookie('end_count_min',$query_count,time() + (60 * 15));
		}
		
		$check_archive=@$_COOKIE['archive_min_'.$live_db.'_'.base64_encode($term)];
		if($check_archive==''){
			$hasarchive['archive_result']=[];
			$range=range(2009,date('Y')-1);
			foreach($range as $ranger):
				$table=$live_db.'_'.$ranger;
				if($this->archive_db->table_exists($table)){
				//	echo $table;
				//	echo '<br>yes';
				$archive_pattern="SELECT title,url,".$image_path." as article_page_image_path ,summary_html FROM ".$table." WHERE ".$type_query." AND publish_start_date <=NOW() AND status='P' ORDER BY publish_start_date DESC";
				
					$temp_query=$this->archive_db->query($archive_pattern);
					$data['table']=$table;
					$data['count']=$temp_query->num_rows();
					if($temp_query->num_rows() !=0):
						$hasarchive['archive_result'][]=$data;
					endif;
				}
			endforeach;
			setcookie('archive_min_'.$live_db.'_'.base64_encode($term),json_encode($hasarchive),time() + (60 * 15));
			$archivelist=$hasarchive;
		}else{
			$archivelist=json_decode($check_archive,true);
		}
		
		if(isset($_GET['archive']) && $_GET['archive']==true && $_GET['year']!=''){
			$archive_count=array_reverse($archivelist['archive_result']);
			for($i=0;$i<count($archive_count);$i++){
				$y=$live_db."_".$_GET['year'];
				if(in_array($y,$archive_count[$i])){
					$archive_total=$archive_count[$i]['count'];
					$archive_tbl=$archive_count[$i]['table'];
					if($i < count($archive_count)-1){
						$nxt='<a href="'.$widget_url.'?term='.$term.'&type='.$type.'&row_type='.$row_type.'&request=MIN&archive=true&year='.str_replace(array('article_','video_','gallery_'),'',$archive_count[$i+1]['table']).'">More from '.str_replace(array('article_','video_','gallery_'),'',$archive_count[$i+1]['table']).'</a>';
					}
				}
			}
			$this->pagination->initialize(pager(['total_rows'=>$archive_total,'per_page'=>15,'base_url'=>$widget_url,'suffix'=>'&term='.$term.'&request=MIN&type='.$type.'&row_type='.$row_type.'&archive=true&year='.$_GET['year'],'first_url'=>$widget_url.'?term='.$term.'&request=MIN&type='.$type.'&row_type='.$row_type.'&archive=true&year='.$_GET['year']]));
			$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
			$pagination=$this->pagination->create_links();
			
			$pattern_arc="SELECT title,url,".$image_path." as article_page_image_path ,summary_html,publish_start_date FROM ".$archive_db_new." WHERE ".$type_query." AND publish_start_date <=NOW() AND status='P' ORDER BY publish_start_date DESC";
			$Result=$this->archive_db->query($pattern_arc." LIMIT ".$row.", 15")->result();
			$LastPage = floor(($_GET['per_page']/15) + 1);
			$queryLastPage=ceil($archive_total/15);
			if($LastPage==$queryLastPage):
				$button=$nxt;
			endif;
			$arc=array_reverse($archivelist['archive_result']);
			if(count($arc) !=0){
					$loadmore .=' <a class ="load_more_url" href="'.$widget_url.'?term='.$term.'&request=MIN&type='.$type.'&row_type='.$row_type.'">Latest</a>';
					for($a=0;$a<count($arc);$a++){
						$load_year=str_replace(array('article_','video_','gallery_'),'',$arc[$a]['table']);
						if($load_year!=$_GET['year']):
							$load_url=$widget_url.'?term='.$term.'&request=MIN&archive=true&year='.$load_year.'&type='.$type.'&row_type='.$row_type;
							$loadmore .=' <a class="load_more_url" href="'.$load_url.'">'.$load_year.'</a>';
						else:
							$loadmore .=' <a class ="load_more_url" style="color:#EA8E1A;">'.$load_year.'</a>';
						endif;
					}
			}
			
			
		}else{
			$this->pagination->initialize(pager(['total_rows'=>$query_count,'per_page'=>15,'base_url'=>$widget_url,'suffix'=>'&term='.$term.'&request=MIN&type='.$type.'&row_type='.$row_type,'first_url'=>$widget_url.'?term='.$term.'&request=MIN&type='.$type.'&row_type='.$row_type]));
			$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
			$pagination=$this->pagination->create_links();
			$Result=$this->live_db->query($pattern." LIMIT ".$row.", 15")->result();
			$LastPage = floor(($_GET['per_page']/15) + 1);
			$queryLastPage=ceil($query_count/15);
			$arc=array_reverse($archivelist['archive_result']);
			if($LastPage==$queryLastPage):
				
				if(count($arc) !=0){
					$button='<a href="'.$widget_url.'?term='.$term.'&request=MIN&type='.$type.'&row_type='.$row_type.'&archive=true&year='.str_replace(array('article_','video_','gallery_'),'',$arc[0]['table']).'">More from '.str_replace(array('article_','video_','gallery_'),'',$arc[0]['table']).'</a>';
				}
			endif;
			$loadmore='';
			if(count($arc) !=0){
					$loadmore .=' <a class ="load_more_url" style="color:#EA8E1A;">Latest</a>';
					for($a=0;$a<count($arc);$a++){
						$load_year=str_replace(array('article_','video_','gallery_'),'',$arc[$a]['table']);
						$load_url=$widget_url.'?term='.$term.'&request=MIN&archive=true&year='.$load_year.'&type='.$type.'&row_type='.$row_type;
						$loadmore .=' <a class="load_more_url" href="'.$load_url.'">'.$load_year.'</a>';
					}
			}
			
		}
		
	endif;
	
	if($tags!=''){
		if(mb_strlen($tags) <3){
			redirect(base_url(),'location',301);
		}
		if($tag_type=='article'){ $image_col=' article_page_image_path '; }
		if($tag_type=='video'){ $image_col='video_image_path'; }
		if($tag_type=='gallery'){ $image_col='first_image_path'; }
		$tag_query="SELECT title,url," .$image_col. " as article_page_image_path ,summary_html,publish_start_date FROM ".$tag_type." WHERE tags LIKE '%".$tags."%' AND publish_start_date <=NOW() AND status='P' ORDER BY publish_start_date DESC";
		$total_count=$this->live_db->query($tag_query)->num_rows();
		$this->pagination->initialize(pager(['total_rows'=>$total_count,'per_page'=>15,'base_url'=>$widget_url,'suffix'=>'','first_url'=>$widget_url]));
		$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
		$pagination=$this->pagination->create_links();
		$Result=$this->live_db->query($tag_query." LIMIT ".$row.", 15")->result();
		
	}

	if(count($Result) > 0):
		if($term=='') { $title= str_replace('_',' ',urldecode($this->uri->segment(2))); $title_type='Tag';} else { $title= $term ; $title_type='Search';}
		print '<ul class="ascending" id="table_sorter"><li style="float:left;">'.$title_type.' results for <span class="active">'. $title .'</span></li></ul>';
	endif;
	print '<table id="example" class="display result-section" cellspacing="0" width="100%">';
	foreach($Result as $fetched):
			$publisheddate    = date('jS F Y', strtotime($fetched->publish_start_date));
			$u=image_url;
			$fetched->article_page_image_path=($fetched->article_page_image_path=='')?'logo/nie_logo_600X390.jpg':$fetched->article_page_image_path;
			$low='logo/nie_logo_600X390.jpg';
			
			print '<tr>';
			print '<td><figure class="result-section-figure"><img src="'.$u.imagelibrary_image_path.$low.'" data-src="'.$u.imagelibrary_image_path.$fetched->article_page_image_path.'" class=""></figure></td>';
			print '<td><div class="search-row_type"><h4><a class="article_click" href="'.BASEURL.$fetched->url.'">'.strip_tags($fetched->title).'</a></h4><p>'.strip_tags($fetched->summary_html).'</p><date>published on : '.$publisheddate.'</date></div></td>';
			print '</tr>';
	endforeach;
	print '</table>';
	if(count($Result) == 0  && count($_GET) > 0):
		print '<div class="search_count text-center"><p>Your search did not match any content In Live</p></div>';
	endif;
	print '<div class="pagina">'.$pagination.$button.'</div>';
	if($tags==''):
		print '<div class="search_count" style="margin-top:5px;text-align:center;"><p><span>More from  :</span>'.$loadmore.'</p></div>';
	endif;
	
	?>
</div>
<script>
function adv_search(){
	var keyword,search_by,row_type_type,url;
	keyword=$('#keyword').val().trim().replace(/[^a-zA-Z ]/g, "");
	search_by=$('#field').val().trim();
	row_type_type=$('#type').val().trim();
	if(keyword==''){
		$('.error_search').show();
	}else if(keyword.length < 3){
		$('.error_search').show().html(' * Please Enter more than 2 letters');
	}else{
		if(keyword!='' && search_by=='' && row_type_type==''){
			url='<?php print $widget_url ?>?term='+keyword+'&request=ALL';
		}else{
			url='<?php print $widget_url ?>?term='+keyword+'&type='+search_by+'&row_type='+row_type_type+'&request=MIN';
		}
		window.location.href=url;
	}
	
}
function trigger_event(e){
	if(e.keyCode === 13){
       e.preventDefault();
       $('.adv_search_btn').trigger('click');
    }
}
</script>
<?php } ?>
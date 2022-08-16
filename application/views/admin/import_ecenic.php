<link href="<?php echo base_url(); ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />	
<link href="<?php echo base_url(); ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
<style>
.Ecenic_import_image td {
    float: none !important;
}
.Ecenic_import_image .fa-download {
    font-size: 26px;
    color: #3c8dbc;
}
</style>
<div class="Container">
<div class="BodyWhiteBG Overflow">


<div class="BodyHeadBg Overflow clear">

<div class="FloatLeft BreadCrumbsWrapper">
<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">Import Ecenic</a></div>
 <h2 class="FloatLeft">Import Ecenic</h2>
 
 <div class="FloatLeft SessionError" id="flash_msg_id" style="display:none;top: 145px;">
</div>

</div>


 
</div>

<div class="Overflow DropDownWrapper Ecenic_import">

  
 
<ul class="tabs margin-top-0 Article-Tab">
            <li class="selected" ><a href="#view1">Import Image</a></li>
            <li class=""><a href="#view2">Import Article</a></li>
			 <li class=""><a href="#view3">Import Gallery</a></li>
			 <li class=""><a href="#view4">Import Video</a></li>
			  <li class=""><a href="#view5">Import Audio</a></li>
</ul>

 <section class="tap-main Article-Tab">
        
        <div class="tabcontents">
    <div id="view1" style="display: block;">
		<section class="form2">
			<table class="Ecenic_import_image">
					<colgroup><col width="auto"><col width="750px"></colgroup>
						<tbody>
						<tr>
							  <td class="video_label" ><label>Import Image</label></td>
							  <td  >&nbsp  &nbsp &nbsp  &nbsp<a id="import_image" href="<?php echo base_url().'niecpan/migration/get_pictures' ?>"<i class="fa fa-download"></i></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Images </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $TotalCount; ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Remaining Images </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $TotalCount - ($TotalSuccess + $TotalFailure); ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Success Images </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $TotalSuccess; ?></td>
						</tr>
							<tr>
							  <td class="video_label" ><label>Total Failure Images </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $TotalFailure; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Success Images </label></td>
							  <td  > : &nbsp  &nbsp  <?php echo $CurrentSuccess; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Failure Images </label></td>
							  <td  > : &nbsp  &nbsp <?php echo $CurrentFailure; ?></td>
						</tr>
						</tbody>
			</table>
		</section>
	</div>
   
   <div id="view2" style="display: none;">
    	<section class="form2">
			<table class="Ecenic_import_image">
					<colgroup><col width="auto"><col width="750px"></colgroup>
						<tbody>
						<tr>
							  <td class="video_label" ><label>Import Articles</label></td>
							  <td  >&nbsp  &nbsp &nbsp  &nbsp<a id="import_article" href="<?php echo base_url().'niecpan/migration/get_articles' ?>"<i class="fa fa-download"></i></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Articles </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $ArticleTotalCount; ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Remaining Articles </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $ArticleTotalCount - ($ArticleTotalSuccess + $ArticleTotalFailure); ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Success Articles </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $ArticleTotalSuccess; ?></td>
						</tr>
							<tr>
							  <td class="video_label" ><label>Total Failure Articles </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $ArticleTotalFailure; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Success Articles </label></td>
							  <td  > : &nbsp  &nbsp  <?php echo $ArticleCurrentSuccess; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Failure Articles </label></td>
							  <td  > : &nbsp  &nbsp <?php echo $ArticleCurrentFailure; ?></td>
						</tr>
						</tbody>
			</table>
		</section>
	</div> 
	
	  
   <div id="view3" style="display: none;">
    	<section class="form2">
			<table class="Ecenic_import_image">
					<colgroup><col width="auto"><col width="750px"></colgroup>
						<tbody>
						<tr>
							  <td class="video_label" ><label>Import Gallery</label></td>
							  <td  >&nbsp  &nbsp &nbsp  &nbsp<a id="import_article" href="<?php echo base_url().'niecpan/migration/get_galleries' ?>"<i class="fa fa-download"></i></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Gallery </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $GalleryTotalCount; ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Remaining Gallery </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $GalleryTotalCount - ($GalleryTotalSuccess + $GalleryTotalFailure); ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Success Gallery </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $GalleryTotalSuccess; ?></td>
						</tr>
							<tr>
							  <td class="video_label" ><label>Total Failure Gallery </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $GalleryTotalFailure; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Success Gallery </label></td>
							  <td  > : &nbsp  &nbsp  <?php echo $GalleryCurrentSuccess; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Failure Gallery </label></td>
							  <td  > : &nbsp  &nbsp <?php echo $GalleryCurrentFailure; ?></td>
						</tr>
						</tbody>
			</table>
		</section>
	</div> 
	
	
   <div id="view4" style="display: none;">
    	<section class="form2">
			<table class="Ecenic_import_image">
					<colgroup><col width="auto"><col width="750px"></colgroup>
						<tbody>
						<tr>
							  <td class="video_label" ><label>Import Video</label></td>
							  <td  >&nbsp  &nbsp &nbsp  &nbsp<a id="import_article" href="<?php echo base_url().'niecpan/migration/get_video' ?>"<i class="fa fa-download"></i></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Video </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $VideoTotalCount; ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Remaining Video </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $VideoTotalCount - ($VideoTotalSuccess + $VideoTotalFailure); ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Success Video </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $VideoTotalSuccess; ?></td>
						</tr>
							<tr>
							  <td class="video_label" ><label>Total Failure Video </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $VideoTotalFailure; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Success Video </label></td>
							  <td  > : &nbsp  &nbsp  <?php echo $VideoCurrentSuccess; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Failure Video </label></td>
							  <td  > : &nbsp  &nbsp <?php echo $VideoCurrentFailure; ?></td>
						</tr>
						</tbody>
			</table>
		</section>
	</div> 
	
	<div id="view5" style="display: none;">
    	<section class="form2">
			<table class="Ecenic_import_image">
					<colgroup><col width="auto"><col width="750px"></colgroup>
						<tbody>
						<tr>
							  <td class="video_label" ><label>Import Audio</label></td>
							  <td  >&nbsp  &nbsp &nbsp  &nbsp<a id="import_article" href="<?php echo base_url().'niecpan/migration/get_audio' ?>"<i class="fa fa-download"></i></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Audio </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $AudioTotalCount; ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Remaining Audio </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $AudioTotalCount - ($AudioTotalSuccess + $AudioTotalFailure); ?></td>
						</tr>
						<tr>
							  <td class="video_label" ><label>Total Success Audio </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $AudioTotalSuccess; ?></td>
						</tr>
							<tr>
							  <td class="video_label" ><label>Total Failure Audio </label></td>
							  <td  > :  &nbsp  &nbsp <?php echo $AudioTotalFailure; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Success Audio </label></td>
							  <td  > : &nbsp  &nbsp  <?php echo $AudioCurrentSuccess; ?></td>
						</tr>
						</tr>
							<tr>
							  <td class="video_label" ><label>Current Failure Audio </label></td>
							  <td  > : &nbsp  &nbsp <?php echo $AudioCurrentFailure; ?></td>
						</tr>
						</tbody>
			</table>
		</section>
	</div> 
	
        </div>

</section>
  </div>
  

	
        </div>

</section>
  </div>
  


</div>
                     
</div>    
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/tabcontent.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#import_image").click(function(){
		$("#commom_loading").show();
		$("#loading_msg").html("Importing the Images");
	});
	
		$("#import_article").click(function(){
		$("#commom_loading").show();
		$("#loading_msg").html("Importing the Articles");
	});
});
</script>
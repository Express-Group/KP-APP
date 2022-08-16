<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/video-up.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
<script type="text/javascript"  src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"> </script>

<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/additional-methods.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#datetimepicker1').datetimepicker({
     	pickDate: false,
  	 	pickSeconds: true
    });
	 $('#datetimepicker2').datetimepicker({
     	pickDate: false,
 	  	pickSeconds: true
    });
	
	/*$('body').keypress(function (e) {
		if(e.which == 13) {
			$("#submit_btn").click();
		}
	});*/
	
	var p = document.getElementById("speed"),
	res = document.getElementById("result");
	p.addEventListener("input", function() {
		res.innerHTML = p.value;
		$("#hidden_val").val(p.value);
	}, false);
	
	$("#setttings_form").validate({
		rules: {
			slide_count: { required: true, number:true },
			other_stories: { required: true, number:true },
			subsection_count: { required: true, number:true, maxlength:4 },
			otherstories_perpage: { required: true, number:true, maxlength:4 },
			magazine_perpage: { required: true, number:true, maxlength:4 },
			trending_now: { required: true, number:true, maxlength:3 },
			trending_time: { required: true },
			mostread_time: { required: true},
			email_to:  {required: true, email: true},
			google_url: { required: true, url:true, maxlength:255 },
			facebook_url: { required: true, url:true, maxlength: 255 },
			twitter_url: { required: true, url:true, maxlength: 255 },
			rss_url: { required: true, url:true, maxlength: 255 },
			site_logo: { required: true, accept: 'JPEG|JPG|PNG|GIF'},
			fav_icon: { required: true, accept: 'ICO|ICON|GIF'},
		},
		messages:
		{
			email_to :  {  accept: 'Please enter valid email'},
			site_logo: {  accept: 'Please select valid image'},
			fav_icon: { accept: 'Please select valid icon'},
		},
		errorPlacement: function(error, element)
		{
			if(element.attr("name") == "site_logo")
				error.insertAfter($("#logo_error"));
			
			else if(element.attr("name") == "fav_icon")
				error.insertAfter($("#icon_error"));
				
			else if(element.attr("name") == "trending_time")
				error.insertAfter($("#trending_time_error"));
			
			else if(element.attr("name") == "mostread_time")
				error.insertAfter($("#read_time_error"));
			else if(element.attr("name") == "email_to")
			error.insertAfter($("#email_to_error"));
		
			else
				error.insertAfter($("#"+element.attr("name"))); 
		}
	});
	
	$("#submit_btn").click(function(){
		
		<?php if(isset($settings_result['sitelogo']) && $settings_result['sitelogo'] != "") {  ?>
			$('[name="site_logo"]').each(function(){
				 $(this).rules('remove', 'required');
			});
		<?php } ?>
		<?php if(isset($settings_result['favouriteicon']) && $settings_result['favouriteicon'] != "") {  ?>
			$('[name="fav_icon"]').each(function(){
				 $(this).rules('remove', 'required');
			});
		<?php } ?>
		
		if($("#hddn_icon").val() == "") {
			var image = $('#favicon_img');
			
			var ext = $('#fav_icon').val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','icon','ico']) > -1) 
			{
				//console.log(ext);
				//console.log($.inArray(ext, ['gif','icon','ico']));
				var originalWidth = image[0].naturalWidth; 
				var originalHeight = image[0].naturalHeight;
				//console.log(originalWidth);
				//console.log(originalHeight);
				if(originalWidth != 16 && originalHeight != 16)
				{
					$("#icon_error").html('Please upload 16X16 image.');
					return false;
				}
				else
				{
					$("#icon_error").html('');
				}
			}
		}
			
		if($("#setttings_form").valid())
		{
			var cnfrm = confirm("Are you sure you want to update the changes?");
			if(cnfrm == true)
				$("#setttings_form").submit();
		}
	});
	
	$("#site_logo").change(function(){
		preview_img(this, 'logo_img', 'hddn_logo');
	});
	
	$("#fav_icon").change(function(){
		preview_img(this, 'favicon_img', 'hddn_icon');
	});
	
	$("#newsletter_mail_on").change(function()
	{
	if(this.checked) 
	{
	$("#subscribe_emailto").show();
	} 
	else 
	{
	$("#subscribe_emailto").hide();
	}
	});
	
	<?php if(isset($settings_result['settings_id']) && $settings_result['settings_id'] == "") {  ?>
		$("#logo_img").hide();
		$("#favicon_img").hide();
	<?php } ?>
	
	<?php if($this->session->flashdata("message") != "") { ?>
		alert(<?php echo $this->session->flashdata("success_alert"); ?>);
	<?php } ?>
 });
 
 function preview_img(input, input_id, text_id)
 {
	 $('#'+text_id+'').val('');
	 $('#'+input_id+'').show();
	 if(input.files && input.files[0])
	 {
		 var newfile = new FileReader();
		 newfile.onload = function()
		 {
			 $('#'+input_id+'').attr('src', newfile.result);
		 }
		 newfile.readAsDataURL(input.files[0]);
	 }
 }
</script>

<style>
.time-icon .add-on{
	height:35px;
}
.time-icon i{
	margin-top:6px;
}
.br_top{
	border-top:1px solid #ccc;
}

.error
{
	color:#F00;
	display:table;
}
</style>

<form id="setttings_form" name="setttings_form" method="post" action="<?php echo base_url(); ?>niecpan/settings/insert_update" enctype="multipart/form-data">
	<div class="Container">
		<div class="BodyWhiteBG">
			<div class="BodyHeadBg Overflow clear">
				<div class="FloatLeft BreadCrumbsWrapper">
					<div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
					<h2 class="FloatLeft"><?php echo $title; ?></h2>
				</div>
				<p class="FloatRight save-back save_margin"> 
					<button class="btn-primary btn" id="submit_btn" name="submit_btn" type="button"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
			</div>
			<div class="Overflow DropDownWrapper">
				<section class="tap-main">
					<div class="setting">
						<div class="tabcontents">
							<div id="view1" style="display: block;">
								<div action="#" class="system-setting">
									<div class="create-use-set">
										<div class="qnsans">
											<div class="qns">
												<div class="question">Breaking news scroll speed</div>
											</div>
											<div class="ans">
												<span id="result"><?php if(isset($settings_result['breakingNews_scrollSpeed']) && $settings_result['breakingNews_scrollSpeed'] != "") { echo $settings_result['breakingNews_scrollSpeed']; } else { echo 2;} ?></span>
												<input class="BorderBoxNone margin-top-5" id="speed" type="range" min="1" max="10" value="<?php if(isset($settings_result['breakingNews_scrollSpeed']) && $settings_result['breakingNews_scrollSpeed'] != "") { echo $settings_result['breakingNews_scrollSpeed']; } else { echo 2;} ?>" />
												
												<input type="hidden" name="hidden_val" id="hidden_val" value="<?php if(isset($settings_result['breakingNews_scrollSpeed']) && $settings_result['breakingNews_scrollSpeed'] != "") { echo $settings_result['breakingNews_scrollSpeed']; } else { echo 2;} ?>" />
												
												<input type="hidden" name="hddn_settngid" id="hddn_settngid" value="<?php if(isset($settings_result['settings_id']) && $settings_result['settings_id'] != "") { echo $settings_result['settings_id']; }  ?>" />
                                                <p id="speed_note"><span>Note:</span> Increasing the scroll speed will slow down the breaking news</p>
											</div>
										</div>
                                        <div class="qnsans">
											<div class="qns">
												<div class="question">Slider count - Gallery Videos (Home page)</div>
											</div>
											<div class="ans">
												<input class="tb_style2 Width-70" type="text" min="1" max="10" maxlength="2" name="slide_count" id="slide_count" value="<?php if(isset($settings_result['slider_count']) && $settings_result['slider_count'] != "") { echo $settings_result['slider_count']; } ?>" />
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">Article count - Right side "other stories" (Article page)</div>
											</div>
											<div class="ans">
												<input class="tb_style2 Width-70" type="text" maxlength="3" name="other_stories" id="other_stories" value="<?php if(isset($settings_result['articlecountfortotherstories']) && $settings_result['articlecountfortotherstories'] != "") { echo $settings_result['articlecountfortotherstories']; } ?>" />
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">Number of Article count - Most read</div>
											</div>
											<div class="ans">
												<input class="tb_style2 Width-70" type="text"  maxlength="3" name="trending_now" id="trending_now" value="<?php if(isset($settings_result['articlecountformostreadnow']) && $settings_result['articlecountformostreadnow'] != "") { echo $settings_result['articlecountformostreadnow']; } ?>" />
											</div>
										</div>
										
										<div class="qnsans">
											<div class="qns">
												<div class="question">Section "other stories" count - Per page</div>
											</div>
											
											<div class="ans">
												<input  class="tb_style2 Width-70" type="text"  maxlength="3" name="otherstories_perpage" id="otherstories_perpage" value="<?php if(isset($settings_result['subsection_otherstories_count_perpage']) && $settings_result['subsection_otherstories_count_perpage'] != "") { echo $settings_result['subsection_otherstories_count_perpage']; } ?>" />
											</div>
										</div>
										
										<div class="qnsans">
											<div class="qns">
												<div class="question">Magazine list count - Per page</div>
											</div>
											
											<div class="ans">
												<input  class="tb_style2 Width-70" type="text"  maxlength="3" name="magazine_perpage" id="magazine_perpage" value="<?php if(isset($settings_result['magazine_list_count_perpage']) && $settings_result['magazine_list_count_perpage'] != "") { echo $settings_result['magazine_list_count_perpage']; } ?>" />
											</div>
										</div>
										
										<!--<div class="qnsans">
											<div class="qns">
												<div class="question">Section other stories count</div>
											</div>
											<div class="ans">
												<input  class="tb_style2 Width-70" type="text"  maxlength="3" name="subsection_count" id="subsection_count" value="<?php if(isset($settings_result['subsection_otherstories_autoCount']) && $settings_result['subsection_otherstories_autoCount'] != "") { echo $settings_result['subsection_otherstories_autoCount']; } ?>" />
											</div>
										</div>-->
										<div class="qnsans" style="display:none;">
											<div class="qns">
												<div class="question">Days interval - Most read</div>
											</div>
											<div class="ans">
												<input  class="tb_style2 Width-70" type="text"  maxlength="3" name="trending_time" id="trending_time" value="<?php if(isset($settings_result['Daysintervalformostreadnow']) && $settings_result['Daysintervalformostreadnow'] != "") { echo $settings_result['Daysintervalformostreadnow']; } ?>" />
                                                <p id="trending_time_error"></p>
											</div>
										</div>
										
										<div class="qnsans">
											<div class="qns">
												<div class="question">Time interval - Most Read</div>
											</div>
											<div class="ans">
												<div id="datetimepicker2" class="input-append time-icon">
													<input class="tb_style Width-70" data-format="hh:mm:ss" type="text" placeholder"From" name="mostread_time" id="mostread_time" value="<?php if(isset($settings_result['timeintervalformostreadarticle']) && $settings_result['timeintervalformostreadarticle'] != "") { echo $settings_result['timeintervalformostreadarticle']; } ?>"/>
													<span class="add-on"> <i  data-date-icon="icon-calendar"> </i> </span> </div>
													<p id="read_time_error"></p>
											</div>
										</div>
                                        <div class="qnsans">
											<div class="qns">
												<div class="question">Send Email on News letter Subscription</div>
											</div>
											<div class="ans">
												<div id="datetimepicker2" class="input-append time-icon">
													<input class="" type="checkbox" name="newsletter_mail_on" id="newsletter_mail_on" <?php if(isset($settings_result['send_email']) && $settings_result['send_email'] == 1) { ?> checked="checked" <?php } ?> value="1"/>
											</div>
													<p id="newsletter_check"></p>
											</div>
										</div>
                                        <div class="qnsans" id="subscribe_emailto" <?php if(isset($settings_result['send_email']) && $settings_result['send_email'] == 0) { ?> style="display:none;" <?php } ?>>
											<div class="qns">
												<div class="question">News letter Subscription Email to </div>
											</div>
											<div class="ans">
												<div id="datetimepicker2" class="input-append time-icon">
													<input type="text" class="tb_style2" maxlength="255" name="email_to" id="Email_to" value="<?php if(isset($settings_result['email_to']) && $settings_result['email_to'] != "") { echo $settings_result['email_to']; } ?>">
											</div>
													<p id="email_to_error"></p>
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">Site Logo</div>
											</div>
											<div class="ans">
												<div class="fileUpload btn btn-primary"> <span>Browse</span>
													<input class="upload" name="site_logo" id="site_logo" type="file">
												</div>
												
												<div class="WidthPercentage margin-top-5 FloatLeft"> 
													<img src="<?php if(isset($settings_result['sitelogo']) && $settings_result['sitelogo'] != "") { echo image_url.$settings_result['sitelogo']; } else '#'; ?>" style="height:100px; width:450px;" id="logo_img" name="logo_img" border="0" />
													
													<input type="hidden" name="hddn_logo" id="hddn_logo" value="<?php if(isset($settings_result['sitelogo']) && $settings_result['sitelogo'] != "") { echo $settings_result['sitelogo']; }  ?>" />
													
													<p id="logo_error"></p>
												</div>
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">Favicon</div>
											</div>
											<div class="ans">
												<div class="fileUpload btn btn-primary FloatLeft"> <span>Browse</span>
													<input class="upload" name="fav_icon" id="fav_icon" type="file">
												</div>
												<div class="margin-left-10 FloatLeft"> 
													<img src="<?php if(isset($settings_result['favouriteicon']) && $settings_result['favouriteicon'] != "") { echo image_url.$settings_result['favouriteicon']; } else '#'; ?>" style="height:35px; width:35px;" id="favicon_img" name="favicon_img" border="0" />
													
													<input type="hidden" name="hddn_icon" id="hddn_icon" value="<?php if(isset($settings_result['favouriteicon']) && $settings_result['favouriteicon'] != "") { echo $settings_result['favouriteicon']; }  ?>" />
													<p id="icon_error" style="color:#F00;"></p>
												</div>
											</div>
										</div>
										<div class="qnsans">
											<h3 class="br_top">External Services</h3>
											<div class="qns">
												<div class="question">Google+ Page URL</div>
											</div>
											<div class="ans">
												<input type="text" class="tb_style2" maxlength="255" name="google_url" id="google_url" value="<?php if(isset($settings_result['google_plus_url']) && $settings_result['google_plus_url'] != "") { echo $settings_result['google_plus_url']; } ?>">
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">Facebook URL</div>
											</div>
											<div class="ans">
												<input type="text" class="tb_style2" maxlength="255" name="facebook_url" id="facebook_url" value="<?php if(isset($settings_result['facebook_url']) && $settings_result['facebook_url'] != "") { echo $settings_result['facebook_url']; } ?>">
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">Twitter URL</div>
											</div>
											<div class="ans">
												<input type="text" class="tb_style2" maxlength="255" name="twitter_url" id="twitter_url" value="<?php if(isset($settings_result['twitter_url']) && $settings_result['twitter_url'] != "") { echo $settings_result['twitter_url']; } ?>">
											</div>
										</div>
										<div class="qnsans">
											<div class="qns">
												<div class="question">RSS URL</div>
											</div>
											<div class="ans">
												<input type="text" class="tb_style2"  maxlength="255" name="rss_url" id="rss_url" value="<?php if(isset($settings_result['rss_url']) && $settings_result['rss_url'] != "") { echo $settings_result['rss_url']; } ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</form>

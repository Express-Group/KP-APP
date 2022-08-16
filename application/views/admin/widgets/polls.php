<?php
$widget_bg_color       = $content['widget_bg_color'];
$is_home               = $content['is_home_page'];
$view_mode             = $content['mode'];
$domain_name           = base_url();
?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="polls">
      <fieldset class="FieldTopic">
        <legend class="topic">Poll</legend>
      </fieldset>
      <?php
	   $dummy_image  = image_url. imagelibrary_image_path.'logo/nie_logo_150X150.jpg';
		$polls = $this->widget_model->get_widget_Polls($view_mode);
			if($polls) 
					{ 
						$show_image                  = '';
						if($polls['image_path']!="")
						{
							$Image150X150            = str_replace("original","w150X150", $polls['image_path']);
							$imagealt                = $polls['image_alt'];
							$imagetitle              = $polls['image_title'];
						
						if (getimagesize(image_url_no . imagelibrary_image_path . $Image150X150) && $Image150X150 != '')
						{
							$show_image = image_url. imagelibrary_image_path . $Image150X150;
						}
					}
						$poll_vote = $this->widget_model->select_poll($polls['Poll_id'])->row_array();
				?>
      <?php if(isset($polls['Content_ID']) && $polls['Content_ID'] !="") { ?>
      <div class="posi_rel">
        <?php 
				
				$content_url      = $polls['article_url'];
				$param            = $content['close_param'];
				$live_article_url = $domain_name. $content_url.$param;
				$display_title    = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$polls['article_title']); //to remove first<p> and last</p>  tag
				 											
				?>
        <p class="polls_head"><strong>Read Article:</strong> <a href="<?php echo $live_article_url;?>" class="article_click">
          <?php if(isset($polls['Content_ID']) && $polls['Content_ID'] !="")echo $display_title; ?>
          </a></p>
        
      </div>
      <?php } ?>
      <div class="BeforePoll">
        <div class="polls_img">
          <?php if($show_image!="") { ?>
          <img src="<?php echo $dummy_image; ?>" data-src="<?php echo $show_image; ?>" title="<?php echo $imagetitle; ?>" alt="<?php echo $imagealt; ?>" width="150" height="150"/>
          <?php } ?>
          <p><?php echo $polls['PollQuestion']; ?></p>
        </div>
		<p id="error_txt" style="color:#F00"></p>
		<div id="thankyou_text" style="color:#337ab7; display:none;">You Already Voted.</div>
        <table>
          <tr>
            <td id="option_table"><?php if($polls['OptionText1']!="") { ?>
              <div>
                <input type="radio" value="1" set_value="<?php //if(isset($poll_vote['textvalue1']) && $poll_vote['textvalue1']!="") { echo $poll_vote['textvalue1']+1; } else { echo 1; } ?>"  name="radioOption" id="radioOption1"/>
                <label  for="radioOption1"><?php echo $polls['OptionText1']; ?></label>
              </div>
              <?php } ?>
              <?php if($polls['OptionText2']!="") { ?>
              <div>
                <input type="radio" value="2" set_value="<?php //if(isset($poll_vote['textvalue2']) && $poll_vote['textvalue2']!="") { echo $poll_vote['textvalue2']+1; } else { echo 1; } ?>" name="radioOption" id="radioOption2"/>
                <label  for="radioOption2"><?php echo $polls['OptionText2']; ?></label>
              </div>
              <?php } ?>
              <?php if($polls['OptionText3']!="") { ?>
              <div>
                <input type="radio" value="3" set_value="<?php //if(isset($poll_vote['textvalue3']) && $poll_vote['textvalue3']!="") { echo $poll_vote['textvalue3']+1; } else { echo 1; } ?>" name="radioOption" id="radioOption3"/>
                <label  for="radioOption3"><?php echo $polls['OptionText3']; ?></label>
              </div>
              <?php } ?>
              <?php if($polls['OptionText4']!="") { ?>
              <div>
                <input type="radio" value="4" name="radioOption" set_value="<?php //if(isset($poll_vote['textvalue4']) && $poll_vote['textvalue4']!="") { echo $poll_vote['textvalue4']+1; } else { echo 1; } ?>" id="radioOption4"/>
                <label  for="radioOption4"><?php echo $polls['OptionText4']; ?></label>
              </div>
              <?php } ?>
              <?php if($polls['OptionText5']!="") { ?>
              <div>
                <input type="radio" value="5" set_value="<?php //if(isset($poll_vote['textvalue5']) && $poll_vote['textvalue5']!="") { echo $poll_vote['textvalue5']+1; } else { echo 1; } ?>"  name="radioOption" id="radioOption5"/>
                <label  for="radioOption5"><?php echo $polls['OptionText5']; ?></label>
              </div>
              <?php } ?>
			</td>
            <td class="VoteButton padding-top-10">
				<button id="vote_button" name="vote_button" type="button">Vote</button>
              <br />
              <button id="vote-list" name="vote-list">View Results</button></td>
          </tr>
        </table>
      </div>
      <?php $get_count = $this->widget_model->select_poll($polls['Poll_id'])->num_rows();	?>
      <div class="after-vote">
        <p id="poll_msg" class="text-center"></p>
        <table>
          <tr>
            <th>Result</th>
          </tr>
          <?php if($polls['OptionText1']!="") { ?>
          <tr>
            <td class="vote-yes"><?php echo $polls['OptionText1']; ?></td></tr>
			<tr>
            <td class="vote-no"><div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="70"
						  aria-valuemin="0" aria-valuemax="100" id="result1"> </div>
              </div></td>
          </tr>
          <?php } ?>
          <?php if($polls['OptionText2']!="") { ?>
          <tr>
            <td class="vote-yes"><?php echo $polls['OptionText2']; ?></td></tr>
			<tr>
            <td class="vote-no"><div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="70"
						  aria-valuemin="0" aria-valuemax="100" id="result2"> </div>
              </div></td>
          </tr>
          <?php } ?>
          <?php if($polls['OptionText3']!="") { ?>
          <tr>
            <td class="vote-yes"><?php echo $polls['OptionText3']; ?></td></tr>
			<tr>
            <td class="vote-no"><div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="70"
						  aria-valuemin="0" aria-valuemax="100" id="result3"> </div>
              </div></td>
          </tr>
          <?php } ?>
          <?php if($polls['OptionText4']!="") { ?>
          <tr>
            <td class="vote-yes"><?php echo $polls['OptionText4']; ?></td></tr>
			<tr>
            <td class="vote-no"><div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="70"
						  aria-valuemin="0" aria-valuemax="100" id="result4"> </div>
              </div></td>
          </tr>
          <?php } ?>
          <?php if($polls['OptionText5']!="") { ?>
          <tr>
            <td class="vote-yes"><?php echo $polls['OptionText5']; ?></td></tr>
			<tr>
            <td class="vote-no"><div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="70"
						  aria-valuemin="0" aria-valuemax="100" id="result5"> </div>
              </div></td>
          </tr>
          <?php } ?>
          <tr>
            <td id="detailInfo"></td>
            <td class="back-botton"><button id="back-list">BACK</button></td>
          </tr>
        </table>
      </div>
      <?php } 
	   elseif($view_mode=="adminview")
	  {
	       echo '<div class="margin-bottom-10">No poll details to show</div>';
	  }
	  ?>
    </div>
  </div>
</div>
<script>

<?php if($polls) { ?>

$(document).ready(function()
{
	$("#error_txt").hide();
	var poll_id = "<?php echo $polls['Poll_id']; ?>";
	$("#vote_button").click(function()
	{
		var checkbox_count = $('input:radio[name=radioOption]:checked').attr('set_value');
		
		if(checkbox_count == "")
			checkbox_count = 0;
		var vote_count = parseInt(checkbox_count)+parseInt(1);
		
		//var vote_count = parseInt($('input:radio[name=radioOption]:checked').attr('set_value'))+parseInt(1);
		var check_option_length = $('input:radio[name=radioOption]:checked').length;
		var option_val = $('input:radio[name=radioOption]:checked').val();
		
		var option = (check_option_length != "") ? option_val : '';	
		
		if(option != "" && typeof option != 'undefined')
		{	
			$.ajax({
				type: "POST",
				data: {"get_option":option, "get_poll_id":poll_id, "get_count":vote_count},
				url: "<?php echo base_url(); ?>user/commonwidget/get_poll_results",
				success:function(data)
				{
					if(data == "success")
					{
						var session = "<?php echo $this->input->cookie('IE_pollID'); ?>";
						$('input:radio[name=radioOption]').attr('disabled', true);
						$('#vote_button').attr('disabled', true);
						
						poll_results();
						//$("#poll_msg").html("Thank you for voting.");
						$('#vote_button').hide();
						$('#option_table').hide();
						$('#thankyou_text').show();
						
						$(".BeforePoll").hide();
						$(".after-vote").show();	
					}
					$("#error_txt").html("").hide();
				}
			});
		}
		else
		{
			$("#error_txt").html("Please choose an option to vote.").show();
		}
	});
	
	
	$("#vote-list").click(function(){
		poll_results();
		$(".BeforePoll").hide();
		$(".after-vote").show();	
	});

	$("#back-list").click(function(){
		//$("#poll_msg").html("");
    	$(".BeforePoll").show();
		$(".after-vote").hide();
	});
	
	disable_buttton();
	poll_results();
	
function poll_results()
{	
	$.ajax({
			type: "POST",
			data: {"get_poll_id":poll_id},
			url: "<?php echo base_url(); ?>user/commonwidget/select_poll_results",
			dataType: "JSON",
			success:function(data)
			{
				//alert(data.textvalue3);
				if(data != "")
				{
					var calculate_count = (+data.textvalue1) + (+data.textvalue2) + (+data.textvalue3) + (+data.textvalue4) + (+data.textvalue5); 
					var option1_perc = data.textvalue1*100/calculate_count;
					var option2_perc = data.textvalue2*100/calculate_count;
					var option3_perc = data.textvalue3*100/calculate_count;
					var option4_perc = data.textvalue4*100/calculate_count;
					var option5_perc = data.textvalue5*100/calculate_count;
					
					var get_option1_perc = option1_perc.toFixed(0);
					var get_option2_perc = option2_perc.toFixed(0);
					var get_option3_perc = option3_perc.toFixed(0);
					var get_option4_perc = option4_perc.toFixed(0);
					var get_option5_perc = option5_perc.toFixed(0);
					
					$("#result1").html('('+data.textvalue1+')'+get_option1_perc+'%');
					$("#result2").html('('+data.textvalue2+')'+get_option2_perc+'%');
					$("#result3").html('('+data.textvalue3+')'+get_option3_perc+'%');
					$("#result4").html('('+data.textvalue4+')'+get_option4_perc+'%');
					$("#result5").html('('+data.textvalue5+')'+get_option5_perc+'%');
					
					$("#radioOption1").attr('set_value',data.textvalue1);
					$("#radioOption2").attr('set_value',data.textvalue2);
					$("#radioOption3").attr('set_value',data.textvalue3);
					$("#radioOption4").attr('set_value',data.textvalue4);
					$("#radioOption5").attr('set_value',data.textvalue5);
					
					$("#result1").css('width', get_option1_perc+'%');
					$("#result2").css('width', get_option2_perc+'%');
					$("#result3").css('width', get_option3_perc+'%');
					$("#result4").css('width', get_option4_perc+'%');
					$("#result5").css('width', get_option5_perc+'%');
					//alert(calculate_count);
					$('#detailInfo').html('Votes so far: '+calculate_count+'');
				}
				else
				{
					$("#result1, #result2, #result3, #result4, #result5").html('('+0+')'+0+'%');
					$("#result1, #result2, #result3, #result4, #result5").css('width', 0+'%');
					$('#detailInfo').html('Votes so far: 0');
				}
			}
		});
}
});


function disable_buttton()
{
	
	var poll_id = "<?php echo $polls['Poll_id']; ?>";
	var session = "<?php echo $this->input->cookie('IE_pollID'); ?>";
	
	var cookie_id = checkCookie();
	//if(session == poll_id)
	if(cookie_id == poll_id)
	{
		$('input:radio[name=radioOption]').attr('disabled', true);
		$('#vote_button').attr('disabled', true);
		$('#vote_button').hide();
		$('#option_table').hide();
		$('#thankyou_text').show();
	}
}

//Set poll id in cookie for home page html file

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie() {
    var check_cookieId = getCookie("IE_pollID");
    if (check_cookieId != "") {
		return check_cookieId;
       // alert("Welcome again " + user);
    } else {
		return '';
		//alert('no cookie');
    }
}
 <?php } ?>
</script>
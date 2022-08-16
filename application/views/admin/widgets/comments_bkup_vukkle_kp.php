<style>
#disclaimer{display:none;float:left;font-size:10px;margin-top:10px;bordeR:1px solid #d3cccc;padding:5px;}
#disclaimer p{font-size:14px; font-family: 'Droid regular', sans-serif;}
</style>
<?php
$content_id      = $content['content_id'];
$content_from    = $content['content_from'];
$content_type_id = $content['content_type'];
if($content_id!=''){
$content_det     = $content['detail_content'][0];
$allow_comments  = ($content_from=="preview")? $content_det['Allowcomments'] : $content_det['allow_comments'];
if($allow_comments == 1) { ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ArticleDetail">
    <div class="ArticleComments">
      <?php 	
		$article_comments = $this->comment_model->get_comments_by_article_id($content_id); 
		$comments_count = ($article_comments['count']>0)? '('.$article_comments['count'].')': '' ;
		?>
      <h4 class="ArticleHead comment_head">Comments<span id="comments_count"><?php echo $comments_count;?></span></h4>
      <div class="CommentForm"> 
        <form action="<?php echo base_url(); ?>user/commonwidget/post_comment" method="post" name="comment_form" id="comment_form">
          <textarea placeholder="Write a comment..."  name="article_comment" id="article_comment" style="resize: vertical;overflow: auto;"></textarea>
          <!--<div class="social_icons FloatLeft"> <a href="#" class="fb"><i class="fa fa-facebook"></i></a> <a href="#" class="google"><i class="fa fa-google-plus"></i></a> <a href="#" class="twit"><i class="fa fa-twitter"></i></a>  </div>-->
          <div class="post">
            <input type="text" placeholder="Name" name="name" class="form-control tb">
            <input type="text" placeholder="Email" name="email" class="form-control tb">
            <input type="hidden" id="content_id" class="content_id" name="content_id" value="<?php echo $content_id;?>" />
            <input type="hidden" id="content_type_id" class="content_type_id" name="content_type_id" value="<?php echo $content_type_id;?>" />
            <input type="hidden" id="comment_id" class="comment_id" name="comment_id" value="" />
            <input type="submit" value="Post" class="submit_post" name="submit">
          </div>
        </form>
        <h6 id="comment_validate" class="WidthFloat_L margin-0" style="color:red;"></h6>
        <div class="CloseReply" style="display:none;"><i class="fa fa-times-circle"></i></div>
		<div id="disclaimer"> 
			  <p><span style="color:red;">Disclaimer </span>: We respect your thoughts and views! But we need to be judicious while moderating your comments. All the comments will be moderated by the newindianexpress.com editorial. Abstain from posting comments that are obscene, defamatory or inflammatory, and do not indulge in personal attacks. Try to avoid outside hyperlinks inside the comment. Help us delete comments that do not follow these guidelines.</p>

			  <p>The views expressed in comments published on newindianexpress.com are those of the comment writers alone. They do not represent the views or opinions of newindianexpress.com or its staff, nor do they represent the views or opinions of The New Indian Express Group, or any entity of, or affiliated with, The New Indian Express Group. newindianexpress.com reserves the right to take any or all comments down at any time.</p>
		</div>
      </div>
	
    </div>
    <?php $comments = $article_comments['view_comments'];
	if($comments!=''){
	?>
    <div id="show_comments">
      <?php echo $comments;?> 
    </div>
   <?php } ?>
  </div>
</div>
<?php } 
}
?>
<script>
$('#article_comment').keyup(function() {
     var x = document.getElementById('disclaimer');
    if($(this).val() == "") {
      x.style.display = 'none';
    } else {
      x.style.display = 'block';
    }
});



</script>
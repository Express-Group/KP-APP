<style>
.rasipalan-side{
    border: 1px solid #ccc!important;
    float: left;
    width: 100%;
    margin-bottom: 10px;
}
.rasipalan-side h4{
    color: #fff!important;
    background: #92000b;
    margin: 0;
    text-align: center;
    padding: 7px;
}
.rasipalan-side .rasi-icon{
    margin: 0;
    float: left;
	padding: 25px 0 25px;
}
.rasi-gap, .rasi-icon{
    width: 100%;
    float: left;
    background: #faebd7;
}
.rasi-12{
    padding: 3px 21px;
    width: 100%;
	text-align:center;
}
.rasi-12 img{
    width: 15.17%;
    margin: 0 14px;
}
@media only screen and (max-width: 1550px) and (min-width: 1297px){
.rasi-12{
    padding: 3px 1px;
}
.rasi-12 img{
    margin: 0 13px;
}
}
@media only screen and (max-width: 1199px){
	.rasi-12 img{margin: 0 11px;width: 15%;}
	.rasi-12{padding: 3px 4px;}
}
</style>
<?php
$widget_bg_color     = $content['widget_bg_color'];
$param 		= $content['page_param'];
$widget_id 	= $content['widget_values']['data-widgetpageid'];
$url 		= base_url();
$image_url 	= image_url;
$result 	=  $this->db->query('CALL get_astro_Ids()')->result_array();
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="rasipalan-side" >
			<h4>ರಾಶಿ ಭವಿಷ್ಯ   </h4>
			<articel  class="rasi-icon"  <?php echo $widget_bg_color ; ?>>
				<div class="rasi-12"> 
					<?php if(isset($result[0]['URLSectionStructure'])) { ?>
					<a href="<?php echo $url.$result[0]['URLSectionStructure']; ?>" title="<?php echo $result[0]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-2.png" /></a> 
					<?php } ?>
					<?php if(isset($result[1]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[1]['URLSectionStructure']; ?>" title="<?php echo $result[1]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-12.png" /></a> 
					<?php } ?>
					<?php if(isset($result[2]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[2]['URLSectionStructure']; ?>" title="<?php echo $result[2]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-5.png" /></a> 
					<?php } ?>
					<?php if(isset($result[3]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[3]['URLSectionStructure']; ?>" title="<?php echo $result[3]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-1.png"  /></a> 
					<?php } ?>
				</div>
				<div class="rasi-12"> 
				<?php if(isset($result[4]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[4]['URLSectionStructure']; ?>" title="<?php echo $result[4]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-4.png" /></a>
					<?php } ?>
					<?php if(isset($result[5]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[5]['URLSectionStructure']; ?>" title="<?php echo $result[5]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-10.png" /></a>
					<?php } ?>
					<?php if(isset($result[6]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[6]['URLSectionStructure']; ?>" title="<?php echo $result[6]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-3.png" /></a>
					<?php } ?>
					<?php if(isset($result[7]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[7]['URLSectionStructure']; ?>" title="<?php echo $result[7]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-7.png" /></a>
					<?php } ?>
				</div>
				<div class="rasi-12"> 
				<?php if(isset($result[8]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[8]['URLSectionStructure']; ?>" title="<?php echo $result[8]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-8.png" /></a> 
					<?php } ?>
					<?php if(isset($result[9]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[9]['URLSectionStructure']; ?>" title="<?php echo $result[9]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-6.png" /></a> 
					<?php } ?>
					<?php if(isset($result[10]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[10]['URLSectionStructure']; ?>" title="<?php echo $result[10]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-11.png" /></a> 
					<?php } ?>
					<?php if(isset($result[11]['URLSectionStructure'])) { ?>
					<a href="<?php echo  $url.$result[11]['URLSectionStructure']; ?>" title="<?php echo $result[11]['Sectionname']; ?>"><img src="<?php echo $image_url; ?>images/FrontEnd/images/social-icon-set/social_icon.png" data-src="<?php echo $image_url; ?>images/FrontEnd/images/rasi/rasi-9.png" /></a> 
					<?php } ?>
				</div>
			</articel>
		</div>
	</div>
</div>
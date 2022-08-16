<?php 
// widget config block Starts - This code block assign widget background colour, title and instance id. Do not delete it 
$widget_bg_color 		= $content['widget_bg_color'];
$widget_custom_title 	= $content['widget_title'];
$widget_instance_id 	=  $content['widget_values']['data-widgetinstanceid'];
$view_mode              =  $content['mode'];
$widget_instancemainsection	= $this->widget_model->get_tab_sectiondetails($widget_instance_id, $content['mode']);
// Code block A - this code block is needed for creating simple tab widget. Do not delete
$domain_name =  base_url();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="navbar navbar-inverse navbar-fixed-top main-menu secondary-menu menu" role="navigation" style="margin-bottom:0px; position:relative; color:#fff;">
			
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand home_logo" rel="home" href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a>
			</div>
			
			<div class="collapse navbar-collapse">
			
				<ul class="nav navbar-nav menus">
                <?php
				$l=0; 

				foreach($widget_instancemainsection as $get_section)
				{
				$MainSectionPageURL = base_url(). $get_section['URLSectionStructure'];
				$SectionPageURL     = $get_section['URLSectionStructure'];
				$url_segment = $this->uri->uri_string();
				$add_active = ($view_mode=="live" && ($SectionPageURL==$url_segment))? "active" : "";
				$add_attr = ($l>1)?'id="tab'.$get_section['Section_ID'].'"' : '';
				echo '<li '.$add_attr.' class="StatesHover"><a class="MenuItem '.$add_active.'" href="'.$MainSectionPageURL.'">'.$get_section['CustomTitle'].'</a></li>';
				$l++;
				}			
				?>
				</ul>
				
			</div>
		</div>
	</div>
</div>



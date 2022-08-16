<?php
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
$is_home = $content['is_home_page'];
$view_mode = $content['mode'];
$header_details = $this->widget_model->select_setting($view_mode);
?>
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
  function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

<center>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff">
	<tbody>
		<tr>
			<td valign="top" align="center">
				<div class="main_logo">
					<?php 
						echo '<a target="_blank" href="'.base_url().'"><img src="'.image_url.$header_details['sitelogo'].'"></a>';
					?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
</center>
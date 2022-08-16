<?php $is_home = $content['is_home_page']; ?>
<div class="main_logo">
<?php
if($is_home != "y") 
{
echo '<a href="'.base_url().'">

<img src="'.$image_path_inwd.'images/main-logo.jpg"></a>';
}
else
{
	echo '<img  src="'.$image_path_inwd.'images/main-logo.jpg">';
}
?>

</div>

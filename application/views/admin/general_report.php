<?php $script_url = image_url; ?>
<link href="<?php echo $script_url; ?>css/admin/bootstrap.min.css" rel="stylesheet" >
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<style>
.Mis-Report {
	width:100%;
	float:left;
	margin-top: 50px;
}
.Mis-Report .Mis-Left-Area {
	float:left;
	width:40%;
	text-align:right;
	margin-right:50px;
}
.Mis-Report .Mis-Right-Area {
	float:left;
	width:40%;
	text-align:left;
	margin-left:50px;
}
.Mis-Exchange {
	float:left;
}
.Mis-Exchange i {
	font-size: 31px;
	color: #3c8dbc;
	margin-top: 75px;
}
.Mis-Left-Area h4 {
	margin-top:0;
}
.Mis-Report .Mis-Left-Area textarea, .Mis-Report .Mis-Right-Area textarea {
	min-height:200px;
	border: 2px solid #ccc;
}
.Mis-Export {
	width: 78%;
	margin-top: 30px;
}
.Mis-Export button {
	float:right;
}
.Mis-Right-Area select[disabled]{
	background:#fff !important;
}
.Mis-Right-Area select option{
	color:#666 !important;
}
</style>

<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
				<div class="breadcrumbs">Dashboard > <?php echo $title; ?></div>
				<h2><?php echo $title; ?></h2>
			</div>
		</div>
		
		<form method="post" id="general_report" name="general_report" action="<?php echo base_url().folder_name;?>/general_report/general_report_excel" enctype="multipart/form-data">
		
		<div class="Overflow DropDownWrapper">
			<div class="container">
				<div class="row AskPrabuCheckBoxWrapper">
					<ul class="AskPrabuCheckBox FloatLeft">
						<!--<li class="has-pretty-child">
							<div class="clearfix prettycheckbox labelright  red">
								<input type="checkbox" id="search_based_check"  class="myClass" value="yes" name="answer">
								<a class=" " href="#"></a>
								<label for="search_based_check">Search Based on Date Range</label>
							</div>
							<a href="#" class=""></a> </li>-->
						<li>
							<p class="CalendarWrapper" id="checkin_checkout_div">
								<label for="search_based_check">Search Based on Date Range</label>
								<input type="text" value="" id="date_timepicker_start" name="date_timepicker_start" placeholder="Start Date">
								<input type="text" value="" id="date_timepicker_end" name="date_timepicker_end" placeholder="End Date">
							</p>
						</li>
					</ul>
				</div>
			</div>
			<div class="FloatLeft TableColumn">
				<!--<div class="FloatLeft w2ui-field">
					<select id="article_status" class="controls">
						<option value="">Author: All</option>
					</select>
				</div>-->
				<div class="FloatLeft w2ui-field">
					<select id="article_status" name="article_status" class="controls">
						<option value="">Status: All</option>
						<option value="D" >Draft</option>
						<option value="P" >Published</option>
						<option value="U" >Unpublished</option>
					</select>
				</div>
			</div>
			<div class="Mis-Report">
				<div class="Mis-Left-Area">
					<h4 class="FloatLeft">Section</h4>
					<select id="ddsection" name="ddsection[]" class="controls" multiple="multiple" size="10" onchange="select_section();" >
					
						<?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {   
				 
				 
				$condition = $mapping['Sectionname'] != 'Galleries' && $mapping['Sectionname'] != 'Videos' && $mapping['Sectionname'] != 'Audios' &&  $mapping['Sectionname'] != 'Resources';
				 
				 //if($condition) {
					 
				 ?>
						<option id="MainSectionOption" style="color:#933;font-size:18px;" <?php if(isset($mapping['sub_section']) && count($mapping['sub_section']) > 0) { ?> disabled='disabled' <?php } ?> class="blog_option" <?php if(set_value("ddMainSection") == $mapping['Section_id'] || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $mapping['Section_id'] )) echo  "selected";  ?> sectoin_data="<?php //echo @$mapping['Sectionname']; ?>" rel="<?php echo @$mapping['LinkedToColumnist']; ?>"  value="<?php echo $mapping['Section_id']; ?>" url_structure="<?php echo ucwords(str_replace("-"," ",str_replace("/"," > ",trim($mapping['URLSectionStructure'])))); ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
						<?php if(!(empty($mapping['sub_section'])) ) { ?>
						<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
						<option  id="MainSectionOption" <?php if(set_value("ddMainSection") == $sub_mapping['Section_id']  || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_mapping['Section_id'] )) echo  "selected"; ?>  sectoin_data="<?php echo @$mapping['Sectionname']; ?>"  rel="<?php echo @$sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_mapping['Section_id']; ?>" url_structure="<?php echo ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_mapping['URLSectionStructure'])))); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
						<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
						<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
						<option id="MainSectionOption" <?php if($sub_sub_mapping['Section_landing'] == 1) { ?> disabled='disabled' <?php } ?>  <?php if(set_value("ddMainSection") == $sub_sub_mapping['Section_id']  || (isset($get_article_details['content_id']) && $get_article_details['Section_id'] == $sub_sub_mapping['Section_id'] )) echo  "selected"; ?>  rel="<?php echo @$sub_sub_mapping['LinkedToColumnist']; ?>" value="<?php echo $sub_sub_mapping['Section_id']; ?>"  sectoin_data="<?php echo @$mapping['Sectionname']; ?>" url_structure="<?php echo ucwords(str_replace("-"," ",str_replace("/"," > ",trim($sub_sub_mapping['URLSectionStructure'])))); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
						<?php } } ?>
						<?php  } } ?>
						<?php  } } ?>
					</select>
				</div>
				<!--<div class="Mis-Exchange"><a href="javascript:void(0);" ><i class="fa fa-exchange" onclick="select_section();"></i></a></div>-->
				
				<div class="Mis-Exchange"><!--<i class="fa fa-exchange"></i>--></div>
				<div class="Mis-Right-Area">
					<h4 class="FloatLeft" style="margin-right:20px">Selected Section</h4>
					<select id="selected_section" class="controls" multiple="multiple" size="10" disabled="disabled"></select>
					<!--<textarea></textarea>-->
				</div>
			</div>
			<div class="FloatLeft TableColumn Mis-Export">
				<!--<a href="#" id="export_excel" target="_blank" ><button class="btn btn-primary" id="clear_search">Export</button></a>-->
				<button class="btn btn-primary" id="export_excel">Export</button>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script> 
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script> 
<script>
function get_date(input) {
if(input == '') {
return false;
}	else {
// Split the date, divider is '/'
var parts = input.match(/(\d+)/g);
return parts[2]+'/'+parts[1]+'/'+parts[0];
} 
}

jQuery(function(){
 jQuery('#date_timepicker_start').datetimepicker({
  format:'d-m-Y',
  onShow:function(ct){
   this.setOptions({
	   maxDate:get_date($('#date_timepicker_end').val())?get_date($('#date_timepicker_end').val()):false,
   })
  },
  timepicker:false
 });
 jQuery('#date_timepicker_end').datetimepicker({
  format:'d-m-Y',
  onShow:function(ct){
   this.setOptions({
	   minDate:get_date($('#date_timepicker_start').val())?get_date($('#date_timepicker_start').val()):false,
   })
  },
  timepicker:false
 });
});

$(document).ready(function() 
{
	$("#search_based_check").change(function()
	{
		if(this.checked) 
		{
			$("#checkin_checkout_div").show();
		} 
		else 
		{
			$("#date_timepicker_start").val('');
			$("#date_timepicker_end").val('');
			$("#checkin_checkout_div").hide();
		}
		$("#checkin_id").val('');
		$("#checkout_id").val('');
	});
	
	$("#export_excel").click(function()
	{
		var ddsection = $("#ddsection").prop('selectedIndex');
		if(ddsection == -1) {
			alert("Please select section to export in excel");
			return false;
		}
		else {
			$("#general_report").submit();
		}
	});
	
	$("#export_excel_old").click(function()
	{
		var new_section = []; 
		$('#ddsection :selected').each(function(i, selected){ 
			var sel_text = $.trim($(selected).text());
			var sel_val =  $.trim($(selected).val());
			var parent_section =  $.trim($(selected).attr("sectoin_data"));
			var url_structure =  $.trim($(selected).attr("url_structure"));
			new_section.push({"sel_value":sel_val, "parent_section":parent_section});
		});
		var selected_data = JSON.stringify(new_section);
		
		var data_url = "<?php echo base_url().folder_name;?>/general_report/general_report_excel";
		var status = $("#article_status").val();
		$.ajax({
				type: "POST",
				data: {"selected_data":selected_data, "status":status},
				url: data_url,
				//dataType: 'json',
				success:function(response)
				{
					console.log(response);
					//location.href = response.URL;
				}
		});
	});
});

function select_section()
{
	$('#selected_section').empty(); //empty the selected drop down 
	
	/*var new_section = []; 
	$('#ddsection :selected').each(function(i, selected){ 
		var sel_text = $.trim($(selected).text());
		var sel_val =  $.trim($(selected).val());
		var parent_section =  $.trim($(selected).attr("sectoin_data"));
		var url_structure =  $.trim($(selected).attr("url_structure"));
		
		new_section.push({"text": sel_text, "sel_value":sel_val, "parent_section":parent_section, "url_structure":url_structure});
	});
	var selected_data = JSON.stringify(new_section);*/
	
	
	//console.log(new_section);
	
	$('#ddsection :selected').each(function(i, selected)
	{ 
		var sel_text = $.trim($(selected).text());
		var sel_val =  $.trim($(selected).val());
		var url_structure =  $.trim($(selected).attr("url_structure"));
		var parent_section =  $.trim($(selected).attr("sectoin_data"));
		
		if(parent_section != "")
			var display_text = parent_section+' > '+sel_text;
		else
			var display_text = sel_text;
			
		 $('#selected_section').append(
			$("<option></option>")
			.attr({"value": sel_val, "sectoin_data":parent_section, "url_structure":url_structure})
			.text(display_text)
		); 
	});}



</script>
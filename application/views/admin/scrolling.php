<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
	th{background-color:#84c7ea;text-align:center;}
	.table{margin-top:7%;}
	.modal{top:75px;}
	.form-group{width:100%;}
	.form-group input[type="text"]{border:none;border-radius:0;border-bottom:2px solid #3c8dbc;width:100%;}
	.form-group input[type="text"]:hover,.form-group input[type="text"]:active,.form-group input[type="text"]:focus{border-bottom:2px solid #4CAF50;}
	.success{background-color:#009688 !important;}
	.success:hover,.success:active,.success:focus{background-color:#0d9689 !important;}
	.error{background-color:#F44336 !important;}
	.error:hover,.error:active,.error:focus{background-color:#e42315  !important;}
	.error_message{width:100%;float:left;color:#e42315;}
	.table tr:nth-of-type(even){background-color: #dff5ff}
	.table tr:nth-of-type(even):hover{background-color: #cce9f6}
	.BodyHeadBg{position:relative;top: 34px;}
	label{font-weight: 700 !important;font-size: 17px;}
	.modal-content{border-radius:0px;}
	.modal-title{text-align: center;color: #3c8dbc;font-weight: 700 !important;}
	.btn-primary{border-radius:0px !important;}
	.delete_title{margin:0;text-align:center;color:red;}
  </style>
<div class="Container">
	<div class="BodyHeadBg Overflow clear">
		<div class="FloatLeft  BreadCrumbsWrapper PollResult">
			<div class="breadcrumbs">Dashboard > Scrolling News Manager</div>
			<h2>Scrolling News Manager</h2>
		</div>
		<p class="FloatRight SaveBackTop article_save">
			<a href="javascript:add_scroll_news();" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Add Scrolling News</a>
		</p>
		<table class="table table-bordered" id="table-jquery">
			<tr>
				<th>ID</th>
				<th>Content</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			<?php
			$i=1;
			foreach($content as $data){
			print '<tr class="scroll_'.$data->sid.' text-center">';
			print '<td>'.$i.'</td>';
			print '<td class="content_'.$data->sid.'">'.$data->content.'</td>';
			print '<td><button class="btn btn-primary" onclick="edit_news('.$data->sid.');" data-toggle="tooltip" data-placement="right" title="edit"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>';
			print '<td><button class="btn btn-primary error" onclick="delete_news('.$data->sid.');" data-toggle="tooltip" data-placement="right" title="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>';
			print '</tr>';
			$i++;
			}
			?>
		</table>
	</div>
		<div class="modal fade" id="add_news" role="dialog">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Add Scrolling News</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Enter Scrolling News:</label>
						<input type="text" name="news" id="news" class="">
						<span class="error_message" id="news_error"></span>
						<input type="hidden" id="edit_new_id" value="">
					</div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary success main-btn" onclick="save_news();"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
				  <button type="button" class="btn btn-primary error" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
				</div>
			  </div>
			</div>
	  </div>
	  <div class="modal fade" id="delete_news" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Are you sure want to delete?</h4>
				  <p class="delete_title"></p>
				</div>
				<div class="modal-body">
					<div class="form-group" style="text-align:center;">
						<button type="button" class="btn btn-primary success main-btn" id="delete-news-modal" ><i class="fa fa-check-circle" aria-hidden="true" ></i> Ok</button>
						<button type="button" class="btn btn-primary error" data-dismiss="modal"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Cancel</button>
					</div>
				</div>
			  </div>
			</div>
	  </div>
</div>

<script>
 $('[data-toggle="tooltip"]').tooltip(); 
function add_scroll_news(){
	$('.main-btn').attr('onclick','save_news()');
	$('#news').val('');
	$("#add_news").modal({backdrop: 'static', keyboard: false});
	$('.modal-title').html('Add Scrolling News');
	$('input[type="text"]').focus();
}

function save_news(){
	var news=$('#news').val();
		if(news.trim() ==''){
			//$('#news_error').show().html('Please fill the required fields');
			toastr["error"]("Please fill the required fields");
		}else{
			//$('#news_error').hide();
			var ajax=$.ajax({
				type:'post',
				cache:false,
				url:'<?php print base_url()?>niecpan/scrolling_news/save_news/',
				data:{'news':news}
			});
			ajax.success(function(result){
				if(result==1){
					toastr["success"]("Scrolling News Created Successfully");
					$('#news').val('');
					$("#add_news").modal("hide");
					location.reload();
				}else{
					toastr["error"]("Something went wrong..try again");
				}
				
				
			});
		}	
}
function edit_news(sid){
	var news=$('.content_'+sid).text();
	$('#news').val(news);
	$('#edit_new_id').val(sid);
	$('.main-btn').attr('onclick','save_edit_news()');
	$("#add_news").modal({backdrop: 'static', keyboard: false});
	$('.modal-title').html('Edit Scrolling News');
	$('input[type="text"]').focus();
}

function save_edit_news(){
	var sid=$('#edit_new_id').val();
	var news=$('#news').val();
	if(news==''){
			toastr["error"]("Please fill the required fields");
		}else{
			//$('#news_error').hide();
			var ajax=$.ajax({
				type:'post',
				cache:false,
				url:'<?php print base_url()?>niecpan/scrolling_news/save_edit_news/',
				data:{'news':news,'sid':sid}
			});
			ajax.success(function(result){
				if(result==1){
					$('.content_'+sid).html(news);
					toastr["success"]("Scrolling News updated Successfully");
					$('#news').val('');
					$("#add_news").modal("hide");
				}else{
					toastr["error"]("Something went wrong..try again");
				}
				
				
			});
		}

}


function delete_news(sid){
$('.modal-title').html('Are you sure want to delete?');
$("#delete_news").modal({backdrop: 'static', keyboard: false});
$('#delete-news-modal').attr('onclick','del_news('+sid+')');
$('.delete_title').html($('.content_'+sid).text());
}

function del_news(sid){
	var ajax=$.ajax({
		type:'post',
		cache:false,
		url:'<?php print base_url()?>niecpan/scrolling_news/delete_news/',
		data:{'sid':sid}
		});
		
		ajax.success(function(result){
		if(result==1){
			$("#delete_news").modal("hide");
			toastr["success"]("Scrolling News deleted Successfully");
				$('.scroll_'+sid).hide(1000);
		}else{
			toastr["error"]("Something went wrong..try again");
			$("#delete_news").modal("hide");
		}
		});

}
</script>

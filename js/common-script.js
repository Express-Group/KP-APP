// JavaScript Document

/*---------------------------------------*/

$(function () {
               
 $("#datetimepicker1").datetimepicker({
format: 'DD-MM-YYYY',
});
});
/*---------------------------------------*/

   $(function () {
                $("#datetimepicker2").datetimepicker({
format: 'DD-MM-YYYY',
});
            });
/*---------------------------------------*/

$().ready(function(){

  $('input.myClass').prettyCheckable({
    color: 'red'
  });

});

/*---------------------------------------*/
function uncheck()
{
	$(".checked").removeClass("checked");
}

/*---------------------------------------*/
$(document).ready(function() {
	$('#example').dataTable( {
		/*"processing": true,
		"serverSide": true,
		"ajax": "scripts/server_processing.php",
		"deferLoading": 57*/
	} );
} );

/*---------------------------------------*/

function ck_width()
{
	$("#cke_editor1").css({"width": "99%"});
}


/*---------------------------------------*/

	var people = ['George Washington', 'John Adams', 'Thomas Jefferson', 'James Buchanan', 'James Madison', 'Abraham Lincoln', 'James Monroe', 'Andrew Johnson', 'John Adams', 'Ulysses Grant', 'Andrew Jackson', 'Rutherford Hayes', 'Martin VanBuren', 'James Garfield', 'William Harrison', 'Chester Arthur', 'John Tyler', 'Grover Cleveland', 'James Polk', 'Benjamin Harrison', 'Zachary Taylor', 'Grover Cleveland', 'Millard Fillmore', 'William McKinley', 'Franklin Pierce', 'Theodore Roosevelt', 'John Kennedy', 'William Howard', 'Lyndon Johnson', 'Woodrow Wilson', 'Richard Nixon', 'Warren Harding', 'Gerald Ford', 'Calvin Coolidge', 'James Carter', 'Herbert Hoover', 'Ronald Reagan', 'Franklin Roosevelt', 'George Bush', 'Harry Truman', 'William Clinton', 'Dwight Eisenhower', 'George W. Bush', 'Barack Obama'];
	$('input[type=list]').w2field('list', { items: people });
	$('input[type=combo]').w2field('combo', { items: people });
	
	$("#placehold").attr("placeholder", "Sort By: All");
	$("#placehold1").attr("placeholder", "Status: All");
	
	
		$("#datetimepicker1").attr("placeholder", "From Date");
	$("#datetimepicker2").attr("placeholder", "To Date");
	
/*---------------------------------------*/

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});


    $(function () {
                $('#datetimepicker1').datetimepicker();
            });
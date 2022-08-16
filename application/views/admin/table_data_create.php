<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<!--JQuery-->
<script src="<?php echo base_url(); ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url(); ?>js/jquery.jeditable.js" type="text/javascript"></script>
       <!-- <script src="media/js/jquery-ui.js" type="text/javascript"></script>
        <script src="media/js/jquery.validate.js" type="text/javascript"></script>-->
<script src="<?php echo base_url(); ?>js/jquery.dataTables.editable.js" type="text/javascript"></script>
<link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />

<style>
.error {
	color:#F00;
	display:block;
}
#exist_msg {
	color:#F00;
}
</style>
<?php
$columns = "";
$newcol ="";
$table_data="";
if(isset($table_details))
{
foreach($table_details as $data)
{
	$table_data_id = $data['id'];
	$table_data_name = $data['table_data_name'];
	$table_data_heading = $data['Headings'];
	$column_data = ($data['column_data']!='')? unserialize($data['column_data']): '';
	$column_count = ($column_data!='')? count($column_data): '';
	$table_data = unserialize($data['table_data']);
}
$columns = "";
$newcol ="";
if($table_data!=''){
$mdataprop =  array_keys($table_data[0]);
$newcol = substr_count(implode(',', $mdataprop), 'newField');
$i=0;
foreach($column_data as $cdata){
   // $columns[] = array("sTitle" => $i , "sType" => "string");
	 $columns[] = array("mDataProp" => $mdataprop[$i] , "sTitle" => $cdata , "sType" => "string");
$i++;
}
//print_r(json_encode($columns));exit;
$table_data = json_encode($table_data);
$columns = json_encode($columns);
//$mdataprop = array_keys($table_data[0]);
//print_r(array_keys($table_data[0]));exit;
}
}
function changekeyname($array, $newkey, $oldkey)
{
   foreach ($array as $key => $value) 
   {
      if (is_array($value)){
         print_r($newkey);exit;
		 $array[$key] = changekeyname($value,$newkey,$oldkey);
	  }else
        {
             $array[$newkey] =  $array[$oldkey];    
        }

   }
   unset($array[$oldkey]);          
   return $array;   
}
?>
<body >
<div class="Container">
  <div class="BodyWhiteBG">
    <form name="frmtableMaster" id="frmtableMaster" method="post" action="<?php echo base_url()."niecpan/table_result_manager/table_details";?>" enctype="multipart/form-data" >
      <div class="BodyHeadBg Overflow clear">
        <div class="FloatLeft BreadCrumbsWrapper">
          <?php if(isset($table_data_id)) 
{?>
          <div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">Table data Edit</a></div>
          <h2 class="FloatLeft">Table data Edit</h2>
          <?php } else {?>
          <div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">Table data create</a></div>
          <h2 class="FloatLeft">Table data create</h2>
          <?php }?>
        </div>
        
        <!--<div class="FloatLeft Error">Error Message</div>-->
        
        <p class="FloatRight save-back save_margin"> <a class="FloatLeft back-top" href="<?php echo base_url()."niecpan/table_result_manager";?>"><i class="fa fa-reply fa-2x"></i></a>
          <button class="btn-primary btn" type="button" id="btnSave"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
        </p>
      </div>
      <div class="poll_content role-depart">
        <div class="role-dept">
          <div class="role-first">
            <div class="qnsans">
              <div class="qns">
                <label class="question TextAlignRight WidthPercentage">Table data name<span style="color:#F00">*</span></label>
              </div>
              <div class="ans w2ui-field">
                <input type="text" id="name" name="table_data_name" class="tb_style2 box-shad box-shad1" value="<?php if(isset($table_data_name)){ echo $table_data_name;}else{echo set_value('table_data_name');}?>">
              </div>
              <p id="exist_msg"></p>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question TextAlignRight WidthPercentage">Headings<span style="color:#F00">*</span></label>
              </div>
              <div class="ans w2ui-field">
                <input type="text" id="table_data_heading" name="table_data_heading" class="tb_style2 box-shad box-shad1" value="<?php if(isset($table_data_heading)){ echo $table_data_heading ;}else{echo set_value('table_data_heading');}?>">
              </div>
            </div>
            <div class="qnsans">
              <div class="qns">
                <label class="question TextAlignRight WidthPercentage">No of Columns<span style="color:#F00">*</span></label>
              </div>
              <div class="ans">
                <input type="number" name="noofcolumns" id="noofcolumns"  value="<?php if(isset($column_count)){ echo $column_count ;}else{echo set_value('noofcolumns');}?>"  >
              </div>
            </div>
            <div class="qnsans">
              <input type="hidden" name="t_id" id="t_id"  value="<?php if(isset($table_data_id)){ echo $table_data_id ;}?>"  >
              <input type="button" id="generate_result" style="cursor:pointer" value="Generate table">
            </div>
          </div>
           </form>
          <div class="save_poll">
           <!-- <button id="btnAddNewRowOk">Add</button><button id="btnDeleteRow">Delete</button>
             <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Column 1</th>
			<th>Column 2</th>
			<th>Column 3</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>row-column</td>
			<td>row-column</td>
			<td>row-column</td>
			<td><p><a title="Add" data-toggle="tooltip" href="javascript:void(0);" class="button tick" onClick="fnClickAddRow();"> <i class="fa fa-pencil"></i> </a><a  href="javascript:void(0);" onClick="fnDeleteTableRows();" title="Move to Trash" data-toggle="tooltip" class="button tick"> <i class="fa fa-trash-o"></i> </a></p></td>
		</tr>
	</tbody>
</table>--> Select any col/row to add col/row after/below it<br/>
<button id="btnAddRow" type="button">Add new row</button>
<button id="btnAddCol" type="button">Add new column</button>
<br>
<table class="dataTable" id="example">
</table>
             </div>
        </div>
      </div>
   
  </div>
</div>
<script type="text/javascript"> 
	$(document).ready(function()
	{
		$("#frmtableMaster").validate({
		rules: 
		{			
			table_data_name: 
			{ 
				required: true,
			},
			table_data_heading: 
			{ 
				required: true,
			},
			noofcolumns :
			{
				required: true,
				max:10,

			}
			
			
		},
		messages: 
		{
			table_data_name:
			{
				required: "Please Enter Table Data name",
				
			},
			table_data_heading:
			{
				required: "Please enter Table data heading",
			},
			noofcolumns:
			{
				required: "Please enter no of columns to append",
				max:"Please do not enter more than 10 columns."
			},
			
		},
	});
	
	$('#generate_result').click(function()
	{
		if($("#frmtableMaster").valid())
			{
				
				
			}
	});
		
		$("#btnSave").click(function() 
		{
			if($("#frmtableMaster").valid())
			{
				var noofcolumns =$('#noofcolumns').val();
				var table_dataname = $('#name').val();
				var table_dataheading = $('#table_data_heading').val();
				var tid = $('#t_id').val();
				
					$.ajax({
					type: "POST",
					data: {"table_data_name":table_dataname, "t_id":tid, "table_heading":table_dataheading},
					url:"<?php echo base_url(); ?>niecpan/table_result_manager/check_tabletypename",
					success: function(result)
					{
						if(result == "Table data name already exists")
						{
							$('#exist_msg').html('Table data name already exists');
							return false;
						}
						else
						{
							<?php
							if(isset($table_data_id))
							{
							?>
							var r = confirm("Are you sure you want to update Table details?");
							<?php } else {?>
							var r = confirm("Are you sure you want to add Table details?");
							
							<?php }?>
							if(r==true)
							{
								//$("#frmeltableMaster").submit();
									 oTable = $('#example').dataTable();
									  var tabledataArray=[];
									  $.each( oTable.fnGetData(), function(i, row){
										  tabledataArray.push( row);
									});
                                   var tid= $('#t_id').val();
								   var table_data_name = $('input[name="table_data_name"]').val();
								   var table_data_heading = $('input[name="table_data_heading"]').val();
								   var noofcolumns = $('input[name="noofcolumns"]').val();
								   table_data = tabledataArray;
								    cTable = $('#example').DataTable();
								   var columnHtml = cTable.columns().header();
									var columnarray=[];
									$.each(columnHtml, function(i,value){
									columnarray.push(columnHtml[i].textContent);
									});
									columndata = columnarray;
								$.ajax({
										type: "post",
										url: "<?php echo base_url()?>niecpan/table_result_manager/table_details",
										data: {"t_id": tid,"table_data_name": table_data_name,"table_data_heading": table_data_heading, "noofcolumns": noofcolumns,  "table_data" : table_data, "column_data" :columndata },
										//dataType:'json',
										success: function(data){
											window.location.href="<?php echo base_url() ?>niecpan/table_result_manager/?status="+data;
										 },
							           });
							}
							else
							{
								return false;
							}
						}
					}
					
				});
			
				
				}
				
		});
		
 });
 	
</script>
<script type="text/javascript">//&lt;![CDATA[
$(window).load(function(){
	var data_table, row_num=1, col_num=3, row_cell=1, col_cell=0, iter=<?php echo ($newcol!='')? $newcol : 0;?>;
	<?php if($columns==''){ ?>
var cols = [
    { "mDataProp": "Field1", sTitle: "Date", sType : "date"},
    { "mDataProp": "Field2", sTitle: "Number", sType : "numeric"},
    { "mDataProp": "Field3" , "sTitle": "FName", sType : "string"},
    { "mDataProp": "Field4" ,  sTitle: "LName", sType : "string"}
];
/*var cols = [
    { "sTitle": "FName", sType : "string"},
    {  sTitle: "LName", sType : "string"},
    {  sTitle: "DOB", sType : "date"},
    {  sTitle: "Votecount", sType : "numeric"}
];*/
<?php }else { ?>
var cols = <?php echo $columns;?>;
<?php } ?>
 
//Get stored data from HTML table element
<?php if($table_data=='') { ?>
var results = [{
       Field1: "2011/04/23",
       Field2: 8,
       Field3: "Adam",
       Field4: "Den"},
      {
       Field1: "2011/03/25",
       Field2: 6,
       Field3: "Honey",
       Field4: "Singh"}
    ];
	//var results = [["Adam","Den","23/02/1970","4"],["Honey","Singh","23/02/1964","5"]];
<?php } else{ ?>

	var results = <?php echo stripslashes($table_data);?>;
	<?php } ?>
 
function initDT(){
    //Construct the measurement table
    data_table = $('#example').dataTable({
        "bDeferRender": true,
        "bInfo" : true,
        "bSort" : true,
        "bDestroy" : true,
        "bFilter" : true,
       // "bPagination" : false,
        "aaData": results,
        "aoColumns": cols,
    });
    attachTableClickEventHandlers();
}

initDT();

function attachTableClickEventHandlers(){
  //row/column indexing is zero based
  $("#example thead tr th").click(function() {     
            col_num = parseInt( $(this).index() );
            console.log("column_num ="+ col_num );   
    });
    $("#example tbody tr td").click(function() {     
            col_cell = parseInt( $(this).index() );
            row_cell = parseInt( $(this).parent().index() );    
            console.log("Row_num =" + row_cell + "  ,  column_num ="+ col_cell );

    });  
};


$("#btnAddRow").click(function(){
    //adding/removing row from datatable datasource
    //create test new record
    var aoCols = data_table.fnSettings().aoColumns;
    var newRow = new Object();
    for(var iRec=0; iRec<aoCols.length; iRec++){
        
        if(aoCols[iRec]._sManualType === "date"){
           var fullDate = new Date(); //Tue Feb 23 2016 17:10:43 GMT+0530 (India Standard Time)
 
			//convert month to 2 digits
			var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
			 
			var currentDate = fullDate.getDate() + "/" + twoDigitMonth + "/" + fullDate.getFullYear();  //23/02/2016
		    newRow[aoCols[iRec].mDataProp] = currentDate;
        }else if(aoCols[iRec]._sManualType === "numeric"){
            newRow[aoCols[iRec].mDataProp] = "Click to edit";
        }else if(aoCols[iRec]._sManualType === "string"){
            newRow[aoCols[iRec].mDataProp] = 'Click to edit';
        }
    }    
	console.log(newRow);
    results.splice(row_cell+1, 0, newRow);
    data_table.fnDestroy();
    initDT();  
    addDBClikHandler();
});

$('#btnAddCol').click(function () {
       
        //new column information
        //row's new field(for new column)
        //cols must be updated
		 var aoCols = data_table.fnSettings().aoColumns;
		 console.log(aoCols);
		
        cols.splice(col_num+1, 0, {"mDataProp": "newField"+iter, sTitle: "Col-"+iter, sType : "string"});
        //update the result, actual data to be displayed
        for(var iRes=0; iRes<results.length ;iRes++){
            results[iRes]["newField"+iter] = "data-"+iter;
        }
        //destroy the table
		data_table.fnDestroy();
        $("#example thead tr th").eq(col_num).after('<th>Col-'+iter+'</th>');
        //init again
		initDT();
        iter++;
        addDBClikHandler();
});

    
function restoreRow ( oTable, nRow ){
    var aData = oTable.fnGetData(nRow);
    var jqTds = $('>td', nRow);
    
    for ( var i=0, iLen=jqTds.length ; i<iLen ; i++ ) 
    {
        oTable.fnUpdate( aData[i], nRow, i, false );
    }
};

function editRow ( oTable, nRow ){
	
    var aData = oTable.fnGetData(nRow);
    var jqTds = $('>td', nRow);
	$( "#typedtext" ).parent().html($( "#typedtext" ).val());
    jqTds[col_cell].innerHTML = '<input type="text" id ="typedtext" value="'+aData[cols[col_cell].mData]+'"/>';
    $( "#typedtext" ).focus();
};

function saveRow ( oTable, nRow ){
    var jqInputs = $('input', nRow);
	console.log(col_cell);
    oTable.fnUpdate( jqInputs[0].value, row_cell, col_cell, false );
};

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function ( a ) {
        var ukDatea = a.split('/');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1
    },
    
    "date-uk-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    
    "date-uk-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );

/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal ){
    var aReturn = new Array();
    var aTrs = oTableLocal.fnGetNodes();
    
    for ( var i=0 ; i<aTrs.length ; i++ )
    {
        if ( $(aTrs[i]).hasClass('row_selected') )
        {
            aReturn.push( aTrs[i] );
        }
    }
    return aReturn;
};

function addDBClikHandler(){
        $('#example tbody tr ').on('dblclick', function (e) {
        e.preventDefault();
        
            var nRow = $(this)[0];
    
             var jqTds = $('>td', nRow);
			
			 console.log($(this).html());
           // if($.trim(jqTds[0].innerHTML.substr(0,6)) != '<input') 
          //  {
              
				if ( nEditing !== null && nEditing != nRow ) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                   // restoreRow( oTable, nEditing );
                      nEditing = nRow;
                    editRow( oTable, nRow );
                   console.log('hello');
                }
                else {
                    /* No edit in progress - let's start one */
                      nEditing = nRow;
					   console.log('add');
					   console.log(nEditing);
                    editRow( oTable, nRow );
                  
                }
                
           // }
        
        
     } );
	 
	 $('#example thead th .DataTables_sort_wrapper').on('dblclick', function (e) {
 $("#headtxt").replaceWith($("#headtxt").val());
 console.log($(this).text());
 var headertext =  $(this).text();
 $(this).text('');
 $(this).append("<input type='text' id='headtxt' value='" + headertext + "'></input><span class='DataTables_sort_icon'></span>");
 });
    
     $('#example thead th .DataTables_sort_wrapper').keydown(function(event){
    var headertext = $('#headtxt').val();
         if(event.keyCode==13)
        {
		$(this).html(""+ headertext + "<span class='DataTables_sort_icon'></span>");
		event.preventDefault();
		}
    } );
	
     $('#example tbody tr').keydown(function(event){
    
        if(event.keyCode==13)
        {
        event.preventDefault();
    
         if(nEditing==null)
            alert("Select Row");
        else
            saveRow( oTable, nEditing );
            nEditing = null;
        }
        /* Editing this row and want to save it */
    
    } );
    
    
};

var nEditing = null;

var oTable = null;

$(document).ready(function() {
    initDT();
    oTable = data_table;  
    addDBClikHandler();
} );
$(document).on('click',function(event){
	 // oTable.fnUpdate( $( "#typedtext" ).val(), sessionStorage.row_cell, sessionStorage.col_cell, false );
});


});//]]&gt; 

</script> 

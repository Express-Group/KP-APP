$(document).ready(function() 
{
$("#parent_chkbox").hide();
$("#parent_columnist").hide();	

$("#fileBackgroundImage").change(function()
{
var file = $('input[type="file"]').val();
var exts = ['jpg','jpeg','png','GIF','JPG','JPEG','PNG','gif'];
if ( file )// first check if file field has any value
{
var get_ext = file.split('.');// split file name at dot
get_ext = get_ext.reverse();// reverse name to check extension
if ($.inArray ( get_ext[0].toLowerCase(), exts ) > -1 )// check file type is valid as given in 'exts' array
{
$("#preview_image").show();	
$("#deleteimages").show();
display_image(this);
} 
else 
{
$("#preview_image").hide();	
$("#deleteimages").hide();
}
}
});

$("#deleteimages").click(function() 
{
$('#imgBackgndimage').attr('src','');
$('input[type="file"]').val('');
$("#preview_image").hide();	
$("#deleteimages").hide();
$("#imgBackgndimage").hide();
$("#image_show").html('Browse');
});

$("#chkSubSection").change(function()
{
if(this.checked) 
{
$("#parent_chkbox").show();
$('#ddDisplayOrder1').val('');
} 
else 
{
$("#ddSectionName").val('')
$('#ddDisplayOrder').val('');
$("#parent_chkbox").hide();
}
});

$("#chkColumnist").change(function()
{
if(this.checked) 
{
$("#parent_columnist").show();
} 
else 
{	
$("#ddColumnist").val('');
$("#parent_columnist").hide();
}
});

/*$("#chkSeperateMenu").change(function()
{
if(this.checked) 
{
$("#hide_spldd").hide();
} 
else 
{	
$("#hide_spldd").show();
}
});
*/




$("#month3").click(function() 
{
$("#external_link").show();
});
$("#week3").click(function() 
{
$("#txtExternalLink ").val('');
$("#external_link").hide();
});


$("#day4").click(function() 
{
$("#menu_visibility").hide();
});

$("#week4").click(function() 
{
$("#menu_visibility").show();
});

if($("#chkSubSection").is(':checked'))
{
$("#chkSubSection").trigger('change');
}
if($("#chkColumnist").is(':checked'))
{
$("#chkColumnist").trigger('change');
}
if($('input:radio[name=view3]:checked').val() == "I")
{
$("#external_link").hide();

}
else
{
$("#external_link").show();
}

});

////////////////////////

function display_image(input)
{
if (input.files && input.files[0])
{
var reader = new FileReader();
reader.onload = function (e)
{

$('#imgBackgndimage').attr('src', e.target.result);
$("#imgBackgndimage").show();
$("#image_show").html('Change');


}
reader.readAsDataURL(input.files[0]);
}
}

function uncheck()
{
$(".checked").removeClass("checked");

}


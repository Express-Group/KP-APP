<?php
$success = 0;
if(isset($_POST['sb'])){
	$user = (isset($_POST['username']) && $_POST['username']!='') ? trim($_POST['username']) : '';
	$pass = (isset($_POST['password']) && $_POST['password']!='') ? trim($_POST['password']) : '';
	if($user!='' && $pass!='' && $user=='AdvTeam' && $pass =='Express@456@!'){
		$success = 1;
	}else{
		echo '<script>alert("Invalid Username & password");</script>';
	}
}
if(isset($_GET['type']) && $_GET['type']=='save'){
	$content = (isset($_POST['ads']) && $_POST['ads']!='') ? $_POST['ads'] : '';
	if($content!=''){
		file_put_contents('ads.txt' , $content);
		echo 1;
	}else{
		echo 0;
	}
	
	exit;
}
$content  =file_get_contents('ads.txt');
?> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php if($success==0): ?>
<form method="post">
<input type="text" placeholder="enter username" name="username"><br>
<input type="password" placeholder="enter Password" name="password"><br>
<button type="submit" name="sb">login</button>
</form>
<?php endif; ?>
<?php if($success==1): ?>
<textarea style="width: 600px;height: 600px;"><?php echo $content; ?></textarea> <br>

<button>save</button>
<script>
$(document).ready(function(e){
	$('button').on('click' , function(e){
		var content = $('textarea').val();
		//alert(content);
		if(content!=''){
			$.ajax({
				type:'post',
				url:'adstxt.php?type=save',
				data:{'ads' : content },
				success:function(result){
					if(result==1){
						alert('updated Successfully');
						location.href = 'adstxt.php?q='+new Date().getTime();
					}else if(result==2){
						alert('Invalid Username & password');
					}else{
						alert('Something went wrong. Please try again');
					}
				},
				error:function(err , errcode){
					alert(errcode)
				}
			})
		}else{
			alert('error');
		}
	});
})
</script>
<?php endif; ?>
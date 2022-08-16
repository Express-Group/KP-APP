<!DOCTYPE html>
<html>
<head>
<title>LiveNow</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<style>
@import url('https://fonts.googleapis.com/css?family=Mukta+Malar&display=swap');
body{margin:0;font-family: 'Mukta Malar', sans-serif;}
.livenow-content{float: left;padding: 2%;background: #eeeeee94;width:96%;}
.livenow-content .live-inner-content{background: #fff;padding: 1%;float: left;width: 98%;border-radius: 8px;box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0), 0 1px 10px 0 rgba(0,0,0,0.12), 0 2px 4px -1px rgba(0, 0, 0, 0.08);margin-bottom: 20px;
}
.time{font-size: 14px;font-weight: 700;color: #3883f3;font-family: sans-serif;}
.livenow-content img{width:100%;height:auto;}
.livenow-content iframe{width:100%}
</style>
</head>
<body>
<div class="livenow-content">
<?php
$i=0;
$Template='';
$pinTemplate='';
foreach($result as $Data):
	if($Data['status']==1){
		$Date=explode(' ',$Data['date']);
		$Date=explode(':',$Date[1]);
		$Date=$Date[0].':'.$Date[1];
		$Time=strtotime($Data['date']);
		$Time=Date('M j',$Time);
		if(isset($Data['pin']) && $Data['pin']=='1'){
			$pinTemplate .='<div style="box-shadow: 0px 2px 6px 2px #00000096;" class="live-inner-content live-'.$i.'">';
			$pinTemplate .='<span class="time">'.$Date.' '.$Time.' <i class="fa fa-thumb-tack" aria-hidden="true"></i></span>';
			if($Data['title']!=''):
				$pinTemplate .='<h3>'.$Data['title'].'</h3>';
			endif;
			$pinTemplate .='<div>'.$Data['content'].'</div>';
			$pinTemplate .='</div>'; 
		}else{
			$Template .='<div class="live-inner-content live-'.$i.'">';
			$Template .='<span class="time">'.$Date.' '.$Time.' </span>';
			if($Data['title']!=''):
				$Template .='<h3>'.$Data['title'].'</h3>';
			endif;
			$Template .='<div>'.$Data['content'].'</div>';
			$Template .='</div>'; 
		}
		
	}
	$i++;
endforeach;
echo $pinTemplate.$Template;
?>
</div>
</body>
</html>
<?php if($error==false):
$table = json_decode($data[0]['table_properties'],true);
$table = $table['data'];
$id =rand(100,10000);
?>
<!DOCTYPE html>
<html lan="en">
	<head>
		<meta charset="UTF-8">
		<title>Election tables - Kannadaprabha</title>
		<style>
			.nietb-<?php echo $id; ?>{border-collapse: collapse;width: 100%;float:left;font-family: Droid regular,sans-serif!important;font-size: 14px;}
			.nietb-<?php echo $id; ?> th , .nietb-<?php echo $id; ?> td{border: 1px solid #dddddd;text-align: left;padding: 8px;}
			.nietb-<?php echo $id; ?> thead tr:first-child{background:#000;color:#fff;}
			.nietb-<?php echo $id; ?> thead tr:last-child{background:#7a0025;color:#fff;text-align:center;}
			.nietb-<?php echo $id; ?> thead tr:first-child th{border: 1px solid #000;}
			.nietb-<?php echo $id; ?> thead tr:first-child th:last-child{text-align:right;}
			.nietb-<?php echo $id; ?> thead tr:last-child th{text-align:center;}
			.nietb-<?php echo $id; ?> thead tr:last-child th:first-child{border-left: 1px solid #7a0025;}
			.nietb-<?php echo $id; ?> thead tr:last-child th:last-child{border-right: 1px solid #7a0025;}
			.nietb-<?php echo $id; ?> tbody{background:#ebebeb;}
			.nietb-<?php echo $id; ?> tbody td{text-align:center;}
		</style>
	</head>
	<body>
		<table class="nietb-<?php echo $id; ?>">
			<thead>
				<tr><th colspan="3"><?php echo $data[0]['table_name']; ?></th><th colspan="1"><?php echo $data[0]['total']; ?></th></tr>
				<tr><th>Party</th><th>Lead</th><th>Won</th><th>Total</th></tr>
			</thead>
			<tbody>
				<?php
					$numcount = strlen($data[0]['total']);
					for($i=0;$i<count($table);$i++){
						$total= $table[$i]['field2'] + $table[$i]['field3'];
						$totalCount = strlen($total);
						if($totalCount < $numcount){
							$prependString = str_repeat("0",$numcount- $totalCount);
							$total = $prependString.$total;
						}
						echo '<tr>';
						echo '<td>'.$table[$i]['field1'].'</td>';
						echo '<td>'.$table[$i]['field2'].'</td>';
						echo '<td>'.$table[$i]['field3'].'</td>';
						echo '<td>'.$total.'</td>';
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
	</body>

<?php else :  ?>
<!DOCTYPE html>
<html lan="en">
	<head>
		<meta charset="UTF-8">
		<title>Election tables</title>
	</head>
	<body>
		<h5 style="text-align:center;">No tables found</h5>
	</body>
</html>
<?php endif; ?> 
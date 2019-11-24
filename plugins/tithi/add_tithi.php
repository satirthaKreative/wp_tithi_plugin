<?php 
	error_reporting(E_ALL);
	include('../wp-config.php');
	global $wpdb;
	// $wpdb->query("CREATE TABLE wp_tithi(id int(11),tithi varchar(255),date_tithi varchar(255),date_tithi_f varchar(255));");
	if(isset($_POST['submit'])){
		$select = $wpdb->get_row("SELECT * FROM wp_tithi");
		$num_rows = $wpdb->num_rows;
		if($num_rows>0){
			// $date_p = date('Y-m-d');
			// $date_f = strtotime("+14 day");
			// $date_f1 =  date('Y-m-d', $date_f);
			// $update = $wpdb->update('wp_tithi',array(
			// 	'tithi'=>$_POST['select_tithi'],
			// 	'date_tithi'=>$_POST['start_date'],
			// 	'date_tithi_f'=>$_POST['end_date']
			// ),array('id'=>1));
			$update = $wpdb->query("update wp_tithi set tithi = '".$_POST['select_tithi']."' , date_tithi = '".$_POST['start_date']."', date_tithi_f = '".$_POST['end_date']."' where id = 1 ");
			if($update){
				$msg = "Successfully Updated";
			}else{
				$msg1 = "Something wrong! Try Again Later";
			}
		}else{
			// $date_p = date('Y-m-d');
			// $date_f = strtotime("+14 day");
			// $date_f1 =  date('Y-m-d', $date_f);
			$insert = $wpdb->insert('wp_tithi',array(
			'tithi'=>$_POST['select_tithi'],
			'date_tithi'=>$_POST['start_date'],
			'date_tithi_f'=>$_POST['end_date']
			));
			if($insert){
				$msg = "Successfully Updated";
			}else{
				$msg1 = "Something wrong! Try Again Later";
			}
		}
	}
 ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  jQuery( function() {
    jQuery( "#datepicker1" ).datepicker({dateFormat:'dd-mm-yy'});
  } );
</script>
<script>
  jQuery( function() {
    jQuery( "#datepicker2" ).datepicker({dateFormat:'dd-mm-yy'});
  } );
</script>
<style>
	.form-layout{
		background: #eee;
		padding: 0px 20px 20px 20px;
		border-top: 3px solid #337ab7;
		border-bottom: 3px solid #337ab7;
	}
	.tithi-header{
		padding-bottom: 15px;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 form-layout">
			<h3 class="text-center tithi-header">Tithi Form</h3>
			  	<?php if(isset($msg)){ ?>
				<div class="col-sm-12">
					<div class="alert alert-info">
					  <strong><?= $msg; ?> </strong>
					</div>
				</div>
			  	<?php } ?>
			    <?php if(isset($msg1)){ ?>
			  	<div class="col-sm-12">
			  		<div class="alert alert-danger">
			  		  <strong><?= $msg1; ?> </strong>
			  		</div>
			  	</div>
			    <?php } ?>
			<form action="" method="post">
				<div class="form-group">
					<label for="select_tithi">Select Paksh:</label>
					<select name="select_tithi" name="select_tithi" id="select_tithi" class="form-control">
						<option value="">Select Paksh</option>
						<option value="Shukla">Shukla Paksh</option>
						<option value="krishna">krishna Paksh</option>
					</select>
				</div>
				<div class="form-group">
					<label for="start_date">Start Date:</label>
					<input type="text" id="datepicker1" placeholder="starting date" class="form-control"  name="start_date">
				</div>
				<div class="form-group">
					<label for="start_date">End Date:</label>
					<input type="text" id="datepicker2" placeholder="ending date" class="form-control"  name="end_date">
				</div>
				<button type="submit" name="submit" class="btn btn-info">Submit</button>
			</form>
		</div>
	</div>
</div>
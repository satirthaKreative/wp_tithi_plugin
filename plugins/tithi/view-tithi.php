
<?php 
	include('../wp-config.php');
	global $wpdb;
	// $wpdb->query("CREATE TABLE wp_test(id int(11),tithi varchar(255),date_tithi varchar(255),date_tithi_f varchar(255));");
	$select = $wpdb->get_row("SELECT * FROM wp_tithi");
	$tithi_name = $select->tithi;
	$date1 = $select->date_tithi;
	$date2 = $select->date_tithi_f;
	$date3 = date('d-m-Y');
	if(strtotime($date3)>strtotime($date2)){
		if($tithi_name=='krishna'){
			$date_f = strtotime($date3."+14 day");
			$date_f1 =  date('d-m-Y', $date_f);
			$update1 = $wpdb->update('wp_tithi',
							array(
								'tithi'=>'Shukla',
								'date_tithi'=>$date3,
								'date_tithi_f'=>$date_f1
							),
							array(
								'id'=>1
							)
						);
		}elseif($tithi_name=='Shukla'){
			$date_f = strtotime($date3."+14 day");
			$date_f1 =  date('d-m-Y', $date_f);
			$update1 = $wpdb->update('wp_tithi',
							array(
								'tithi'=>'krishna',
								'date_tithi'=>$date3,
								'date_tithi_f'=>$date_f1
							),
							array(
								'id'=>1
							)
						);
		}
	}
?>
<meta http-equiv="refresh" content="3600"/>
<meta name="Pragma" content="no-cache;">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
		<div class="col-md-6 offset-md-3 form-layout">
			<h3 class="text-center">Tithi Details</h3>
			<table class="table table-bordered table-striped">
			    <thead>
			      <tr>
			        <th>Date</th>
			        <th>paksh</th>
			        <th>Tithi</th>
			      </tr>
			    </thead>
			    <tbody>
			      <tr>
			        <td><?php echo $date3; ?></td>
			        <td><?php echo $select->tithi; ?></td>
			        <td>
			        	<?php 
			        		$daten1 = date('d-m-Y', strtotime($select->date_tithi."+0 day"));
			        		$daten2 = date('d-m-Y', strtotime($select->date_tithi."+1 day"));
			        		$daten3 = date('d-m-Y', strtotime($select->date_tithi."+2 day"));
			        		$daten4 = date('d-m-Y', strtotime($select->date_tithi."+3 day"));
			        		$daten5 = date('d-m-Y', strtotime($select->date_tithi."+4 day"));
			        		$daten6 = date('d-m-Y', strtotime($select->date_tithi."+5 day"));
			        		$daten7 = date('d-m-Y', strtotime($select->date_tithi."+6 day"));
			        		$daten8 = date('d-m-Y', strtotime($select->date_tithi."+7 day"));
			        		$daten9 = date('d-m-Y', strtotime($select->date_tithi."+8 day"));
			        		$daten10 = date('d-m-Y', strtotime($select->date_tithi."+9 day"));
			        		$daten11 = date('d-m-Y', strtotime($select->date_tithi."+10 day"));
			        		$daten12 = date('d-m-Y', strtotime($select->date_tithi."+11 day"));
			        		$daten13 = date('d-m-Y', strtotime($select->date_tithi."+12 day"));
			        		$daten14 = date('d-m-Y', strtotime($select->date_tithi."+13 day"));
			        		$daten15 = date('d-m-Y', strtotime($select->date_tithi."+14 day"));
			        		if(strtotime($daten1)==strtotime($date3)){
			        			echo "pratipad";
			        		}elseif(strtotime($daten2)==strtotime($date3)){
			        			echo "Dwitiya";
			        		}elseif(strtotime($daten3)==strtotime($date3)){
			        			echo "Tritiya";
			        		}elseif(strtotime($daten4)==strtotime($date3)){
			        			echo "Chaturthi";
			        		}elseif(strtotime($daten5)==strtotime($date3)){
			        			echo "Panchami";
			        		}elseif(strtotime($daten6)==strtotime($date3)){
			        			echo "Shashthi";
			        		}elseif(strtotime($daten7)==strtotime($date3)){
			        			echo "Saptami";
			        		}elseif(strtotime($daten8)==strtotime($date3)){
			        			echo "Ashtami";
			        		}elseif(strtotime($daten9)==strtotime($date3)){
			        			echo "Navami";
			        		}elseif(strtotime($daten10)==strtotime($date3)){
			        			echo "Dasami";
			        		}elseif(strtotime($daten11)==strtotime($date3)){
			        			echo "Ekadasi";
			        		}elseif(strtotime($daten12)==strtotime($date3)){
			        			echo "Dwadasi";
			        		}elseif(strtotime($daten13)==strtotime($date3)){
			        			echo "Trayodasi";
			        		}elseif(strtotime($daten14)==strtotime($date3)){
			        			echo "Chaturdasi";
			        		}elseif(strtotime($daten15)==strtotime($date3)){
			        			$tithina=$select->tithi;
			        			if($tithina='Shukla'){
			        				echo 'Purnima';
			        			}else{
			        				echo 'Amavasya';
			        			}
			        		}

			        	 ?>
			        </td>
			      </tr>
			    </tbody>
			  </table>
		</div>
	</div>
</div>
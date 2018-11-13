<!DOCTYPE html>
<html>
	<head>
		<title>Farm Game</title>
	</head>
	<body>
		<form method="POST" action="farmer.php">
		<?php
			$msg = '';
			if( isset($_POST) && !empty($_POST) ){

				//echo "<pre>"; print_r($_POST); echo "</pre>";
				
				$post_check = time();
				
				$arr = array('farmer1','cow1','cow2','bunny1','bunny2','bunny3','bunny4','total');
				if( isset($_POST['feed']) && !empty($_POST['feed']) ){
					
					$check = array();				
					$check_arr = array('farmer1','cow1','cow2','bunny1','bunny2','bunny3','bunny4');
					foreach( $check_arr as $key=>$value ){
						if( isset($_POST['status'][$value]) && !empty($_POST['status'][$value]) && $_POST['status'][$value] != 'dead' ){
							$check[] = $value;
						}
					}
					if( isset($check) && !empty($check) ){
						$check = array_values($check);
					}else{
						$check = array('farmer1','cow1','cow2','bunny1','bunny2','bunny3','bunny4');
					}
					
					$i = 0;
					$cnt = count($check)-1;
					$i = rand(0,$cnt);
					shuffle($check);
					$value = $check[$i];
					
					$msg = '';
					foreach( $arr as $val ){
						if( $val == $value && isset($_POST['status'][$val]) && !empty($_POST['status'][$val]) && $_POST['status'][$val] == 'fed' ){
							$msg = $val.' already fed';
						}elseif( $val == $value ){
							$msg = $val.' fed';
							$_POST[$val]++;
						}elseif( $val == 'total' ){
							$_POST[$val]++;
						}
						echo '<input type="hidden" name="'.$val.'" value="'.$_POST[$val].'" />';
					}
								
				}else{
					
					foreach( $arr as $val ){
						echo '<input type="hidden" name="'.$val.'" value="0" />';
					}

				}
			
				if(  !isset($_POST['total']) || ( ( isset($_POST['total']) && !empty($_POST['total']) && $_POST['total'] < 50 ) && ( !isset($_POST['status']['farmer1']) || ( isset($_POST['status']['farmer1']) && !empty($_POST['status']['farmer1']) &&  $_POST['status']['farmer1'] != 'dead' ) ) ) ){

					echo '<input type="submit" name="feed" value="Feed" />';
					if( !empty($msg) ){ echo '  ( '.$msg.' )'; }

				}else{
					
					$win = 0;
					if( isset($_POST['status']) && !empty($_POST['status']) ){	
						if( $_POST['status']['farmer1'] != 'dead'  ){
							if( $_POST['status']['cow1'] != 'dead' || $_POST['status']['cow2'] != 'dead' ){
								if( $_POST['status']['bunny1'] != 'dead' || $_POST['status']['bunny2'] != 'dead' || $_POST['status']['bunny3'] != 'dead' || $_POST['status']['bunny4'] != 'dead' ){
									$win = 1;
								}
							}
						}						
					}
					
					echo "Game Over. ";

					if( $win == 1 ){
						echo "You won the game.";					
					}else{
						echo "You lost the game.";
					}

					echo "<br/><br/><a href='farmer.php'>click here</a> to start the game again";

				}

				if( isset($_POST['feed']) && !empty($_POST['feed']) ){
		?>
					<br/><br/>

					<table border="1" cellspacing="0" cellpadding="5">
						<tr>
							<th>Entity</th>
							<th>Feed</th>
							<th>Status</th>
							<th>Remarks</th>
						</tr>
						<?php
							foreach( $_POST as $key => $val ){
								if( !in_array($key,array('start','feed','status','post_check')) ){
									
									$status_str = 'alive';
									if( isset($_POST['status'][$key]) and !empty($_POST['status'][$key]) ){
										$status_str = $_POST['status'][$key];
									}
									
									if( $status_str != 'dead' ){

										$total = $_POST['total'];

										// Farmer
										$farmer_arr = array('farmer1');
										if( in_array($key,$farmer_arr) ){
											$chk = $total/15;
											if( $total % 15 == 0 && $val < $chk ){
												$status_str = 'dead';
											}elseif( $val >= $chk ){
												$status_str = 'fed';
											}else{
												$status_str = 'alive';
											}
										}

										// Cow
										$cow_arr = array('cow1','cow2');									
										if( in_array($key,$cow_arr) ){
											$chk = $total/10;
											if( $total % 10 == 0 && $val < $chk ){
												$status_str = 'dead';
											}elseif( $val >= $chk ){
												$status_str = 'fed';
											}else{
												$status_str = 'alive';
											}
										}

										// Bunny
										$bunny_arr = array('bunny1','bunny2','bunny3','bunny4');
										if( in_array($key,$bunny_arr) ){
											$chk = $total/8;
											if( $total % 8 == 0 && $val < $chk ){
												$status_str = 'dead';
											}elseif( $val >= $chk ){
												$status_str = 'fed';
											}else{
												$status_str = 'alive';
											}
										}
										
									}
									
									$remarks = '';
									$status_display_str = $status_str;
									if( $status_str == 'alive' ){
										$remarks = 'unfed';
										$status_display_str = 'alive';
									}elseif( $status_str == 'fed' ){
										$remarks = 'fed';
										$status_display_str = 'alive';
									}
									
									if( $key == 'total' ){
										echo "<tr><td><b>Total</b></td><td>{$val}</td><td></td><td></td></tr>";
									}else{
										echo "<tr><td>{$key}</td><td>{$val}</td><td><input type='hidden' name='status[".$key."]' value='".$status_str."'/>".$status_display_str."</td><td>".$remarks."</td></tr>";
									}
								}
							}
						?>
					</table>
		<?php
				}
				
			}else{
				unset($_POST);
		?>		<input type="submit" name="start" value="Start a new game" />
		<?php
			}
		?>
		</form>
	</body>
</html>

<script>
	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>

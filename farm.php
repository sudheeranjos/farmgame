<html>
	<head>
		<title>Farm Game</title>
	</head>
	<body>
		<form method="POST" action="farm.php">
		<?php
			if( isset($_POST) && !empty($_POST) ){

				//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
					
				$arr= $check = array('farmer','cow1','cow2','bunny1','bunny2','bunny3','bunny4');
				array_push($arr,'total');

				if( isset($_POST['feed']) && !empty($_POST['feed']) ){
					$cnt = count($check)-1;
					$i = rand(0,$cnt);
					shuffle($check);
					$value = $check[$i];
					foreach( $arr as $val ){
						if( $val == $value || $val == 'total' ){
							$_POST[$val]++;
						}
						echo '<input type="hidden" name="'.$val.'" value="'.$_POST[$val].'" />';
					}
				}else{
					foreach( $arr as $val ){
						echo '<input type="hidden" name="'.$val.'" value="0" />';
					}
					echo '<input type="hidden" name="total" value="0" />';
				}

				if(  !isset($_POST['total']) || ( ( isset($_POST['total']) && !empty($_POST['total']) && $_POST['total'] < 50 ) && ( !isset($_POST['status']['farmer']) || ( isset($_POST['status']['farmer']) && !empty($_POST['status']['farmer']) &&  $_POST['status']['farmer'] == 'alive' ) ) ) ){

					echo '<input type="submit" name="feed" value="Feed" />';

				}else{
					
					$win = 0;
					if( isset($_POST['status']) and !empty($_POST['status']) ){	
						if( $_POST['status']['farmer'] == 'alive'  ){
							if( $_POST['status']['cow1'] == 'alive' || $_POST['status']['cow2'] == 'alive' ){
								if( $_POST['status']['bunny1'] == 'alive' || $_POST['status']['bunny2'] == 'alive' || $_POST['status']['bunny3'] == 'alive' || $_POST['status']['bunny4'] == 'alive' ){
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

					echo "<br/><br/><a href='farm.php'>click here</a> to start the game again";

				}

				if( isset($_POST['feed']) && !empty($_POST['feed']) ){
		?>
					<br/><br/>

					<table border="1" cellspacing="0" cellpadding="5">
						<tr>
							<th>Entity</th>
							<th>Feed</th>
							<th>Status</th>
						</tr>
						<?php
							foreach( $_POST as $key => $val ){
								if( !in_array($key,array('start','feed','status')) ){
									
									$status_str = 'alive';
									if( isset($_POST['status'][$key]) and !empty($_POST['status'][$key]) ){
										$status_str = $_POST['status'][$key];
									}

									$total = $_POST['total'];

									// Bunny
									if( $total % 8 == 0 ){
										$chk = $total/8;
										$bunny_arr = array('bunny1','bunny2','bunny3','bunny4');
										if( in_array($key,$bunny_arr) && $val < $chk ){
											$status_str = 'dead';
										}
									}

									// Cow
									if( $total % 10 == 0 ){
										$chk = $total/10;
										$cow_arr = array('cow1','cow2');
										if( in_array($key,$cow_arr) && $val < $chk ){
											$status_str = 'dead';
										}
									}

									// Farmer
									if( $total % 15 == 0 ){
										$chk = $total/15;
										$farmer_arr = array('farmer');
										if( in_array($key,$farmer_arr) && $val < $chk ){
											$status_str = 'dead';
										}
									}

									if( $key == 'total' ){
										echo "<tr><td><b>Total</b></td><td>{$val}</td><td></td></tr>";
									}else{
										echo "<tr><td>{$key}</td><td>{$val}</td><td><input type='hidden' name='status[".$key."]' value='".$status_str."'/>".$status_str."</td></tr>";
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

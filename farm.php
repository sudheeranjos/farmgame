<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Farm Game</title>
	</head>
	<body>
		<?php
			
			// alive
			$farm_arr = $alive_arr = array('farmer1','cow1','cow2','bunny1','bunny2','bunny3','bunny4');					
			if( isset($_SESSION['dead']) && !empty($_SESSION['dead']) && is_array($_SESSION['dead']) ){
				foreach( $alive_arr as $key=>$value ){
					if( in_array($value,$_SESSION['dead']) ){
						unset($alive_arr[$key]);
					}
				}
				$alive_arr = array_values($alive_arr);
			}
			
			// new game 
			if( isset($_POST['start']) && !empty($_POST['start']) ){
				session_destroy();
				$filename = basename($_SERVER['PHP_SELF']);
				header("location:".$filename);
			}

			if( isset($_POST['feed']) && isset($_POST['check']) && isset($_SESSION['check']) && $_POST['check'] == $_SESSION['check'] ){
				
				// total
				if( !isset($_SESSION['total']) ){
					$_SESSION['total'] = 1;
				}else{
					$_SESSION['total']++;
				}			
				
				// random select
				$i = 0;
				$alive_cnt = count($alive_arr)-1;
				//$i = rand(0,$alive_cnt);
				shuffle($alive_arr);
				$selected = $alive_arr[$i];
				
				// feed
				foreach( $alive_arr as $value ){
					
					if( $value == $selected ){
						if( !isset($_SESSION['count'][$value]) ){
							$_SESSION['count'][$value] = 1;
						}else{
							$_SESSION['count'][$value]++;
						}
						$_SESSION['farm'][][$value] = 'fed';
					}

					$limit = 8; // bunny
					if( $value == 'farmer1' ){
						$limit = 15; // farmer
					}elseif( in_array( $value, array('cow1','cow2') ) ){
						$limit = 10; // cow
					}
					
					$fed = 0;
					if( isset($_SESSION['count'][$value]) && !empty($_SESSION['count'][$value]) ){
						$fed = $_SESSION['count'][$value];
					}
					$total = $_SESSION['total'];
					$min = $total / $limit;
					
					// dead
					if( $total % $limit == 0 && $fed < $min ){
						$_SESSION['dead'][] = $value;
					}
					
				}
				
			}
			
			$msg = '';
			$stop = 0;
			if( isset($_SESSION['total']) && !empty($_SESSION['total']) && $_SESSION['total'] >= 50 ){
				$stop = 1;
				if( isset($alive_arr) && !empty($alive_arr) ){
					$farmer = $cow = $bunny = 0;
					foreach( $alive_arr as $alive ){
						if( $alive == 'farmer1' ){
							$farmer++;						
						}if( strpos($alive,'cow') !== false ){
							$cow++;						
						}if( strpos($alive,'bunny') !== false ){
							$bunny++;						
						}
					}
					if( $farmer >= 1 && $cow >= 1 && $bunny >= 1 ){
						$msg = 'You won the game.';
					}else{
						$msg = 'You lost the game. Atleast the farmer, 1 cow and 1 bunny should be alive.';
					}
				}
			}elseif( isset($_SESSION['dead']) && !empty($_SESSION['dead']) && in_array('farmer1',$_SESSION['dead']) ){
				$stop = 1;
				$msg = 'You lost the game, farmer died.';
			}
			
			$name = 'feed';
			$type = 'submit';
			$disabled = '';
			if( $stop == 1 ){
				$name = 'stop';
				$type = 'button';
				$disabled = 'disabled';
			}
			
			$check = time();
			$_SESSION['check'] = $check;
			
		?>

		<form method="post" action="">
			<input type="hidden" name="check" value="<?php echo $check; ?>" />
			<input type="<?php echo $type; ?>" name="<?php echo $name; ?>" value="Feed" <?php echo $disabled; ?> />
			<?php if( $stop == 1 ){ ?>
				<input type="submit" name="start" value="Start a new game" />
				<br/><br/> Game Over. <?php echo $msg; ?>
			<?php } ?>
		</form>
		
		<br/>

		<?php
			if( isset($_SESSION['farm']) && !empty($_SESSION['farm']) ){
		?>
			<table border="1" cellspacing="0" cellpadding="10">
				<tr>
					<th>Round</td>
					<?php
						foreach( $farm_arr as $header ){
							$bgcolor = '';
							if( isset($_SESSION['dead']) && !empty($_SESSION['dead']) && in_array($header,$_SESSION['dead']) ){
								$bgcolor = 'bgcolor="red"';
							}
							echo "<th ".$bgcolor.">".ucfirst($header)."</th>";
						}
					?>
				</tr>
				<?php
					foreach( $_SESSION['farm'] as $key => $value ){
						echo "<tr>";
						echo "<td>".($key+1)."</td>";
						foreach( $farm_arr as $entity ){
							if( isset($_SESSION['farm'][$key][$entity]) && !empty($_SESSION['farm'][$key][$entity]) ){
								echo "<td>".$_SESSION['farm'][$key][$entity]."</td>";
							}else{
								echo "<td></td>";
							}
						}
						echo "</tr>";
					}				
				?>
			</table>
		<?php
			}
		?>
	
		<?php
			//echo "<pre>"; print_r($alive_arr); echo "</pre>";
			//if( isset($_POST) ){ echo "<pre>"; print_r($_POST); echo "<pre/>"; }
			//if( isset($_SESSION) ){ echo "<pre>"; print_r($_SESSION); echo "<pre/>"; }
		?>

	</body>
</html>

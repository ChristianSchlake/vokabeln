<?php
//	session_start();
	include("sub_init_database.php");
	include("functions.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">
	
	<?php
		echo "<title>Einstellungen</title>";
	?>

	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="icons/foundation-icons.css"/>

<style>      
	.size-12 { font-size: 12px; }
	.size-14 { font-size: 14px; }
	.size-16 { font-size: 16px; }
	.size-18 { font-size: 18px; }
	.size-21 { font-size: 21px; }
	.size-24 { font-size: 24px; }
	.size-36 { font-size: 36px; }
	.size-48 { font-size: 48px; }
	.size-60 { font-size: 60px; }
	.size-72 { font-size: 72px; }
	.size-X { font-size: 26px; }
</style>

</head>

<body>

<?php	
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen eintragen */
	$updateStatus=0;
	$editStatus=0;
	$id="";
//	print_r($_GET);
	$abfrage="";
	foreach ($_GET as $key => $value) {
		if ($key=="id") {
			$id=$value;
		}
		if ($key=="updateStatus") {
			$updateStatus=$value;
		}
		if ($key=="editStatus") {
			$editStatus=$value;
		}
		if ($key=="wert") {
			$abfrage=$abfrage.",".$key."=".$value;
		}
	}

	if ($updateStatus==1) {
		$abfrage=substr($abfrage,1);
		$abfrage="UPDATE einstellungen SET ".$abfrage." WHERE id=".$id;
		mysql_query($abfrage);
	}	
?>

<nav class="top-bar" data-topbar data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<h1><a href="main_suche.php?reset=true"><i class="fi-refresh"></i> Einstellungen</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="left">
	        <li class="divider hide-for-small"></li>
		</ul>
	</section>
</nav>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->
<div class="row">
	<fieldset>
		<legend>Einstellungen</legend>
		<div class="row collapse">
			<div class="small-12 large-12 columns"\>
				<dl class="sub-nav">
					<?php
						if($editStatus==0){
			  				echo "<dd class=\"active\"><a href=\"sub_einstellungen.php?editStatus=0\">Show</a></dd>";
		  					echo "<dd><a href=\"sub_einstellungen.php?editStatus=1\">Edit</a></dd>";
						}
						else {
			  				echo "<dd><a href=\"sub_einstellungen.php?editStatus=0\">Show</a></dd>";
		  					echo "<dd class=\"active\"><a href=\"sub_einstellungen.php?editStatus=1\">Edit</a></dd>";
						}
					?>
				</dl>		
			</div>
		</div>

		<div class="row collapse">
			<div class="small-12 large-4 columns">	
				<p><strong>Name</strong></p>
			</div>
			<div class="small-12 large-8 columns">	
				<p><strong>Wert</strong></p>
			</div>
			<hr>
		</div>

		<?php
			$abfrage="SELECT DISTINCT * FROM einstellungen ORDER BY name";
			$ergebnis = mysql_query($abfrage);
			if($editStatus==0){
				while($row = mysql_fetch_object($ergebnis))
				{
					echo "<div class=\"row collapse\">";
						echo "<div class=\"small-12 large-4 columns\">";
							echo "<p>",$row->name,"</p>";
						echo "</div>";
						echo "<div class=\"small-12 large-8 columns\">";
							echo "<p>",$row->wert,"</p>";
						echo "</div>";
						echo "<hr>";
					echo "</div>";
				}
			} else {
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<form class=\"custom\" action=\"sub_einstellungen.php\" method=\"get\">";
						echo "<div class=\"row collapse\">";
							echo "<input type=\"hidden\" name=\"id\" value=\"".$row->id."\"\>";
							echo "<input type=\"hidden\" name=\"editStatus\" value=\"1\">";
							echo "<div class=\"small-2 large-4 columns\">";
								echo "<p>".$row->name."</p>";
							echo "</div>";
							echo "<div class=\"small-2 large-8 columns\">";
								echo "<input type=\"text\" value=\"",$row->wert,"\" name=\"wert\">";
							echo "</div>";
							echo "<div class=\"small-12 large-12 columns\">";
								echo "<button class=\"tiny fi-page-edit secondary expand\" name=\"updateStatus\" value=\"1\" type=\"submit\"></button>";
							echo "</div>";
						echo "</div>";
					echo "</form>";
				}
			}
		?>
	</fieldset>
</div>

<?php
	mysql_close($verbindung);
?>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>
  <script src="js/foundation/foundation.reveal.js"></script>
  <script>$(document).foundation();</script> 

</body>

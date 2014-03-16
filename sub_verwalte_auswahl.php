<?php
//	session_start();
	include("sub_init_database.php");
	include("functions.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">
	<title>Lisen Verwalten</title>

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
	$deleteStatus=0;
	$suchWert="%";
	$editWert="";
	$editNeuerWert="";
	$tabellenBeschreibung="";
//	print_r($_GET);
	foreach ($_GET as $key => $value) {
		if ($key=="tabelle") {$tabelle=$value;}
		if ($key=="editWert") {$editWert=$value;}
		if ($key=="suchWert") {$suchWert=$value;}
		if ($key=="tabellenBeschreibung") {$tabellenBeschreibung=$value;}
		if ($key=="editStatus" AND $value=="1"){$editStatus=1;}
		if ($key=="updateStatus" AND $value=="1"){$updateStatus=1;}
		if ($key=="deleteStatus" AND $value=="1"){$deleteStatus=1;}
		if ($key=="id"){$updateID=$value;}
		if ($key=="editNeuerWert"){
			$editNeuerWert=$value;
			$suchWert=$value;
		}
	}
	if ($updateStatus==1) {
		$abfrage="UPDATE ".$tabelle." SET ".$tabelle." =\"".$editWert."\" WHERE ".$tabelle."ID=\"".$updateID."\"";
		mysql_query($abfrage);
	}

	if ($deleteStatus==1) {
		$abfrage="DELETE FROM ".$tabelle." WHERE ".$tabelle."ID=\"".$updateID."\"";
		mysql_query($abfrage);
	}
	if ($editNeuerWert!="") {
		$abfrage="INSERT INTO ".$tabelle." (".$tabelle.") VALUES (\"".$editNeuerWert."\")";
		mysql_query($abfrage);
	}
	
	
?>

<nav class="top-bar" data-topbar data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<?php
				echo "<h1><a href=\"main_suche.php?aufruf=1\"><i class=\"fi-refresh \"></i> ".$tabellenBeschreibung."</a></h1>";
			?>			
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="left">
			<li class="active"><a href="sub_verwalte_auswahl.php" data-reveal-id="eingabeModal"><i class="fi-page-add"></i> neuer Eintrag</a></li>
		</ul>
	</section>

</nav>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Suchformular -->
<div class="row collapse">
	<fieldset>
		<?php
			echo "<legend>".$tabellenBeschreibung."</legend>";
		?>
		<form action="sub_verwalte_auswahl.php" method="get">
			<?php
				echo "<input type=\"hidden\" name=\"editStatus\" value=\"".$editStatus."\">";
				echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";
				echo "<input type=\"hidden\" name=\"tabellenBeschreibung\" value=\"".$tabellenBeschreibung."\">";
			?>
			<div class="small-8 columns">
				<input type="text" placeholder="Suche" name="suchWert">
			</div>
			<div class="small-4 columns">
				<input class="button prefix secondary" value="suchen" type="Submit">
			</div>
		</form>		
	</fieldset>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->

<div class="row">
	<div class="small-12 large-12 columns"\>
		<dl class="sub-nav">
			<?php
				if($editStatus==0){
	  				echo "<dd class=\"active\"><a href=\"sub_verwalte_auswahl.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=0&tabelle=".$tabelle."\">Show</a></dd>";
  					echo "<dd><a href=\"sub_verwalte_auswahl.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=1&tabelle=".$tabelle."\">Edit</a></dd>";
				}
				else {
	  				echo "<dd><a href=\"sub_verwalte_auswahl.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=0&tabelle=".$tabelle."\">Show</a></dd>";
  					echo "<dd class=\"active\"><a href=\"sub_verwalte_auswahl.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=1&tabelle=".$tabelle."\">Edit</a></dd>";
				}
			?>
		</dl>		
	</div>
</div>


<div class="row collapse">
	<div class="small-2 large-2 columns">	
		<h2>ID</h2>
	</div>
	<div class="small-10 large-10 columns">	
		<h2>Wert</h2>
	</div>
	<hr>
</div>

<?php
	$spalteX=$tabelle."ID";
	$abfrage="
		SELECT DISTINCT ".$spalteX.",".$tabelle."
		FROM ".$tabelle."
		WHERE ".$tabelle." LIKE \"".$suchWert."\"
		ORDER BY upper(".$tabelle.")
	";
	$ergebnis = mysql_query($abfrage);
	if($editStatus==0){							
		while($row = mysql_fetch_object($ergebnis))
		{
			echo "<div class=\"row collapse\">";
				echo "<div class=\"small-1 large-1 columns\">";
					echo "<p>",$row->$spalteX,"</p>";
				echo "</div>";
				echo "<div class=\"small-11 large-11 columns\">";
					echo "<p>",$row->$tabelle,"</p>";
				echo "</div>";
			echo "</div>";
		}
	} else {
		while($row = mysql_fetch_object($ergebnis)) {
			echo "<div class=\"row collapse\">";
				echo "<form class=\"custom\" action=\"sub_verwalte_auswahl.php\" method=\"get\">";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$row->$spalteX."\"\>";
					echo "<input type=\"hidden\" name=\"editStatus\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";
					echo "<div class=\"small-2 large-2 columns\">";
						echo "<p>",$row->$spalteX,"</p>";
					echo "</div>";
					echo "<div class=\"small-4 large-4 columns\">";
						echo "<input type=\"text\" value=\"",$row->$tabelle,"\" name=\"editWert\">";
					echo "</div>";
					echo "<div class=\"small-6 large-6 columns\">";
						echo "<button class=\"fi-page-edit secondary size-X\" name=\"updateStatus\" value=\"1\" type=\"submit\"></button>";
						echo "<button class=\"fi-page-delete secondary size-X\" name=\"deleteStatus\" value=\"1\" type=\"submit\"></button>";
					echo "</div>";
				echo "</form>";
			echo "</div>";
		}
	}
?>

<div id="eingabeModal" class="reveal-modal" data-reveal>
	<fieldset>
		<?php
			echo "<legend>Eingabeformular - ".$tabellenBeschreibung."</legend>"
		?>			
		<form action="sub_verwalte_auswahl.php" method="GET" class="custom">
			<div class="row collapse">
				<div class="small-12 large-12 columns">
					<?php
						echo "<input type=\"hidden\" name=\"editStatus\" value=\"0\">";
						echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";					
						echo "<input type=\"text\" placeholder=\"Beschreibung\" name=\"editNeuerWert\">";
					?>
				</div>
			</div>					
			<div class="row collapse">
				<div class="small-12 large-12 columns">
					<button class="button expand" type="Submit">eintragen</button>
				</div>
			</div>
			<?php
				echo "<input type=\"hidden\" name=\"tabellenBeschreibung\" value=\"".$tabellenBeschreibung."\">";
			?>
		</form>
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

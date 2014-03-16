<?php
	session_start();
	include("sub_init_database.php");
	include("functions.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>Finanzen</title>
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
		.size-X { font-size: 20px; }
	</style>

</head>

<body>
	<?php
		foreach ($_GET as $key => $value) {
			if ($key=="wort") {$wort=$value;}
			if ($key=="sprache") {$sprache=$value;}		
		}
	?>


	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name"><h1><a href="main_suche.php?reset=true">Vokabeltrainer</a></h1></li>
			<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
		</ul>
		<section class="top-bar-section">
			<!--ul class="left">
			    <li class="divider"></li>
			    <li class="has-dropdown"><a href="#"><i class="fi-graph-bar "></i> Auswertungen</a>
			            <ul class="dropdown">
							<li><a href="sub_auswertungStruktur.php?spaltenTypX=auswahlStruktur&spalte=konto&tabelle=buchung_kategorie&tabellenBeschreibung=Konto">Konto</a></li>
			            </ul>
			    </li>
			    <li class="divider"></li>
			    <li class="has-dropdown"><a href="#"><i class="fi-list "></i> Listen verwalten</a>
			            <ul class="dropdown">
							<li><a href="sub_verwalte_auswahlStruktur.php?editStatus=0&tabelle=verwendung&tabellenBeschreibung=Verwendung">Verwendung</a></li>
							<li><a href="sub_verwalte_auswahlStruktur.php?editStatus=0&tabelle=buchung_kategorie&tabellenBeschreibung=Konto">Konto</a></li>
			            </ul>
			    </li>
			    <li class="divider"></li>
				<li><a href="sub_einstellungen.php"><i class="fi-wrench"></i> Einstellungen</a></li>
			    <li class="divider"></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="newFileModal"><i class="fi-page-add"></i> neuer Eintrag</a></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="searchFileModal"><i class="fi-page-search"></i> Eintrag suchen</a></li>
			</ul>
		</section-->
	</nav>

	<div class="row">
		<fieldset>
			<legend>Übersetzung</legend>
			<?php
				$abfrage="SELECT * FROM vokabeln WHERE ".$sprache."=\"".$wort."\"";
				$ergebnis = mysql_query($abfrage);
//				echo $abfrage;
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<div class=\"row\">";
						echo "<div class=\"small-12 large-4 columns\">";
							echo "<a href=\"sub_vokabel.php?wort=".$row->englisch."&sprache=englisch\">".$row->englisch."</a>";
						echo "</div>";
						echo "<div class=\"small-12 large-4 columns\">";
							echo "<a href=\"sub_vokabel.php?wort=".$row->deutsch."&sprache=deutsch\">".$row->deutsch."</a>";
						echo "</div>";
						echo "<div class=\"small-12 large-4 columns\">";
							echo "<a href=\"main_suche.php?reset=true\">".$row->wortart."</a>";
						echo "</div>";
					echo "</div>";
				}
			?>
		</fieldset>
	</div>

	<div class="row">
		<fieldset>
			<legend>Sätze</legend>
			<?php
				$abfrage="SELECT * FROM saetze WHERE 
					   ".$sprache." LIKE \"% ".$wort." %\" 
					OR ".$sprache." LIKE \"".$wort." %\" 
					OR ".$sprache." LIKE \"% ".$wort."?\" 
					OR ".$sprache." LIKE \"% ".$wort.".\" 
					OR ".$sprache." LIKE \"% ".$wort."!\"  
					OR ".$sprache." LIKE \"% ".$wort."\"";
				$ergebnis = mysql_query($abfrage);
/*				echo "<div class=\"row\">";
					echo "<div class=\"small-12 large-6 columns\">";
						echo "<h1>englisch</h1>";
					echo "</div>";
					echo "<div class=\"small-12 large-6 columns\">";
						echo "<h1>deutsch</h1>";
					echo "</div>";
				echo "</div>";
*/
				while($row = mysql_fetch_object($ergebnis)) {
					echo "<hr>";
					echo "<div class=\"row\">";
						$worter=explode(" ", $row->englisch);
						echo "<div class=\"small-12 large-6 columns\">";
							foreach ($worter as $i => $value) {
								if ($value!="") {
									$abfrageX="SELECT COUNT(id) FROM vokabeln WHERE englisch=\"".$value."\"";
									$ergebnisX = mysql_query($abfrageX);
									$mengeX = mysql_fetch_row($ergebnisX);
									$mengeX = $mengeX[0];
									if ($mengeX>0) {
										$abfrageXX="SELECT id FROM vokabeln WHERE englisch=\"".$value."\"";
										$ergebnisXX = mysql_query($abfrageXX);
										$mengeXX = mysql_fetch_row($ergebnisXX);
										$mengeXX=$mengeXX[0];
										if ($value==$wort) {
											echo "<em><strong><a href=\"sub_vokabel.php?wort=".$value."&sprache=englisch\"> ",$value,"</a></strong></em>";
										} else {
											echo "<a href=\"sub_vokabel.php?wort=".$value."&sprache=englisch\"> ",$value,"</a>";
										}
									} else {
										echo " ",$value;
									}
								}
							}
						echo "</div>";
						$worter=explode(" ", $row->deutsch);
						echo "<div class=\"small-12 large-6 columns\">";
							foreach ($worter as $i => $value) {
								if ($value!="") {
									$abfrageX="SELECT COUNT(id) FROM vokabeln WHERE deutsch=\"".$value."\"";
									$ergebnisX = mysql_query($abfrageX);
									$mengeX = mysql_fetch_row($ergebnisX);
									$mengeX = $mengeX[0];
									if ($mengeX>0) {
										$abfrageXX="SELECT id FROM vokabeln WHERE deutsch=\"".$value."\"";
										$ergebnisXX = mysql_query($abfrageXX);
										$mengeXX = mysql_fetch_row($ergebnisXX);
										$mengeXX=$mengeXX[0];
										if ($value==$wort) {
											echo "<em><strong><a href=\"sub_vokabel.php?wort=".$value."&sprache=deutsch\"> ",$value,"</a></strong></em>";
										} else {
											echo "<a href=\"sub_vokabel.php?wort=".$value."&sprache=deutsch\"> ",$value,"</a>";
										}
									} else {
										echo " ",$value;
									}
								}
							}
						echo "</div>";
					echo "</div>";
				}
			?>
		</fieldset>
	</div>
	
	<?php
		$abfrage="SELECT verb.verbenID FROM vokabeln INNER JOIN verben as verb ON (verb.verbenID = vokabeln.verbenID) INNER JOIN zeiten as zeit ON (zeit.zeitenID = vokabeln.zeitenID) WHERE vokabeln.".$sprache."=\"".$wort."\" ORDER BY zeit.zeitenID";
		$ergebnis = mysql_query($abfrage);
		$menge = mysql_fetch_row($ergebnis);
		if ($menge>0) {
			echo "<div class=\"row\">";
				echo "<fieldset>";
					echo "<legend>Verbformen</legend>";
					$abfrage="SELECT verb.verbenID FROM vokabeln INNER JOIN verben as verb ON (verb.verbenID = vokabeln.verbenID) INNER JOIN zeiten as zeit ON (zeit.zeitenID = vokabeln.zeitenID) WHERE vokabeln.".$sprache."=\"".$wort."\" ORDER BY zeit.zeitenID";
					$ergebnis = mysql_query($abfrage);
					$id = mysql_fetch_row($ergebnis);
					$id=$id[0];
					$abfrage="SELECT verb.grundform FROM vokabeln INNER JOIN verben as verb ON (verb.verbenID = vokabeln.verbenID) INNER JOIN zeiten as zeit ON (zeit.zeitenID = vokabeln.zeitenID) WHERE vokabeln.".$sprache."=\"".$wort."\" ORDER BY zeit.zeitenID";
					$ergebnis = mysql_query($abfrage);
					$grundform = mysql_fetch_row($ergebnis);
					$grundform=$grundform[0];
					echo "<div class=\"row\">";
						echo "<h1>",$grundform,"</h1>";
					echo "</div>";
				
					$abfrage="SELECT * FROM vokabeln INNER JOIN verben as verb ON (verb.verbenID = vokabeln.verbenID) INNER JOIN zeiten as zeit ON (zeit.zeitenID = vokabeln.zeitenID) WHERE verb.verbenID=".$id." ORDER BY zeit.zeitenID";
					$ergebnis = mysql_query($abfrage);
					while($row = mysql_fetch_object($ergebnis)) {
						echo "<hr>";
						echo "<div class=\"row\">";
							echo "<div class=\"small-12 large-4 columns\">";
								echo "<a href=\"sub_vokabel.php?wort=".$row->englisch."&sprache=englisch\">".$row->englisch."</a>";
							echo "</div>";
							echo "<div class=\"small-12 large-4 columns\">";
								echo "<a href=\"sub_vokabel.php?wort=".$row->deutsch."&sprache=deutsch\">".$row->deutsch."</a>";
							echo "</div>";
							echo "<div class=\"small-12 large-4 columns\">";
								echo "<a href=\"main_suche.php?reset=true\">".$row->wortart."</a>";
							echo "</div>";
						echo "</div>";
					}
				echo "</fieldset>";
			echo "</div>";
		}
	?>
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

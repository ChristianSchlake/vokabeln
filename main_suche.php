<?php
	session_start();
	include("sub_init_database.php");
	include("functions.php");

	// reset session
	foreach ($_GET as $key => $value) {
		if ($key=="reset" AND $value=="true") {
			$_SESSION = array();
		}
	}
	foreach ($_POST as $key => $value) {
		if ($key=="reset" AND $value=="true") {
			$_SESSION = array();
		}
	}

/*	if (!isset($_SESSION['datum'])) {$_SESSION['datum'] ="01.01.1902-".date("d.m.Y");}
	if (!isset($_SESSION['verwendung'])) {$_SESSION['verwendung'] = "%";}
	if (!isset($_SESSION['beschreibung'])) {$_SESSION['beschreibung'] = "%";}
	if (!isset($_SESSION['konto'])) {$_SESSION['konto'] = "%";}
	if (!isset($_SESSION['betrag'])) {$_SESSION['betrag'] = "%";}
	if (!isset($_SESSION['id'])) {$_SESSION['id'] = "%";}
*/

	if (!isset($_SESSION['startPage'])) {$_SESSION['startPage'] ="0";}


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
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen eintragen */
	$maxEintraegeProSite=abfrageEinstellung("maxEintraegeProSite");
/*	$neuerEintrag=0;
	$suchEintrag=0;
	$updateEintrag=0;
	$sortBy="DESC";
	$sort="datum";
	$whereClause="";
	$kontoVon="%";
	$kontoNach="%";
	$showEingabemaske=abfrageEinstellung("showEingabemaske");
	$showSuchmaske=abfrageEinstellung("showSuchmaske");
*/
	$neuerSatz=0;
	$neueVokabel=0;
	$deutsch="";
	$englisch="";
	foreach ($_POST as $key => $value) {
		if ($key=="uebergabe") {
			switch ($value) {
				case "neuerSatz":
					$neuerSatz=1;							
					break;
				case "neueVokabel":
					$neueVokabel=1;
					break;
			}
		}
		if ($key=="deutsch") {$deutsch=$value;}
		if ($key=="englisch") {$englisch=$value;}
		if ($key=="wortart") {$wortart=$value;}
		if ($key=="zeiten") {$zeiten=$value;}
		if ($key=="verben") {$verben=$value;}
	}
	/*
	foreach ($_GET as $key => $value) {
		if ($key=="uebergabe" AND $value=="suchEintrag") {$suchEintrag=1;}
		if ($key=="sort") {$sort=$value;}
		if ($key=="sortBy") {$sortBy=$value;}
		if ($key=="datum") {$_SESSION['datum'] = $value;}
		if ($key=="verwendung") {$_SESSION['verwendung'] = $value;}
		if ($key=="konto") {$_SESSION['konto'] = $value;}
		if ($key=="betrag") {$_SESSION['betrag'] = $value;}
		if ($key=="startPage") {$_SESSION['startPage'] = $value;}
		if ($key=="id") {$_SESSION['id'] = $value;}		
	}

	switch ($sortBy) {
		case "ASC":
			$sortBy="DESC";
			break;
		case "DESC":
			$sortBy="ASC";
			break;
	}
*/

// Neuer Eintrag
	if ($neuerSatz==1) {
		$aufruf="INSERT INTO saetze (deutsch,englisch) VALUES (\"".$deutsch."\",\"".$englisch."\")";
//		echo $aufruf;
		$eintragen = mysql_query($aufruf);
	}
	if ($neueVokabel==1) {
		switch ($wortart) {
			case 1:
				$aufruf="INSERT INTO vokabeln (deutsch,englisch,wortartID,verbenID,zeitenID) VALUES (\"".$deutsch."\",\"".$englisch."\",".$wortart.",".$verben.",".$zeiten.")";
				break;
		    default:
				$aufruf="INSERT INTO vokabeln (deutsch,englisch,wortartID) VALUES (\"".$deutsch."\",\"".$englisch."\",".$wortart.")";
				break;
		}
//		echo $aufruf;
		$eintragen = mysql_query($aufruf);
	}
/*
// Update Eintrag
	if ($updateEintrag==1) {
		$betragNeg=$betrag*-1;

		$aufruf="UPDATE metadaten SET datum=STR_TO_DATE(\"".$datum."\", \"%d.%m.%Y\"),verwendung=".$verwendung.",beschreibung=\"".$beschreibung."\" WHERE id=".$id;
		$eintragen = mysql_query($aufruf);

		$aufruf="UPDATE buchungen SET konto=".$kontoVon.",betrag=".$betragNeg." WHERE id=".$idVon;
		$eintragen = mysql_query($aufruf);

		$aufruf="UPDATE buchungen SET konto=".$kontoNach.",betrag=".$betrag." WHERE id=".$idNach;
		$eintragen = mysql_query($aufruf);

		$_SESSION['id']=$id;
	}

// Suchen
	if ($suchEintrag==1) {$_SESSION['startPage'] ="0";}
	// Datum auseinandernehmen
	$datumX=explode("-",$_SESSION['datum']);		
	if (strtotime($datumX[0])>strtotime("01.01.1902")) {			
		$whereClause="WHERE meta.datum >= STR_TO_DATE('".$datumX[0]."','%d.%m.%Y')";
	}
	if (strtotime($datumX[1])>strtotime("01.01.1902")) {
		$whereClause=$whereClause." AND meta.datum <= STR_TO_DATE('".$datumX[1]."','%d.%m.%Y')";
	} else {
		// wenn nur ein Wert, dann nach dem genauen Datum suchen!
		$whereClause="WHERE meta.datum = STR_TO_DATE('".$datumX[0]."','%d.%m.%Y')";
	}
	// Betrag auseinandernehmen
	$betragX=explode("-",$_SESSION['betrag']);
	$betragX[0]=str_replace(",",".",$betragX[0]);
	$betragX[1]=str_replace(",",".",$betragX[1]);
	if ($betragX[0]!="%") {
		$AddClause=" AND betrag >= ".$betragX[0];
	}
	if ($betragX[1]!="") {
		$AddClause=$AddClause." AND betrag <= ".$betragX[1];
	} else {
		// wenn nur ein Wert, dann nach dem genauen Datum suchen!
		$AddClause=" AND betrag = ".$betragX[0];
	}
	if ($betragX[0]=="%") {
		$AddClause="";
	}	
	$whereClause=$whereClause.$AddClause;
	// rest eintragen
	if ($_SESSION['verwendung']!="%") {$whereClause=$whereClause." AND meta.verwendung LIKE ".$_SESSION['verwendung'];}
	if ($_SESSION['konto']!="%") {$whereClause=$whereClause." AND konto LIKE ".$_SESSION['konto'];}
	if ($_SESSION['id']<>"%") {$whereClause=$whereClause." AND meta.id = ".$_SESSION['id'];}
*/
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name"><h1><a href="main_suche.php?reset=true">Vokabeltrainer</a></h1></li>
			<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
		</ul>
		<section class="top-bar-section">
			<ul class="left">
			    <li class="divider"></li>
			    <!--li class="has-dropdown"><a href="#"><i class="fi-graph-bar "></i> Auswertungen</a>
			            <ul class="dropdown">
							<li><a href="sub_auswertungStruktur.php?spaltenTypX=auswahlStruktur&spalte=konto&tabelle=buchung_kategorie&tabellenBeschreibung=Konto">Konto</a></li>
			            </ul>
			    </li-->
			    <li class="divider"></li>
			    <li class="has-dropdown"><a href="#"><i class="fi-list "></i> Listen verwalten</a>
			            <ul class="dropdown">
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=zeiten&tabellenBeschreibung=Zeiten">Zeiten</a></li>
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=wortart&tabellenBeschreibung=Wortart">Wortart</a></li>
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=verben&tabellenBeschreibung=Verben">Verben</a></li>
			            </ul>
			    </li>
			    <!--li class="divider"></li>
				<li><a href="sub_einstellungen.php"><i class="fi-wrench"></i> Einstellungen</a></li>
			    <li class="divider"></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="newFileModal"><i class="fi-page-add"></i> neuer Eintrag</a></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="searchFileModal"><i class="fi-page-search"></i> Eintrag suchen</a></li-->
			    <li class="divider"></li>
				<li><a href="sub_einstellungen.php"><i class="fi-wrench"></i> Einstellungen</a></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="newSatz"><i class="fi-page-add"></i> neuer Satz</a></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="newVokabel"><i class="fi-page-add"></i> neue Vokabel</a></li>
			</ul>
		</section>
	</nav>

	<div class="row">
		<fieldset>
			<legend>Vokabeln</legend>
			<?php
				$abfrage="SELECT * FROM vokabeln INNER JOIN wortart as wArt ON (vokabeln.wortartID = wArt.wortartID) WHERE vokabeln.zeitenID IS NULL OR vokabeln.zeitenID=1 ORDER BY englisch";
//				$ergebnis = mysql_query($abfrage);
//				$menge = mysql_num_rows($ergebnis);
//				echo "menge:",$menge,"<br>";
				$abfrage=$abfrage." LIMIT ".$_SESSION['startPage'].",".$maxEintraegeProSite;
				$ergebnis = mysql_query($abfrage);
//				echo $abfrage;

//				$abfrage2="SELECT sum(betrag) as summe FROM buchungen INNER JOIN metadaten as meta ON (idBuchung = meta.id) inner join buchung_kategorie as buchung on (buchung.buchung_kategorieID = konto) inner join verwendung as verw on (verw.verwendungID = meta.verwendung) ".$whereClause." ORDER BY ".$sort." ".$sortBy;
//				echo "<br>",$abfrage2;
//				$ergebnis2=mysql_query($abfrage2);
//				$row2 = mysql_fetch_assoc($ergebnis2);
//				$summe = $row2['summe'];				
				echo "<div class=\"row\">";
					echo "<div class=\"small-12 large-4 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=englisch&sortBy=".$sortBy."\">englisch</a>";
					echo "</div>";
					echo "<div class=\"small-12 large-4 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=deutsch&sortBy=".$sortBy."\">deutsch</a>";
					echo "</div>";
					echo "<div class=\"small-12 large-4 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=wArt.wortart&sortBy=".$sortBy."\">Wortart</a>";
					echo "</div>";
				echo "</div>";

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
			?>

			<div class="row">
				<div class="pagination-centered">
					<ul class="pagination">
						<?php
							echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$_SESSION['startPage']-$maxEintraegeProSite,"\">&laquo;</a></li>";
							for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
								if($i>=$_SESSION['startPage'] and $i <$_SESSION['startPage']+$maxEintraegeProSite){
									echo "<li class=\"current\"><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
								}
								else{
									echo "<li><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
								}
							}
							echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$_SESSION['startPage']+$maxEintraegeProSite,"\">&raquo;</a></li>";							
						?>
					</ul>
				</div>
			</div>
		</fieldset>
	<div>

	<!-- neuer Satz -->
	<div id="newSatz" class="reveal-modal" data-reveal>
		<fieldset>
		<legend>neuer Satz</legend>
			<?php
				include("sub_addsatz.php");
			?>
		</fieldset>
	</div>
	<!-- neue Vokabel -->
	<div id="newVokabel" class="reveal-modal" data-reveal>
		<fieldset>
		<legend>neue Vokabel</legend>
			<?php
				include("sub_addVokabel.php");
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

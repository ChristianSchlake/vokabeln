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
	if (!isset($_SESSION['suchwort'])) {$_SESSION['suchwort'] ="%";}
	if (!isset($_SESSION['typ'])) {$_SESSION['typ'] ="%";}


?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>Vokabeln</title>
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
	$exportAnsicht=abfrageEinstellung("exportansicht");
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
	$suchEintrag=0;
	$changeSort=0;
	$editStatus=0;
	$deutsch="";
	$englisch="";
	$whereClause="";
	$sortBy="DESC";
	$sort="englisch";
	foreach ($_POST as $key => $value) {
		if ($key=="uebergabe") {
			switch ($value) {
				case "neuerSatz":
					$neuerSatz=1;							
					break;
				case "neueVokabel":
					$neueVokabel=1;
				case "suchen":
					$suchEintrag=1;
					break;
			}
		}
		if ($key=="deutsch") {$deutsch=$value;}
		if ($key=="englisch") {$englisch=$value;}
		if ($key=="deutschEdit") {$deutschEdit=$value;}
		if ($key=="id") {$id=$value;}
		if ($key=="englischEdit") {$englischEdit=$value;}
		if ($key=="typEdit") {$typEdit=$value;}
		if ($key=="wortartEdit") {$wortartEdit=$value;}
		if ($key=="wortart") {$wortart=$value;}
		if ($key=="zeiten") {$zeiten=$value;}
		if ($key=="verben") {$verben=$value;}
		if ($key=="typ") {$typ=$value;}
		if ($key=="suchwort") {$_SESSION['suchwort']=$value;}
		if ($key=="updateStatus" AND $value=="1"){$updateStatus=1;}
		if ($key=="deleteStatus" AND $value=="1"){$deleteStatus=1;}
		if ($key=="editStatus") {$editStatus=$value;}
	}
	if ($updateStatus==1) {
		$abfrage="UPDATE vokabeln SET deutsch=\"".$deutschEdit."\", englisch=\"".$englischEdit."\", typID=".$typEdit.", wortartID=".$wortartEdit."  WHERE id=\"".$id."\"";
		mysql_query($abfrage);
//		echo $abfrage;
	}

	if ($deleteStatus==1) {
		$abfrage="DELETE FROM vokabeln WHERE id=\"".$id."\"";
		mysql_query($abfrage);
//		echo $abfrage;
	}
	
	foreach ($_GET as $key => $value) {
		if ($key=="sort") {$sort=$value;}
		if ($key=="sortBy") {$sortBy=$value;}
		if ($key=="startPage") {$_SESSION['startPage'] = $value;}
		if ($key=="typ") {$_SESSION['typ'] = $value;}
		if ($key=="changeSort") {$changeSort=$value;}
		if ($key=="editStatus") {$editStatus=$value;}
		if ($key=="uebergabe" AND $value="suchen") {$suchEintrag=1;}
		if ($key=="suchwort") {$_SESSION['suchwort']=$value;}
	}

	if ($changeSort==1) {
		switch ($sortBy) {
			case "ASC":
				$sortBy="DESC";
				break;
			case "DESC":
				$sortBy="ASC";
				break;
		}
	}

// Neuer Satz
	if ($neuerSatz==1) {				
		$aufruf="INSERT INTO saetze (deutsch,englisch,typID) VALUES (\"".$deutsch."\",\"".$englisch."\",".$typ.")";
		$eintragen = mysql_query($aufruf);
//		echo $aufruf;
	}	
// Neue Vokabel
	if ($neueVokabel==1) {
		$eintrag="false";
		$deutsch=explode(";",$deutsch);
		$englisch=explode(";",$englisch);
		foreach ($deutsch as $key => $value) {
			foreach ($englisch as $keyE => $valueE) {
				switch ($wortart) {
					case 1:
						// verben
						if ($eintrag=="false") {
							$eintrag="true";
							$sql="SELECT MAX(verbenID) FROM verben";
							$result=mysql_query($sql);
							$maxID=mysql_result($result,0,0);
							$maxID=$maxID+1;

							$aufruf="INSERT INTO verben(verbenID, verben) VALUES (".$maxID.",\"".$valueE."\")";
//							echo "verb eintragen: ",$aufruf,"<br>";
							$eintragen = mysql_query($aufruf);
						} else {
							if ($keyE>0) {
								$aufruf="INSERT INTO vokabeln (deutsch,englisch,wortartID,verbenID,zeitenID) VALUES (\"".$value."\",\"".$valueE."\",".$wortart.",".$maxID.",".$keyE.")";
//								echo $aufruf,"<br>";
								$eintragen = mysql_query($aufruf);
							}
						}
						break;
					default:
						// sonstiges
						$aufruf="INSERT INTO vokabeln (deutsch,englisch,wortartID) VALUES (\"".$value."\",\"".$valueE."\",".$wortart.")";
//						echo $aufruf,"<br>";
						$eintragen = mysql_query($aufruf);
						break;
				}
			}
		}
	}

// Suchen
	if ($suchEintrag==1) {
		$_SESSION['startPage'] ="0";
	}
		if ($_SESSION['suchwort']!="%") {
			$whereClause=$whereClause." 
				AND (vokabeln.englisch LIKE \"%".$_SESSION['suchwort']."%\" 
				OR vokabeln.deutsch LIKE \"%".$_SESSION['suchwort']."%\")";
		}
		if ($_SESSION['typ']!="%") {
			$whereClause=$whereClause." 
				AND (vokabeln.typID LIKE ".$_SESSION['typ'].") "; 
		}
?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
	<?php
		include("sub_hauptmenu.php");
	?>
	<!--nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name"><h1><a href="main_suche.php?reset=true">Vokabeltrainer</a></h1></li>
			<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
		</ul>
		<section class="top-bar-section">
			<ul class="left">
			    <li class="divider"></li>			    
			    <li class="has-dropdown"><a href="#"><i class="fi-list "></i> Listen verwalten</a>
			            <ul class="dropdown">
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=zeiten&tabellenBeschreibung=Zeiten">Zeiten</a></li>
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=wortart&tabellenBeschreibung=Wortart">Wortart</a></li>
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=verben&tabellenBeschreibung=Verben">Verben</a></li>
							<li><a href="sub_verwalte_auswahl.php?editStatus=0&tabelle=typ&tabellenBeschreibung=Typen">Typen</a></li>
							<li><a href="sub_verwalte_saetze.php">Sätze</a></li>
			            </ul>
			    </li>
			    <li class="divider"></li>
			    <li class="has-dropdown"><a href="#"><i class="fi-list "></i> Vokabeln lernen</a>
			            <ul class="dropdown">
							<li><a href="sub_vokabel.php?rand=1&sprache=englisch"><i class="fi-wrench"></i> Englisch übersetzen</a></li>
							<li><a href="sub_vokabel.php?rand=1&sprache=deutsch"><i class="fi-wrench"></i> Deutsch übersetzen</a></li>
			            </ul>
			    </li>
			    <li class="divider"></li>
				<li><a href="sub_einstellungen.php"><i class="fi-wrench"></i> Einstellungen</a></li>
				<li class="divider"></li>
				<li class="active"><a href="main_suche.php" tabindex="1" data-reveal-id="newSatz"><i class="fi-page-add"></i> neuer Satz</a></li>
				<li class="active"><a href="main_suche.php" tabindex="2" data-reveal-id="newVokabel"><i class="fi-page-add"></i> neue Vokabel</a></li>
				<li class="divider"></li>
			</ul>
			<ul class ="right">
				<li class="has-dropdown">
					<a>Typ eingrenzen</a>
					<ul class="dropdown">
						<li><a href="?typ=%&suchwort=%&uebergabe=suchen">--Alles anzeigen--</a></li>
						<?php
							generateListOrdnerAuswahl("typ");
						?>
					</ul>
				</li>
				<li class="divider"></li>
				<li class="has-form">
					<div class="row collapse">
						<form action="main_suche.php" method="POST" class="custom">
							<input name="suchwort" type="text" placeholder="Suche">
							<input type="hidden" name="uebergabe" value="suchen">
						</form>
					</div>
				</li>
			</ul>
		</section>
	</nav-->

<div class="row">
	<div class="small-12 large-12 columns"\>
		<dl class="sub-nav">
			<?php
				if($editStatus==0){
	  				echo "<dd class=\"active\"><a href=\"main_suche.php?editStatus=0\">Show</a></dd>";
  					echo "<dd><a href=\"main_suche.php?editStatus=1\">Edit</a></dd>";
				}
				else {
	  				echo "<dd><a href=\"main_suche.php?editStatus=0\">Show</a></dd>";
  					echo "<dd class=\"active\"><a href=\"main_suche.php?editStatus=1\">Edit</a></dd>";
				}
			?>
		</dl>		
	</div>
</div>


<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle -->
	<?php
		$abfrage="SELECT * FROM vokabeln 
			INNER JOIN wortart as wArt ON (vokabeln.wortartID = wArt.wortartID)
			INNER JOIN typ as t ON (t.typID = vokabeln.typID)  
			WHERE (vokabeln.zeitenID IS NULL 
				OR vokabeln.zeitenID=1)";
		$abfrage=$abfrage.$whereClause;
		$abfrage=$abfrage." ORDER BY ".$sort." ".$sortBy;
		$ergebnis = mysql_query($abfrage);
		$menge = mysql_num_rows($ergebnis);

		$abfrage=$abfrage." LIMIT ".$_SESSION['startPage'].",".$maxEintraegeProSite;
//		echo $abfrage;
		if ($exportAnsicht==0) {
	?>
		<div class="row">
			<fieldset>
				<legend>Vokabeln</legend>
				<?php
					$ergebnis = mysql_query($abfrage);
					echo "<div class=\"row\">";
						echo "<div class=\"small-6 large-1 columns\">";
							echo "<a class=\"button expand\" href=\"main_suche.php?changeSort=1&sort=id&sortBy=".$sortBy."\">ID</a>";
						echo "</div>";
						echo "<div class=\"small-6 large-3 columns\">";
							echo "<a class=\"button expand\" href=\"main_suche.php?changeSort=1&sort=englisch&sortBy=".$sortBy."\">englisch</a>";
						echo "</div>";
						echo "<div class=\"small-6 large-3 columns\">";
							echo "<a class=\"button expand\" href=\"main_suche.php?changeSort=1&sort=deutsch&sortBy=".$sortBy."\">deutsch</a>";
						echo "</div>";
						echo "<div class=\"small-6 large-2 columns\">";
							echo "<a class=\"button expand\" href=\"main_suche.php?changeSort=1&sort=wArt.wortart&sortBy=".$sortBy."\">Wortart</a>";
						echo "</div>";
						echo "<div class=\"small-6 large-2 columns\">";
							echo "<a class=\"button expand\" href=\"main_suche.php?changeSort=1&sort=t.typ&sortBy=".$sortBy."\">Typ</a>";
						echo "</div>";
						echo "<div class=\"small-6 large-1 columns\">";
							echo "<a class=\"button expand\" href=\"main_suche.php?changeSort=1&sort=richtig&sortBy=".$sortBy."\">Rating</a>";
						echo "</div>";
					echo "</div>";

					if ($editStatus==0) {
						while($row = mysql_fetch_object($ergebnis)) {
							echo "<hr>";
							echo "<div class=\"row\">";
								echo "<div class=\"small-6 large-1 columns\">";
									echo "<a href=\"main_suche.php?reset=true\">".$row->id."</a>";
								echo "</div>";
								echo "<div class=\"small-6 large-3 columns\">";
									echo "<a href=\"sub_vokabel.php?wort=".$row->englisch."&sprache=englisch\">".$row->englisch."</a>";
								echo "</div>";
								echo "<div class=\"small-6 large-3 columns\">";
									echo "<a href=\"sub_vokabel.php?wort=".$row->deutsch."&sprache=deutsch\">".$row->deutsch."</a>";
								echo "</div>";
								echo "<div class=\"small-6 large-2 columns\">";
									echo "<a href=\"main_suche.php?reset=true\">".$row->wortart."</a>";
								echo "</div>";
								echo "<div class=\"small-6 large-2 columns\">";
									echo "<a href=\"main_suche.php?reset=true\">".$row->typ."</a>";
								echo "</div>";
								echo "<div class=\"small-6 large-1 columns\">";
									echo "<a href=\"main_suche.php?reset=true\">".$row->richtig."</a>";
								echo "</div>";
							echo "</div>";
						}
					} else {
						while($row = mysql_fetch_object($ergebnis)) {
							echo "<div class=\"row collapse\">";
								echo "<form class=\"custom\" action=\"main_suche.php\" method=\"POST\">";
									echo "<input type=\"hidden\" name=\"id\" value=\"".$row->id."\"\>";
									echo "<input type=\"hidden\" name=\"editStatus\" value=\"1\">";
									echo "<input type=\"hidden\" name=\"suchWort\" value=\"".$suchWort."\">";
									echo "<input type=\"hidden\" name=\"sortBy\" value=\"".$sortBy."\">";
	//								echo "<input type=\"hidden\" name=\"typ\" value=".$typ.">";
									echo "<div class=\"small-12 large-6 columns\">";
										echo "<input type=\"text\" value=\"",$row->englisch,"\" name=\"englischEdit\">";
									echo "</div>";
									echo "<div class=\"small-12 large-6 columns\">";
										echo "<input type=\"text\" value=\"",$row->deutsch,"\" name=\"deutschEdit\">";
									echo "</div>";
									echo "<div class=\"small-12 large-6 columns\">";
										echo "<select name=\"typEdit\">";
											generateListFormular($row->typID,"typ","typ");
										echo "</select>";
									echo "</div>";
									echo "<div class=\"small-12 large-6 columns\">";
										echo "<select name=\"wortartEdit\">";
											generateListFormular($row->wortartID,"wortart","wortart");
										echo "</select>";
									echo "</div>";
									echo "<div class=\"small-6 large-6 columns\">";
										echo "<button class=\"fi-page-edit secondary size-X expand\" name=\"updateStatus\" value=\"1\" type=\"submit\"></button>";
									echo "</div>";
									echo "<div class=\"small-6 large-6 columns\">";
										echo "<button class=\"fi-page-delete secondary size-X expand\" name=\"deleteStatus\" value=\"1\" type=\"submit\"></button>";
									echo "</div>";
									echo "<hr>";
								echo "</form>";
							echo "</div>";
						}
					}
				?>
			
				<div class="row">
					<hr>
					<div class="pagination-centered">
						<ul class="pagination">
							<?php
								echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$_SESSION['startPage']-$maxEintraegeProSite,"\">&laquo;</a></li>";
								for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
									if($i>=$_SESSION['startPage'] and $i <$_SESSION['startPage']+$maxEintraegeProSite){
										echo "<li class=\"current\"><a href=\"main_suche.php?sort=".$sort."&sortBy=".$sortBy."&startPage=",$i,"\">",$i,"</a></li>";
									}
									else{
										echo "<li><a href=\"main_suche.php?sort=".$sort."&sortBy=".$sortBy."&startPage=",$i,"\">",$i,"</a></li>";
									}
								}
								echo "<li class=\"arrow\"><a href=\"main_suche.php?sort=".$sort."&sortBy=".$sortBy."&startPage=",$_SESSION['startPage']+$maxEintraegeProSite,"\">&raquo;</a></li>";							
							?>
						</ul>
					</div>
				</div>
			</fieldset>
		<div>
	<?php
		}
		if ($exportAnsicht==1) {
	?>
		<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
		<!-- Exportansicht -->
			<div class="row">
				<fieldset>
					<legend>Exportansicht</legend>
					<?php
						$ergebnis = mysql_query($abfrage);
						while($row = mysql_fetch_object($ergebnis)) {
							echo "<p>".$row->id.";".$row->englisch.";".$row->deutsch.";".$row->wortart.";".$row->typ.";".$row->richtig."<br></p>";
						}
					?>
			
					<div class="row">
						<hr>
						<div class="pagination-centered">
							<ul class="pagination">
								<?php
									echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$_SESSION['startPage']-$maxEintraegeProSite,"\">&laquo;</a></li>";
									for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
										if($i>=$_SESSION['startPage'] and $i <$_SESSION['startPage']+$maxEintraegeProSite){
											echo "<li class=\"current\"><a href=\"main_suche.php?sort=".$sort."&sortBy=".$sortBy."&startPage=",$i,"\">",$i,"</a></li>";
										}
										else{
											echo "<li><a href=\"main_suche.php?sort=".$sort."&sortBy=".$sortBy."&startPage=",$i,"\">",$i,"</a></li>";
										}
									}
									echo "<li class=\"arrow\"><a href=\"main_suche.php?sort=".$sort."&sortBy=".$sortBy."&startPage=",$_SESSION['startPage']+$maxEintraegeProSite,"\">&raquo;</a></li>";							
								?>
							</ul>
						</div>
					</div>
				</fieldset>
			<div>
	<?php
		}
	?>



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

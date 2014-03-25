<nav class="top-bar" data-topbar>
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
</nav>

<form action="main_suche.php" method="POST" class="custom">
		<div class="small-12 large-4 columns">
			<label>Englisch</label>
			<input tabindex="2" type="text" name="englisch" />
		</div>
		<div class="small-12 large-4 columns">
			<label>Deutsch</label>
			<input tabindex="3" type="text" name="deutsch" />
		</div>
		<div class="small-12 large-4 columns">
			<label>Wortart</label>
			<select tabindex="4" name="wortart">
				<?php
					generateListFormular("-","wortart","wortart");
				?>
			</select>
		</div>

		<div class="small-12 large-12 columns">
			<button tabindex="5" class="button expand" type="Submit">Daten eintragen</button>
		</div>
		<input type="hidden" name="uebergabe" value="neueVokabel">
</form>

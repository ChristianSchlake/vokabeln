<form action="main_suche.php" method="POST" class="custom">
		<div class="small-12 large-4 columns">
			<label>Englisch</label>
			<textarea tabindex="1" name="englisch"></textarea>			
		</div>

		<div class="small-12 large-4 columns">
			<label>Deutsch</label>
			<textarea tabindex="2" name="deutsch"></textarea>			
		</div>

		<div class="small-12 large-4 columns">
			<label>Typ</label>
			<select tabindex="4" name="typ">
				<?php
					generateListFormular("-","typ","typ");
				?>
			</select>
		</div>

		<div class="small-12 large-12 columns">
			<button tabindex="3" class="button expand" type="Submit">Daten eintragen</button>
		</div>
		<input type="hidden" name="uebergabe" value="neuerSatz">
</form>

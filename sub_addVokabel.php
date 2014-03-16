<form action="main_suche.php" method="POST" class="custom">
		<div class="small-12 large-4 columns">
			<label>Deutsch</label>
			<input type="text" name="deutsch" />
		</div>
		<div class="small-12 large-4 columns">
			<label>Englisch</label>
			<input type="text" name="englisch" />
		</div>
		<div class="small-12 large-4 columns">
			<div class="small-12 large-6 columns">
				<label>Wortart</label>
				<select name="wortart">
					<?php
						generateListFormular("-","wortart","wortart");
					?>
				</select>
			</div>
		</div>

		<div class="row">
			<fieldset>
				<legend>Verbeigenschaften</legend>
				<div class="small-12 large-6 columns">
					<label>Zeitform</label>					
					<select name="zeiten">
						<option selected value="-">-</option>
						<?php
							generateListFormular("-","zeiten","zeitenID");
						?>
					</select>
				</div>
				<div class="small-12 large-6 columns">
					<label>zugehöriges Verb</label>
					<select name="verben">
						<option selected value="-">-</option>
						<?php
							generateListFormular("-","verben","verben");
						?>
					</select>
				</div>
			</fieldset>
		</div>

		<div class="small-12 large-12 columns">
			<button class="button expand" type="Submit">Daten eintragen</button>
		</div>
		<input type="hidden" name="uebergabe" value="neueVokabel">
</form>

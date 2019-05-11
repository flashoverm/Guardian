<?php
require_once '../resources/library/db_engines.php';

if (! $isAdmin) {
	showAlert ( "Kein Administrator angemeldet - <a href=\"" . $config["urls"]["html"] . "/events\" class=\"alert-link\">Zurück</a>" );
} else {
	if (! count ( $user )) {
		showInfo ( "Es ist kein Personal angelegt" );
	} else {
		?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center">Vorname</th>
				<th class="text-center">Nachname</th>
				<th class="text-center">Löschzug</th>
				<th class="text-center">E-Mail</th>
				<th class="text-center">Wachteilnahme</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ( $user as $row ) {
			?>
			<tr>
				<td class="text-center"><?= $row->firstname; ?></td>
				<td class="text-center"><?= $row->lastname; ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center"><?= $row->email; ?></td>
				<td class="text-center">
			<?php
				if ($row->active) {
					echo "Freigegeben";
				} else {
					echo "Gesperrt";
				}
			?>
				</td>
				<td class="text-center">
					<form method="post" action="">
			<?php
			if ($row->active) {
				echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" value='" . $row->uuid . "'/>";
				echo "<input type=\"submit\" value=\"Sperren\"  class=\"btn btn-outline-primary btn-sm\"/>";
			} else {
				echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" value='" . $row->uuid . "'/>";
				echo "<input type=\"submit\" value=\"Freigeben\"  class=\"btn btn-primary btn-sm\"/>";
			}
			?>
					</form>
				</td>
			</tr>
	<?php
		}
		?>
			</tbody>
	</table>
</div>

<?php
	}
}
?>
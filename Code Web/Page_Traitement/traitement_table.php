<div id="table" class="table-responsive">
	<table align="center">
		<tbody>
			<tr>
				<th>Identification</th>
				<th>Poids</th>
				<th>% de remplissage</th>
				<th>Frequence d'utilisation</th>
				<th>Longitude</th>
				<th>Latitude</th>
			</tr>
		
		<?php 
			//Affichage de toutes les poubelles dans un tableau 
			$monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');
			$mabdd = $monPDO->query('SELECT * FROM poubelle');
			while($mesdonnee = $mabdd->fetch())
			{
				echo '<tr><td>'.$mesdonnee['ID_Poubelle'].'</td>';
				echo '<td>'.$mesdonnee['Poids'].'</td>';
				echo '<td>'.$mesdonnee['Remplissage'].'</td>';
				echo '<td>'.$mesdonnee["Frequence_utilisation"].'</td>';
				echo '<td>'.$mesdonnee['Longitude'].'</td>';
				echo '<td>'.$mesdonnee['Latitude'].'</td></tr>';
			}
		?>
		</tbody>
	</table>
</div>
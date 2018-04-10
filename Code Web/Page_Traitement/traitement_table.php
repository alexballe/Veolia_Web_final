<div id="table" class="table-responsive">
	<table align="center">
		<tbody>
			<tr>
				<th>Num. de la poubelle</th>
				<th>Poids</th>
				<th>Taux de remplissage</th>
				<th>Longitude</th>
				<th>Latitude</th>
				<th>Frequence d'utilisation</th>
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
				echo '<td>'.$mesdonnee['Longitude'].'</td>';
				echo '<td>'.$mesdonnee['Latitude'].'</td>';
				if ($mesdonnee["Frequence_utilisation"] >= 0 && 5 >= $mesdonnee["Frequence_utilisation"])
				{
					echo '<td style="text-align:center;">0 - 25% ('.$mesdonnee["Frequence_utilisation"].' fois/semaine)</td></tr>';
				}
				else if ($mesdonnee["Frequence_utilisation"] >= 5 && 10 >= $mesdonnee["Frequence_utilisation"])
				{
					echo '<td style="text-align:center;">25 - 50% ('.$mesdonnee["Frequence_utilisation"].' fois/semaine)</td></tr>';
				}
				else if ($mesdonnee["Frequence_utilisation"] >= 10 && 15 >= $mesdonnee["Frequence_utilisation"])
				{
					echo '<td style="text-align:center;">50 - 75% ('.$mesdonnee["Frequence_utilisation"].' fois/semaine)</td></tr>';
				}
				else if ($mesdonnee["Frequence_utilisation"] >= 15 && 20 >= $mesdonnee["Frequence_utilisation"])
				{
					echo '<td style="text-align:center;">75 - 100% ('.$mesdonnee["Frequence_utilisation"].' fois/semaine)</td></tr>';
				}
				else if ($mesdonnee["Frequence_utilisation"] < 20)
				{
					echo '<td style="text-align:center;">+ 100% ('.$mesdonnee["Frequence_utilisation"].' fois/semaine)</td></tr>';
				}
			}
		?>
		</tbody>
	</table>
</div>
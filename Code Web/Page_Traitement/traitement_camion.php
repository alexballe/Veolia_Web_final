<div id="tableau_camion" class="table-responsive">
	
	<form method="POST" action="tableau_camion.php">
		<table align="center">
			<tbody>
				<tr>
					<th>ID</th>
					<th>Nom</th>
					<th>Supression</th>
				</tr>
		
		<?php 
			$monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');

			if(isset($_POST['delete'])){
		        if(isset($_POST['select'])){
		            foreach($_POST['select'] as $valeur){
		                $mabdd=$monPDO->prepare(" DELETE FROM `camion` WHERE `ID_Camion`=".$valeur );
		                $mabdd->execute();
		            }
		        }
		    }
		
			$mabdd = $monPDO->query('SELECT * FROM camion');
			while($mesdonnee = $mabdd->fetch())
			{
				echo '<tr><td style="text-align:center">'.$mesdonnee['ID_Camion'].'</td>';
				echo '<td style="text-align:center">'.$mesdonnee['Nom'].'</td>';
				if(isset($mesdonnee['ID_Camion']))
				{
					$ID=$mesdonnee['ID_Camion'];
				}
				
		?>
                    <td align=center><input type="checkbox" name="select[]" value="<?php echo $ID; ?>"></td>
                </tr>
        <?php
			}
			
		?>
			</tbody>
		</table>

        <p><input type="submit" name="delete" value="Supprimer" class="btn btn-success" style="margin-top:20px"></p>
        <h5>Veuillez selectioner la/les ligne(s) à supprimer</h5>
	</form>
</div>
<?php
	$monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');
	$mabdd = $monPDO->query('SELECT * FROM `poubelle` , `trajet` WHERE `trajet`.`ID_Poubelle` = `poubelle`.`ID_Poubelle` AND `trajet`.`ID_Camion`= '.$_POST['camion']);
	$i=0;
	while($mesdonnee = $mabdd->fetch())
	{	
		if ($mesdonnee['Remplissage'] > 75 || $mesdonnee['Poids'] > 5)
		{
			$ID[$i] = $mesdonnee['ID_Poubelle'];
			$long[$i] = $mesdonnee['Longitude'];
			$lat[$i] = $mesdonnee['Latitude'];
			$Poids[$i] = $mesdonnee['Poids'];
			$Remplissage[$i] = $mesdonnee['Remplissage'];
			$Ouverture[$i] = $mesdonnee["Frequence_utilisation"];
			$i++;
		}
	}
?>
<div id="code_map">

	<?php	
		//
		echo "<div class =\"poubaffiche\"><p>Point de départ A : Centre de traitement</p>Nombre total de poubelle pleine : ".$i."</div>"; 
	?>
	<script>
//-----------------------------------------------------------------------------------------------------------		

		//Initialisation des variables 
		var lat = new Array();
		var long = new Array();
		var tableauMarqueurs = new Array();
		var contentString = new Array();
		
		//Reset de l'itineraire
		if (directionsDisplay != null) {
		    directionsDisplay.setMap(null);
		    directionsDisplay = null;
		}

		var directionsDisplay = new google.maps.DirectionsRenderer();
		var directionsService = new google.maps.DirectionsService();
		var i = 0, marqueur, maCarte, infowindow, request, content, j = 0, point = 1, infopoint = 1;
		
		<?php 	

			//Insertion des données recuperer en base php dans des tableau javascript
	 		for($j=0; $j<$i;$j++)
	 		{ 
		 		if(isset($long[$j]) && isset($lat[$j]))
				{

		?>
					lat[point] = <?php echo $lat[$j]; ?>;
					long[point] = <?php echo $long[$j]; ?>;
					point++;
		<?php 
				}  
			}
		?>
	
//-----------------------------------------------------------------------------------------------------------

		//Creation d'un point de depart, le centre de traitement 
		tableauMarqueurs[0] = new google.maps.LatLng(49.8775453, 2.2976631);
		contentString[0] = '<div>Centre de traitement</div>';

		//Creation de tableaux d'infoview et point LatLng
		for(infopoint = 1; infopoint <point; infopoint++)
		{

			tableauMarqueurs[infopoint]  = new google.maps.LatLng(lat[infopoint], long[infopoint]);
	
		}
	
//------------------------------------------------------------------------------------------------------------

		//Creation d'une bulle d'info de marker
		infowindow = new google.maps.InfoWindow();  

		if (tableauMarqueurs.length > 1)
		{
			directionsDisplay.setMap(maCarte);
	    }


//-----------------------------------------------------------------------------------------------------------

		//Creation des markers 
		for( i = 0; i < tableauMarqueurs.length; i++ ) {
	    	
	    	//Creation du marker 
			marqueur = new google.maps.Marker({
				position: tableauMarqueurs[i]
			});


//-----------------------------------------------------------------------------------------------------------

	 		//Si il n'y qu'un point, on affiche qu'un marqueur sans itinéraire
	 		if (tableauMarqueurs.length == 1)
	 		{

 				//Creation du marqueur
	            marqueur = new google.maps.Marker(
	            {
					position: tableauMarqueurs[i],
					map: maCarte
				});
					
				//On recupere les info de chaque marker et on les insere dans la bulle d'info
				content = contentString[i];

	            //Et de son info-bulle 
				marqueur.addListener('click', (function(marqueur,content) 
		 		{  
		            return function() 
		            {  
			 			infowindow.setContent(content);
					    infowindow.open(maCarte, marqueur);
					} 
		        })(marqueur,content));

	        }
	        //Si il y a plusieurs marqueurs on créer un itineraire 
	        else if (tableauMarqueurs.length > 1)
	        {
	        	//Le premier marqueur devient le depart/origin de l'itineraire 
		        if (i == 0) 
	            {
					request.waypoints = [];
	                request.origin = marqueur.getPosition();

	            }
	            //Le dernier marqueur devient la destination de l'itineraire
	            else if (i == tableauMarqueurs.length - 1) 
	            {
					request.destination = marqueur.getPosition();
	            }
	            //Les autres deviennent des points de passages
	            else if (i != tableauMarqueurs.length - 1 || i != 0)
	            {
					request.waypoints.push(
					{
						location: marqueur.getPosition(),
						stopover: true
					});
	            }
	        }
		}

//-----------------------------------------------------------------------------------------------------------
		
		//Si il y a plus d'un marqueur
		if (tableauMarqueurs.length > 1)
		{

			//Creation de l'itinéraire 
			directionsService.route(request, function(result, status) {
		        if (status == google.maps.DirectionsStatus.OK) {
		            directionsDisplay.setDirections(result);
		        }
		    });
	    }

//-----------------------------------------------------------------------------------------------------------
	</script>
</div>
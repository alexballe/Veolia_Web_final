<div id="connexion_filaire">
<?php  
	//Connexion TCP/IP 
 	$resul="";
    $adresse = '192.168.240.1';
    $port = 255;
    //Ouverture du Socket
    $socket = fsockopen($adresse, $port);
    //Si le socket n'existe pas, on affiche un message d'erreur  
    if (!$socket) {
        echo "connexion echoue";
    }
    //Si le socket existe, on en recupere les informations recues
    else
    {
        
        $resul.= fgets($socket);
        $taille=strlen($resul);
        $posdecoup=0;
        $i=0;

        //Decoupage des informations de la poubelle 
        for($j=0;$j<$taille;$j++)
        {
            if($resul[$j] == ',')
            {
                $taillevar=$j-$posdecoup;
                $stringDonneePoubelle[$i] = substr($resul,$posdecoup,$taillevar);
                $posdecoup=$j+1;
                $i++;
            }  
        }
        //Decoupage de la derniere information de la poubelle 
        $stringDonneePoubelle[$i] = substr($resul, $posdecoup, $taille);

//---------------------------------------------------------------------------------------------------------------------------------------------------

        $monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');        
        $mabdd = $monPDO->query('SELECT * FROM `poubelle`');
        $nbPoubelle=0; 
        while($mesdonnee = $mabdd->fetch())
        {   
            $ID_Poubelle[$nbPoubelle]=$mesdonnee["ID_Poubelle"];
            $nbPoubelle++;
        }

        $compteurPoubelle = 0;
        for ($i = 0; $i < $nbPoubelle; $i++)
        {
        	//On verifie si la poubelle existe deja ou pas dans la table poubelle de la BDD
            if($ID_Poubelle[$i] == $stringDonneePoubelle[0])
            {
                $compteurPoubelle++;
            }
        }

        //Si la poubelle n'est pas dans la table poubelle on l'insere avec les informations qui la concerne 
        if($compteurPoubelle == 0)
        {
        	$mabdd=$monPDO->prepare(" INSERT INTO `poubelle`( `ID_Poubelle`, `Poids`, `Latitude`, `Longitude`, `Frequence_utilisation`, `Remplissage`) VALUES ( :ID_Poubelle, :Poids, :Latitude, :Longitude, :Frequence_utilisation, :Remplissage) ");
            $mabdd->execute(array('ID_Poubelle'=>$stringDonneePoubelle[0],'Poids'=>$stringDonneePoubelle[1], 'Latitude'=>"50.0368",'Longitude'=>"2.33442", 'Frequence_utilisation'=>$stringDonneePoubelle[2], 'Remplissage'=>$stringDonneePoubelle[3]));
        }
        //Si la poubelle est dans la table poubelle on modifie les informations qui la concerne avec les informations recu precedemment 
        else 
        {
            $mabdd=$monPDO->query(" UPDATE `poubelle` SET `ID_Poubelle`=".$stringDonneePoubelle[0].", `Poids`=".$stringDonneePoubelle[1].", `Latitude`=50.0368, `Longitude`=2.33442, `Frequence_utilisation`=".$stringDonneePoubelle[2].", `Remplissage`=".$stringDonneePoubelle[3]."  WHERE `ID_Poubelle`=".$stringDonneePoubelle[0]);
        }
    }
?>
</div>
<div style="margin-top:15px; margin-bottom:25px; float:left;" id="test">
    <?php
        $monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');

//---------------------------------------------------------------------------------------------------------------------------------------------------

        $mabdd = $monPDO->query('SELECT * FROM `poubelle`');
        $nbPoubelle=0; 
        $poubelleNonPleine=0;
        
        while($mesdonnee = $mabdd->fetch())
        {   
            if($mesdonnee["Remplissage"] > 75)
            {
                $ID_Poubelle[$nbPoubelle]=$mesdonnee["ID_Poubelle"];
                $Day[$nbPoubelle]=date("Y-m-d");
                $nbPoubelle++;
                
            }
            else if ($mesdonnee["Remplissage"] < 75)
            {
                $ID_PoubelleNonPleine[$poubelleNonPleine]=$mesdonnee["ID_Poubelle"];
                $poubelleNonPleine++;
                
            }
        }

//---------------------------------------------------------------------------------------------------------------------------------------------------

        $mabdd = $monPDO->query('SELECT * FROM `trajet`');
        $nbPoubelleARamasser=0;

        while($mesdonnee = $mabdd->fetch())
        {
            $ID_PoubelleARamasser[$nbPoubelleARamasser]=$mesdonnee["ID_Poubelle"];
            $ID_CamionTrajet[$nbPoubelleARamasser]=$mesdonnee["ID_Camion"];
            $nbPoubelleARamasser++;
        }

//---------------------------------------------------------------------------------------------------------------------------------------------------

        $mabdd = $monPDO->query('SELECT * FROM `camion`');
        $nbCamion=0;

        while($mesdonnee = $mabdd->fetch())
        {
            $ID_Camion[$nbCamion]=$mesdonnee["ID_Camion"];
            $nbCamion++;
        }

//---------------------------------------------------------------------------------------------------------------------------------------------------
        if(isset($ID_Camion) && isset($ID_Poubelle))
        {
            $camion=count($ID_Camion);
            $poubelle=count($ID_Poubelle);

            //Nombre de poubelle par camion
            $taille=$poubelle/$camion;
            $taille1 = round($taille,0,PHP_ROUND_HALF_UP);

            $moitiePoubelle = $poubelle/2;
            $seuil=0;
            $Camion=1;
            $AucunCamion=0;

            //Si il y a plus de Camion que la moitie de Poubelle 
            if ($camion > $moitiePoubelle) 
            {
                $taille1=2;
                //On passe dans la table poubelle 
                for ($i = 0; $i < $nbPoubelle; $i++) 
                {
            
                    if ($seuil == $taille1)
                    {
                        $taille1+=2;
                        $Camion++;
                    }
                    $ID_CamionPoubelle[$i]=$Camion;
                    $seuil++;
                }
            }   //Si il y a moins de Camion que la moitie de Poubelle 
            else if ($camion < $moitiePoubelle)
            {
                //On passe dans la table poubelle 
                for ($i = 0; $i < $nbPoubelle; $i++)
                {
                    if ($seuil == $taille1)
                    {
                        $taille1+=$taille1;
                        $Camion++;
                    }
                    $ID_CamionPoubelle[$i]=$Camion;
                    $seuil++;
                }
            }
        }   //Si il n'y a plus de Camion 
        else if (!isset($camion))
        {
            $AucunCamion=1;
        }

        //Si il n'y a auncun Camion 
        if($AucunCamion == 1)
        {
            //Toutes les poubelles S'affiche sur la meme carte 
            $mabdd=$monPDO->query(" UPDATE `trajet` SET `ID_Camion`= 1");    
            //On passe dans la table poubelle 
            for ($i = 0; $i < $nbPoubelle; $i++)
            {
                $PoubelleAucunCamion=0;
                //Si il y a des poubelles dans la table trajet
                if($nbPoubelleARamasser != 0)
                {
                    //On passe dans la table trajet 
                    for ($j = 0; $j < $nbPoubelleARamasser; $j++)
                    {
                        //Si la poubelle est deja dans la table trajet
                        if($ID_Poubelle[$i] == $ID_PoubelleARamasser[$j])
                        {
                            $PoubelleAucunCamion++;
                        }   //Si la poubelle n'est dans la table trajet
                        else if($ID_Poubelle[$i] != $ID_PoubelleARamasser[$j])
                        {
                            $AjoutPoubelle=$ID_Poubelle[$i];
                        }
                    }
                }   //Si il n'y a pas de poubelle dans la table trajet
                else
                {
                    $AjoutPoubelle1=$ID_Poubelle[$i];
                    $mabdd=$monPDO->prepare(" INSERT INTO `trajet`( `ID_Poubelle`, `ID_Camion`, `Date`) VALUES (:ID_Poubelle, :ID_Camion, :Day) ");
                    $mabdd->execute(array('ID_Poubelle'=>$AjoutPoubelle1,'ID_Camion'=>1, 'Day'=>date("Y-m-d"),));
                }

                //Si la poubelle ne se trouve pas dans la table trajet on l'insere 
                if($PoubelleAucunCamion == 0)
                {
                    if(isset($AjoutPoubelle))
                    {
                        $mabdd=$monPDO->prepare(" INSERT INTO `trajet`( `ID_Poubelle`, `ID_Camion`, `Date`) VALUES (:ID_Poubelle, :ID_Camion, :Day) ");
                        $mabdd->execute(array('ID_Poubelle'=>$AjoutPoubelle,'ID_Camion'=>1, 'Day'=>date("Y-m-d"),));
                    }
                }
            }     
        }   //Si il y a des Camions
        else
        {

//---------------------------------------------------------------------------------------------------------------------------------------------------

            //On passe dans la table poubelle 
            for ($i = 0; $i < $nbPoubelle; $i++)
            {
                $compteurPoubellePrise=0;
                //On passe dans la table trajet
                for ($j = 0; $j < $nbPoubelleARamasser; $j++)
                {
                    //Si la poubelle se trouve dans la table trajet
                    if($ID_PoubelleARamasser[$j] == $ID_Poubelle[$i])
                    {
                        $compteurPoubellePrise++;
                    }
                }

                //Si la poubelle ne se trouve pas dans la table trajet on l'insere
                if($compteurPoubellePrise == 0)
                {
                    $mabdd=$monPDO->prepare(" INSERT INTO `trajet`( `ID_Poubelle`, `ID_Camion`, `Date`) VALUES (:ID_Poubelle, :ID_Camion, :Day) ");
                    $mabdd->execute(array('ID_Poubelle'=>$ID_Poubelle[$i],'ID_Camion'=>$ID_CamionPoubelle[$i], 'Day'=>$Day[$i],));
                }
            }
        }

        //On passe dans la table poubelle en cherchant les poubelles dites "Vide"
        for ($m = 0; $m < $poubelleNonPleine; $m++)
        {
            //On passe dans la table trajet
            for ($j = 0; $j < $nbPoubelleARamasser; $j++)
            {
                //Si la poubelle dites "Vide" se trouve dans la table trajet, on la supprime 
                if($ID_PoubelleNonPleine[$m] == $ID_PoubelleARamasser[$j])
                {
                    $mabdd=$monPDO->prepare("DELETE FROM `trajet` WHERE `ID_Poubelle`=".$ID_PoubelleARamasser[$j]);
                    $mabdd->execute();
                }
            }
        }
        

//---------------------------------------------------------------------------------------------------------------------------------------------------

        //On passe dans la table trajet
        for ($p = 0; $p < $nbPoubelleARamasser; $p++)
        {
            $compteurTrajet=0;
            //On passe dans la table camion
            for ($j = 0; $j < $nbCamion; $j++)
            {
                //Si le Camion se trouve dans la table trajet
                if($ID_CamionTrajet[$p] == $ID_Camion[$j])
                {
                    $compteurTrajet++; //Si le camion se trouve dans la table camion
                }
                else
                {
                    $camion1[$p]=$ID_CamionTrajet[$p];
                }
            }

            //Si le Camion ne se trouve pas dans la table trajet
            if($compteurTrajet == 0)
            {
                $taille=$nbCamion;
                if(isset($ID_Camion[$taille-1]) && isset($camion1[$p]))
                {
                    $mabdd=$monPDO->query(" UPDATE `trajet` SET `ID_Camion`=".$ID_Camion[$taille-1]." WHERE `ID_Camion`=".$camion1[$p]);
                }
            }
        }

        //On passe dans la table camion
        for ($p = 0; $p < $nbCamion; $p++)
        {
            $compteurTrajet=0;
            //On passe dans la table trajet
            for ($j = 0; $j < $nbPoubelleARamasser; $j++)
            {
                //Si le camion se trouve dans la table trajet
                if($ID_CamionTrajet[$j] == $ID_Camion[$p])
                {
                    $compteurTrajet++; //Si le camion se trouve dans la table camion
                }
                else
                {
                    $camiondutrajet[$j]=$ID_CamionTrajet[$j];

                    $IDPoubelleaCamion[$j] = $ID_PoubelleARamasser[$j];
                }
            }

            //Si le camion ne se trouve pas dans la table trajet
            if($compteurTrajet == 0)
            {
                $IDavantdernierCamion = $ID_Camion[$p];
                $IDavantdernierCamion-=1;
                $vartab=0;
                //On passe dans la table trajet
                for ($j = 0; $j < $nbPoubelleARamasser; $j++)
                {
                    if($camiondutrajet[$j] == $IDavantdernierCamion)
                    {
                        $avantdernierCamion[$vartab]=$camiondutrajet[$j];
                        $avantdernierCamionPoubelle[$vartab]=$IDPoubelleaCamion[$j];
                        $vartab++;
                    }
                }

                $moitierCamionavant = $vartab/2;
                $ProchainCamion = round($moitierCamionavant,0,PHP_ROUND_HALF_DOWN);
                $varIncremCamion=0;
                //On passe dans la table trajet
                for ($j = 0; $j < $nbPoubelleARamasser; $j++)
                {
                    if($camiondutrajet[$j] == $IDavantdernierCamion)
                    {
                        if($varIncremCamion != $ProchainCamion)
                        {
                            $mabdd=$monPDO->query(" UPDATE `trajet` SET `ID_Camion`=".$ID_Camion[$p]." WHERE `ID_Poubelle`=".$avantdernierCamionPoubelle[$j]);
                            $varIncremCamion++;
                        }
                    }
                }
            }
        }
        
       
//---------------------------------------------------------------------------------------------------------------------------------------------------

    ?>
</div>
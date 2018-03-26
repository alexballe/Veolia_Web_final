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
            $taille=$poubelle/$camion;
            $taille1 = round($taille,0,PHP_ROUND_HALF_UP);

            $moitiePoubelle = $poubelle/2;
            $seuil=0;
            $Camion=1;
            $AucunCamion=0;


            if ($camion > $moitiePoubelle) //Si il y a plus de Camion que la moitie de Poubelle 
            {
                $taille1=2;
                for ($i = 0; $i < $nbPoubelle; $i++) //On passe dans la table poubelle 
                {
            
                    if ($seuil == $taille1)
                    {
                        $taille1+=2;
                        $Camion++;
                    }
                    $ID_CamionPoubelle[$i]=$Camion;
                    $seuil++;
                }
            }
            else if ($camion < $moitiePoubelle)
            {
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
        }
        else if (!isset($camion))
        {
            $AucunCamion=1;
        }


        if($AucunCamion == 1)
        {
            $mabdd=$monPDO->query(" UPDATE `trajet` SET `ID_Camion`= 1");     
            for ($i = 0; $i < $nbPoubelle; $i++)
            {
                $PoubelleAucunCamion=0;
                if($nbPoubelleARamasser != 0)
                {
                    for ($j = 0; $j < $nbPoubelleARamasser; $j++)
                    {
                        if($ID_Poubelle[$i] == $ID_PoubelleARamasser[$j])
                        {
                            $PoubelleAucunCamion++;
                        }
                        else if($ID_Poubelle[$i] != $ID_PoubelleARamasser[$j])
                        {
                            $AjoutPoubelle=$ID_Poubelle[$i];
                        }
                    }
                }
                else
                {
                    $AjoutPoubelle1=$ID_Poubelle[$i];
                    $mabdd=$monPDO->prepare(" INSERT INTO `trajet`( `ID_Poubelle`, `ID_Camion`, `Date`) VALUES (:ID_Poubelle, :ID_Camion, :Day) ");
                    $mabdd->execute(array('ID_Poubelle'=>$AjoutPoubelle1,'ID_Camion'=>1, 'Day'=>date("Y-m-d"),));
                }

                if($PoubelleAucunCamion == 0)
                {
                    if(isset($AjoutPoubelle))
                    {
                        $mabdd=$monPDO->prepare(" INSERT INTO `trajet`( `ID_Poubelle`, `ID_Camion`, `Date`) VALUES (:ID_Poubelle, :ID_Camion, :Day) ");
                        $mabdd->execute(array('ID_Poubelle'=>$AjoutPoubelle,'ID_Camion'=>1, 'Day'=>date("Y-m-d"),));
                    }
                }
            }     
        }
        else
        {

//---------------------------------------------------------------------------------------------------------------------------------------------------

            for ($i = 0; $i < $nbPoubelle; $i++)
            {
                $compteurPoubellePrise=0;

                for ($j = 0; $j < $nbPoubelleARamasser; $j++)
                {
                    if($ID_PoubelleARamasser[$j] == $ID_Poubelle[$i])
                    {
                        $compteurPoubellePrise++;
                    }
                }

                if($compteurPoubellePrise == 0)
                {
                    $mabdd=$monPDO->prepare(" INSERT INTO `trajet`( `ID_Poubelle`, `ID_Camion`, `Date`) VALUES (:ID_Poubelle, :ID_Camion, :Day) ");
                    $mabdd->execute(array('ID_Poubelle'=>$ID_Poubelle[$i],'ID_Camion'=>$ID_CamionPoubelle[$i], 'Day'=>$Day[$i],));
                }
            }
        }

        for ($m = 0; $m < $poubelleNonPleine; $m++)
        {
            for ($j = 0; $j < $nbPoubelleARamasser; $j++)
            {
                if($ID_PoubelleNonPleine[$m] == $ID_PoubelleARamasser[$j])
                {
                    $mabdd=$monPDO->prepare("DELETE FROM `trajet` WHERE `ID_Poubelle`=".$ID_PoubelleARamasser[$j]);
                    $mabdd->execute();
                }
            }
        }
        

//---------------------------------------------------------------------------------------------------------------------------------------------------

        for ($p = 0; $p < $nbPoubelleARamasser; $p++)
        {
            $compteurTrajet=0;

            for ($j = 0; $j < $nbCamion; $j++)
            {
                if($ID_CamionTrajet[$p] == $ID_Camion[$j])
                {
                    $compteurTrajet++; //Si le camion se trouve dans la table camion
                }
                else
                {
                    $camion1[$p]=$ID_CamionTrajet[$p];
                }
            }

            if($compteurTrajet == 0)
            {
                $taille=$nbCamion;
                if(isset($ID_Camion[$taille-1]) && isset($camion1[$p]))
                {
                    $mabdd=$monPDO->query(" UPDATE `trajet` SET `ID_Camion`=".$ID_Camion[$taille-1]." WHERE `ID_Camion`=".$camion1[$p]);
                }
            }
        }

        for ($p = 0; $p < $nbCamion; $p++)
        {
            $compteurTrajet=0;
            for ($j = 0; $j < $nbPoubelleARamasser; $j++)
            {
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

            if($compteurTrajet == 0)
            {
                $IDavantdernierCamion = $ID_Camion[$p];
                $IDavantdernierCamion-=1;
                $vartab=0;
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
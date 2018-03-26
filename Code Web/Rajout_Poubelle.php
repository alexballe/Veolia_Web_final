<html>
    <head>
        <meta charset="utf-8">
        <title>Poubelle Connectée</title>
        <link rel="icon" type="image/png" href="img/favicon.ico" />
        <!-- CSS -->
        <link href="assets/CSS/table.css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="assets/CSS/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
        <!-- ====================================Sidebar================================ -->
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="sidebar">
                        <ul>
                            <li class="logo">
                                <a href="index.php">
                                    <img src="img/logo.png" />
                                </a>
                            </li>
                            <li class="spacer">Poubelle</li>
                            <li>
                                <a href="index.php">Google Map</a>
                            </li>
                            <li>
                                <a href="tableau.php">Tableau de Poubelles</a>
                            </li>
                            <li class="spacer">Camion de ramassage</li>
                            <li class="current">
                                <a href="Rajout_Poubelle.php" class="cur">Ajout Camions</a>
                            </li>
                            <li>
                                <a href="tableau_camion.php">Tableau de camions</a>
                            </li>
                        </ul>                    
                    </div>
                </div>                 
            </div>
        <!-- ====================================Sidebar================================ -->
        <!-- ====================================Content================================ -->
            <div class="row">
                <div class="content">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="header">
                            &nbsp;
                            <div class="menu">
                                <span class="lvertical"></span>
                                <a href="">Connexion</a>
                            </div>
                        </div>
                    </div>
                    <div class="main">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="widget totals">
                                <h4 class="widget-title"><span>Carte de ramassage</span></h4>
                                <div class="widget-content text-center">
                                    <form method="POST">
                                        <label>Ajoutez un Camion : </label>
                                        <input type="text" name="NomCamion" class="form-control" placeholder style="margin-bottom:15px; margin-top:15px;" requiered>
                                        <input type="submit" name="envoipoubelle" class="btn btn-success">
                                        <?php 
                                            if(isset($_POST["envoipoubelle"]) && isset($_POST["NomCamion"]) && !empty($_POST["NomCamion"]))
                                            {
                                                $monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');
                                                $mabdd = $monPDO->query('SELECT * FROM `camion` ORDER BY `ID_Camion` ASC');
                                                $i=0;
                                                while($mesdonnee = $mabdd->fetch())
                                                {
                                                    $ID_Camion[$i]=$mesdonnee["ID_Camion"];
                                                    $i++;
                                                }

                                                $nomCamion=$_POST["NomCamion"];
                                                $m=0;

                                                /*for($j=0; $j<$i; $j++)
                                                {
                                                    if(isset($ID_Camion[$j+1]))
                                                    {
                                                        $posSuivante=$ID_Camion[$j+1]-1;
                                                        if($ID_Camion[$j] != $posSuivante)
                                                        {
                                                            $posActu[$m]=$ID_Camion[$j]+1;
                                                            $m++;
                                                        }
                                                    }
                                                }*/
                                                $ID=$ID_Camion[$i-1]+1;
                                                $mabdd=$monPDO->prepare(" INSERT INTO `camion`( `ID_Camion`, `Nom`) VALUES (:ID_Camion, :Nom) ");
                                                $mabdd->execute(array('ID_Camion'=>$ID,'Nom'=>$nomCamion,));
                                            }
                                        ?>
                                    </form>
                                    <div style="margin-top:15px; margin-bottom:25px; float:left;" id="test">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ====================================Content================================ -->
        </div>
        <!-- Script -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAB8uAAXIngnrtnDihmcbH5h_jyTOisj8k"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="assets/js/function.js"></script>    
    </body>
</html>

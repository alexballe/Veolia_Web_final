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
                            <li class="current">
                                <a href="tableau.php" class="cur">Tableau de Poubelles</a>
                            </li>
                            <li class="spacer">Camion de ramassage</li>
                            <li>
                                <a href="Rajout_Poubelle.php">Ajout Camions</a>
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
                                <h4 class="widget-title"><span>Toutes les Poubelles Connectées</span></h4>
                                <div class="widget-content text-center">
                                    <div id="table" class="table-responsive"></div>
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

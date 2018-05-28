//----------------------------------------------------------------------------------

//Ajax Tableau de données

$("#table").load("Page_Traitement/traitement_table.php",function(){
    Ajax_table();
});

function Ajax_table()
{
    $.ajax(
        {
            url : 'Page_Traitement/traitement_table.php',
            type : 'POST',
            datatype : 'html',
            success: function(code_html,statut)
            {
                $("#table").replaceWith(code_html);
            },
            error : function(resultat,statut,erreur)
            {
            },
            complete : function(resultat,statut)
            {
            }
        }
    );
}

setInterval("Ajax_table()",5000);

//----------------------------------------------------------------------------------

//Choix du camion de ramassage

var val=1;
function numeroCamion(element)
{
    var idx=element.selectedIndex;
    val=element.options[idx].value;
    $.ajax(
        {
            url : 'Page_Traitement/traitement_map.php',
            type : 'POST',
            data : 'camion='+val,
            datatype : 'html',
            success: function(code_html,statut)
            {
                $("#code_map").replaceWith(code_html);
            },
            error : function(resultat,statut,erreur)
            {
            },
            complete : function(resultat,statut)
            {
            }
        }
    );
}

//----------------------------------------------------------------------------------

//Ajax API Google

$("#code_map").load("Page_Traitement/traitement_map.php",function(){
    Ajax_map();
});

function Ajax_map()
{
    $.ajax(
        {
            url : 'Page_Traitement/traitement_map.php',
            type : 'POST',
            data : 'camion='+val,
            datatype : 'html',
            success: function(code_html,statut)
            {
                $("#code_map").replaceWith(code_html);
            },
            error : function(resultat,statut,erreur)
            {
            },
            complete : function(resultat,statut)
            {
            }
        }
    );
}

setInterval("Ajax_map()",10000);

//----------------------------------------------------------------------------------

$("#tableau_camion").load("Page_Traitement/traitement_camion.php",function(){
    Camion();
});

function Camion()
{
    $.ajax(
        {
            url : 'Page_Traitement/traitement_camion.php',
            type : 'POST',
            datatype : 'html',
            success: function(code_html,statut)
            {
                $("#tableau_camion").replaceWith(code_html);
            },
            error : function(resultat,statut,erreur)
            {
            },
            complete : function(resultat,statut)
            {
            }
        }
    );
}

setInterval("Camion()",1000);

//----------------------------------------------------------------------------------

$("#test").load("Page_Traitement/traitement_donnee.php",function(){
    donnee();
});

function donnee()
{
    $.ajax(
        {
            url : 'Page_Traitement/traitement_donnee.php',
            type : 'POST',
            datatype : 'html',
            success: function(code_html,statut)
            {
                $("#test").replaceWith(code_html);
            },
            error : function(resultat,statut,erreur)
            {
            },
            complete : function(resultat,statut)
            {
            }
        }
    );
}

setInterval("donnee()",1000);

//----------------------------------------------------------------------------------

// Ajax Donnee Poubelle

$("#connexion_filaire").load("Page_Traitement/traitement.php",function(){
    Ajax_donneepoubelle();
});

function Ajax_donneepoubelle()
{
    $.ajax(
        {
            url : 'Page_Traitement/traitement.php',
            type : 'POST',
            datatype : 'html',
            success: function(code_html,statut)
            {
                $("#connexion_filaire").replaceWith(code_html);
            },
            error : function(resultat,statut,erreur)
            {
            },
            complete : function(resultat,statut)
            {
            }
        }
    );
}

setInterval("Ajax_donneepoubelle()",1000);

//----------------------------------------------------------------------------------

function afficherCarte()
{
    //Creation d'une carte Google Map
    maCarte = new google.maps.Map( document.getElementById("map"), {
        zoom: 13,
        center: new google.maps.LatLng(49.8775453, 2.2976631),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    request = {
        travelMode: google.maps.TravelMode.DRIVING, // route / voiture
        optimizeWaypoints : true,
        drivingOptions : {
            departureTime: new Date(Date.now()),
            trafficModel: 'pessimistic'
        }
    };
}
    
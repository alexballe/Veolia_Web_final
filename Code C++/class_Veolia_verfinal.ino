#include "Veolia.h"
#include <Bridge.h>
#include <YunServer.h>
#include <YunClient.h>

#define PORT 255
YunServer server(PORT);
YunClient client;

//Pin Magnétique  : A0
//Pin Ultrason    : trigger = 2, echo = 3 
//Pin dout de la pesée : A2
//Pin sck de la pesée : A3

Veolia Veolia(2,3,A2,A3);
int temps=0, ouverture=0, StatutPrec=1, verifIncrement=0, avertissement=0;
String NombreOuverture;

void setup() 
{
  Bridge.begin();   
  Serial.begin(115200);
  while (!Serial)
  {
    Serial.println("En attence d'une connexion...");
  } 

  server.noListenOnLocalhost();
  server.begin();
  client = server.accept();
  
//  Configuration des broches utilisées
//---------------------------------------------------------------------------------------
  Veolia.init();
//---------------------------------------------------------------------------------------

}

void loop() 
{  
  String TauxRemplissage = Veolia.mesureDistance(Veolia.getTrigger(), Veolia.getEcho());    //Récuperation de la Distance du Telemetre
  String NombreOuv = Veolia.mesureOuverture(&ouverture,&verifIncrement,&avertissement,&StatutPrec);                          //Récuperation de l'état du Capteur magnétique
  int id = 10;
  float poids = Veolia.mesurePoids();

  
   String trameStringEnvoie = (String)id;
   trameStringEnvoie += ",";
   trameStringEnvoie += (String)poids;
   trameStringEnvoie += ",";
   trameStringEnvoie += (String)NombreOuv;
   trameStringEnvoie += ",";
   trameStringEnvoie += (String)TauxRemplissage;
   //envoi de données
   Serial.println(trameStringEnvoie);

  if (client.connected())
  {
     String trameStringEnvoie = (String)id;
     trameStringEnvoie += ",";
     trameStringEnvoie += (String)poids;
     trameStringEnvoie += ",";
     trameStringEnvoie += (String)NombreOuv;
     trameStringEnvoie += ",";
     trameStringEnvoie += (String)TauxRemplissage;
     //envoi de données
     client.println(trameStringEnvoie);
  }
  else
  {
      // client non connecté
      if ((bool)client)
      {
        client.stop();
        Serial.println("client deconnecte");
      }
      
      client = server.accept();//on attend une nouvelle connection, client actuel pas connecté

      // nouvelle connection
      if (client.connected())
      {
        Serial.print("nouvelle connection du client ");
      }
         
  }
  
  
  Veolia.timer(&temps,&NombreOuv,&ouverture);
  delay(1000);
}

#include "Veolia.h"
#include <Bridge.h>
#include <YunServer.h>
#include <YunClient.h>

#define PORT 255
YunServer server(PORT);
YunClient client;

//Pin Magnétique  : A0
//Pin Ultrason    : 
Veolia Veolia(2,3);
int i=0;
int j=1;
int StatutPrec=1;

void setup() 
{
  Bridge.begin();   
  Serial.begin(115200);
/*  while (!Serial)
  {
    
  } */ 

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
  int remplissage = Veolia.mesureDistance(Veolia.getTrigger(), Veolia.getEcho());    //Récuperation de la Distance du Telemetre
  int sensorValue = Veolia.mesureOuverture();                          //Récuperation de l'état du Capteur magnétique
  int id = 10;
  float poids = 5;
     
  if(StatutPrec == 0)
  {
    
    if(sensorValue > 50)
    {
      Serial.println(j);
      j++;
      StatutPrec=1;
    }
  }
  else
  {
    if(sensorValue < 50)
    {
      StatutPrec=0;
    }
  }

  if (client.connected())
  {
     String trameStringEnvoie = (String)id;
     trameStringEnvoie += ",";
     trameStringEnvoie += (String)poids;
     trameStringEnvoie += ",";
     trameStringEnvoie += (String)j;
     trameStringEnvoie += ",";
     trameStringEnvoie += (String)remplissage;
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
  
//  Timer
//---------------------------------------------------------------------------------------
  
  i++;
  
  if(i == 300)
  {
    i=0;
    j=1;
  }

//---------------------------------------------------------------------------------------
  
  delay(1000);
}

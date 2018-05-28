#include <stdio.h>
#include <Arduino.h>
#include "Veolia.h"

//  Constructeur
//---------------------------------------------------------------------------------------
Veolia::Veolia(int trigger, int echo, uint8_t dout, uint8_t sck )
{
  trigPin = trigger;
  echoPin = echo;

  pesee = new Hx711(dout,sck);
}
//---------------------------------------------------------------------------------------



//  Configuration des broches utilisées
//---------------------------------------------------------------------------------------
void Veolia::init()
{
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
}
//---------------------------------------------------------------------------------------



//  Capteur de distance
//---------------------------------------------------------------------------------------

int Veolia::getTrigger()
{
  return trigPin;
}

int Veolia::getEcho()
{
  return echoPin;
}

String Veolia::mesureDistance(int trigger, int echo)
{
  float Distance;
  String TauxRemplissage;
  // Le capteur envoie une impulsion HIGH de 10 µs
  digitalWrite(trigger, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigger, LOW);

  // Lecture du signal du capteur : echo
  pinMode(echo, INPUT);
  // duree est le temp en microsecondes 
  long duree = pulseIn(echo, HIGH);

  // Converti le temps (ms) en distance (cm)
  Distance = (duree/2) / 29.1;
  float EspaceLibre = (Distance / 5)*100;
  float Pourcentage = 100.00 - EspaceLibre;

  
  if(Pourcentage < 0 || Pourcentage > 100)
  {
    TauxRemplissage = "ERROR";
  }
  else
  {
    TauxRemplissage = (String)Pourcentage;
  }
  
  return TauxRemplissage;
}

//---------------------------------------------------------------------------------------


//  Capteur de distance
//---------------------------------------------------------------------------------------

String Veolia::mesureOuverture(int *ouverture, int *verifIncrement, int *avertissement, int *StatutPrec)
{
  int sensorValue = analogRead(A0);
  String NombreOuverture;

  //StatutPrec permet d'inplementer la variable qu'une seul fois
  if(*StatutPrec == 0)
  {
    //Poubelle ouverte
    if(sensorValue > 50)
    {
      *StatutPrec=1;
      *verifIncrement=1;
    }
    NombreOuverture = String(*ouverture);
  }
  else
  {
    //Poubelle fermée
    if(sensorValue < 50)
    {
      *StatutPrec=0;
      *avertissement=0;
      if(*verifIncrement == 1)
      {
        *ouverture+=1;
      }
      NombreOuverture = String(*ouverture);
      Serial.print("Nombre d'ouverture de la poubelle : ");
      Serial.println(NombreOuverture);
      
    }
    else
    {
      *avertissement+=1;
      //La poubelle est resté ouverte trop longtemps
      if(*avertissement >= 20)
      {
        NombreOuverture = "ERROR";
      }
      else
      {
        NombreOuverture = String(*ouverture);
      }
    }
  }
  return NombreOuverture;
}

//---------------------------------------------------------------------------------------



//  Capteur de pesée
//---------------------------------------------------------------------------------------

  float Veolia::mesurePoids()
  {
    return pesee->getGram();
  }

//---------------------------------------------------------------------------------------


//  Timer
//---------------------------------------------------------------------------------------

int Veolia::timer(int *temps, String *NombreOuv, int *ouverture)
{
    *temps+=1;
    if(*temps == 20)
    {
      //Résumé des 20 dernières secondes
      Serial.print("La poubelle a était ouverte ");
      Serial.print(*NombreOuv);
      Serial.print(" fois en ");
      Serial.print(*temps);
      Serial.println(" secondes");

      //Réinitialisation
      *temps=0;
      *ouverture=0;
      Serial.print("Nombre d'ouverture de la poubelle : ");
      Serial.println(*ouverture);
    }
}
//---------------------------------------------------------------------------------------

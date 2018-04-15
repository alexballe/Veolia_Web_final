#include <stdio.h>
#include <Arduino.h>
#include "Veolia.h"

//  Constructeur
//---------------------------------------------------------------------------------------
Veolia::Veolia(int trigger, int echo)
{
  trigPin = trigger;
  echoPin = echo;
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

long Veolia::mesureDistance(int trigger, int echo)
{
  float Distance;
  Serial.println(Distance);
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
  float EspaceLibre = (Distance / 10)*100;
  float Pourcentage = 100.00 - EspaceLibre;
  
  if(Pourcentage >= 00.00)
  {
    
    Serial.print("Remplissage de la poubelle : ");
    Serial.print(Pourcentage);
    Serial.println(" %");
  }
  
  return Pourcentage;
}

//---------------------------------------------------------------------------------------


//  Capteur de distance
//---------------------------------------------------------------------------------------

int Veolia::mesureOuverture()
{
  int sensorValue = analogRead(A0);
  
  return sensorValue;
}

//---------------------------------------------------------------------------------------

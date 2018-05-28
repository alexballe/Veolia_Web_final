#include "Hx711.h"
class Veolia 
{
  private:
  //  Capteur de distance
  //---------------------------------------------------------------------------------------
    int trigPin;    //Trig
    int echoPin;    //Echo
    Hx711 *pesee;   //pesée
  //---------------------------------------------------------------------------------------


  
  public:
    
    Veolia(int trigger, int echo,uint8_t sck ,uint8_t dout);      //Constructeur
    void init();                                                  //Initialisation des pins

  //  Capteur de distance
  //---------------------------------------------------------------------------------------
    int getTrigger();
    int getEcho();
    String mesureDistance(int trigger, int echo);
  //---------------------------------------------------------------------------------------



  //  Capteur magnétique
  //---------------------------------------------------------------------------------------
    String mesureOuverture(int *ouverture, int *verifIncrement, int *avertissement, int *StatutPrec);
  //---------------------------------------------------------------------------------------



  //  Capteur de pesée 
  //---------------------------------------------------------------------------------------
  float mesurePoids();
  //---------------------------------------------------------------------------------------
  
  int timer(int *temps, String *NombreOuv, int *ouverture);
};

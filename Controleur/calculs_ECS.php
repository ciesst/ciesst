<?php
//implementation of the calculs
class calculs_ECS{
	
	const Cp=4185; // chaleur massique de l'eau 
	const Rho=1; // masse volumique de l'eau en kg/L
	const Kj_to_KWh=0.000277778;
	
	
	// rentrés par l'utililsateur ou bdd
	var $Volume_ballon;
	var $TCH; //température eau chaude souhaitée
	var $Teau; // température eau du réseau 
	var $Nb_personnes;
	var $mois;
	var $nbJours;
	
	//calculés par fonctions
	
	var $Becs; // énergie thermique necessaire en Kwh
	
	
	function calculs_ECS($TCH,$Nb,$mois,$Teau,$VolBallon,$boolElectrique){		
		$this->TCH = $TCH;
		$this->setVolumeBallon($Nb,$VolBallon,$boolElectrique);
		$this->mois = $mois;
		$this->nbJours = $this->nbJours($this->convert_mois_jours($this->mois));	
		$this->Teau = $Teau;	
		$this->calcul_Becs();
		
	}
	
	function setVolumeBallon($NB,$Volume,$boolElectrique){
		if($Volume!=0){
			$this->Volume_ballon = $Volume;
			$this->Nb_personnes = 0;
		}
		else{
			if($boolElectrique==1){
				$this->Nb_personnes = $NB;
				$this->Volume_ballon = $this->Nb_personnes*75;
			}
			else{
					$this->Nb_personnes = $NB;
				$this->Volume_ballon = $this->Nb_personnes*200;
			}
		}
	}
	
	 function nbJours($fin) {
            //60 secondes X 60 minutes X 24 heures dans une journée
            $nbSecondes= 60*60*24;
     
            $debut_ts = strtotime("2016-01-01");
            $fin_ts = strtotime($fin);
            $diff = $fin_ts - $debut_ts;
            return round(($diff / $nbSecondes)+1);
        }
		
		 function convert_mois_jours(){
		 
		 switch($this->mois){
			 case "janvier":
				return "2016-01-15";
				break;
				
			case "fevrier":
				return "2016-02-15";
				break;
				
			case "mars":
				return "2016-03-15";
				break;
				
			case "avril":
				return "2016-04-15";
				break;
				
			case "mai":
				return "2016-05-15";
				break;
				
			case "juin":
				return "2016-06-15";
				break;
				
			case "juillet":
				return "2016-07-15";
				break;
				
			case "aout":
				return "2016-08-15";
				break;
				
			case "septembre":
				return "2016-09-15";
				break;
				
			case "octobre":
				return "2016-10-15";
				break;
				
			case "novembre":
				return "2016-11-15";
				break;
				
			case "decembre":
				return "2016-12-15";
				break;
				
			
		 }
	 }
		
	 

 function calcul_Becs(){
	 $this->Becs = (self::Cp*self::Rho*$this->Volume_ballon*($this->TCH-$this->Teau))*self::Kj_to_KWh/1000;
 }
	
	
}




?>
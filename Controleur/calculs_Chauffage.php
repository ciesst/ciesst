<?php
//implementation of the calculs 
class calculs_Chauffage{

	var $Surface_tot_sol;
	var $Hauteur_ss_plafond;
	var $Coeff_dep;
	var $DJU;
	var $mois;
	var $nbJours;
	

	var $Volume;
	var $Besoins_ch;
	
	
	
	
	function calculs_Chauffage($surf,$haut,$mois,$coef,$dju){
		$this->Surface_tot_sol = $surf;
		$this->Hauteur_ss_plafond = $haut;
		$this->mois = $mois;
		$this->nbJours = $this->nbJours($this->convert_mois_jours($this->mois));	
		$this->Coeff_dep = $coef;
		$this->DJU = $dju;
		$this->Volume = $this->Surface_tot_sol*$this->Hauteur_ss_plafond;
		$this->calcul_Bch();
		
		
		
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
		
		
		
	function calcul_Bch(){
		if($this->DJU!=0){
		$this->Besoins_ch = $this->Coeff_dep*$this->Volume*$this->DJU*24/1000;
		}
		else{
		$this->Besoins_ch = 1;//we can't let the results equals to zero. Otherwise, it makes a lot of problem in the F_chart method (divison by zero)
		}		
	}	
	
}



?>
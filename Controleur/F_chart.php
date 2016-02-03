<?php
//implementation of the F-chart method
class F_chart{
	//capteurs
	var $Capteur; // array avec toutes les caracs capteurs
	var $a1;
	var $n0;
	var $Surface;
	var $K1;
	var $K2;
	var $Type_capteur;
	var $K3;

	

	var $G_dep;
	
	var $Surface_tot_sol;
	var $Hauteur_ss_plafond;
	var $Volume; 
	var $Ta; 
	
	var $Ual; //G_dep*Volume
	
	var $TCH; //température eau chaude souhaitée
	var $Teaufroide; 
	
	
	var $NBJoursMois; 
	var $NBSecMois;
	var $nbJours; 
	
	var $IGP;
	var $Chauffage;
	var $ECS;
	
	var $X;
	var $Y;
	var $Y_prime;
	var $A;
	var $Xc;
	var $Xcc;
	var $X_prime;
	var $Yc;
	var $Z;	
	var $Capacitance;
	
	var $mois;
	
	var $Taux_couverture; // f-> par mois
	 
	
	var $Type_chauff;//choix utilisateur
	var $Teau_chauff;
	var $Densite_air;
	var $Cp_air;
	
	var $Nb_personnes;
	var $Volume_ballon;
	
	var $Besoin;
	
	var $Type_usage;
	
	
	
	const Tref=100;
	const Eff_ballon_plus_echangeur=0.97;
	const Eff_convecteur=0.7;
	const Vol_ballon_div_surface_capteur_ref=75;
	const Debit_air=500;
	const KWh_to_J = 3600000;
	
	function F_chart($surf,$haut,$mois,$Nbpers,$Type_chauff,$coef,$capteur,$Surface,$igp,$chauff,$ecs,$type_usage,$Ta,$Teaufroide,$TCH,$VolBallon,$boolElectrique){
		$this->setVolumeBallon($Nbpers,$VolBallon,$boolElectrique);
		$this->mois=$mois;
		$this->nbJours = $this->nbJours($this->convert_mois_jours($this->mois));
		$this->NBJoursMois= $this->case_nb_jours_mois();
		$this->NBSecMois = $this->NBJoursMois*3600*24;
		$this->Type_chauff = $Type_chauff;
		$this->set_chauff();
		$this->set_capacitance();
		$this->Surface_tot_sol = $surf;
		$this->Hauteur_ss_plafond = $haut;
		$this->Volume = $this->Surface_tot_sol*$this->Hauteur_ss_plafond;
		$this->G_dep = $coef;
		$this->Ual = $this->G_dep*$this->Volume;
		$this->set_Z();
		$this->Type_usage = $type_usage;
		$this->calcul_Yc();
		$this->Capteur = $capteur;
		$this->Surface = $Surface;
		$this->set_carac_capteur();
		$this->IGP = $igp*self::KWh_to_J;
		$this->Chauffage = $chauff*self::KWh_to_J;
		$this->ECS = $ecs*self::KWh_to_J;		
		$this->set_Besoin();
		$this->calcul_Y();
		$this->Ta = $Ta;
		$this->Teaufroide=$Teaufroide;
		$this->calcul_X();		
		$this->A = ($this->Volume_ballon/$this->Surface)/self::Vol_ballon_div_surface_capteur_ref;
		$this->calcul_Xc();
		$this->TCH = $TCH;
		$this->calcul_Xcc();
		$this->X_prime=$this->X*$this->Xc*$this->Xcc;
		$this->Y_prime=$this->Y*$this->Yc;
		$this->calcul_taux_couverture();
		
		
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
	
	function set_Besoin(){
		
		if($this->Type_usage == 'ecs'){
			$this->Besoin = $this->ECS;
		}
		else if ($this->Type_usage == 'ecs+chauffage'){
			$this->Besoin = $this->Chauffage+$this->ECS;
		}
		else{
			
			 $this->Besoin = $this->Chauffage;
			
		}
		
	}
	
	function set_carac_capteur(){
		$this->a1 = $this->Capteur['a1'];
		$this->n0 = $this->Capteur['n0'];
		$this->K1 = $this->Capteur['K1'];
		$this->K2 = $this->Capteur['K2'];		
		$this->Type_capteur = $this->Capteur['Type'];
		
		if($this->Type_capteur == 'plan vitre'){
			$this->K3 = $this->K1;
		}
		else{
			$this->K3 = $this->K1*$this->K2;
		}
		
		
	}
	
	function calcul_Y(){
	
		$this->Y=$this->n0*self::Eff_ballon_plus_echangeur*($this->K3*$this->IGP*$this->NBJoursMois*($this->Surface/($this->Besoin*$this->NBJoursMois)));
	
		
		
	}
	
	function calcul_X(){
		
	
		$this->X=($this->Surface*$this->a1*self::Eff_ballon_plus_echangeur*(self::Tref-$this->Ta)*$this->NBSecMois)/($this->Besoin*$this->NBJoursMois);
	
	}
	
	function calcul_Xc(){
		if(0.5<=$this->A && $this->A<=4){
			$this->Xc = pow($this->A,-0.25);
		}
		else{
			$this->Xc=1;
		}
	}
	
	function calcul_Xcc(){
		$this->Xcc=(11.6+1.18*$this->TCH+3.86*$this->Teaufroide-2.32*$this->Ta)/(100-$this->Ta);
	}
	
	function calcul_Yc(){
		if($this->Type_usage== 'ecs'){
			$this->Yc=1;
		}
		else{
		$this->Yc = 0.39+0.65*exp(-0.139/$this->Z);
		}
	}
	
	function set_Z(){
		$this->Z = (self::Eff_convecteur*$this->Capacitance)/$this->Ual;
	}
	function set_chauff(){
		if($this->Type_chauff == 'basse')
		{
			$this->Teau_chauff = 35;
			$this->Densite_air = 1.1774;
			$this->Cp_air = 1.0057;
		}
		else{
			$this->Teau_chauff = 80;
			$this->Densite_air = 0.998;
			$this->Cp_air = 1.009;			
		}
	}
	
	function set_capacitance(){
		
		$this->Capacitance=(self::Debit_air*$this->Densite_air*$this->Cp_air)/1000;
	}
	
	function calcul_taux_couverture(){
		if($this->Type_usage== 'chauffage' && $this->Besoin==3600000){
		$this->Taux_couverture=100;
	}
	else{
		$this->Taux_couverture=100*1.029*$this->Y_prime-0.065*$this->X_prime-0.245*pow($this->Y_prime,2)+0.0018*pow($this->X_prime,2)+0.0215*pow($this->Y_prime,3);
	}
	}
	
	function case_nb_jours_mois(){
		
		 switch($this->mois){
			 case "janvier":
				return 31;
				break;
				
			case "fevrier":
				return 29;
				break;
				
			case "mars":
				return 31;
				break;
				
			case "avril":
				return 30;
				break;
				
			case "mai":
				return 31;
				break;
				
			case "juin":
				return 30;
				break;
				
			case "juillet":
				return 31;
				break;
				
			case "aout":
				return 31;
				break;
				
			case "septembre":
				return 30;
				break;
				
			case "octobre":
				return 31;
				break;
				
			case "novembre":
				return 30;
				break;
				
			case "decembre":
				return 31;
				break;
				
			
		 }
	}
	
	
	
	
	
	
}



?>
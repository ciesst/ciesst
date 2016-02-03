    <?php
	//implementation of the calculs
	class calculs_IGP{
		
		var $longitude;
		var $latitude;
		var $mois;
		var $nbJours;
		var $io;
		var $declinaison;
		var $orientation;
		var $angle_horaire;
		var $angle_horaire_ss;
		var $angle_horaire_sr;
		var $A;
		var $B;
		var $indice_clarte;
		var $kd;
		var $IG0;
		var $igh;	
		var $idh;
		var $ibh;
		var $idp;
		var $irp;
		var $albedo;
		var $Rb;
		var $Ibp;
		var $Igp;
		
		const const_solaire_annee=1367;
		
		
		function calculs_IGP($longitude,$latitude,$orientation,$inclinaison,$albedo,$mois,$igh){
			$this->longitude=$longitude;
			$this->latitude=deg2rad($latitude);
			$this->set_orientation($orientation);
			$this->inclinaison = deg2rad($inclinaison);
			$this->albedo = $albedo;
			$this->mois=$mois;
			$this->nbJours = $this->nbJours($this->convert_mois_jours($this->mois));				
			$this->declinaison = deg2rad(23.45*sin(deg2rad(360/365*(284+$this->nbJours))));			
			$this->igh=$igh;
			$this->calcul_io();
			$this->calcul_irradiation_extra_ter();
			$this->calcul_indice_clarte();
			$this->calcul_kd();
			$this->calcul_irradation_diff_horizontale();
			$this->calcul_irradation_dir_horizontale();
			$this->calcul_irra_diff_inc();
			$this->calcul_irra_ref();
			$this->calcul_A();
			$this->calcul_B();
			$this->calcul_angle_sunrise();
			$this->calcul_angle_sunset();
			$this->calcul_facteur_transpo();
			$this->calcul_irra_dir_plan_inc();
			$this->calcul_irra_globale_plan_inc();

		}
		
		function set_orientation($orientation){
			if($orientation == 0){
				$this->orientation = 1*pow(10,-5);
			}
			else{
				$this->orientation = deg2rad($orientation);
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
		
		function calcul_io(){					
			
			$date = $this->convert_mois_jours();
			
			
			
			$io = self::const_solaire_annee*(1+0.033*cos(deg2rad((($this->nbJours($date)-4)/365)*360)));
			
			$this->io=round($io);
			
		}
		
		function calcul_irradiation_extra_ter(){
			
			
			$angle_horaire = acos(-tan($this->latitude)*tan($this->declinaison));
			
			$this->angle_horaire=$angle_horaire;							
						
			$IG0= 24/pi()*$this->io*((cos($this->latitude)*cos($this->declinaison)*sin($this->angle_horaire))+($this->angle_horaire*sin($this->latitude)*sin($this->declinaison)));
			
			$this->IG0 = $IG0/1000; // en kW/H
			
		}  
	
		 
 
		 function calcul_indice_clarte(){
			 
			
			
			$indice_clarte=$this->igh/$this->IG0;
			
			$this->indice_clarte=$indice_clarte;			
		 
	 }
	 
	 function calcul_kd(){
		$kd=1.0988-1.1988*$this->indice_clarte;
		$this->kd=$kd;
		
	 }
	 
	 function calcul_irradation_diff_horizontale(){
	
		 
		$idh = $this->kd*$this->igh;
		$this->idh=$idh;
		
		
	 }
	 
	 	 function calcul_irradation_dir_horizontale(){
		
		 
		$ibh = $this->igh - $this->idh;
		
		$this->ibh=$ibh;
	 }
	 
	function calcul_irra_diff_inc(){
		
		$idp=$this->idh*((1+cos($this->inclinaison))/2);
		$this->idp=$idp;
		
	}
	
	function calcul_irra_ref(){
	
		$irp=$this->igh*$this->albedo*((1-cos($this->inclinaison))/2);
		$this->irp=$irp;
	}
	
	function calcul_A(){
		$A = (cos($this->latitude)/(sin($this->orientation)*tan($this->inclinaison)))+(sin($this->latitude)/tan($this->orientation));
		$this->A = $A;
	}
	
	function calcul_B(){
		$B = tan($this->declinaison)*((cos($this->latitude)/tan($this->orientation))-(sin($this->latitude)/(sin($this->orientation)*tan($this->inclinaison))));
		$this->B = $B;
	}
	
	function calcul_angle_sunset(){
		if((pow($this->A,2)-pow($this->B,2)+1)<0){//if the numerator is null, it makes problems in the F-chart method (square root negative), so we handle this case
		$this->angle_horaire_ss=$this->angle_horaire;	
		}
		else if($this->orientation > 0){
				$angle_horaire_ss = min($this->angle_horaire,acos((($this->A*$this->B)-sqrt(pow($this->A,2)-pow($this->B,2)+1))/(pow($this->A,2)+1)));
				$this->angle_horaire_ss = $angle_horaire_ss;
			}
			else
			{
				$angle_horaire_ss = min($this->angle_horaire,acos((($this->A*$this->B)+sqrt(pow($this->A,2)-pow($this->B,2)+1))/(pow($this->A,2)+1)));
				$this->angle_horaire_ss = $angle_horaire_ss;
			}

	}
	
	function calcul_angle_sunrise(){
		if(pow($this->A,2)-pow($this->B,2)+1<0){//if the numerator is null, it makes problems in the F-chart method (square root negative), so we handle this case
			$this->angle_horaire_sr=-$this->angle_horaire;	
		}
		else if($this->orientation > 0){
				$angle_horaire_sr = -min($this->angle_horaire,acos((($this->A*$this->B)+sqrt(pow($this->A,2)-pow($this->B,2)+1))/(pow($this->A,2)+1)));
				$this->angle_horaire_sr = $angle_horaire_sr;
			}
			else
			{
				$angle_horaire_sr = -min($this->angle_horaire,acos((($this->A*$this->B)-sqrt(pow($this->A,2)-pow($this->B,2)+1))/(pow($this->A,2)+1)));
				$this->angle_horaire_sr = $angle_horaire_sr;
			}

	}
	
	function calcul_facteur_transpo(){
		
				
		$Rb = ((((cos($this->inclinaison)*sin($this->declinaison)*sin($this->latitude))*($this->angle_horaire_ss-$this->angle_horaire_sr))-((cos($this->latitude)*sin($this->declinaison)*sin($this->inclinaison)*cos($this->orientation))*($this->angle_horaire_ss-$this->angle_horaire_sr))
			+ ((cos($this->latitude)*cos($this->declinaison)*cos($this->inclinaison))*(sin($this->angle_horaire_ss)-sin($this->angle_horaire_sr)))+((cos($this->declinaison)*cos($this->orientation)*sin($this->latitude)*sin($this->inclinaison))*(sin($this->angle_horaire_ss)-sin($this->angle_horaire_sr)))
			- ((cos($this->declinaison)*sin($this->inclinaison)*sin($this->orientation))*(cos($this->angle_horaire_ss)-cos($this->angle_horaire_sr))))
			/ (2*((cos($this->latitude)*cos($this->declinaison)*sin($this->angle_horaire))+($this->angle_horaire*sin($this->latitude)*sin($this->declinaison)))));
			
		$this->Rb = $Rb;

	}
	
	function calcul_irra_dir_plan_inc(){
		$Ibp = $this->Rb*$this->ibh;
		if($Ibp<0){
			$Ibp=0;
		}
		$this->Ibp = $Ibp;
	}
	function calcul_irra_globale_plan_inc(){
		$Igp = $this->idp+$this->irp+$this->Ibp;
		$this->Igp = $Igp;
	}
	
		 
	}
	


       
    ?>
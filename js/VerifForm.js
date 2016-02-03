//check if the location is in france or not

function verifPays(champs){
	if(champs.value == 'France'){
	
	
	  return true;
	}
	else{	

	  return false;	
	 
	}
	
}


function verif(f){

	var paysOK = verifPays(f.pays);
	
	if(paysOK){
		return true;
		 
	}
	else{		
		 alert('Le point selectionne n\'est pas en France');
		return false;
		
	}
	
}


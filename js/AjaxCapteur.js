	function getXhr(){
                                var xhr = null; 
				if(window.XMLHttpRequest) // Firefox et autres
				   xhr = new XMLHttpRequest(); 
				else if(window.ActiveXObject){ // Internet Explorer 
				   try {
			                xhr = new ActiveXObject("Msxml2.XMLHTTP");
			            } catch (e) {
			                xhr = new ActiveXObject("Microsoft.XMLHTTP");
			            }
				}
				else { // XMLHttpRequest non supporté par le navigateur 
				   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
				   xhr = false; 
				} 
                                return xhr;
			}
 
			/**
			* Méthode qui sera appelée sur le click du bouton
			*/
			function go(){
				var xhr = getXhr();
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
					// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
					if(xhr.readyState == 4 && xhr.status == 200){
						leselect = xhr.responseText;
						// On se sert de innerHTML pour rajouter les options a la liste
						document.getElementById('liste').innerHTML = leselect;
					}
				}
 
				// Ici on va voir comment faire du post
				xhr.open("POST","../Controleur/ajaxCapteur.php",false);
				// ne pas oublier ça pour le post
				xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				// ne pas oublier de poster les arguments
				// ici, l'id de l'auteur
				sel = document.getElementById('capteur');
				type_capteur = sel.options[sel.selectedIndex].value;				
				xhr.send("type_capteur="+type_capteur);
			}
			
function add_capteur()
{
popup = window.open('', 'popup', 'height=220, width=450');
popup.document.write('<form action="../Controleur/addCapteur.php" method="post" onsubmit=self.close();>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Type: </label><select  id="type" name="type" ><option value=\'1\'>Capteur plan vitre</option><option value=\'2\'>Capteur tubes sous vide</option></select></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Modele: </label><input type="text" id="modele" name="modele" /></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Facteur de conversion n0: </label><input type="text" id="n0" name="n0" /></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Facteur de perte a1: </label><input type="text" id="a1" name="a1" /></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Facteur de perte a2: </label><input type="text" id="a2" name="a2" /></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Surface unitaire: </label><input type="text" id="surface" name="surface" /></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Facteur d\'angle longitudinal K1: </label><input type="text" id="K1" name="K1" /></div>');
popup.document.write('<div><label style="display:inline-block;width: 250px;text-align: right;">Facteur d\'angle transversal K2: </label><input type="text" id="K2" name="K2" /></div>');
popup.document.write('<div style="margin-left:76%"><input style="text-align:right;display:inline-block;" type="submit" value="Ajouter" onclick="opener.location.reload();" /></div>');
popup.document.write('</form>');
}
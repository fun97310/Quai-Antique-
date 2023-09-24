//fonction pour trouver jours d'une date 
function getJourSemaine(date) {
  const joursSemaine = ['dimanche','lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
  var date = new Date(date);
  const jours = date.getDay(); 
  return joursSemaine[jours];
}

//choix horaire ici soutraire une heure a l'heure de fermeture
function calculheure(heure){
  var currentTime = new Date();
  currentTime.setHours(parseInt(heure.split(":")[0]), parseInt(heure.split(":")[1]));
  currentTime.setHours(currentTime.getHours() - 1);
  var hour = currentTime.getHours();
  var minute = currentTime.getMinutes();
  hour = ("0" + hour).slice(-2);
  minute = ("0" + minute).slice(-2);
  var newTime = hour + ":" + minute;
  return newTime; 
}

//crée des array a intervalle de 15minute
function arraychoix(heureOuverture, heureFermeture) {
  heureFermeture = calculheure(heureFermeture);
  var choix = [];
  var houverture = new Date();
  var hfermeture = new Date();
  houverture.setHours(heureOuverture.split(':')[0], heureOuverture.split(':')[1], 0);
  hfermeture.setHours(heureFermeture.split(':')[0], heureFermeture.split(':')[1], 0);
  while (houverture < hfermeture) {
    var timeString = ('0' + houverture.getHours()).slice(-2) + ':' + ('0' + houverture.getMinutes()).slice(-2);
    choix.push(timeString);
    houverture.setMinutes(houverture.getMinutes() + 15);
  }
  return choix;
}
//il faut determiner comportement is isclose true
function displaymybutton(heure_ouverture_matin, heure_fermeture_matin, heure_ouverture_soir, heure_fermeture_soir, iscloseM, iscloseS) {
  let array;
  let choixmatin = arraychoix(heure_ouverture_matin, heure_fermeture_matin);
  let choixsoir = arraychoix(heure_ouverture_soir, heure_fermeture_soir);
  if (iscloseM == false && iscloseS == false) {
    array = choixmatin.concat(choixsoir);
  } else {
    if(iscloseM == false){
      array = choixmatin;
    }else{
      array = choixsoir;
    }
  }
  let buttoncontainer = document.getElementById('listedebutton');
  
  array.forEach(function(element) {
    var button = document.createElement('button');
    button.textContent = element;
    button.classList.add('buttonform');
    button.value = element;
    
    buttoncontainer.appendChild(button); // Ajout du bouton au conteneur
  });
}

function fetcheddata(FormDate) {
  return new Promise((resolve, reject) => {
    fetch('/disponibilite')
      .then(function (response) {
        if (response.ok) {
          return response.json();
        } else {
          throw new Error('Erreur de requête. Statut : ' + response.status);
        }
      })
      .then(function (data) {
        console.log(data);
        let jForm = getJourSemaine(FormDate);
        let dataday = data.jours.find(function (element) {
          return element.jour.toLowerCase() === jForm.toLowerCase();
        });
        resolve(dataday);
        console.log(dataday);
        //on appel la function displaymybuttons qui va crée les buttons et les afficher selon l'heure d'ouverture et de fermeture du jours choisis
        displaymybutton(dataday.heure_ouverture_matin, dataday.heure_fermeture_matin, dataday.heure_ouverture_soir, dataday.heure_fermeture_soir, dataday.hmatin.isclose, dataday.hsoir.isclose)
        //on recupére les boutons afficher 
        let buttons = document.getElementsByClassName('buttonform');
        //on le transforme en array afin de pouvoir iterer dessus
        buttons = Array.from(buttons);
        //on recupere le champs heure original du formulaire
        let champheure = document.getElementById('reservation_heure');
        //au click d'un des boutons afficher sa valeur sera celle indiquer dans le champ heure
        buttons.forEach(element => {
          element.addEventListener('click', event => {
            //premierement on s'assure que tt les btn son vierge
            buttons.forEach(element =>{
              element.classList.remove('clickedbtn');
            })
            event.preventDefault();
            champheure.value = element.value; // Attribuer la valeur du bouton à champheure.value
            //on ajuste le style au bouton activer(val introduite)
            element.classList.add('clickedbtn');
          });
        });
      })
      .catch(function (error) {
        reject(error);
      });
  });
}


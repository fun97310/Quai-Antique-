//champs formulaire allergie
var radioOui = document.getElementById('reservation_haveallergie_0');
var radioNon = document.getElementById('reservation_haveallergie_1');
var allergie = [
                    'reservation_allergie',
                    'description-allergie-field-label'
               ];

allergie.forEach(function(element){
    console.log(document.getElementById(element));
    document.getElementById(element).classList.add('cacher');
});

radioOui.addEventListener('change', function() {
  if (radioOui.checked) {
    allergie.forEach(function(element){
        document.getElementById(element).classList.remove('cacher');
    })
  }
});

radioNon.addEventListener('change', function() {
  if (radioNon.checked) {
    allergie.forEach(function(element){
        document.getElementById(element).classList.add('cacher');
    })
  }
});
let champheure = document.getElementById('reservation_heure');
//champheure.classList.add('');
//choix horaire
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
//fonction pour trouver jours de la semaine correspondant a la date
function getJourSemaine(date) {
  const joursSemaine = ['dimanche','lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
  var date = new Date(date);
  const jours = date.getDay(); 
  return joursSemaine[jours];
}
function getjoursemaineform(formdate, dateid, datedate){
  if(formdate == dateid){
    let jour = getJourSemaine(datedate);
    return jour;
  }
  
}



function displaymybutton(array) {
  let buttoncontainer = document.getElementById('listedebutton');
  
  array.forEach(function(element) {
    var button = document.createElement('button');
    button.textContent = element;
    button.classList.add('buttonform');
    button.value = element;
    
    buttoncontainer.appendChild(button); // Ajout du bouton au conteneur
  });
}

function formatDate(date) {
  const d = new Date(date);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}
//mes element formulaire
const selectDate = document.getElementById('reservation_date');
let FormDate = selectDate.value;
selectDate.addEventListener('input',function(){
  FormDate = selectDate.value;
})
console.log(FormDate)
let capacitemaxmatin;
let capacitemaxsoir;
//fetch request

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
    const reservations = data.Reservations;
    const jours = data.jours;
    const champheure = document.getElementById('reservation_heure');
    const dateinput = document.getElementById('reservation_date');
    //transformer valeur du champs date en jour de la semaine
    dateinput.addEventListener('change',function(event){
      // Sélectionner tous les boutons avec la classe "buttonform"
      const buttons = document.querySelectorAll('.buttonform');

      // Parcourir les boutons et les supprimer
      buttons.forEach(function(button) {
        button.remove();
      });
      let datevalue = event.target.value;
      datevalue = getJourSemaine(datevalue);
      let capaciteMax;
      jours.forEach(function(elmt){
          if(elmt.jour == datevalue){
            //crée choix heure 
            let heureouverturematin = elmt.heure_ouverture_matin;
            let heureouverturesoir = elmt.heure_ouverture_soir;
            let heurefermeturematin = elmt.heure_fermeture_matin;
            let heurefermeturesoir = elmt.heure_fermeture_soir;      
            let matin = arraychoix(heureouverturematin, heurefermeturematin);
            let soir = arraychoix(heureouverturesoir, heurefermeturesoir);

            let heure = [];
            if(elmt.hmatin.isclose == false || elmt.hmatin.isclose == null){
              let heurematin = matin;
              heure.push(heurematin);
            } else{
              let heurematin = [];
              heure.push(heurematin);
            }
              
            if(elmt.hsoir.isclose == false || elmt.hmatin.isclose == null){
              let heuresoir = soir;
              heure.push(heuresoir);
            }else{
              let heuresoir = [];
              heure.push(heuresoir);
            }
            displaymybutton(heure[0]) ;
            displaymybutton(heure[1]) ;

            const buttons = document.querySelectorAll('.buttonform');
            buttons.forEach(function(elmt){
              elmt.addEventListener('click', function(){
                champheure.value = elmt.textContent;
              })
            })
              
          
      
            
            //recuperer capacitemax
            capaciteMax = elmt.capacite;
            console.log(capaciteMax)
          }
      })
      reservations.forEach(function(elmt){
        console.log(elmt.date.date);
        console.log( event.target.value);
        let formatedDate = formatDate(elmt.date.date);
        if(formatedDate == event.target.value){
          capaciteMax = capaciteMax - elmt.nmbr_couvert;
        }
      })
      console.log('capacite max final'+capaciteMax);
      let info = document.getElementById('infocapacite');
      info.innerHTML = '<p>il nous reste '+ capaciteMax + 'place</p>';
    })
  })
  .catch(function (error) {
    console.error(error);
  });





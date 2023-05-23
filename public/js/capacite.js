//comment trouver la capacité max 

//une date par defaut est dans le formulaire et une heure par defaut (a faire sa)
//selon date et heure par defaut 
    //si heure par defaut = matin 
        //capacitémax = capacité matin 
        //si date a des reservation
            //reservation.heure == matin
                //capacitemax = capacité - nmbrcouvert(par reservation)
    //si heure par dft = soir
        //capacitemax = capacitésoir
        //si date a des reservation
            //et que ces reservation.heure = soir
            //capacitémax = capacitémax - nmbr(couvertpar resa)

// on as besoin de : valeur champ heure et date
// liste des horaire lié au matin et ceux au soir
//la capacité matinale de l'objet
// la capacité de la soirée de l'objet
    
    



//fonction pour trouver jours de la semaine correspondant a la date
function getJourSemaine(date) {
    const joursSemaine = ['Dimanche','Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    var date = new Date(date);
    const jours = date.getDay(); 
    return joursSemaine[jours];
}
//on passe une date en param et elle nous retourne le jours correspondant

//fonction pour soustraire -1h a une heure donner
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
//prend un horaire en parametre et lui enleve une heure

//prend en parametre houverture et heurefermeture 
//crée des array a intervalle de 15minute
function arraychoix(heureOuverture, heureFermeture) {
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
    console.log(choix);
    return choix;
}
function setval(idbtn, champheure){
    const buttons = document.querySelectorAll(idbtn);
    buttons.forEach(function(btn){
        btn.addEventListener('click', function(){
            const value = btn.value;
            champheure.value = value;
        })
    })
}

//on recherche la capacité max par apport a l'heure si elle appartient au matin on recupere capacité matin si soir bah voila
function capacitemax(heurevalue, choix, capacitematin, capacitesoir){
    let capaciteMax = 0;
    choix.forEach(function(element){
      if(heurevalue == element){
         capaciteMax = capacitematin;
      }else{
         capaciteMax = capacitesoir;
      }
    })
    console.log('capacitemax return'+ capaciteMax);
    return capaciteMax;
}

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
      

    })
    .catch(function (error) {
      console.error(error);
    });

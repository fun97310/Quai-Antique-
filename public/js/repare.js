document.addEventListener('DOMContentLoaded', function() {
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = String(currentDate.getMonth() + 1).padStart(2, '0');
    var day = String(currentDate.getDate()).padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
  
    var dateInput = document.getElementById('reservation_date');
    dateInput.value = formattedDate;
    let FormDate = formattedDate;
  
    var champcouvert = document.getElementById('reservation_nmbr_couvert');
  
    // Fonction pour appeler CapaciteMax et mettre à jour les informations
    function updateCapacity() {
      fetcheddata(FormDate)
        .then(function(datadayValue) {
          CapaciteMax(datadayValue, dateInput.value, champcouvert);
          console.log(datadayValue);
        })
        .catch(function(error) {
          console.error(error);
        });
    }
  
    // Appeler updateCapacity au chargement de la page
    updateCapacity();
  
    // Écouteur d'événement sur le champ de date
    dateInput.addEventListener('input', function() {
      //on supprime les boutons horaire deja présent 
      let buttons = Array.from(document.getElementsByClassName('buttonform'));
      buttons.forEach(button => {
        button.remove(); // Supprimer chaque bouton du DOM
      });  
      FormDate = dateInput.value;
      updateCapacity();
      console.log(FormDate);
    });
  
    // Écouteur d'événement sur le champ de couvert
    champcouvert.addEventListener('blur', function() {
      //on supprime les boutons horaire deja présent 
      let buttons = Array.from(document.getElementsByClassName('buttonform'));
      buttons.forEach(button => {
        button.remove(); // Supprimer chaque bouton du DOM
      });  
      updateCapacity();
    });
});
  









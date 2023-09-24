function CapaciteMax(dataday, Dateval, champcouvert) {
  // Récupération de la capacité maximale du jour indiqué dans le formulaire
  let Cmax = dataday.capacite;
  
  // Récupération des réservations depuis le cache ou via une requête réseau
  const reservationsPromise = fetch('/disponibilite')
    .then(response => {
      if (!response.ok) {
        throw new Error('Erreur de requête. Statut : ' + response.status);
      }
      return response.json();
    });

  reservationsPromise.then(data => {
    // Si des réservations existent
    if (data.Reservations.length > 0) {
      // Création d'un objet de réservation pour un accès plus efficace
      const reservationsByDate = {};
      data.Reservations.forEach(element => {
        const daterev = element.date.date.split(' ')[0];
        if (!reservationsByDate[daterev]) {
          reservationsByDate[daterev] = 0;
        }
        reservationsByDate[daterev] += element.nmbr_couvert;
      });

      // Si la date du formulaire correspond à une réservation, ajustez la capacité maximale
      if (reservationsByDate[Dateval]) {
        Cmax = Math.max(0, Cmax - reservationsByDate[Dateval]);
      }
    }

    // Ajuster la capacité en fonction de champcouvert.value
    const nmbrconvive = parseInt(champcouvert.value, 10); // Convertir en nombre entier
    if (!isNaN(nmbrconvive) && nmbrconvive > 0) {
      Cmax = Math.max(0, Cmax - nmbrconvive);
    }

    // Mise à jour du DOM avec la valeur de Cmax
    const capacityElement = document.getElementById('infocapacite');
    if (Cmax < 0) {
      capacityElement.textContent = 'Nous n\'avons plus de place à cette date';
    } else {
      capacityElement.textContent = 'Il nous reste ' + Cmax + ' places';
    }
  }).catch(error => {
    console.error(error);
  });
}


/*function CapaciteMax(dataday, Dateval, champcouvert) {
  //on recup capacite max du jour indiqué dans le formulaire
  let Cmax = dataday.capacite;
  console.log('capacite max' + Cmax);
  fetch('/disponibilite')
    .then(function (response) {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Erreur de requête. Statut : ' + response.status);
      }
    })
    .then(function (data) {
      //si il y a des reservations
      if (data.Reservations.length > 0) {
        //on analyse chaque élément
        data.Reservations.forEach(element => {
          let daterev = element.date.date.split(' ')[0];
          //si la date de la reservation correspond a la date du formulaire
          console.log(daterev + ' et ' + Dateval);
          //on enleve son nombre de convive à la capacité max disponible
          if (daterev == Dateval) {
            Cmax = Math.max(0, Cmax - element.nmbr_couvert);
          }
        });
      }
      let nmbrconvive = champcouvert.value;
      if (champcouvert.value > 0) {
        Cmax = Math.max(0, Cmax - nmbrconvive);
        nmbrconvive = 0;
      }
      // Mise à jour du DOM avec la valeur de Cmax
      const capacityElement = document.getElementById('infocapacite');
      if (Cmax < 0) {
        capacityElement.textContent = 'Nous n\'avons plus de place à cette date';
      } else {
        capacityElement.textContent = 'Il nous reste ' + Cmax + ' places';
      }
    })
    .catch(function (error) {
      console.error(error);
    });
}*/

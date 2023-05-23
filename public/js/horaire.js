function renderHoraires(jours) {
    const horairesList = document.getElementById('horaires-list');
    
    jours.forEach(function(jour) {
      const listItem = document.createElement('li');
      const divJour = document.createElement('div');
      divJour.classList.add('jour');
      divJour.textContent = jour.jour;
      
      const divHeure = document.createElement('div');
      divHeure.classList.add('heure');
      
      if (jour.hmatin && jour.hmatin.isclose == 1) {
        const divHMatin = document.createElement('div');
        divHMatin.classList.add('h-matin');
        divHMatin.textContent = 'fermer';
        divHeure.appendChild(divHMatin);
      } else if (jour.hmatin && jour.hmatin.houverture && jour.hmatin.hfermeture) {
        const divHMatin = document.createElement('div');
        divHMatin.classList.add('h-matin');
        const heureOuvertureMatin = jour.hmatin.houverture;
        const heureFermetureMatin = jour.hmatin.hfermeture;
        divHMatin.textContent = heureOuvertureMatin.replace(':', 'h') + ' | ' + heureFermetureMatin.replace(':', 'h');
        divHeure.appendChild(divHMatin);
      }
      
      if (jour.hsoir && jour.hsoir.isclose == 1) {
        const divHSoir = document.createElement('div');
        divHSoir.classList.add('h-soir');
        divHSoir.textContent = 'fermer';
        divHeure.appendChild(divHSoir);
      } else if (jour.hsoir && jour.hsoir.houverture && jour.hsoir.hfermeture) {
        const divHSoir = document.createElement('div');
        divHSoir.classList.add('h-soir');
        const heureOuvertureSoir = jour.hsoir.houverture;
        const heureFermetureSoir = jour.hsoir.hfermeture;
        divHSoir.textContent = heureOuvertureSoir.replace(':', 'h') + ' | ' + heureFermetureSoir.replace(':', 'h');
        divHeure.appendChild(divHSoir);
      }
      
      listItem.appendChild(divJour);
      listItem.appendChild(divHeure);
      
      horairesList.appendChild(listItem);
    });
  }
  
// Fonction pour cacher/afficher des éléments
function toggleElements(elements, action) {
  for (let i = 0; i < elements.length; i++) {
    elements[i].classList[action]('cacher');
  }
}

// Réutilisation d'éléments HTML
const button1 = document.getElementById('navbtn');
const button2 = document.getElementById('closebtn');
const elmtToShow = document.getElementById('nav');
const elmtToHide = [document.getElementById('logo'), document.querySelector('header img')];

// Ajout d'événements aux boutons
button1.addEventListener('click', () => {
  toggleElements(elmtToHide, 'add');
  elmtToShow.style.display = 'flex';
  button2.style.display = 'flex';
});

button2.addEventListener('click', () => {
  toggleElements(elmtToHide, 'remove');
  elmtToShow.style.display = 'none';
  button2.style.display = 'none';
});

// Fonction pour définir un élément comme actif
function setActive(myId) {
  const element = document.getElementById(myId);
  if (element) {
    element.classList.add("active");
  }
}

// Gestion du formulaire d'allergies
const radioOui = document.getElementById('reservation_haveallergie_0');
const radioNon = document.getElementById('reservation_haveallergie_1');
const allergieElements = [
  'reservation_allergie',
  'description-allergie-field-label'
];

function handleAllergieChange(allergieState) {
  allergieElements.forEach(elementId => {
    const element = document.getElementById(elementId);
    if (element) {
      element.classList.toggle('cacher', !allergieState);
    }
  });
}

radioOui.addEventListener('change', () => {
  handleAllergieChange(true);
});

radioNon.addEventListener('change', () => {
  handleAllergieChange(false);
});

// Définir la date du jour par défaut dans le champ de date
document.addEventListener('DOMContentLoaded', () => {
  const currentDate = new Date();
  const formattedDate = currentDate.toISOString().split('T')[0];
  const dateInput = document.getElementById('reservation_date');
  dateInput.value = formattedDate;
});

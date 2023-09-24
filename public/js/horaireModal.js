//select btn d'action modal (btn)
const buttons = document.querySelectorAll('[id^="btn"]');

//on selectionne les elements composant la modal
var modal = document.getElementById("myModal");
var modalContent = document.getElementById("modalContent");
var closeButton = modal.querySelector(".close");
//on s'assure que la modal n'est pas afficher
modal.classList.add('hidden');
// Ajoutez des gestionnaires d'événements aux boutons
let id;
let functionName;
let form;
buttons.forEach(element => {
    element.addEventListener("click", function(){
        id = element.dataset.id;
        functionName = element.dataset.function;
        formName = element.dataset.form;
        console.log('id '+ id + 'function ' + functionName + 'form Name '+ formName)
        loadEditForm(id, functionName);
        modal.classList.remove("hidden");  
    })
});
// Ajoutez un gestionnaire d'événements au clic sur le bouton "Enregistrer" 
modalContent.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'editsubmit') {
        // Récupérez les données du formulaire
        let form = document.getElementsByName(formName);
        const formData = new FormData(form[0]);
        // Utilisez AJAX ou Fetch API pour envoyer les données au serveur Symfony
        updateform(id, formData, functionName)
    }
});

// fermer la modal
closeButton.addEventListener('click', function(){
    modal.classList.add('hidden');
})

   
// Fonction pour charger le contenu dans la modale
function loadEditForm(entityId, functionName) {
    fetch("/admin/" + entityId + "/"+functionName)
        .then(response => response.text())
        .then(data => {
            // Remplacez le contenu de votre modale avec le formulaire
            document.getElementById("modalContent").innerHTML = data;
            document.getElementById("myModal").style.display = "flex";
        })
        .catch(error => {
            console.error(error);
        });
}
//fonction pour sauvegarder les informations dans la bdd
function updateform(entityId, formData, functionName){
    fetch("/admin/" + entityId + "/"+functionName, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Gérez la réponse en cas de succès
        } else {
            // Gérez la réponse en cas d'erreur
        }
    })
    .catch(error => {
        // Gérez les erreurs réseau
    });
}














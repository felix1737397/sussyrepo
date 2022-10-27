// il faut laisser le temps à WordPress de charger la page et plus particulièrement le shortcode qui affiche le formulaire
document.addEventListener("DOMContentLoaded", function(event) {
    let formulaireContact = document.getElementById('envoyer-courriel');

    if (formulaireContact != null) {
        formulaireContact.onsubmit = validerFormulaire;
    }
});


function validerFormulaire(event) {
    event.preventDefault();
    let valide = true;


    let sujet = document.getElementById('message_visiteur');

    if (sujet == null) {
        valide = false;
    }
    else {
        if (sujet.value == '') {
            valide = false;
        }
    }

    if (valide) {
        gererRecaptcha(event);
    }
}

function gererRecaptcha(event) {
    // note : ceci n'est pas du jQuery et fonctionnera avec en JavaScript pur
    grecaptcha.ready(function() {
        grecaptcha.execute('6LdGTCodAAAAAMIUIzeMx-_DWP-j1YlukhsR5zJY', {action: 'soumissioncontact'}).then(function(token) {
            // ajout de la réponse de Google reCAPTCHA dans le formulaire
            let input = document.createElement('input');
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "g-recaptcha-response");
            input.setAttribute("value", token);
            formulaireContact.appendChild(input);

            // soumission du formulaire
            formulaireContact.submit();   // ne cause pas de boucle sans fin puisqu'ici, aucun événement submit n'est déclenché (https://developer.mozilla.org/fr/docs/Web/API/HTMLFormElement/submit)
        });
    });
}
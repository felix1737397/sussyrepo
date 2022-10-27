document.addEventListener("DOMContentLoaded", function(event) {
    let boutonAppelAjax = document.getElementById('boutonAppelAjax');
    if (boutonAppelAjax != null) {
        boutonAppelAjax.onchange = effectuerAppelAjax;
    }
});
const url = variablesPHP.urlThemeEnfant + '/initialiser-ville.php';


function effectuerAppelAjax(event) {
    event.preventDefault();
    const divReponse = document.getElementById('reponseAjax');
    let province = document.getElementById('boutonAppelAjax');

    if (divReponse != null) {
        fetch(url + `?id=${province.value}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Problème - code d'état HTTP  : " + response.status);
                }
                return response.json();
            }).then((body) => {
            divReponse.innerHTML = body.villes;
            console.log(body.villes);
        }).catch((e) => {
            console.log(e.toString());
            divReponse.innerHTML = "Un problème nous empêche de compléter le traitement demandé.";
        });
    }
    else {
        console.log("Erreur : la division reponseAjax n'existe pas.");
    }
}
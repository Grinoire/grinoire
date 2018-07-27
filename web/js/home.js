/**
 * Permet de cacher le message de la validation de creation de compte
 * après la redirection vers la vue home
 * si tout c'est bien passé
 */
window.addEventListener("load", function () {
    let msgValid = document.getElementById('message-info');
    if (msgValid) {
        setTimeout(function () {
            msgValid.style.visibility = "hidden";
        }, 4000);
    }
});
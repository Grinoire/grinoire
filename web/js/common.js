//element est le span ou doit etre afficher les messages
//message est le message a afficher
function spanReturnMessageCommon(element, message) {
    element.innerHTML = message;//methode qui d'interpreter les balises html (exemple les messages de validation php en vert)
    //si element.append() n'interprete pas les balises html comme <p></p>
    //pas besoin non plus de vider la element, puisque message ecrase message
    element.style.color = "red";
    setTimeout(function () {
        element.innerHTML = "";
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('section').classList.add('is-ready');
    let headerImage = document.getElementById('header-img');
    headerImage.classList.remove('header-img-none');
    headerImage.classList.add('header-img-display');
}, false);
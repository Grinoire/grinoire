//element est le span ou doit etre afficher les messages
//message est le message a afficher
function spanReturnMessageCommon(element, message) {
    element.innerHTML = message;//methode pour interpreter les balise html
    //si element.append() n'interprete pas les balises html comme <p></p>
    //pas besoin non plus de vider la element, puisque message ecrase message
    element.style.color = "red";
    setTimeout(function () {
        element.innerHTML = "";
    }, 3000);
}
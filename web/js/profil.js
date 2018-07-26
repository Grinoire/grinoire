let profilForm = document.getElementById('profil-form');
let profilSpanMsg = document.getElementById('profilSpanMessage');
let profilPseudoForm = document.getElementById('profil-input-login');
let profilEmailForm = document.getElementById('profil-input-email');
let profilPasswordForm = document.getElementById('profil-input-password');
let profilNomForm = document.getElementById('profil-input-nom');
let profilPrenomForm = document.getElementById('profil-input-prenom');

function verifProfiAjax(dataForm, callBack) {
    let xhr = new XMLHttpRequest();
    let data = new FormData(dataForm);
    xhr.open("POST", "?c=Home&a=profilAjax");
    xhr.send(data);
    xhr.onreadystatechange = function () {
        if (xhr.status === 200 && xhr.readyState === 4) {
            callBack(xhr.responseText);
        }
    }
};

let profilNomEtPrenomValid = function (event, elt = null) {
    let element;
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    let flag = true;
    if (element.value.length > 25) {
        element.style.color = "red";
        profilSpanMsg.innerHTML = "Votre nom doit contenir au maximum 25 caractères";
        profilSpanMsg.style.color = "red";
        flag = false;
    } else {
        element.style.color = "black";
        flag = true;//sinon les données utilisateurs sont correcte et le flag est à true
    }
    return flag;
};

let profilPseudoValid = function (event, elt = null) {
    let element;
    if (event == null) {//Si on ne fait pas appel à l'évent de base
        element = elt;//on définit l'élément
    }
    else {
        element = event.target;//Sinon on se sert l'élément de base
    }
    let flag = true;//on definit un flag a true
    profilSpanMsg.innerHTML = "";//on le vide
    if (element.value.length > 10 || element.value.length < 2) {//Si l'élément n'est pas entre 2 et 10
        element.style.color = "red";
        profilSpanMsg.innerHTML = "Votre pseudo doit contenir entre 2 et 10 caractères";
        profilSpanMsg.style.color = "red";
        flag = false;//alors on met le flag a false
    } else {
        element.style.color = "black";
        flag = true;//sinon les données utilisateurs sont correcte et le flag est à true
    }
    return flag;//retourne la valeur du flag "false" / "true"
};

let profilEmailValid = function (event, elt = null) {
    let flag = true;
    let element;
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    let regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    profilSpanMsg.innerHTML = "";
    if (!regex.test(element.value) && element.value.length > 100) {
        profilSpanMsg.append('L\'email n\'est pas conforme : exemple@gmail.com');
        profilSpanMsg.style.color = "red";
        element.style.color = "red";
        flag = false;
    } else {
        element.style.color = "green";
        flag = true;
    }
    return flag;
};

let profilPasswordValid = function (event, elt = null) {
    let flag = true;
    let element;
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    profilSpanMsg.innerHTML = "";
    if (element.value.length > 25 || element.value.length < 4) {
        element.style.color = "red";
        profilSpanMsg.append('Votre mot de passe doit contenir entre 4 et 25 caractères');
        profilSpanMsg.style.color = "red";
        flag = false;
    } else if (element.value.length >= 4 && element.value.length <= 10) {
        element.style.color = "orange";
        element.style.border = "2px solid green";
        profilSpanMsg.append('Mot de passe correctement sécurisé');
        profilSpanMsg.style.color = "orange";
        flag = true;
    } else if (element.value.length > 10 && element.value.length <= 25) {
        element.style.color = "green";
        element.style.border = "2px solid green";
        profilSpanMsg.append('Mot de passe très sécurisé');
        profilSpanMsg.style.color = "green";
        flag = true;
    }
    return flag;
};

if (document.getElementById('profil-form')) {
    profilForm.addEventListener('submit', function (event) {
        event.preventDefault();
        if (profilPseudoValid(null, profilPseudoForm) === true && profilEmailValid(null, profilEmailForm) === true && profilPasswordValid(null, profilPasswordForm) === true && profilNomEtPrenomValid(null, profilNomForm) === true && profilNomEtPrenomValid(null, profilPrenomForm) === true) {
            verifProfiAjax(this, (response) => {
                if (response) {
                    spanReturnMessageCommon(profilSpanMsg, response);
                } else {
                    this.submit();
                }
            })
        }
    });
    profilPseudoForm.addEventListener('keyup', profilPseudoValid);
    profilEmailForm.addEventListener('keyup', profilEmailValid);
    profilPasswordForm.addEventListener('keyup', profilPasswordValid);
    profilNomForm.addEventListener('keyup', profilNomEtPrenomValid);
    profilPrenomForm.addEventListener('keyup', profilNomEtPrenomValid);
}

window.addEventListener("load", function () {
    let msgValid = document.getElementById('profilMessageValid');
    if (msgValid) {
        setTimeout(function () {
            msgValid.style.visibility = "hidden";
        }, 4000);
    }
});
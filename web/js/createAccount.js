let formCreateAccount = document.getElementById('create-account-form');
let pseudo = document.getElementById('create-account-pseudo');
let email = document.querySelector('#create-account-email');
let password = document.querySelector('#create-account-password');
let span = document.getElementById('create-account-span');
let confirmPassword = document.getElementById('create-account-confirmer-password');

/**
 *Permet d'afficher un message pour l'utilisateur au blur
 */
let blur = function (event) {
    span.innerHTML = "";
    if (event.target.value === "") {
        event.target.style.border = "1px solid red";
        event.target.style.color = "red";
        if (event.target === document.getElementById('create-account-pseudo')) {
            spanReturnMessage(event.target, 'Veuillez renseigner votre pseudo');
        } else if (event.target === document.querySelector('#create-account-email')) {
            spanReturnMessage(event.target, 'Veuillez renseigner votre email');
        } else if (event.target === document.querySelector('#create-account-password')) {
            spanReturnMessage(event.target, 'Veuiller renseigner votre mot de passe');
        }
    }
};

/**
 *  Au focus du champ, selectionne tous le texte du champ
 */
let focusChamp = function (event) {
    event.target.select();
};

/**
 * Permet de retourner un message dans la span
 * remmet les effets à 0 a partir de 3s
 * Cette methode est utilisé que pour le formulaire create account
 */
function spanReturnMessage(event, message) {
    span.append(message);
    setTimeout(function () {
        span.innerHTML = "";
        event.style.borderColor = "transparent";
        event.style.borderBottom = "1px solid black";
    }, 3000);
}

/////// PSEUDO  ///////
let pseudoValid = function (event, elt = null) {
    let element;
    if (event == null) {//Si on ne fait pas appel à l'évent de base
        element = elt;//on définit l'élément
    }
    else {
        element = event.target;//Sinon on se sert l'élément de base
    }
    let flag = true;//on definit un flag a true
    let span = document.getElementById('create-account-span');//on récupère le span
    span.innerHTML = "";//on le vide
    if (element.value.length > 10 || element.value.length < 2) {//Si l'élément n'est pas entre 2 et 10
        element.style.color = "red";
        element.style.border = "2px solid black";
        span.innerHTML = "Votre pseudo doit contenir entre 2 et 10 caractères";
        span.style.color = "red";
        flag = false;//alors on met le flag a false
    } else {
        element.style.color = "black";
        element.style.border = "2px solid green";
        flag = true;//sinon les données utilisateurs sont correcte et le flag est à true
    }
    return flag;//retourne la valeur du flag "false" / "true"
};
//  PSEUDO END -------------------------

//  ------------------ EMAIL ----------------------------------
let emailValid = function (event, elt = null) {
    let flag = true;
    let element;
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    let regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    span.innerHTML = "";
    if (!regex.test(element.value)) {
        span.append('L\'email n\'est pas conforme : exemple@gmail.com');
        span.style.color = "red";
        element.style.color = "red";
        flag = false;
    } else {
        element.style.color = "green";
        element.style.border = "2px solid green";
        flag = true;
    }
    return flag;
}
///////////////// END EMAIL //////////////
////////////////    PASSWORD    //////////////////
let passwordValid = function (event, elt = null) {
    let flag = true;
    let element;
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    span.innerHTML = "";
    if (element.value.length > 25 || element.value.length < 4) {
        element.style.color = "red";
        span.append('Votre mot de passe doit contenir entre 4 et 25 caractères');
        span.style.color = "red";
        flag = false;
    } else if (element.value.length >= 4 && element.value.length <= 10) {
        element.style.color = "green";
        element.style.border = "2px solid green";
        span.append('Mot de passe correctement sécurisé');
        span.style.color = "green";
        flag = true;
    } else if (element.value.length > 10 && element.value.length <= 25) {
        element.style.color = "green";
        element.style.border = "2px solid green";
        span.append('Mot de passe très sécurisé');
        span.style.color = "green";
        flag = true;
    }
    return flag;
};
let confirmPasswordValid = function (event, elt = null) {
    let flag = true;
    let element;
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    span.innerHTML = "";
    if(element.value == password.value){
        span.append('Confirmation du mot de passe correcte');
        span.style.color = "green";
        flag = true;
    }else{
        span.append('Confirmation du mot de passe incorrect');
        span.style.color = "red";
        flag = false;
    }
    return flag;
};

// Quand j'appelle la fonction sur un évènment, la fonction comprend automatiquement / implicitement QUE 'event' fait réfférence à l'évenement
// si la fonction est appellé hors d'un évènement, il faut lui spécifier "l'élément" sur lequel "rechercher / travailler"

// formCreateAccount.addEventListener('submit', function(event){
function ajaxCreateAccount(dataForm, callBack) {

    let xhr = new XMLHttpRequest();

    let data = new FormData(dataForm);//traite les données du formulaire

    xhr.open("POST", "?c=Home&a=createAccountAjax");
    xhr.send(data);

    xhr.onreadystatechange = function () {
        if (xhr.status === 200 && xhr.readyState === 4) {

            callBack(xhr.responseText);
        }
    }
};
if (document.getElementById('create-account-form')) {
    formCreateAccount.addEventListener('submit', function (event) {

        event.preventDefault();

        if (pseudoValid(null, pseudo) === true && emailValid(null, email) === true && passwordValid(null, password) === true && confirmPasswordValid(null, confirmPassword) === true) {//on doit dire à la fonction sur quel élément rechercher
            ajaxCreateAccount(this, (response) => {
                if (response) {
                    spanReturnMessageCommon(span, response);
                } else {
                    this.submit();
                }
            });
        }
    });
    pseudo.addEventListener('focus', focusChamp);
    pseudo.addEventListener('blur', blur);//event ne sera pas null
    pseudo.addEventListener('keyup', pseudoValid);
    email.addEventListener('focus', focusChamp);
    email.addEventListener('blur', blur);
    email.addEventListener('keyup', emailValid);
    password.addEventListener('blur', blur);
    password.addEventListener('keyup', passwordValid);
    confirmPassword.addEventListener('keyup', confirmPasswordValid);
}
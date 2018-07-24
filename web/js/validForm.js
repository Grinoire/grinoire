let formCreateAccount = document.getElementById('create-account-form');
let pseudo = document.getElementById('create-account-pseudo');
let email = document.querySelector('#create-account-email');
let password = document.querySelector('#create-account-password');
let span = document.getElementById('create-account-span');

formCreateAccount.addEventListener('submit', function (event) {

    event.preventDefault();

    if (pseudoValid(null, pseudo) === true && emailValid(null, email) === true && passwordValid(null, password) === true) {//on doit dire à la fonction sur quel élément rechercher
        ajaxCreateAccount(this, (response) => {
            if (response) {
                span.innerHTML = response;
                span.style.color = "red";
            } else {
                this.submit();
            }
        });
    }


});

let blur = function (event) {
    let span = document.getElementById('create-account-span');
    if (event.target.value === "") {
        // span.innerHTML = "";
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

function spanReturnMessage(event, message) {
        span.append(message);
    setTimeout(function () {
        span.innerHTML = "";
        event.style.borderColor = "transparent";
        event.style.borderBottom = "1px solid black";
    }, 3000);
}

//      PARTIE CHAMP PSEUDO   ----------------------------
let pseudoFocus = function (event) {
    event.target.select();
    //ou this.select();
};

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
let emailFocus = function (event) {
    event.target.select();
};
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
        element.style.color = "orange";
        element.style.border = "2px solid green";
        span.append('Mot de passe correctement sécurisé');
        span.style.color = "orange";
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

// Quand j'appelle la fonction sur un évènment, la fonction comprend automatiquement / implicitement QUE 'event' fait réfférence à l'évenement
// si la fonction est appellé hors d'un évènement, il faut lui spécifier "l'élément" sur lequel "rechercher / travailler"

pseudo.addEventListener('focus', pseudoFocus);
pseudo.addEventListener('blur', blur);//event ne sera pas null
pseudo.addEventListener('keyup', pseudoValid);

email.addEventListener('focus', emailFocus);
email.addEventListener('blur', blur);
email.addEventListener('keyup', emailValid);

password.addEventListener('blur', blur);
password.addEventListener('keyup', passwordValid);


// formCreateAccount.addEventListener('submit', function(event){
function ajaxCreateAccount(dataForm, callBack) {

    let xhr = new XMLHttpRequest();

    let data = new FormData(dataForm);//traite les données du formulaire

    xhr.open("POST", "?c=Home&a=createAccountAjax");
    xhr.send(data);

    xhr.onreadystatechange = function () {
        if (xhr.status === 200 && xhr.readyState === 4) {

            callBack(xhr.responseText);
            // span.innerHTML = xhr.responseText;
            // console.log(xhr.responseText);
        }
    }
};
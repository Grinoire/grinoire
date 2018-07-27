let formLogin = document.getElementById('login-form');
let spanLogin = document.getElementById('login-span');
let loginPassword = document.getElementById('login-form-password');
let pseudoEmail = document.getElementById('login-form-email');

function ajaxLogin(dataForm, loginCallBack) {

    let xhr = new XMLHttpRequest();

    let data = new FormData(dataForm);

    xhr.open("POST", "?c=Home&a=loginAjax");

    xhr.send(data);

    xhr.onreadystatechange = function () {
        if (xhr.status === 200 && xhr.readyState === 4) {
            loginCallBack(xhr.responseText);
        }
    }
};

let validPseudoEmailPasswor = function (event, elt) {
    let element;
    let flag = true;
    spanLogin.innerHTML = "";
    if (event == null) {
        element = elt;
    } else {
        element = event.target;
    }
    if (element.value == "") {
        flag = false;
        spanReturnMessageCommon(spanLogin, 'Veuillez renseigner le formulaire');
    }
    return flag;
};

if (document.getElementById('login-form')) {
    formLogin.addEventListener('submit', function (event) {
        event.preventDefault();
        if (validPseudoEmailPasswor(null, pseudoEmail) === true && validPseudoEmailPasswor(null, loginPassword) === true) {
            ajaxLogin(this, (response) => {
                if (response) {
                    spanReturnMessageCommon(spanLogin, response);
                } else {
                    this.submit();
                }
            });
        }
    });
}


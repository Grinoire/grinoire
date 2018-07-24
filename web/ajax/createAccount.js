let formCreateAccount = document.querySelector('#create-account-form');
let span = document.querySelector('#create-account-span');

formCreateAccount.addEventListener('submit', function(event){
    event.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if(xhr.status === 200 && xhr.readyState === 4){
            span.innerHTML = xhr.responseText;
        }
    }
    let data = new FormData(this);

    xhr.open("POST", "?c=Home&a=createAccount", true);
    xhr.send(data);

});
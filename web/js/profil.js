let profilForm = document.getElementById('profil-form');
let profilSpanMsg = document.getElementById('profilSpanMessage');
if(document.getElementById('profil-form')){
    profilForm.addEventListener('submit', function(event){
        event.preventDefault();
        verifProfiAjax(this, (response) => {
            if(response){
                spanReturnMessageCommon(profilSpanMsg, response);
            }
        })
    });
}
function verifProfiAjax(dataForm, callBack){
    let xhr = new XMLHttpRequest();
    let data = new FormData(dataForm);
    xhr.open("POST", "?c=Home&a=profilAjax");
    xhr.send(data);
    xhr.onreadystatechange = function(){
        if(xhr.status === 200 && xhr.readyState === 4){
            callBack(xhr.responseText);
        }
    }
};
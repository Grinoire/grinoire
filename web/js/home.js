window.addEventListener("load", function () {
    let msgValid = document.getElementById('message-info');
    if (msgValid) {
        setTimeout(function () {
            msgValid.style.visibility = "hidden";
        }, 4000);
    }
});
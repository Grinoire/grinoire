function spanReturnMessageCommon(element, message) {
    element.innerHTML = "";
    element.append(message);
    element.style.color = "red";
    setTimeout(function () {
        element.innerHTML = "";
    }, 3000);
}
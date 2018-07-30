(function() {
    'use strict';

    let containerHand = document.getElementById("playerHand");
    let cardInHand = document.querySelectorAll('#playerHand .card');

    let zonePlayerBook = document.getElementById('playerCenterBoard');

    /** @type {Number} id de la carte actuellement selectionné */
    let idCard = null;

    //Si une carte en main est clique on recupere l'id
    for (let card of cardInHand) {
        card.addEventListener('click' , getId);
    }

    //Si la zone central est clique et qu'une carte a ete selectionne on la deplace
    zonePlayerBook.addEventListener('click', function() {
        if (idCard != null && isCardExist(cardInHand, idCard)) {
            ajax("id=" + idCard, '?c=game&a=moveCard&ajax', renderBoard);
        }
        idCard = null;
    });

    /**
    *   Recupere l'id de l'element clique
    *
    *   @return {void}
    */
    function getId() {
        idCard = this.dataset.id;
        console.log('idCard=' + idCard);
    }



    /**
    *   Affiche les données retourner par ajax sur le plateau
    *
    *   @param  {JSON}   ajaxResponse   Données retourné par ajax
    *   @return {void}
    */
    function renderBoard(ajaxResponse) {
        if (ajaxResponse.move) {
            let playerMana = document.getElementById("playerMana");
            let cardInHand = document.querySelectorAll('#playerHand .card');
            let zonePlayerBook = document.getElementById('playerCenterBoard');

            playerMana.innerHTML = ajaxResponse.heroMana;
            for (let element of cardInHand) {
                if (element.dataset.id == ajaxResponse.move) {
                    let movable = element;
                    element.remove();
                    movable.classList.remove('card');
                    movable.classList.add('blazon');
                    movable.classList.add('hover');
                    movable.classList.add('selected');
                    movable.removeEventListener('mouseover', setHover);
                    movable.removeEventListener('click', getId);
                    movable.style.background = "url('../view/blazon/'" + ajaxResponse.bg + ")";
                    zonePlayerBook.appendChild(movable);
                }
            }
        }
    }

    /**
    *   Execute une requete ajax au format GET
    *
    *   @param  {string}    data       Paramétres de la requete
    *   @param  {string}    target     Url ciblé par la requete
    *   @param  {Function}  callback   Fonction de recuperation des données
    *   @return {mixed}
    */
    function ajax(data, target, callback) {

        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && (xhr.status == 200)) {
                callback(JSON.parse(xhr.responseText));
            }
        };

        xhr.open("GET", target + '&' + data);
        // xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
        xhr.send();

        // return xhr;
    }



    /**
    *   Test si une carte existe dans un container HTML
    *
    *   @param  {HTMLElement}  container Container ou recherche la carte
    *   @param  {Number}       id        Id de la carte a recherche
    *   @return {Boolean}
    */
    function isCardExist(listElement, id) {
        let valid = false;

        for (let element of listElement) {
            if (element.dataset.id == id) {
                valid = true;
            }
        }
        return valid;
    }



    function setHover() {
        for (let element of container.children) {
            if (element.classList.contains('hover')) {
                element.classList.remove('hover');
            }
        }
        this.classList.add('hover');
    }
}());

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
                    movable.removeEventListener('mouseover', setHover);
                    movable.removeEventListener('click', getId);
                    movable.classList.remove('card');
                    movable.classList.add('blazon');
                    movable.setAttribute('style' , "background-image:url('img/blazon/" + ajaxResponse.bg + "')");
                    zonePlayerBook.appendChild(movable);
                    movable.classList.remove('hover');
                    movable.classList.remove('selected');
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
    *   Test si une carte existe dans une liste d'element
    *
    *   @param  {HTMLCollection}  listElement   Container ou recherche la carte
    *   @param  {Number}          id            Id de la carte a recherche
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
     * --------------------------------------------------
     *     ANIMATE CARD.JS
     * ------------------------------------------------------
     */




     let container = document.querySelector('#playerHand');

     // Selectionne le noeud dont les mutations seront observées
     let targetNode = container;

     // Options de l'observateur (quelles sont les mutations à observer)
     let config = {childList: true};


     // Fonction callback à éxécuter quand une mutation ou un window.resize est observée
     let callback = function(mutationsList) {
         let total = 0;
         if (container.children.length > 0) {
             let elementWidth = container.children[0].offsetWidth;
             let childNbr = container.children.length;
             let itemLength = (childNbr * elementWidth) + elementWidth;
             let margin = 0;

             //on gere pas les carte en responsive donc j'ai desactive la gestion des position des carte
             // if (itemLength > container.offsetWidth) {
             // 	margin = container.offsetWidth / childNbr ;
             // } else {
             // 	margin = itemLength / childNbr;
             // }

             for (let i = 0 ; i < childNbr ; i++) {
                 // container.children[i].style.left = (margin * i) + "px";
                 container.children[i].addEventListener('mouseover', setHover);
                 // container.children[i].addEventListener('click', setSelected); // TODO: sans AJAX php doit se charger de mettre la classe selected
             }
         }
     };



     // Créé une instance de l'observateur lié à la fonction de callback
     let observer = new MutationObserver(callback);
     // Commence à observer le noeud cible pour les mutations précédemment configurées
     observer.observe(targetNode, config);

     //Lance la fonction callback au resize de la fenetre et au chargement de la page
     // window.addEventListener('resize', callback, true);
     window.addEventListener('load', callback, true);





     /////////////////////////////////////////////////////
     /////////////////////////////////////////////////////


     function setHover() {
         for (let element of container.children) {
             if (element.classList.contains('hover')) {
                 element.classList.remove('hover');
             }
         }
         this.classList.add('hover');
     }

     function setSelected() {
         for (let element of container.children) {
             if (element.classList.contains('selected')) {
                 element.classList.remove('selected');
             }
         }
         this.classList.add('selected');
     }


     /////////////////////////////////////////////////////
     /////////////////////////////////////////////////////


     //On bloque le clic droit
     //Retire la class 'hover' de la liste de carte
     //Sinon retire la class 'selected' de la liste de carte
     function noRightClick(e) {
         e.preventDefault();
         let remove = 0;

         for (let element of container.children) {
             if (element.classList.contains('hover')) {
                 element.classList.remove('hover');
                 remove ++;
                 break;
             }
         }
         if (remove === 0) {
             for (let element of container.children) {
             if (element.classList.contains('selected')) {
                 element.classList.remove('selected');
                 break;
             }
             }
         }
     }

     // document.addEventListener('contextmenu', noRightClick);
}());

(function () {
	'use strict';

	if (document.getElementsByClassName('selectCard').length > 0) {                        //si on est sur la vue de selection des cartes
		let cards = document.querySelectorAll('.selectCard');                   //on reupere toutes les carte en tableau HTMLElement
        let randInput = document.querySelector('.deck-select-card-rand');       //On recupere le boutton 'selection aleatoire'

        randInput.addEventListener('click', function(e){
            e.preventDefault();
            randomizeSelection(cards);                                              //on selectionne 20 cartes aleatoirement
        })
	}

}());


/**
*   Selectionne aleatoirement 20 cartes
*   @param  {HTMLCollection}  list  list of cards input
*   @return {void}
*/
function randomizeSelection(list) {
    let rand;
    let randomized = [];

    while (randomized.length < 20) {                       //tant qu'on a pas 20 valeurs
    rand = getRandomIntInclusive(0 , list.length - 1);     //on genere un chiffre aleatoire
    if (randomized.indexOf(rand) == -1) {                  //si il n'est pas deja existant
    randomized.push(rand);                                 //on le stock
}
}

for (let index of randomized) {
    list[index].checked = true;
}
}


/**
*   Retourne un nombre compris entre les valeurs en paramétres, min & max inclus
*   @param  {Number}  min   Valeur minimum incluse
*   @param  {Number}  max   Valeur maximum exclu
*   @return {Number}        Nombre aleatoire
*/
function getRandomIntInclusive(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

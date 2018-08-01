(function () {
	'use strict';

	let containerHand = document.getElementById("playerHand");
	let cardInHand = document.querySelectorAll('#playerHand .card');

	let zonePlayerBook = document.getElementById('playerCenterBoard');
	let zoneOpponentBook = document.getElementById('opponentCenterBoard');

	/** @type {Number} id de la carte actuellement selectionné */
	let idCard = null;
	/** @type {Number} id du blazon actuellement selectionné */
	let idBlazon = null;

	/** @type {Array} Tableau contenant les carte de l'ennemi a la derniere actualisation */
	let lastAjaxValue = null;

	let cardInMiddle = document.querySelectorAll('#playerCenterBoard .blazon');
	let cardOpponentInMiddle = document.querySelectorAll('#opponentCenterBoard .blazon');
	let opponentHero = document.querySelector('#opponentHero .hero');
	let opponentHand = document.getElementById('opponentHand');
	let playerHero = document.querySelector('#playerHero .hero');

	let stopTurn = document.getElementById("stopTurn");

	/**
	 * --------------------------------------------------
	 *     GESTION MOVE ACTION
	 * ------------------------------------------------------
	 */

	//Si une carte en main est clique on recupere l'id
	for (let card of cardInHand) {
		card.addEventListener('click', getCardId);
	}

	//Si la zone central est clique et qu'une carte a ete selectionne on la deplace
	zonePlayerBook.addEventListener('click', function () {
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
	function getCardId() {
		idCard = this.dataset.id;
		console.log('idCard=' + idCard);
	}


	/**
	 * --------------------------------------------------
	 *     GESTION ATTACK ACTION
	 * ------------------------------------------------------
	 */


	//Si une carte en main est clique on recupere l'id
	for (let card of cardInMiddle) {
		card.addEventListener('click', getBlazonId);
	}

	//Si une carte en main est clique on recupere l'id
	for (let card of cardOpponentInMiddle) {
		card.addEventListener('click', attack);
	}

	// TODO: add event quand le hero ennemi est charge
	opponentHero.addEventListener('click', function () {
		if (idBlazon != null && isCardExist(cardInMiddle, idBlazon)) {
			ajax(
				"id=" + idBlazon + '&target=' + this.dataset.id,
				'?c=game&a=attack&ajax&hero',
				renderBoard
			);
		}
		idBlazon = null;
	});


	function attack() {
		if (idBlazon != null && isCardExist(cardInMiddle, idBlazon)) {
			let param = "id=" + idBlazon + '&target=' + this.dataset.id;
			let target = '?c=game&a=attack&ajax';
			ajax(param, target, renderBoard);
		}
		idBlazon = null;
	}


	/**
	 *   Recupere l'id de l'element clique
	 *
	 *   @return {void}
	 */
	function getBlazonId() {
		idBlazon = this.dataset.id;
		console.log('idBlazon=' + idBlazon);
	}


	/**
	 * --------------------------------------------------
	 *     GESTION FIN DE TOUR
	 * ------------------------------------------------------
	 */


	stopTurn.addEventListener('click', function () {
		ajax(null, '?c=game&a=nextTurn&ajax', renderBoard);
		this.click;
	});



	/**
	 * --------------------------------------------------
	 *     MAJ DU PLATEAU OPPOSANT
	 * ------------------------------------------------------
	 */


	setInterval(function () {
		ajax(null, '?c=game&a=renderOpponent', renderBoard);
	}, 1000)




	/**
	 * --------------------------------------------------
	 *     RENDU PLATEAU VIA AJAX
	 * ------------------------------------------------------
	 */
	/**
	 *   Affiche les données retourner par ajax sur le plateau
	 *
	 *   @param  {JSON}   ajaxResponse   Données retourné par ajax
	 *   @return {void}
	 */
	function renderBoard(ajaxResponse) {

		//Action deplacement carte
		if (ajaxResponse.error) {
			console.log('errrrrrrrrrrrrorrrrrrrrrr');
			// TODO: template message erruer a faire
		} else if (ajaxResponse.move) {
			console.log('move');
			let playerMana = document.getElementById("playerMana");
			let cardInHand = document.querySelectorAll('#playerHand .card');
			let zonePlayerBook = document.getElementById('playerCenterBoard');

			playerMana.innerHTML = ajaxResponse.heroMana;
			for (let element of cardInHand) {
				if (element.dataset.id == ajaxResponse.move) {
					let movable = element;
					element.remove();
					movable.removeEventListener('mouseover', setHover);
					movable.removeEventListener('click', getCardId);
					movable.classList.remove('card');
					movable.classList.add('blazon');
					movable.setAttribute('style', "background-image:url('img/blazon/" + ajaxResponse.bg + "')");
					zonePlayerBook.appendChild(movable);
					movable.classList.remove('hover');
					movable.classList.remove('selected');
					movable.addEventListener('click', getBlazonId);
					// TODO: add event on opponent card after move
				}
			}
		} else if (ajaxResponse.attack) {
			console.log('attack');
			let cardInMiddle = document.querySelectorAll('#playerCenterBoard .blazon');
			let cardOpponentInMiddle = document.querySelectorAll('#opponentCenterBoard .blazon');

			for (let element of cardInMiddle) {
				if (element.dataset.id == ajaxResponse.cardPlayer) {
					if (ajaxResponse.cardPlayerLife == 0) {
						element.remove();
					} else {
						element.firstElementChild.innerHTML = ajaxResponse.cardPlayerLife;
					}
				}
			}
			if (ajaxResponse.target) {
				for (let element of cardOpponentInMiddle) {
					if (element.dataset.id == ajaxResponse.target) {
						if (ajaxResponse.targetLife == 0) {
							element.remove();
						} else {
							element.firstElementChild.innerHTML = ajaxResponse.targetLife;
						}
					}
				}
			} else if (ajaxResponse.opponentHero) {
				opponentHero.firstElementChild.innerHTML = ajaxResponse.heroLife;
			}
		} else if (ajaxResponse.turn) {
			console.log('turn');
			let playerMana = document.getElementById('playerMana');

			playerMana.innerHTML = ajaxResponse.mana + ' / ' + ajaxResponse.gameMana;
			if (ajaxResponse.playId == ajaxResponse.userId) {
				stopTurn.classList.add('play');
			} else {
				stopTurn.classList.remove('play');
			}
		} else if (ajaxResponse.render) {

			if (lastAjaxValue == null) {
				lastAjaxValue = ajaxResponse;

				// playerMana.innerHTML = ajaxResponse.user.mana;
				// met en evidence le bouton stop si l'utilisateur commence
				if (ajaxResponse.game.playId == ajaxResponse.game.userId) {
					stopTurn.classList.add('play');
				} else {
					stopTurn.classList.remove('play');
				}

			} else if (JSON.stringify(lastAjaxValue) != JSON.stringify(ajaxResponse)) {

				//on check la liste des carte et defini les modif a faire
				for (var index = 0; index < ajaxResponse.cards.length; index++) { //pr chaque carte
					if (JSON.stringify(ajaxResponse.cards[index]) != JSON.stringify(lastAjaxValue.cards[index])) { //si une carte est differente de la derniere requete

						//Si la carte existe deja dans la vue
						if (document.querySelector("div[data-id='" + ajaxResponse.cards[index].id + "']")) {
							console.log('defausse');

							let element = document.querySelector("div[data-id='" + ajaxResponse.cards[index].id + "']");

							//Si le statut n'est pas le meme
							if (element.dataset.status != ajaxResponse.cards[index].status) {
								if (ajaxResponse.cards[index].status == 2) { //on la defausse
									element.remove();
								}

							//Sinon si la vie n'est plus la meme on la met a jour
							} else if (element.firstElementChild.innerHTML != ajaxResponse.cards[index].life) {
								console.log('life changed');
								element.firstElementChild.innerHTML = ajaxResponse.cards[index].life;
							}

							//Si des carte on ete pioché
						} else if (ajaxResponse.cards[index].status == 1) {
							console.log('drawed');
							let div = document.createElement('DIV', {
								'class': 'back-card'
							});
							opponentHand.appendChild(div);
						} else {
							if (ajaxResponse.cards[index].status == 3) { //pose sur le plateau
								console.log('generation');
								//generation de carte legendaire
								if (ajaxResponse.cards[index].type == 1) { //legendaire
									console.log('generation legendaire');
									let div = document.createElement('DIV');
									div.dataset.id = ajaxResponse.cards[index].id;
									div.dataset.status = ajaxResponse.cards[index].status;
									div.classList.add('blazon');
									div.classList.add('legendary');
									div.setAttribute('style', "background-image:url('img/blazon/" + ajaxResponse.cards[index].bg + "')");

									let spanLife = document.createElement('SPAN');
									spanLife.innerHTML = ajaxResponse.cards[index].life;
									spanLife.classList.add('legendary-life');
									spanLife.classList.add('life');

									let spanAttack = document.createElement('SPAN');
									spanAttack.innerHTML = ajaxResponse.cards[index].attack;
									spanLife.classList.add('legendary-attack');

									let spanMana = document.createElement('SPAN');
									spanMana.innerHTML = ajaxResponse.cards[index].mana;
									spanLife.classList.add('legendary-mana');

									div.appendChild(spanLife);
									div.appendChild(spanAttack);
									div.appendChild(spanMana);
									zoneOpponentBook.appendChild(div);
									div.addEventListener('click', attack);

									//generation de carte sort
								}
								// else if (ajaxResponse.cards[index].type == 3) {
								// 	console.log('generation sort');
								// 	let div = document.createElement('DIV', {
								// 		style: "background-image:url('img/cards/" + ajaxResponse.cards[index].bg + "')",
								// 		class: "card sort"
								// 	});
								// 	div.dataset.id = ajaxResponse.cards[index].id;
								// 	div.dataset.status = ajaxResponse.cards[index].status;
								// 	div.classList.add('card');
								// 	div.classList.add('sort');
								// 	div.setAttribute('style', "background-image:url('img/blazon/" + ajaxResponse.cards[index].bg + "')");
								//
								// 	let spanAttack = document.createElement('SPAN', {
								// 		class: "sort-attack"
								// 	});
								// 	spanAttack.innerHTML = ajaxResponse.cards[index].attack;
								//
								// 	let spanMana = document.createElement('SPAN', {
								// 		class: "sort-mana"
								// 	});
								// 	spanMana.innerHTML = ajaxResponse.cards[index].mana;
								//
								// 	div.appendChild(spanAttack);
								// 	div.appendChild(spanMana);
								// 	zoneOpponentBook.appendChild(div);
								//
								// }

								//generation de carte créature
								else if (ajaxResponse.cards[index].type == 4 || ajaxResponse.cards[index].type == 2) { //creature
									console.log('generation creature');
									let div = document.createElement('DIV');
									div.dataset.id = ajaxResponse.cards[index].id;
									div.dataset.status = ajaxResponse.cards[index].status;
									div.classList.add('blazon');
									div.classList.add('creature');
									div.setAttribute('style', "background-image:url('img/blazon/" + ajaxResponse.cards[index].bg + "')");

									let spanLife = document.createElement('SPAN');
									spanLife.innerHTML = ajaxResponse.cards[index].life;
									spanLife.classList.add('creature-life');
									spanLife.classList.add('life');

									let spanAttack = document.createElement('SPAN');
									spanAttack.innerHTML = ajaxResponse.cards[index].attack;
									spanAttack.classList.add('creature-attack');

									let spanMana = document.createElement('SPAN');
									spanMana.innerHTML = ajaxResponse.cards[index].mana;
									spanMana.classList.add('creature-mana');

									div.appendChild(spanLife);
									div.appendChild(spanAttack);
									div.appendChild(spanMana);
									zoneOpponentBook.appendChild(div);
									div.addEventListener('click', attack);
								}
								//Sinon carte genere de la pioche
							}
						}
					}
				}

				if (opponentHero.firstElementChild.innerHTML != ajaxResponse.opponentHero.life) { // TODO: verifier id peut etre ?????
					console.log('hero life change');
					opponentHero.firstElementChild.innerHTML = ajaxResponse.opponentHero.life;
				}
				if (playerHero.firstElementChild.innerHTML != ajaxResponse.playerHero.life) { // TODO: verifier id peut etre ?????
					console.log('hero life change');
					playerHero.firstElementChild.innerHTML = ajaxResponse.playerHero.life;
				}

				// TODO: opponent mana a gere
				// TODO: opponent mana a gere
				// TODO: opponent mana a gere
				// TODO: opponent mana a gere
				// TODO: opponent mana a gere

				if (ajaxResponse.game.turn != lastAjaxValue.game.turn) {
					console.log('change turn');
					playerMana.innerHTML = ajaxResponse.user.mana;
					if (ajaxResponse.game.playId == ajaxResponse.game.userId) {
						stopTurn.classList.add('play');
					} else {
						stopTurn.classList.remove('play');
					}
				}

			} else { //Si aucune difference avec la derniere MAJ
				console.log('aucune donnée a actualiser');
			}

			lastAjaxValue = ajaxResponse;  //on stock la requete traité en tant que derniere requete execute
		}
	}



	/**
	 * --------------------------------------------------
	 *     AJAX
	 * ------------------------------------------------------
	 */
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

		if (data != null) {
			xhr.open("GET", target + '&' + data);
		} else {
			xhr.open("GET", target);
		}
		// xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
		xhr.send();

		// return xhr;
	}




	/**
	 * --------------------------------------------------
	 *     COMMON
	 * ------------------------------------------------------
	 */
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
	 * --------------------------------------------------
	 *     ANIMATE CARD.JS
	 * ------------------------------------------------------
	 */




	let container = document.querySelector('#playerHand');

	// Selectionne le noeud dont les mutations seront observées
	let targetNode = container;

	// Options de l'observateur (quelles sont les mutations à observer)
	let config = {
		childList: true
	};


	// Fonction callback à éxécuter quand une mutation ou un window.resize est observée
	let callback = function (mutationsList) {
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

			for (let i = 0; i < childNbr; i++) {
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
				remove++;
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

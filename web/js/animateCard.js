(function() {
    'use strict';

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


    function addCard() {
    	let div = document.createElement('DIV');
    	div.classList.add('item');
    	container.append(div);
    	div.addEventListener('mouseover', setHover);
    	div.addEventListener('click', setSelected);
    }

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


    // document.getElementById('draw').addEventListener('click', addCard);



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

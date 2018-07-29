(function() {
    'use strict';

    /**
     *   Redimensionne un container selon la taille du background parent
     *
     *   @param  {HTMLElement} container Element parent de l'element contenant un background
     *   @param  {HTMLElement} target    Element a redimensionner a la taille du backgroud parent
     *   @return {void}
     */
    function resizeWrapper(container, target) {
        let oImage = getBackgroundSize(container);
        target.style.width = oImage.width + 'px';
        target.style.height = oImage.height + 'px';
    }


    window.addEventListener('load', function() {
        let container1 = document.getElementById('book');                       //container du livre
        let target1 = document.getElementById('bookWrapper');                   //grid interne au container

        let container2 = document.getElementById('bottomBand');                 //container bande noir bas
        let target2 = document.getElementById('bottomBandWrapper');             //grid interne au container

        let container3 = document.getElementById('topBand');                    //container bande noir haut
        let target3 = document.getElementById('topBandWrapper');                //grid interne au container

        resizeWrapper(container1, target1);                                     //Resize element at window.load
        resizeWrapper(container2, target2);                                     //Resize element at window.load
        resizeWrapper(container3, target3);                                     //Resize element at window.load

        window.addEventListener('resize', function() {                          //Or if window resized
            resizeWrapper(container1, target1)
        });
        window.addEventListener('resize', function() {                          //Or if window resized
            resizeWrapper(container2, target2)
        });
        window.addEventListener('resize', function() {                          //Or if window resized
            resizeWrapper(container3, target3)
        });
    });

}());


/**
 *   Retourne les propriétés réél  width et height d'un backgroud
 *
 *   @param  {HTMLElement}  elem   Element contenant un backgroud-image
 *   @return {object}              Contient la width et la height
 */
function getBackgroundSize(elem) {
    var computedStyle = getComputedStyle(elem),
    image = new Image(),
    src = computedStyle.backgroundImage.replace(/url\((['"])?(.*?)\1\)/gi, '$2'),
    cssSize = computedStyle.backgroundSize,
    elemW = parseInt(computedStyle.width.replace('px', ''), 10),
    elemH = parseInt(computedStyle.height.replace('px', ''), 10),
    elemDim = [elemW, elemH],
    computedDim = [],
    ratio;
    image.src = src;
    ratio = image.width > image.height ? image.width / image.height : image.height / image.width;
    cssSize = cssSize.split(' ');
    computedDim[0] = cssSize[0];
    computedDim[1] = cssSize.length > 1 ? cssSize[1] : 'auto';
    if(cssSize[0] === 'cover') {
        if(elemDim[0] > elemDim[1]) {
            if(elemDim[0] / elemDim[1] >= ratio) {
                computedDim[0] = elemDim[0];
                computedDim[1] = 'auto';
            } else {
                computedDim[0] = 'auto';
                computedDim[1] = elemDim[1];
            }
        } else {
            computedDim[0] = 'auto';
            computedDim[1] = elemDim[1];
        }
    } else if(cssSize[0] === 'contain') {
        if(elemDim[0] < elemDim[1]) {
            computedDim[0] = elemDim[0];
            computedDim[1] = 'auto';
        } else {
            if(elemDim[0] / elemDim[1] >= ratio) {
                computedDim[0] = 'auto';
                computedDim[1] = elemDim[1];
            } else {
                computedDim[1] = 'auto';
                computedDim[0] = elemDim[0];
            }
        }
    } else {
        for(var i = cssSize.length; i--;) {
            if (cssSize[i].indexOf('px') > -1) {
                computedDim[i] = cssSize[i].replace('px', '');
            } else if (cssSize[i].indexOf('%') > -1) {
                computedDim[i] = elemDim[i] * (cssSize[i].replace('%', '') / 100);
            }
        }
    }
    if (computedDim[0] === 'auto' && computedDim[1] === 'auto') {
        computedDim[0] = image.width;
        computedDim[1] = image.height;
    } else {
        ratio = computedDim[0] === 'auto' ? image.height / computedDim[1] : image.width / computedDim[0];
        computedDim[0] = computedDim[0] === 'auto' ? image.width / ratio : computedDim[0];
        computedDim[1] = computedDim[1] === 'auto' ? image.height / ratio : computedDim[1];
    }
    return {
        width: computedDim[0],
        height: computedDim[1]
    };
}

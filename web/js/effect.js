(function ()
{
    var cardsInHand = document.querySelectorAll("#deck-2 .card");

    for (var i = 0; i < cardsInHand.length; i++)
    {
        cardsInHand[i].addEventListener('click', function(e)
        {
            e.target.style.transform = "translateY(-45.71429px) scale(1.8,1.8)";
            // console.log("click sur la carte " + e.target);
        });
    }

})();

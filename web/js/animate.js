let image = document.getElementById('gandalf');

image.addEventListener("mouseover", function(event){
    event.target.style.width = "25rem";
})
image.addEventListener("mouseout", function(event){
    setTimeout(function(){
        event.target.style.width = "";
    }, 3000);
});
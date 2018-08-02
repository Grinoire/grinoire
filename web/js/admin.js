let button = document.getElementById('admin-button');
let listUser = document.getElementById('grinoire-liste-utilisateur');

button.addEventListener('click', function (event) {


    if (listUser.className == 'hidden') {
        listUser.classList.remove('hidden');
        listUser.classList.add('show');
    } else {
        listUser.classList.remove('show');
        listUser.classList.add('hidden');
    }

});
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
$('#addImage').click(function () {
    //je récupere le numéro des fututrs champs que je vais créer
    const index = +$('#widgets-count').value();

    // je récupère le prototype des entrées
    const tmpl = $('#addImage').data('prototype').replace(/__name__/g, index);
    // j'injecte ce code au sein de la div
    $('#addImage').append(tmpl);

    $('#widgets-count').val(index + 1);

    // je gère le bouton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#addImage div.form-group').length;
    $("#widgets-count").value(count);
}

updateCounter();
handleDeleteButtons();

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

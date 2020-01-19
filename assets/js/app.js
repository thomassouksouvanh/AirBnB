/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
    const $ = require('jquery');


console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

const index = +$('#widgets-counter').val();

const tmpl = $('#annonce_images').data('prototype');

const count = +$('#annonce_images div.form-group').length;



$('#add_images').click(function () {
    $('#annonce_images').append(tmpl);
    $('#widgets-counter').val(index + 1);

    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        $(this.dataset.target).remove()

    });
}

function updateCounter() {
    $('#widgets-counter').val(count);

};

updateCounter();
handleDeleteButtons();

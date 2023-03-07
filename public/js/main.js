$(document).ready(function () {
    $('.submit').click(function (e) {
        if(!checkFormInputs())    {
            e.preventDefault();
        }
    });

    $('#username,#email,#text,#status').on('input', function () {
        $(this).removeClass('is-invalid');
        $('.errors').html('');
    });
})

function checkFormInputs() {
    let username = $('#username');
    let email = $('#email');
    let text = $('#text');
    let status = $('#status');

    let errors = [];

    if (username.val().length < 3) {
        username.addClass('is-invalid');
        errors.push('Username must be at least 3 characters long.');
    }
    if (!checkEmail(email.val())) {
        email.addClass('is-invalid');
        errors.push('Email must be a valid email address.');
        
    }
    if (text.val().length < 3) {
        text.addClass('is-invalid');
        errors.push('Text must be at least 3 characters long.');
    }
    if (!['new', 'in progress', 'done', 'canceled'].includes(status.val())) {
        status.addClass('is-invalid');
        errors.push('Status must be new, in progress, done or canceled.'); 
    }

    if (errors.length > 0) {
        let errorMessages = errors.map(error => `<li>${error}</li>`).join('');
        $('.errors').html(`<ul>${errorMessages}</ul>`);
        
        return false;
    }

    return true;
}

function checkEmail(email) {
    const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return re.test(email);
}
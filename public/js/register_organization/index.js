const registerOrganizationForm = document.getElementById('registerOrganizationForm');


const requiredErrorText = {
    name: 'Organization name is required.',
}


const invalidErrorText = {
    name: 'This organization name is already registered.',
    website: 'Invalid URL.',
}

var validity = {}

window.onload = function() {
    validity = checkFormValidity();
};


registerOrganizationForm.addEventListener('submit', function (event) {
    var focusInvalidInput = null;
    
    for(var input in validity) {
        if(!validity[input] && !focusInvalidInput) {
            focusInvalidInput = input;
            break;
        }
    }

    if(focusInvalidInput) {
        event.preventDefault();
        document.getElementById(focusInvalidInput).focus();
    }

    for(var input in validity) {
        if(!validity[input]) {
            showErrorMessageForInput(input);
        }
    }
})


function checkFormValidity() {
    const validity = {
        name: validateOrganizationName(document.getElementById('name').value),
        website: validateInput('website', 'keyup', validateURL),
        description: validateInput('description'),
    }
    return validity;
}


function validateInput (id, event = 'keyup', validator = null) {
    const input = document.getElementById(id);
    const helper = document.getElementById(id + '-helper');
    const helperText = helper.textContent;

    input.addEventListener(event, () => {
        if(input.value) {
            if(validator && !validator(input.value)) {
                helper.textContent = invalidErrorText[id];
                helper.classList.add('error');
                validity[id] = false;
            }
            else {
                helper.textContent = helperText;
                helper.classList.remove('error');
                validity[id] = true;
            }
        }
        else {
            if(requiredErrorText[id]) {
                helper.textContent = requiredErrorText[id];
                helper.classList.add('error');
                validity[id] = false;
            }
            else {
                helper.textContent = helperText;
                helper.classList.remove('error');
                validity[id] = true;
            }
        }
    })

    const _checkValidity = (validator) => {
        if(input.value) {
            if(validator && !validator(input.value)) { return false }
            else { return true }
        }
        else {
            if(requiredErrorText[id]) { return false }
            else { return true }
        }
    }

    return _checkValidity(validator);
}


function showErrorMessageForInput (id) {
    const helper = document.getElementById(id + '-helper');

    if(invalidErrorText[id] && document.getElementById(id).value.trim()) {
        helper.textContent = invalidErrorText[id];
    }
    else {
        helper.textContent = requiredErrorText[id];
    }

    helper.classList.add('error');
}


function validateURL (url) {
    const pattern = new RegExp('^(https?:\\/\\/)?'+         // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+    // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+                          // ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+                      // port
    '(\\?[;&amp;a-z\\d%_.~+=-]*)?'+                         // query string
    '(\\#[-a-z\\d_]*)?$','i');

    return pattern.test(url);
}
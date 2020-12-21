const registerProjectForm = document.getElementsByTagName('form')[0];


const requiredErrorText = {
    name: 'Project name is required.',
    objective: 'Project objective is required.',
    description: 'Project description is required.',
    domain_expertise: 'Select an option.',
    technical_expertise: 'Select an option.',
    git_repo_data: {
        required  : true,
        errorText1: 'Repo name is required.',
        errorText2: 'Repo URL is required.',
    },
    mailing_lists_data: {
        errorText1: 'List name is required.',
        errorText2: 'Subscribe URL is required.',
    },
    more_info: {
        errorText1: 'Title is required.',
        errorText2: 'URL is required.',
    }
}


const invalidErrorText = {
    website: 'Invalid URL.',
    git_repo_data: {
        invalidText2: 'Invalid repo URL.'
    },
    mailing_lists_data: {
        invalidText2: 'Invalid subscribe URL.'
    },
    more_info: {
        invalidText2: 'Invalid URL.'
    }
}


var validity = {
    name: false,
    objective: false,
    description: false,
    website: true,
    domain_expertise: false,
    technical_expertise: false,
    git_repo_data: false,
    mailing_lists_data: true,
    more_info: true
}


validateInput('name');
validateInput('objective');
validateInput('description');
validateInput('website', 'keyup', validateURL);
validateInput('domain_expertise', 'click');
validateInput('technical_expertise', 'click');
validateDynamicInputGroup('git_repo_data', true);
validateDynamicInputGroup('mailing_lists_data');
validateDynamicInputGroup('more_info');
handleOnSelectDropdownOption('domain_expertise');
handleOnSelectDropdownOption('technical_expertise');


// registerProjectForm.addEventListener('submit', function (event) {
//     var focusInvalidInput = null;
    
//     for(var input in validity) {
//         if(!validity[input] && !focusInvalidInput) {
//             focusInvalidInput = input;
//             break;
//         }
//     }

//     if(focusInvalidInput) {
//         event.preventDefault();
//         document.getElementById(input).focus();
//     }

//     for(var input in validity) {
//         if(!validity[input]) {
//             if(typeof requiredErrorText[input] === 'object') { showErrorMessageForInputGroup(input) }
//             else { showErrorMessageForInput(input) }
//         }
//     }
// })


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
}


function handleOnSelectDropdownOption (id) {
    const element = document.getElementById(id + '-dropdown');
    const helper = document.getElementById(id + '-helper');
    const optionsList = element.querySelectorAll(".option");
    const selectedText = element.querySelector(".md-form").querySelector(".form-control");

    optionsList.forEach((option) => {
        option.addEventListener("click", () => {
          selectedText.classList.remove("error");
          helper.textContent = null;
          validity[id] = true;
        });
    });
}


function validateDynamicInputGroup (id, required = false) {
    const container = document.getElementById(id);
    const inputs = container.getElementsByTagName('input');
    const helpers = container.getElementsByTagName('span');

    const _setupEventListeners = () => {
        inputs.forEach((input, index) => {
            input.addEventListener("keyup", () => {
                const helper = helpers[index];

                if(index % 2 === 0 && !input.value) {
                    if(inputs[index + 1].value) {
                        helper.textContent = requiredErrorText[id].errorText1;
                        helper.classList.add('error');
                    }
                    else if(index) {
                        helper.textContent = null;
                        helper.classList.remove('error');
                        helpers[index + 1].textContent = null;
                        helpers[index + 1].classList.remove('error');
                    }
                }
                else if(index % 2 === 1 && !input.value) {
                    if(inputs[index - 1].value) {
                        helper.textContent = requiredErrorText[id].errorText2;
                        helper.classList.add('helper-text-no-icon');
                        helper.classList.add('error');
                    }
                    else if(index - 1) {
                        helper.textContent = null;
                        helper.classList.remove('error');
                        helpers[index - 1].textContent = null;
                        helpers[index - 1].classList.remove('error');
                    }
                }
                else if(index % 2 === 1 && input.value && !validateURL(input.value)) {
                    helper.textContent = invalidErrorText[id].invalidText2;
                    helper.classList.add('helper-text-no-icon');
                    helper.classList.add('error');
                }
                else {
                    helper.textContent = null;
                    helper.classList.remove('error');
                }

                if(required && !index && !input.value && !inputs[index + 1].value) {
                    helper.textContent = requiredErrorText[id].errorText1;
                    helper.classList.add('error');
                    helpers[index + 1].textContent = requiredErrorText[id].errorText2;
                    helpers[index + 1].classList.add('error');
                    helpers[index + 1].classList.add('helper-text-no-icon');
                }

                validity[id] = _checkValidity(inputs);
            })
        });
    }

    const _checkValidity = (inputs) => {
        var _validity = true;

        try {
            inputs.forEach((input, index) => {
                if(index % 2 === 0 && ((input.value && !inputs[index + 1].value) || (!input.value && inputs[index + 1].value))) {
                    throw 'InvalidException';
                }
            })
        }
        catch (exception) {
            _validity = false;
        }

        return _validity;
    }

    _setupEventListeners();

    container.addEventListener("DOMNodeInserted", () => { _setupEventListeners() });
}


function showErrorMessageForInput (id) {
    const helper = document.getElementById(id + '-helper');
    helper.textContent = requiredErrorText[id];
    helper.classList.add('error');
}


function showErrorMessageForInputGroup (id) {
    const container = document.getElementById(id);
    const inputs = container.getElementsByTagName('input');
    const helpers = container.getElementsByTagName('span');

    inputs.forEach((input, index) => {
        const helper = helpers[index];

        if(index % 2 === 0 && !input.value && inputs[index + 1].value) {
            helper.textContent = requiredErrorText[id].errorText1;
            helper.classList.add('error');
        }
        else if(index % 2 === 1 && !input.value && inputs[index - 1].value) {
            helper.textContent = requiredErrorText[id].errorText2;
            helper.classList.add('helper-text-no-icon');
            helper.classList.add('error');
        }

        if(requiredErrorText[id].required && !index && !input.value && !inputs[index + 1].value) {
            helper.textContent = requiredErrorText[id].errorText1;
            helper.classList.add('error');
            helpers[index + 1].textContent = requiredErrorText[id].errorText2;
            helpers[index + 1].classList.add('error');
            helpers[index + 1].classList.add('helper-text-no-icon');
        }
    })
}


function validateURL (url) {
    const pattern = new RegExp('^(https?:\\/\\/)?'+         // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+    // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+                          // ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+                      //port
    '(\\?[;&amp;a-z\\d%_.~+=-]*)?'+                         // query string
    '(\\#[-a-z\\d_]*)?$','i');

    return pattern.test(url);
}
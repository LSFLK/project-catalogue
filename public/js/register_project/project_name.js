function validateProjectName() {
    const input = document.getElementById('name');
    const helper = document.getElementById('name-helper');
    const helperText = helper.textContent;

    const validIcon = document.getElementById('valid-icon');
    const invalidIcon = document.getElementById('invalid-icon');

    input.addEventListener('keyup', () => {
        const name = input.value.trim();

        if(name) {
            _checkIfProjectNameExists(name).then((result) => {
                if(!result) {
                    helper.textContent = invalidErrorText.name;
                    helper.classList.add('error');
                    validity.name = false;
                    validIcon.style.display = 'none';
                    invalidIcon.style.display = 'block';
                }
                else {
                    helper.textContent = helperText;
                    helper.classList.remove('error');
                    validity.name = true;
                    validIcon.style.display = 'block';
                    invalidIcon.style.display = 'none';
                }
            })
        }
        else {
            helper.textContent = requiredErrorText.name;
            helper.classList.add('error');
            validity.name = false;
            validIcon.style.display = 'none';
            invalidIcon.style.display = 'none';
        }
    })

    const _checkIfProjectNameExists = (name) => {
        return $.get('/projects/validate?name=' + name, function(result){
            return result;
        });
    }
}
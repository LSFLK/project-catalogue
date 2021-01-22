function initiallizeDynamicInput (containerID, currentValuesString, _key, _url) {
    const container = document.getElementById(containerID);

    const inputGroup = container.querySelector('.input-group').cloneNode(true);
    const button = inputGroup.querySelector(".button");
    const buttonIcon = button.querySelector("i");
    buttonIcon.setAttribute("class", "fas fa-minus-circle prefix");

    var index = 1;

    const _handleOnClickAddButton = () => {
        const newInputGroup = inputGroup.cloneNode(true);
        const newButton = newInputGroup.querySelector(".button");
        const newInputGroupID = containerID + '_' + index++;

        newInputGroup.classList.add(newInputGroupID);
        newButton.addEventListener("click", () => _handleOnClickRemoveButton(newInputGroupID));
        container.appendChild(newInputGroup);

        return newInputGroupID;
    }

    const _handleOnClickRemoveButton = (inputGroupID) => {
        container.querySelector('.' + inputGroupID).remove();
    }

    const currentInputGroup = container.querySelector('.input-group');
    const currentButton = currentInputGroup.querySelector(".button");
    currentInputGroup.classList.add(containerID + '_0');
    currentButton.addEventListener("click", _handleOnClickAddButton);

    const currentValues = JSON.parse(currentValuesString.replace(/(&quot\;)/g,"\""));

    const _addObjectValuesToInputGroup = (objectIndex) => {
        const inputGroupID = containerID + '_' + objectIndex;
        const inputGroup = container.querySelector('.' + inputGroupID);
        const key = inputGroup.querySelector('.key').querySelector('input');
        const value = inputGroup.querySelector('.value').querySelector('input');

        const object = currentValues[objectIndex]
        key.value = object[_key];
        value.value = object[_url];
    }

    for(var objectIndex in currentValues) {
        if(parseInt(objectIndex)) { _handleOnClickAddButton() }
        _addObjectValuesToInputGroup(objectIndex);
    }
}
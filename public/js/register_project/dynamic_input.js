function initiallizeDynamicInput (containerID) {
    const container = document.getElementById(containerID);

    const inputGroup = container.querySelector('.input-group').cloneNode(true);

    var index = 0;

    var currentInputGroup = container.querySelector('.input-group');
    var currentButton = currentInputGroup.querySelector(".button");

    currentInputGroup.setAttribute("id", index++);

    const _handleOnClickRemoveButton = (inputGroupID) => {
        container.querySelector('.' + inputGroupID).remove();
    }

    const _handleOnClickAddButton = () => {
        const previousInputGroupID = _createInputGroupID(containerID, currentInputGroup);

        const currentButtonIcon = currentButton.querySelector("i");
        currentButtonIcon.setAttribute("class", "fas fa-minus-circle prefix");

        currentButton.removeEventListener("click", _handleOnClickAddButton);
        currentButton.addEventListener("click", () => _handleOnClickRemoveButton(previousInputGroupID));

        const newInputGroup = inputGroup.cloneNode(true);
        const newButton = newInputGroup.querySelector(".button");

        container.appendChild(newInputGroup);
        newButton.addEventListener("click", _handleOnClickAddButton);

        newInputGroup.setAttribute("id", index++);

        currentInputGroup = newInputGroup;
        currentButton = newButton;
    }

    currentButton.addEventListener("click", _handleOnClickAddButton);
}


function _createInputGroupID (containerID, inputGroup) {
    const inputGroupID = containerID + '_' + inputGroup.id;

    inputGroup.classList.add(inputGroupID);

    return inputGroupID;
}

// function _handleOnClickAddButton (container, inputGroup) {
//     const button = inputGroup.querySelector(".button");
//     const icon = button.querySelector("i");
//     icon.setAttribute("class", "fas fa-minus-circle prefix");

//     button.removeEventListener("click", () => _handleOnClickAddButton(container, inputGroup));
//     button.addEventListener("click", () => _handleOnClickRemoveButton(container, inputGroup));

//     const newInputGroup = inputGroup.cloneNode(true);
//     const newButton = newInputGroup.querySelector(".button");

//     container.appendChild(newInputGroup);
//     newButton.addEventListener("click", () => _handleOnClickAddButton(container, newInputGroup));
// }

// function _handleOnClickRemoveButton (container, inputGroup) {
//     console.log("Remove")
// }



























































// $(function() {
//     // Remove button click
//     $(document).on(
//         'click',
//         '[data-role="dynamic-fields"] > .row [data-role="remove"]',
//         function(e) {
//             e.preventDefault();
//             $(this).closest('.row').remove();
//         }
//     );
//     // Add button click
//     $(document).on(
//         'click',
//         '[data-role="dynamic-fields"] > .row [data-role="add"]',
//         function(e) {
//             console.log("CLICKED")
//             e.preventDefault();
//             var container = $(this).closest('[data-role="dynamic-fields"]');
//             new_field_group = container.children().filter('.row:first-child').clone();
//             new_field_group.find('input').each(function(){
//                 $(this).val('');
//             });
//             container.append(new_field_group);
//         }
//     );
// });
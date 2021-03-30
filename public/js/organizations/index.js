const noOrganizationsLabel = document.getElementById('no-organizations');

function handleNoOrganizationsLabel(organizations) {
    if(organizations.length) { noOrganizationsLabel.style.display = "none" }
    else { noOrganizationsLabel.style.display = "inline" }
}
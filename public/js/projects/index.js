const noProjectsLabel = document.getElementById('no-projects');

function handleNoProjectsLabel(projects) {
    if(projects.length) { noProjectsLabel.style.display = "none" }
    else { noProjectsLabel.style.display = "inline" }
}
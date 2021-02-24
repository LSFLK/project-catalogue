const noProjectsLabel = document.getElementById('no-projects');

function handleNoProjectsLabel() {
    const projects = "{{ projects }}";

    if(projects.length) { noProjectsLabel.style.display = "none" }
    else { noProjectsLabel.style.display = "inline-block" }
}

window.onLoad = function() { handleNoProjectsLabel() };
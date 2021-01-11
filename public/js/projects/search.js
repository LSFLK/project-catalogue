function initiallizeSearch () {
    const element = document.getElementById('search-by-name');
    const searchInput = element.querySelector(".search-box-input");

    const url = new URL(window.location.href);
    const { origin, pathname } = url;
    const browser_url = new URL(origin + pathname);

    searchInput.addEventListener("keyup", (event) => {
        const name = event.target.value.trim();

        if(name.length) {
            browser_url.searchParams.set('name', name);
        }
        else {
            searchInput.value = null;
            browser_url.searchParams.delete('name');
        }

        searchProjectsByName();
    });

    const searchProjectsByName = () => {
        const api_url = origin + pathname + '/search' + browser_url.search;

        $.get(api_url, function(projects){
            history.pushState({}, null, browser_url);
            renderProjectCards(projects);
        });
    }

    const renderProjectCards = (projects) => {
        const container = document.getElementById('project-cards-container');
        container.innerHTML = null;
  
        projects.forEach(project => {
          const cardTemplate = document.createElement("DIV");
          cardTemplate.innerHTML = project;
  
          const projectCard = cardTemplate.querySelector('.project-card');
          container.appendChild(projectCard);
        });
    }
}
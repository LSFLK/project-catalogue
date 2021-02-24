function initiallizeSearch () {
    const container = document.getElementById('project-cards-container');
    const element = document.getElementById('search-by-name');
    const searchInput = element.querySelector(".search-box-input");

    const searchResults = document.getElementById('search-results-count');
    const searchResultsCount = searchResults.querySelector(".count");
    const searchResultsDisplayText = searchResults.querySelector(".text");
    const searchResultsSearchTerm = searchResults.querySelector(".search");

    const domainExpertise = document.getElementById('domain').querySelector(".selected").querySelector(".text");
    const technicalExpertise = document.getElementById('technical').querySelector(".selected").querySelector(".text");
    const programmingLanguage = document.getElementById('language').querySelector(".selected").querySelector(".text");

    const url = new URL(window.location.href);
    const { origin, pathname } = url;
    const browser_url = new URL(origin + pathname);

    var delayTimer;
    var searchInputText;

    searchInput.addEventListener("keyup", (event) => {
        searchInputText = event.target.value.trim();

        domainExpertise.innerHTML = 'Domain Expertise';
        technicalExpertise.innerHTML = 'Technical Expertise';
        programmingLanguage.innerHTML = 'Programming Language';

        if(delayTimer) { clearTimeout(delayTimer) }

        if(searchInputText.length) {
            browser_url.searchParams.set('name', searchInputText);
        }
        else {
            searchInput.value = null;
            browser_url.searchParams.delete('name');
        }

        delayTimer = setTimeout(function() {
            searchProjectsByName();
        }, 250);
    });

    const searchProjectsByName = () => {
        const api_url = origin + pathname + '/search' + browser_url.search;

        $(container).fadeOut(150);

        $.get(api_url, function(projects){
            history.pushState({}, null, browser_url);
            renderProjectCards(projects);
        });
    }

    const renderProjectCards = (projects) => {
        container.innerHTML = null;

        handleNoProjectsLabel(projects);

        projects.forEach(project => {
          const cardTemplate = document.createElement("DIV");
          cardTemplate.innerHTML = project;
  
          const projectCard = cardTemplate.querySelector('.project-card');
          container.appendChild(projectCard);
        });

        $(container).fadeIn(200);

        if(searchInputText.length) {
            searchResultsCount.innerHTML = projects.length;
            searchResultsDisplayText.innerHTML = '&nbsp;result' + (projects.length === 1 ? '&nbsp' : 's&nbsp') + 'for&nbsp';
            searchResultsSearchTerm.innerHTML = searchInputText;
        }
        else {
            searchResultsCount.innerHTML = null;
            searchResultsDisplayText.innerHTML = null;
            searchResultsSearchTerm.innerHTML = null;
        }
        
    }
}
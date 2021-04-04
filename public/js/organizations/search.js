function initiallizeSearch () {
    const container = document.getElementById('organization-cards-container');
    const element = document.getElementById('search-by-name');
    const searchInput = element.querySelector(".search-box-input");

    const searchResults = document.getElementById('search-results-count');
    const searchResultsCount = searchResults.querySelector(".count");
    const searchResultsDisplayText = searchResults.querySelector(".text");
    const searchResultsSearchTerm = searchResults.querySelector(".search");

    const url = new URL(window.location.href);
    const { origin, pathname } = url;
    const browser_url = new URL(origin + pathname);

    var delayTimer;
    var searchInputText;

    searchInput.addEventListener("keyup", (event) => {
        searchInputText = event.target.value.trim();

        if(delayTimer) { clearTimeout(delayTimer) }

        if(searchInputText.length) {
            browser_url.searchParams.set('name', searchInputText);
        }
        else {
            searchInput.value = null;
            browser_url.searchParams.delete('name');
        }

        delayTimer = setTimeout(function() {
            searchOrganizationsByName();
        }, 250);
    });

    const searchOrganizationsByName = () => {
        const api_url = origin + pathname + '/search' + browser_url.search;

        $(container).fadeOut(150);

        $.get(api_url, function(organizations){
            history.pushState({}, null, browser_url);
            renderOrganizationCards(organizations);
        });
    }

    const renderOrganizationCards = (organizations) => {
        container.innerHTML = null;

        handleNoOrganizationsLabel(organizations);

        organizations.forEach(organization => {
          const cardTemplate = document.createElement("DIV");
          cardTemplate.innerHTML = organization;
  
          const organizationCard = cardTemplate.querySelector('.organization-card');
          container.appendChild(organizationCard);
        });

        $(container).fadeIn(200);

        if(searchInputText.length) {
            searchResultsCount.innerHTML = organizations.length;
            searchResultsDisplayText.innerHTML = '&nbsp;result' + (organizations.length === 1 ? '&nbsp' : 's&nbsp') + 'for&nbsp';
            searchResultsSearchTerm.innerHTML = searchInputText;
        }
        else {
            searchResultsCount.innerHTML = null;
            searchResultsDisplayText.innerHTML = null;
            searchResultsSearchTerm.innerHTML = null;
        }
        
    }
}
function initiallizeDropdown (elementID) {
    const element = document.getElementById(elementID);

    const selected = element.querySelector(".selected");
    const optionsContainer = element.querySelector(".options-container");
    const searchBox = element.querySelector(".search-box input");
    
    const optionsList = element.querySelectorAll(".option");
    const selectedText = selected.querySelector(".text");

    const name = selectedText.innerHTML;
    
    selected.addEventListener("click", () => {
      optionsContainer.classList.toggle("active");
    
      searchBox.value = "";
      filterList("");
    
      if (optionsContainer.classList.contains("active")) {
        searchBox.focus();
        optionsContainer.scrollTop = 0;
      }
    });

    optionsList.forEach((option) => {
      option.addEventListener("click", () => {
        const label = option.querySelector("label");
        const labelText = label.innerHTML;
        selectedText.innerHTML = labelText == 'All' ? name : labelText;
        optionsContainer.classList.remove("active");
        labelText == 'All' ? deleteSearchParam(elementID) : setSearchParam(elementID, label.getAttribute('id'));
      });
    });
    
    searchBox.addEventListener("keyup", (event) => {
      filterList(event.target.value);
    });

    optionsContainer.addEventListener("click", () => {
      searchBox.focus();
      optionsContainer.scrollTop = 0;
    })

    optionsContainer.addEventListener("blur", () => {
      console.log("CLOSE")
    })
    
    const filterList = (searchTerm) => {
      searchTerm = searchTerm.toLowerCase();
      optionsList.forEach(option => {
        const label = option.firstElementChild.innerText.toLowerCase();
        if (label.indexOf(searchTerm) != -1) {
          option.style.display = "block";
        } else {
          option.style.display = "none";
        }
      });
    };

    const setSearchParam = (param, value) => {
      const browser_url = new URL(window.location.href);
      browser_url.searchParams.set(param, value);
      fetchProjectsBySearchParams(browser_url);
    }

    const deleteSearchParam = (param) => {
      const browser_url = new URL(window.location.href);
      browser_url.searchParams.delete(param);
      fetchProjectsBySearchParams(browser_url);
    }

    const fetchProjectsBySearchParams = (browser_url) => {
      const { origin, pathname, search } = browser_url;
      const api_url = origin + pathname + '/filter' + search;

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

        const projectCard = cardTemplate.querySelector('.project-card')
        container.appendChild(projectCard);
      });
    }
  }
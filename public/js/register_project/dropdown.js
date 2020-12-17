function initiallizeDropdown (elementID) {
    const element = document.getElementById(elementID);

    const selected = element.querySelector(".md-form");
    const optionsContainer = element.querySelector(".options-container");
    const searchBox = element.querySelector(".search-box input");
    
    const optionsList = element.querySelectorAll(".option");
    const selectedText = selected.querySelector(".form-control");
    
    selected.addEventListener("click", () => {
      optionsContainer.classList.toggle("active");
    
      searchBox.value = "";
      filterList("");
    
      if (optionsContainer.classList.contains("active")) {
        searchBox.focus();
        optionsContainer.scrollTop = 0;
      }
      else {
        if(selectedText.value) { selectedText.focus() }
        else { selectedText.blur() }
      }
    });

    optionsList.forEach((option) => {
      option.addEventListener("click", () => {
        selectedText.value = option.querySelector("label").innerHTML.replace("&amp;", "&");
        optionsContainer.classList.remove("active");
        selectedText.focus();
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
        const label = option.firstElementChild.nextElementSibling.innerText.toLowerCase();
        if (label.indexOf(searchTerm) != -1) {
          option.style.display = "block";
        } else {
          option.style.display = "none";
        }
      });
    };
  }
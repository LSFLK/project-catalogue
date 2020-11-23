function setupDropdowns (elementIDs) {
  elementIDs.forEach((elementID) => setupEventListeners(elementID))
}


function setupEventListeners (elementID) {
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
        const label = option.querySelector("label").innerHTML;
        selectedText.innerHTML = label == 'All' ? name : label;
        optionsContainer.classList.remove("active");
      });
    });

    searchBox.addEventListener("click", () => {
      searchBox.focus();
      optionsContainer.scrollTop = 0;
    })
    
    searchBox.addEventListener("keyup", (event) => {
      filterList(event.target.value);
    });
    
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
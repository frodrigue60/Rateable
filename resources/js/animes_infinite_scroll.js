document.addEventListener("DOMContentLoaded", (event) => {
    const dataDiv = document.querySelector("#data");
    const formFilter = document.querySelector('#form-filter');
    let pageName = undefined;
    let page = 1;
    let lastPage = undefined;
    let url = undefined;
    //const baseUrl = window.location.href;
    const nameInput = document.querySelector('#input-name');
    const baseUrl = document.querySelector('meta[name="base-url"]').content;
    let apiBaseUrl = baseUrl + '/api/animes';
    const inputName = document.querySelector('#input-name');
    const selectYear = document.querySelector('#select-year');
    const selectSeason = document.querySelector('#select-season');
    const loaderDiv = document.querySelector('#loader');


    fetchData(apiBaseUrl);
    let currentUrl = apiBaseUrl;
    console.log('first fetch: ' + apiBaseUrl);


    // Debounce para limitar las llamadas a filterFetch
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // Función para manejar cambios en los filtros
    const handleFilterChange = debounce(() => {
        let year = document.querySelector('#select-year').value;
        let season = document.querySelector('#select-season').value;

        filterFetch(year, season, nameInput.value);
    });

    // Escucha cambios en el formulario y en el input de nombre
    formFilter.addEventListener('change', handleFilterChange);
    nameInput.addEventListener('keyup', handleFilterChange);

    window.addEventListener("scroll", function () {
        if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {
            if (lastPage == undefined) {
                page++;
                loadMoreData(page);
            } else {
                if (page <= lastPage) {
                    page++;
                    loadMoreData(page);
                }
            }
        }
    });

    async function fetchData(url) {
        loaderDiv.style.removeProperty("display");
        inputName.disabled = true;
        selectYear.disabled = true;
        selectSeason.disabled = true;
        try {
            // Realizar la petición GET
            const response = await fetch(url, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                lastPage = 0;
                console.log(`Error HTTP: ${response.status}`);
                return;
            }

            /* const text = await response.text();
            if (!text.trim()) {
                throw new Error('Respuesta vacía del servidor');
            } */

            const data = await response.json();
            console.log(data);

            if (!data.html || data.html === "") {
                lastPage = 0;
                console.log("No data received from backend");
                return;
            }

            lastPage = data.lastPage;
            dataDiv.innerHTML += data.html;

            const titles = document.querySelectorAll('.post-titles');
            titles.forEach(title => {
                if (title.textContent.length > 25) {
                    title.textContent = title.textContent.substr(0, 25) + "...";
                }
            });

        } catch (error) {
            console.error("Error in fetchData:", error);
            lastPage = 0;
        } finally {
            inputName.disabled = false;
            selectYear.disabled = false;
            selectSeason.disabled = false;
            loaderDiv.style.setProperty("display", "none", "important");
        }
    }

    function loadMoreData(page) {
        let urlParams = new URLSearchParams(currentUrl);

        if (urlParams.has('type') || urlParams.has('sort') ||
            urlParams.has('name')) {
            pageName = "&page=";
        } else {
            pageName = "?page=";
        }

        url = currentUrl + pageName + page;
        /* console.log("fetch loadMoreData(): " + url); */
        fetchData(url);
    }

    function filterFetch(year, season, name) {
        page = 1;
        clearDataDiv();
        let queryUrl = "?" + "year=" + year + "&season=" + season + "&name=" + name;
        url = apiBaseUrl + queryUrl;
        fetchData(url);
        /* console.log('filterFetch(): '+url); */
    }

    function clearDataDiv() {
        dataDiv.innerHTML = "";
    }
});


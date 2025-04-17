document.addEventListener("DOMContentLoaded", (event) => {
    const dataDiv = document.querySelector("#data");
    const formFilter = document.querySelector('#form-filter');
    let page = 1;
    let lastPage = undefined;
    const nameInput = document.querySelector('#input-name');
    const apiBaseUrl = formFilter.dataset.apiUrl;
    const inputName = document.querySelector('#input-name');
    const selectYear = document.querySelector('#select-year');
    const selectSeason = document.querySelector('#select-season');
    const loaderDiv = document.querySelector('#loader');


    fetchData(apiBaseUrl);
    let currentUrl = apiBaseUrl;
    //console.log('first fetch: ' + apiBaseUrl);


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
        let year_id = document.querySelector('#select-year').value;
        let season_id = document.querySelector('#select-season').value;
        let name = nameInput.value;
        filterFetch(year_id, season_id, name);
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
        /* inputName.disabled = true; */
        selectYear.disabled = true;
        selectSeason.disabled = true;
        try {
            // Realizar la petición GET
            const response = await fetch(url, {
                method: formFilter.method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                lastPage = 0;
                //console.log(`Error HTTP: ${response.status}`);
                return;
            }

            if (response.status === 204 /* No Content */ || !response.body) {
                throw new Error('Respuesta vacía del servidor');
            }

            const data = await response.json(); // Directamente
            //console.log(data);

            if (!data.html || data.html === "") {
                lastPage = 0;
                //console.log("No data received from backend");
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
        let newUrl = new URL(currentUrl);
        newUrl.searchParams.set('page', page)
        currentUrl = newUrl.toString();
        fetchData(currentUrl);

    }

    function filterFetch(year_id, season_id, name) {
        clearDataDiv();
        let newUrl = new URL(apiBaseUrl);
        page = 1;
        newUrl.searchParams.set('year_id', year_id);
        newUrl.searchParams.set('season_id', season_id);
        newUrl.searchParams.set('name', name);

        currentUrl = newUrl.toString();

        fetchData(currentUrl);

        console.log('filterFetch(): ' + currentUrl);
    }

    function clearDataDiv() {
        dataDiv.innerHTML = "";
    }
});


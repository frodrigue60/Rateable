document.addEventListener("DOMContentLoaded", (event) => {
    const dataDiv = document.querySelector("#data");
    const formFilter = document.querySelector('#form-filter');
    let page = 1;
    let lastPage = undefined;
    const nameInput = document.querySelector('#input-name');
    const apiBaseUrl = formFilter.dataset.apiUrl;
    const loaderDiv = document.querySelector('#loader');

    fetchData(apiBaseUrl);
    let currentUrl = apiBaseUrl;
   /*  console.log(apiBaseUrl); */

    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    const handleFilterChange = debounce(() => {
        let type = document.querySelector('#select-type').value;
        let year_id = document.querySelector('#select-year').value;
        let season_id = document.querySelector('#select-season').value;
        let sort = document.querySelector('#select-sort').value;
        let name = nameInput.value;

        filterFetch(type, year_id, season_id, sort, name);
    });

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
        try {
            loaderDiv.style.removeProperty("display");
            const response = await fetch(url, {
                method: formFilter.method,
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
                return;
            }

            lastPage = data.lastPage;
            
            dataDiv.innerHTML += data.html;

            let titles = document.querySelectorAll('.post-titles');

            function cutTitles() {
                titles.forEach(title => {
                    if (title.textContent.length > 25) {
                        title.textContent = title.textContent.substr(0, 25) + "...";
                    }
                });
            }
            cutTitles();

        } catch (error) {
            console.error("Error in fetchData:", error);
        } finally {
            //console.log('completed cycle');
            loaderDiv.style.setProperty("display", "none", "important");
        }
    }

    function loadMoreData(page) {
        let newUrl = new URL(currentUrl);
        newUrl.searchParams.set('page', page)
        currentUrl = newUrl.toString();
        fetchData(currentUrl);
    }

    function filterFetch(type, year_id, season_id, sort, name) {
        clearDataDiv();
        let newUrl = new URL(apiBaseUrl);
        page = 1;
        newUrl.searchParams.set('type', type);
        newUrl.searchParams.set('year_id', year_id);
        newUrl.searchParams.set('season_id', season_id);
        newUrl.searchParams.set('sort', sort);
        newUrl.searchParams.set('name', name);

        currentUrl = newUrl.toString();

        fetchData(currentUrl);
    }

    function clearDataDiv() {
        dataDiv.innerHTML = "";
    }
});


document.addEventListener("DOMContentLoaded", (event) => {
    const dataDiv = document.querySelector("#data");
    const formFilter = document.querySelector('#form-filter');
    let page = 1;
    let lastPage = undefined;
    const nameInput = document.querySelector('#input-name');
    let loaderDiv = document.querySelector('#loader');
    const artistId = document.querySelector('#artist_id').value;
    const baseUrl = document.querySelector('meta[name="base-url"]').content;
    let apiBaseUrl = baseUrl + '/api/artists/' + artistId + '/themes';
    let currentUrl = apiBaseUrl;

    firstFetch();

    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    const handleFilterChange = debounce(() => {
        let type = document.querySelector('#select-type').value;
        let year = document.querySelector('#select-year').value;
        let season = document.querySelector('#select-season').value;
        let sort = document.querySelector('#select-sort').value;
        let name = nameInput.value;

        filterFetch(type, year, season, sort, name);
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
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            if (!response.ok) {
                lastPage = 0;
                console.log(response.status);
                return;
            }

            const data = await response.json();
            
            if (!data.html || data.html === "") {
                /* console.log("No views received from backend"); */
                return;
            } else {
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
                loaderDiv.style.setProperty("display", "none", "important");
            }
        } catch (error) {
            /* console.error(error) */
        } finally {
            loaderDiv.style.setProperty("display", "none", "important");
        }
    }

    function loadMoreData(page) {
        let newUrl = new URL(currentUrl);

        newUrl.searchParams.set('page', page)
        currentUrl = newUrl.toString();

        fetchData(currentUrl);
    }

    function firstFetch() {
        fetchData(apiBaseUrl);
    }

    function filterFetch(type, year, season, sort, name) {
        let newUrl = new URL(apiBaseUrl);
        page = 1;
        clearDataDiv();
        newUrl.searchParams.set('type', type);
        newUrl.searchParams.set('year', year);
        newUrl.searchParams.set('season', season);
        newUrl.searchParams.set('sort', sort);
        newUrl.searchParams.set('name', name);

        currentUrl = newUrl.toString();

        fetchData(currentUrl);
    }
    function clearDataDiv() {
        dataDiv.innerHTML = "";
    }
});


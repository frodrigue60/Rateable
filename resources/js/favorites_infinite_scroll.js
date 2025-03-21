document.addEventListener("DOMContentLoaded", (event) => {
    const dataDiv = document.querySelector("#data");
    const formFilter = document.querySelector('#form-filter');
    let pageName = undefined;
    let page = 1;
    let lastPage = undefined;
    let url = undefined;
    const baseUrl = window.location.href;
    const nameInput = document.querySelector('#input-name');


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

        filterFetch(type, year, season, sort, nameInput.value);
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

    function fetchData(url) {
        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(response => {
                if (!response.ok) {
                    lastPage = 0;
                    //console.log(response.status);
                    return;
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data.html === "") {
                    lastPage = 0;
                    //console.log("No data from backend");
                    return;
                } else {
                    //console.log(data);
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
                }
            })
            .catch(error => console.error(error));
    }

    function loadMoreData(page) {
        let currentUrl = window.location.href;
        let urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
            urlParams.has('name')) {
            pageName = "&page=";
        } else {
            pageName = "?page=";
        }

        url = currentUrl + pageName + page;
        //console.log("fetch loadMoreData(): " + url);
        fetchData(url);
    }

    function firstFetch() {
        fetchData(baseUrl);
    }

    function filterFetch(type, year, season, sort, name) {
        page = 1;
        clearDataDiv();
        let queryUrl = "?" + "type=" + type + "&year=" + year + "&season=" + season + "&sort=" + sort+ "&name=" + name;
        url = baseUrl + queryUrl;
        history.replaceState(null, null, url);
        fetchData(url);
    }
    function clearDataDiv() {
        dataDiv.innerHTML = "";
    }
});


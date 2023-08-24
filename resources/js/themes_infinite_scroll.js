document.addEventListener("DOMContentLoaded", (event) => {
    const dataDiv = document.querySelector("#data");
    const formFilter = document.querySelector('#form-filter');
    let pageName = undefined;
    let page = 1;
    let lastPage = undefined;
    let url = undefined;
    const baseUrl = window.location.href;

    firstFetch();

    formFilter.addEventListener('change', function (event) {
        let type = document.querySelector('#select-type').value;
        let year = document.querySelector('#select-year').value;
        let season = document.querySelector('#select-season').value;
        let sort = document.querySelector('#select-sort').value;
        let character = document.querySelector('#select-char').value;

        filterFetch(type,year, season,sort, character);
    });

    window.addEventListener("scroll", function () {
        if (window.scrollY + window.innerHeight + 300 >= document.documentElement.scrollHeight) {
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

        if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
            urlParams.has('char')) {
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

    function filterFetch(type,year, season,sort, character) {
        let currentUrl = new URL(window.location.href);
        page = 1;
        clearDataDiv();
        currentUrl.searchParams.set('type', type);
        currentUrl.searchParams.set('year', year);
        currentUrl.searchParams.set('season', season);
        currentUrl.searchParams.set('sort', sort);
        currentUrl.searchParams.set('character', character);
        //let queryUrl ="?"+"type="+type+"&year="+year+"&season="+season+"&sort="+sort+"&char="+character;
        let newUrl = currentUrl.toString();
        //url = baseUrl + queryUrl;

        history.pushState(null, null, newUrl);
        
        fetchData(newUrl);
    }
    function clearDataDiv() {
        dataDiv.innerHTML = "";
    }
});


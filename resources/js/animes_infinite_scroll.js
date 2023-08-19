document.body.onload = function () {
    let currentUrl = window.location.href;
    let pageName = undefined;
    let page = 1;
    let lastPage = undefined;
    let dataDiv = document.querySelector("#data");
    let url = null;

    let urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('tag') || urlParams.has('char')) {
        pageName = "&page=";
    } else {
        pageName = "?page=";
    }

    url = currentUrl + pageName + page;
    //console.log("fetch to: " + url);

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
                //console.log("response status: "+response.status);
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
                //console.log("data html: "+data);
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
        
        function loadMoreData(page) {
            let urlParams = new URLSearchParams(window.location.search);
        
            if (urlParams.has('filterBy') || urlParams.has('type') || urlParams.has('tag') || urlParams.has('sort') ||
                urlParams.has('char')) {
                pageName = "&page=";
            } else {
                pageName = "?page=";
            }
        
            url = currentUrl + pageName + page;
            //console.log("fetch to: " + url);
        
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
}


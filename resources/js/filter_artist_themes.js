import API from '@api/index.js';
const dataDiv = document.querySelector("#data");
const formFilter = document.querySelector('#form-filter');
let page = 1;
let lastPage = undefined;
const inputName = document.querySelector('#input-name');
let loaderDiv = document.querySelector('#loader');
const artistId = document.querySelector('#artist_id').value;
let params = Object.fromEntries(new FormData(formFilter));
let headersData = {};

fetchData();

function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

const handleFilterChange = debounce(() => {
    page = 1
    clearDataDiv();
    fetchData();
});

inputName.addEventListener('input', debounce(handleFilterChange));

const otherFilterElements = formFilter.querySelectorAll(
    'select, input[type="checkbox"], input[type="radio"]'
);

otherFilterElements.forEach(el => {
    el.addEventListener('change', handleFilterChange);
});

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

async function fetchData() {
    try {
        loaderDiv.style.removeProperty("display");

        headersData = {
            'Accept': 'application/json, text/html;q=0.9'
        }

        params.page = page;
        //console.log(params);

        const response = await API.get(API.ARTISTS.SONGS(artistId), headersData, params);
        //console.log(response);

        if (!response.html || response.html === "") {
            console.log("No views received from backend");
            return;
        } else {
            lastPage = response.songs.last_page;
            dataDiv.innerHTML += response.html;
        }
    } catch (error) {
        lastPage = 0;
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        loaderDiv.style.setProperty("display", "none", "important");
    }
}

function clearDataDiv() {
    dataDiv.innerHTML = "";
}



import API from '@api/index.js';
const dataDiv = document.querySelector("#data");
const formFilter = document.querySelector('#form-filter');
let page = 1;
let lastPage = undefined;
const inputName = formFilter.querySelector('#input-name');
let loaderDiv = document.querySelector('#loader');
let params = {};
let headersData = {};

fetchData();

function debounce(func, timeout = 250) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

const handleFilterChange = debounce(() => {
    page = 1;
    clearDataDiv();
    fetchData();
});

inputName.addEventListener('input', debounce(handleFilterChange));

window.addEventListener("scroll", function () {
    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {
        if (lastPage == undefined) {
            page++;
            fetchData();
        } else {
            if (page <= lastPage) {
                page++;
                fetchData();
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

        params = {
            page: page,
            name: inputName.value
        };

        const response = await API.get(API.ARTISTS.FILTER, headersData, params);

        if (!response.html || response.html === "") {
            console.log("No views received from backend");
            return;
        } else {
            lastPage = response.artists.last_page;
            dataDiv.innerHTML += response.html;

            loaderDiv.style.setProperty("display", "none", "important");
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



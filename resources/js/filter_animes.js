import API from '@api/index.js';

const dataDiv = document.querySelector("#data");
let page = 1;
let lastPage = undefined;
const inputName = document.querySelector('#input-name');
const selectYear = document.querySelector('#select-year');
const selectSeason = document.querySelector('#select-season');
const loaderDiv = document.querySelector('#loader');
const formFilter = document.querySelector('#form-filter');

let params = Object.fromEntries(new FormData(formFilter));
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
    loaderDiv.style.removeProperty("display");
    selectYear.disabled = true;
    selectSeason.disabled = true;

    try {
        headersData = {
            'Accept': 'application/json, text/html;q=0.9'
        }

        params.page = page;

        console.log(params);


        const response = await API.get(API.POSTS.ANIMES, headersData, params);

        if (!response.html || response.html === "") {
            lastPage = 0;
            //console.log("No data received from backend");
            return;
        }

        lastPage = response.posts.last_page;
        dataDiv.innerHTML += response.html;

    } catch (error) {
        lastPage = 0;
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        selectYear.disabled = false;
        selectSeason.disabled = false;
        loaderDiv.style.setProperty("display", "none", "important");
    }
}

function clearDataDiv() {
    dataDiv.innerHTML = "";
}

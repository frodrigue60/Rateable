import API from '@api/index.js';
const dataDiv = document.querySelector("#data");
const formFilter = document.querySelector('#form-filter');
let page = 1;
let lastPage = undefined;
const inputName = formFilter.querySelector('#input-name');
const selectType = formFilter.querySelector('#select-type');
const selectYear = formFilter.querySelector('#select-year');
const selectSeason = formFilter.querySelector('#select-season');
const selectSort = formFilter.querySelector('#select-sort');

let loaderDiv = document.querySelector('#loader');
const studioId = formFilter.querySelector('#studio-id').value;
let params = {};
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
            name: inputName.value,
            type: selectType.value,
            year_id: selectYear.value,
            season_id: selectSeason.value,
            sort: selectSort.value,
            page: page
        };

        const response = await API.get(API.STUDIOS.SONGS(studioId), headersData, params);

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



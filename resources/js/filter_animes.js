import API from '@api/index.js';

const dataDiv = document.querySelector("#data");
let page = 1;
let lastPage = undefined;
const formFilter = document.querySelector('#form-filter');
const inputName = formFilter.querySelector('#input-name');
const selectYear = formFilter.querySelector('#select-year');
const selectSeason = formFilter.querySelector('#select-season');
const selectFormat = formFilter.querySelector('#select-format');
const loaderDiv = document.querySelector('#loader');

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

        params = {
            page: page,
            season_id: selectSeason.value,
            year_id: selectYear.value,
            format_id: selectFormat.value,
            name: inputName.value
        }

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

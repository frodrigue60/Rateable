import { API, csrfToken, token } from '@/app.js';

const dataDiv = document.querySelector("#data");

const formFilter = document.querySelector('#form-filter');
let inputName = formFilter.querySelector('#input-name');
let selectType = formFilter.querySelector('#select-type');
let selectYear = formFilter.querySelector('#select-year');
let selectSeason = formFilter.querySelector('#select-season');
let selectSort = formFilter.querySelector('#select-sort');


let page = 1;
let lastPage = undefined;
const loaderDiv = document.querySelector('#loader');
let headersData = {};
let bodyData = {};

fetchData();

function debounce(func, timeout = 300) {
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
    try {
        loaderDiv.style.removeProperty("display");

        headersData = {
            "Content-Type": "application/json",
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        }

        bodyData = JSON.stringify({
            "name": inputName.value,
            "type": selectType.value,
            "year_id": selectYear.value,
            "season_id": selectSeason.value,
            "sort": selectSort.value,
            "page": page
        });

        const response = await API.post(API.USERS.FAVORITES, headersData, bodyData);

        if (!response.html || response.html === "") {
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

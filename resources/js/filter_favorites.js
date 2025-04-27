import API from '@api/index.js';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const apiToken = localStorage.getItem('api_token');
const dataDiv = document.querySelector("#data");
const formFilter = document.querySelector('#form-filter');
let page = 1;
let lastPage = undefined;
const inputName = document.querySelector('#input-name');
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
            'Authorization': 'Bearer ' + apiToken,
        }

        bodyData = Object.fromEntries(new FormData(formFilter));
        bodyData.page = page;

        const response = await API.post(API.USERS.FAVORITES, headersData, bodyData);

        if (!response.html || response.html === "") {
            return;
        }
        lastPage = response.songs.last_page;
        dataDiv.innerHTML = response.html;
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

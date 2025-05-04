import { API, token } from '@/app.js';

let rankingType = '0'; //0 = GLOBAL, 1 = SEASONAL
const sectionHeader = document.getElementById('section-header');
const toggleBtn = document.getElementById('toggle-type-btn');
const containerOps = document.getElementById('container-ops');
const containerEds = document.getElementById('container-eds');

const openingsSection = document.getElementById('openings-section');
const endingsSection = document.getElementById('endings-section');

const loaderOps = document.getElementById('loader-ops');
const loaderEds = document.getElementById('loader-eds');

const paginatorBtnsOps = document.querySelectorAll('.load-more-op');
const paginatorBtnsEds = document.querySelectorAll('.load-more-ed');

let params = {};
let headersData = {};
let page_openings = 1;
let page_endings = 1;
let last_page_openings = undefined;
let last_page_endings = undefined;

getOpenings();
getEndings();

paginatorBtnsOps.forEach(btn => {
    btn.addEventListener('click', function () {
        if (last_page_openings == undefined) {
            page_openings++;
            getOpenings();
        } else {
            if (page_openings <= last_page_openings) {
                page_openings++;
                getOpenings();
            }
        }
    });
});

paginatorBtnsEds.forEach(btn => {
    btn.addEventListener('click', function () {

        if (last_page_endings == undefined) {
            page_endings++;
            getEndings();
        } else {
            if (page_endings <= last_page_endings) {
                page_endings++;
                getEndings();
            }
        }
    });
});

async function getOpenings() {

    loaderOps.style.removeProperty("display");

    paginatorBtnsOps.forEach(btn => {
        btn.setAttribute('disabled', '');
    });

    toggleBtn.setAttribute('disabled', '');

    try {

        params = {
            "ranking_type": rankingType,
            "page_op": page_openings,
            "page_ed": page_endings
        }

        headersData = {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json, text/html;q=0.9'
        }

        const response = await API.get(API.SONGS.RANKING, headersData, params);

        if (!response.html) {
            throw new Error('html: Invalid data structure');
        }
        page_openings = response.openings.current_page;

        last_page_openings = response.openings.last_page;

        renderData(containerOps, response.html.openings);

        updateHeader(rankingType);

        if (rankingType == 0) {
            sectionHeader.textContent = 'Top Openings & Endings Of All Time';
            document.querySelector('#toggle-type-btn-span').textContent = 'Seasonal';
        } else {
            let season = response.currentSeason;
            let year = response.currentYear;
            sectionHeader.textContent = 'Top Openings & Endings ' + season.name + ' ' + year.name;
            document.querySelector('#toggle-type-btn-span').textContent = 'Global';
        }

    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        toggleBtn.removeAttribute('disabled');

        paginatorBtnsOps.forEach(btn => {
            btn.removeAttribute('disabled');
        });

        loaderOps.style.setProperty("display", "none", "important");
    }
}

async function getEndings() {

    loaderEds.style.removeProperty("display");

    paginatorBtnsEds.forEach(btn => {
        btn.setAttribute('disabled', '');
    });

    try {

        params = {
            "ranking_type": rankingType,
            "page_op": page_openings,
            "page_ed": page_endings
        }

        headersData = {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json, text/html;q=0.9'
        }

        const response = await API.get(API.SONGS.RANKING, headersData, params);

        if (!response.html) {
            throw new Error('html: Invalid data structure');
        }
        page_endings = response.endings.current_page;

        last_page_endings = response.endings.last_page;

        renderData(containerEds, response.html.endings);



    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        paginatorBtnsEds.forEach(btn => {
            btn.removeAttribute('disabled');
        });

        loaderEds.style.setProperty("display", "none", "important");
    }
}

function updateHeader(rankingType) {
    sectionHeader.textContent = rankingType === '0' ?
        'Top Openings & Endings Of All Time' :
        'Seasonal Ranking Openings & Endings';
}

function renderData(container, html) {
    container.innerHTML += html;
}

toggleBtn.addEventListener('click', () => {
    rankingType = rankingType === '0' ? '1' : '0';
    containerOps.innerHTML = '';
    containerEds.innerHTML = '';
    page_openings = 1;
    page_endings = 1;
    getOpenings();
    getEndings();
});

document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active');
        });

        button.classList.add('active');

        const tabName = button.dataset.tab;

        if (tabName == 'endings') {
            openingsSection.classList.add('d-none','d-md-block');
            endingsSection.classList.remove('d-none','d-md-block');
        } else {
            openingsSection.classList.remove('d-none','d-md-block');
            endingsSection.classList.add('d-none','d-md-block');
        }
    });
});


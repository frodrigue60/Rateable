import API from '@api/index.js';
const token = localStorage.getItem('api_token');
const contentContainer = document.getElementById('data');
const sectionHeader = document.getElementById('section-header');
const toggleBtn = document.getElementById('toggle-type-btn');
let currentType = 'OP';
let params = {};
let headersData = {};

fetchData(currentType);

async function fetchData(type) {

    try {
        toggleBtn.disabled = true;

        params = {
            type: currentType
        };

        headersData = {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json, text/html;q=0.9'
        };

        const response = await API.get(API.SONGS.SEASONAL, headersData, params);

        if (!response.html) {
            throw new Error('html: Invalid data structure');
        }

        renderData(response.html);

        updateHeader(type);

    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        toggleBtn.disabled = false;
    }
}

function updateHeader(type) {
    sectionHeader.textContent = type === 'OP' ?
        'OPENINGS' :
        'ENDINGS';
    document.querySelector('#btn-toggle-text').textContent = type === 'OP' ?
        'Endings' :
        'Openings';
}

function renderData(html) {
    contentContainer.innerHTML = html;
}

toggleBtn.addEventListener('click', () => {
    currentType = currentType === 'OP' ? 'ED' : 'OP';
    fetchData(currentType);
});

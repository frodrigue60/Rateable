import API from '@api/index.js';
const token = localStorage.getItem('api_token');

let rankingType = '0'; //0 = GLOBAL, 1 = SEASONAL
const sectionHeader = document.getElementById('section-header');
const toggleBtn = document.getElementById('toggle-type-btn');
const containerOps = document.getElementById('container-ops');
const containerEds = document.getElementById('container-eds');

const loaderOps = document.getElementById('loader-ops');
const loaderEds = document.getElementById('loader-eds');
let params = {};
let headersData = {};

fetchData();

async function fetchData() {
    loaderOps.style.removeProperty("display");
    loaderEds.style.removeProperty("display");
    containerOps.innerHTML = '';
    containerEds.innerHTML = '';

    try {
        toggleBtn.disabled = true;

        params = {
            ranking_type: rankingType
        }

        headersData = {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json, text/html;q=0.9'
        }

        const response = await API.get(API.SONGS.RANKING, headersData, params);

        if (!response.html) {
            throw new Error('html: Invalid data structure');
        }

        //console.log(response);

        renderData(response);

        if (rankingType == 0) {
            sectionHeader.textContent = 'Top Openings & Endings Of All Time';
            document.querySelector('#toggle-type-btn-span').textContent = 'Seasonal';
        } else {
            let season = response.currentSeason;
            let year = response.currentYear;
            sectionHeader.textContent = 'Top Openings & Endings ' + season.name + ' ' + year.name;
            document.querySelector('#toggle-type-btn-span').textContent = 'Global';
        }
        updateHeader(rankingType);
        //console.log(data);

        if (window.innerWidth <= 640) {
            let temp1 = document.querySelector(
                '.openings-column .openings-list').innerHTML;
            document.querySelector('.mobile-view .openings-list').innerHTML =
                temp1;

            let temp2 = document.querySelector(
                '.endings-column .endings-list').innerHTML;
            document.querySelector('.mobile-view .endings-list').innerHTML =
                temp2;

        }

    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        toggleBtn.disabled = false;
        loaderOps.style.setProperty("display", "none", "important");
        loaderEds.style.setProperty("display", "none", "important");
    }
}

function updateHeader(rankingType) {
    sectionHeader.textContent = rankingType === '0' ?
        'Top Openings & Endings Of All Time' :
        'Seasonal Ranking Openings & Endings';
}

function renderData(response) {
    //console.log(data);
    containerOps.innerHTML = response.html.openings;
    containerEds.innerHTML = response.html.endings;
}

toggleBtn.addEventListener('click', () => {
    rankingType = rankingType === '0' ? '1' : '0';

    fetchData();
});

document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        // Remover clase active de todos los botones y contenidos
        document.querySelectorAll('.tab-button, .tab-content').forEach(el => {
            el.classList.remove('active');
        });

        // Activar el botón y contenido clickeado
        button.classList.add('active');
        const tabName = button.dataset.tab;
        document.querySelector(`.tab-content[data-tab="${tabName}"]`).classList.add(
            'active');

        // Opcional: Si usas un framework como Laravel, puedes generar el contenido una vez
        const openingsContent = document.querySelector(
            '.openings-column .openings-list').innerHTML;
        const endingsContent = document.querySelector('.endings-column .endings-list')
            .innerHTML;

        document.querySelector('.mobile-view .openings-list').innerHTML =
            openingsContent;
        document.querySelector('.mobile-view .endings-list').innerHTML = endingsContent;
    });
});

// 1. Configuración de breakpoints y funciones asociadas
const breakpoints = {
    sm: 640, // Tailwind-style
    md: 768,
    lg: 1024
};

// 2. Elementos del DOM
const cleanupElements = document.querySelectorAll('.top-list');
const triggerButton = document.getElementById('openings-tab-btn');

// 3. Estado previo para comparación
let previousWidth = window.innerWidth;
let currentBreakpoint = getCurrentBreakpoint(window.innerWidth);

// 4. Función para determinar el breakpoint actual
function getCurrentBreakpoint(width) {
    if (width < breakpoints.sm) return 'xs';
    if (width < breakpoints.md) return 'sm';
    if (width < breakpoints.lg) return 'md';
    return 'lg';
}

// 5. Callback principal
function handleResize(entries) {
    const entry = entries[0];
    const newWidth = entry.contentRect.width || window.innerWidth;
    const newBreakpoint = getCurrentBreakpoint(newWidth);

    if (newBreakpoint === 'sm') {
        triggerButton?.click();

        currentBreakpoint = newBreakpoint;
    }
    previousWidth = newWidth;
}

const resizeObserver = new ResizeObserver(handleResize);

resizeObserver.observe(document.body);

const btnToggleTheme = document.getElementById('themeToggle');
const _themeIcon = document.getElementById('themeIcon');
const htmlElement = document.documentElement;

let lightThemeIcon = document.createElement("i");
lightThemeIcon.classList.add('fa-solid', 'fa-sun');
let darkThemeIcon = document.createElement("i");
darkThemeIcon.classList.add('fa-solid', 'fa-moon');

console.log(lightThemeIcon);


const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

htmlElement.setAttribute('data-bs-theme', savedTheme);
updateIcon(savedTheme);

btnToggleTheme.addEventListener('click', function () {
    const currentTheme = htmlElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    htmlElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateIcon(newTheme);
});

function updateIcon(theme) {
    btnToggleTheme.innerHTML = '';
    btnToggleTheme.appendChild(theme === 'dark' ? darkThemeIcon : lightThemeIcon);
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem('theme')) {
        const newTheme = e.matches ? 'dark' : 'light';
        htmlElement.setAttribute('data-bs-theme', newTheme);
        updateIcon(newTheme);
    }
});

import { API, csrfToken } from '@/app.js';

const formSearch = document.querySelector('#form-search');
const modalSearch = document.querySelector('#modal-search');
const postsDiv = document.querySelector("#posts");
const artistsDiv = document.querySelector("#artists");
const usersDiv = document.querySelector("#users");
const input = formSearch.querySelector('#searchInputModal');
//const loaderContainer = document.querySelector('.loader-container');
//const siteBody = document.querySelector('body');
const modalBody = document.querySelector('#modalBody');
const resDiv = document.querySelector('.res');
let headersData = {};
let params = {};
const baseUrl = formSearch.dataset.urlBase;

let typingTimer;
const delay = 250;

//loaderContainer.style.display = 'none';
//siteBody.removeAttribute('hidden');
nullValueInput();

modalSearch.addEventListener('shown.bs.modal', function () {
    input.focus();
    input.addEventListener('input', () => {
        resetDivs();
        insertLoader();

        if (input.value.length >= 1) {
            setTimeout(function(){
                apiSearch();
            },300)
        } else {
            resetDivs();
            nullValueInput();
        }

    })

    async function apiSearch() {
        try {
            headersData = {
                'Accept': 'application/json, text/html;q=0.9',
                'X-CSRF-TOKEN': csrfToken,
            }

            let q = input.value;

            const response = await API.get(API.POSTS.SEARCH(q), headersData, params);

            //return console.log(response);

            resetDivs();

            response.posts.forEach(post => {
                let url = baseUrl + "/anime/" + post.slug;

                let resultDiv = document.createElement('div');
                resultDiv.classList.add('result', 'text-truncate');

                let a = document.createElement('a');
                a.href = url;
                a.textContent = post.title;

                resultDiv.appendChild(a);
                postsDiv.appendChild(resultDiv);
            });

            response.artists.forEach(artist => {
                let url = baseUrl + "/artists/" + artist.slug;

                let resultDiv = document.createElement('div');
                resultDiv.classList.add('result', 'text-truncate');

                let a = document.createElement('a');
                a.href = url;
                a.textContent = artist.name;

                resultDiv.appendChild(a);
                artistsDiv.appendChild(resultDiv);
            });

            response.users.forEach(user => {

                let url = baseUrl + "/users/" + user.slug;

                let resultDiv = document.createElement('div');
                resultDiv.classList.add('result', 'text-truncate');

                let a = document.createElement('a');
                a.href = url;
                a.textContent = user.name;

                resultDiv.appendChild(a);
                usersDiv.appendChild(resultDiv);
            });

            resDiv.classList.remove('hidden');

        } catch (error) {
            throw new Error(error);
        }
    }
});
function resetDivs() {
    postsDiv.innerHTML = "";
    artistsDiv.innerHTML = "";
    usersDiv.innerHTML = "";
}
function nullValueInput() {
    postsDiv.appendChild(createResultDiv('posts'));
    artistsDiv.appendChild(createResultDiv('artists'));
    usersDiv.appendChild(createResultDiv('users'));
}

function createResultDiv(element_id) {
    let div = document.createElement('div');
    div.className = 'result';
    div.id = element_id; // Puedes asignar un id si es necesario

    let span = document.createElement('span');
    span.textContent = 'Nothing';

    div.appendChild(span);

    return div;
}
function createLoadingElement() {
    const div = document.createElement('div');
    div.className = 'd-flex justify-content-center';

    const spinnerDiv = document.createElement('div');
    spinnerDiv.className = 'spinner-border';
    spinnerDiv.setAttribute('role', 'status');

    const visuallyHiddenSpan = document.createElement('span');
    visuallyHiddenSpan.className = 'visually-hidden';
    visuallyHiddenSpan.textContent = 'Loading...';

    spinnerDiv.appendChild(visuallyHiddenSpan);
    div.appendChild(spinnerDiv);

    return div;
}
function insertLoader() {
    postsDiv.appendChild(createLoadingElement());
    artistsDiv.appendChild(createLoadingElement());
    usersDiv.appendChild(createLoadingElement());
}

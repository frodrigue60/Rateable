const myModal = document.querySelector('#exampleModal');
const postsDiv = document.querySelector("#posts");
const artistsDiv = document.querySelector("#artists");
const tagsDiv = document.querySelector("#tags");
const usersDiv = document.querySelector("#users");
const input = document.querySelector('#searchInputModal');
const token = document.querySelector('meta[name="csrf-token"]').content;
const titles = document.querySelectorAll('.post-titles');
const loaderContainer = document.querySelector('.loader-container');
const siteBody = document.querySelector('body');
const modalBody = document.querySelector('#modalBody');
const resDiv = document.querySelector('.res');
const siteUrl = 'http://127.0.0.1:8000';

let typingTimer;
const delay = 250;

document.addEventListener("DOMContentLoaded", function () {
    loaderContainer.style.display = 'none';
    siteBody.removeAttribute('hidden');
    nullValueInput();
    cutTitles();

    myModal.addEventListener('shown.bs.modal', function () {
        input.focus();
        input.addEventListener('keyup', () => {
            resetDivs();
            insertLoader();
            clearTimeout(typingTimer);
            if (input.value.length >= 1) {
                typingTimer = setTimeout(apiSearch, 250);
            } else {
                resetDivs();
                nullValueInput();
            }

        })

        function apiSearch() {
            try {
                fetch(siteUrl + '/api/search', {
                    headers: {
                        'X-Request-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    method: "POST",
                    body: JSON.stringify({ q: input.value }),
                }).then(response => {
                    return response.json()
                }).then((data) => {
                    resetDivs();

                    data.posts.forEach(element => {
                        let url = siteUrl + "/anime/" + element.slug;

                        let resultDiv = document.createElement('div');
                        resultDiv.classList.add('result');

                        let a = document.createElement('a');
                        a.href = url;
                        a.textContent = element.title;

                        resultDiv.appendChild(a);
                        postsDiv.appendChild(resultDiv);
                    });

                    data.artists.forEach(element => {
                        let url = siteUrl + "/artists/"+ element.name_slug;

                        let resultDiv = document.createElement('div');
                        resultDiv.classList.add('result');

                        let a = document.createElement('a');
                        a.href = url;
                        a.textContent = element.name;

                        resultDiv.appendChild(a);
                        artistsDiv.appendChild(resultDiv);
                    });

                    data.users.forEach(element => {

                        let url = siteUrl + "/users/" + element.slug;

                        let resultDiv = document.createElement('div');
                        resultDiv.classList.add('result');

                        let a = document.createElement('a');
                        a.href = url;
                        a.textContent = element.name;

                        resultDiv.appendChild(a);
                        usersDiv.appendChild(resultDiv);
                    });
                    resDiv.classList.remove('hidden');
                });
            } catch (error) {
                //console.log(error)
            }
        }
    });
    function resetDivs() {
        postsDiv.innerHTML = "";
        artistsDiv.innerHTML = "";
        tagsDiv.innerHTML = "";
        usersDiv.innerHTML = "";
    }
    function nullValueInput() {
        postsDiv.appendChild(createResultDiv('posts'));
        artistsDiv.appendChild(createResultDiv('artists'));
        usersDiv.appendChild(createResultDiv('users'));
    }
    function cutTitles() {
        titles.forEach(title => {
            if (title.textContent.length > 25) {
                title.textContent = title.textContent.substr(0, 25) + "...";
            }
        });
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
        tagsDiv.appendChild(createLoadingElement());
        usersDiv.appendChild(createLoadingElement());
    }
});
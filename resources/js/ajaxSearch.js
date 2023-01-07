const myModal = document.getElementById('exampleModal');
const postsDiv = document.querySelector("#posts");
const artistsDiv = document.querySelector("#artists");
const tagsDiv = document.querySelector("#tags");
const input = document.getElementById('searchInputModal');
const token = document.querySelector('meta[name="csrf-token"]').content;
const titles = document.querySelectorAll('.post-titles');

let typingTimer; //timer identifier
let doneTypingInterval = 300; //time in ms (5 seconds)

document.addEventListener("DOMContentLoaded", function () {
    nullValueInput();
    cutTitles();
    myModal.addEventListener('shown.bs.modal', function () {
        input.focus();

        input.addEventListener('keyup', () => {
            postsDiv.innerHTML =
                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            artistsDiv.innerHTML =
                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            tagsDiv.innerHTML =
                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            console.log('input: ' + input.value);

            clearTimeout(typingTimer);
            if (input.value.length >= 1) {
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            } else {
                postsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                    '</span></div>';
                artistsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                    '</span></div>';
                tagsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
                    '</span></div>';
            }

        })

        function doneTyping() {
            try {
                fetch('http://localhost:8000/api/posts/search?q=' + input.value, {
                    headers: {
                        'X-Request-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    method: "get",
                }).then(response => {
                    return response.json()
                }).then((data) => {
                    postsDiv.innerHTML = "";
                    artistsDiv.innerHTML = "";
                    tagsDiv.innerHTML = "";

                    data.posts.forEach(element => {
                        postsDiv.innerHTML +=
                            '<div class="result"><a href="http://127.0.0.1:8000/show/' +
                            element.id + '/' + element.slug + '"><span>' +
                            element
                                .title + '</span></a></div>';
                    });

                    data.artists.forEach(element => {
                        artistsDiv.innerHTML +=
                            '<div class="result"><a href="http://127.0.0.1:8000/artist/' +
                            element.name_slug + '"><span>' + element.name +
                            '</span></a></div>';
                    });

                    data.tags.forEach(element => {
                        tagsDiv.innerHTML +=
                            '<div class="result"><a href="http://127.0.0.1:8000/tag/' +
                            element.slug + '"><span>' + element.name +
                            '</span></a></div>';
                    });
                });
            } catch (error) {
                console.log(error)
            }
        }
    });
    function nullValueInput() {
        postsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
            '</span></div>';
        artistsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
            '</span></div>';
        tagsDiv.innerHTML = '<div class="result" id="posts"><span>' + "Nothing" +
            '</span></div>';
    }
    function cutTitles(){
        titles.forEach(title => {
            if (title.textContent.length > 25) {
              title.textContent = title.textContent.substr(0, 25) + "...";
            }
          });
    }
});
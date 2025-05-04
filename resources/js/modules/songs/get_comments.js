import { token, API, csrfToken } from '@/app.js'

const commentsContainer = document.getElementById('comments-container');
const songId = commentsContainer.dataset.songId;
const loaderComments = document.getElementById('loader-comments');
const loadMoreCommentsBtn = document.getElementById('load-more-comments');
let params = {};
let headersData = {};
let page = 1;
let last_page = undefined;

getComments(songId);

loadMoreCommentsBtn.addEventListener('click', function(){
    if (last_page == undefined) {
        page++;
        getComments();
    } else {
        if (page <= last_page) {
            page++;
            getComments();
        }
    }
})




async function getComments() {
    loaderComments.classList.remove('d-none');
    loadMoreCommentsBtn.setAttribute('disabled','');
    try {
        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            /* 'Authorization': 'Bearer ' + token, */
        }

        params = {
            'page': page
        }

        const response = await API.get(API.SONGS.GETCOMMENTS(songId), headersData, params);
        last_page = response.comments.last_page;

        if (response.html) {
            commentsContainer.innerHTML += response.html;
            //loaderComments.classList.add('d-none');
            loadMoreCommentsBtn.removeAttribute('disabled')
        }
        loaderComments.classList.add('d-none');

    } catch (error) {
        console.log(error);

    } finally {

    }
}


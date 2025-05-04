import { API, csrfToken, token } from '@/app.js';

const commentForm = document.querySelector('#commnent-form');
const commentContainer = document.querySelector('#comments-container');
const songId = document.querySelector('meta[name="song-id"]').content;
const submitBtn = document.querySelector('#submit-comment-btn');
let commentTextarea = document.querySelector('#comment-content');
let headersData = {};
let bodyData = {};

commentForm.addEventListener("submit", function (event) {
    event.preventDefault()
    if (commentTextarea.value != '') {
        makeComment(commentTextarea.value, songId);
    }

});

async function makeComment(commentContent, songId) {
    submitBtn.setAttribute('disabled', '');
    commentTextarea.setAttribute('disabled', '');
    try {
        headersData = {
            'Content-Type': 'application/json',
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        }
        bodyData = JSON.stringify({
            content: commentContent,
            song_id: songId,
        });

        const response = await API.post(API.SONGS.COMMENTS, headersData, bodyData);


        commentTextarea.value = '';
        /* commentContainer.innerHTML += data.comment; */
        commentContainer.insertAdjacentHTML('afterbegin', response.html);
    } catch (error) {
        console.log(error)
    }
    finally {
        setTimeout(function () {
            submitBtn.removeAttribute('disabled');
            commentTextarea.removeAttribute('disabled');
        }, 500)

    }
}



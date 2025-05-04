import { token, API, csrfToken } from '@/app.js'

let headersData = {};
let params = {};

document.body.addEventListener('click', (event) => {
    const button = event.target.closest('button.btn-dislike-comment');

    if (button) {
        likeComment(button.dataset.commentId, button);
    }
});

async function likeComment(commentId, button) {
    button.setAttribute('disabled','');
    try {
        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        }

        const response = await API.get(API.COMMENTS.DISLIKE(commentId), headersData, params);

        if (response.success == true) {
            let likesSpan = document.querySelector(`#likes-span-${commentId}`);
            let dislikesSpan = document.querySelector(`#dislikes-span-${commentId}`);

            likesSpan.textContent = response.likesCount;
            dislikesSpan.textContent = response.dislikesCount;

           /*  swal('Ok!', 'Comment disliked!', 'success', {
                buttons: false,
                timer: 1500,
            }); */
        }
    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        setTimeout(function(){
            button.removeAttribute('disabled');
        },1000)
    }
}

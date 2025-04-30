import { token, API, csrfToken } from '@/app.js'
console.log('like-comment.js loaded');


let headersData = {};
let params = {};

document.body.addEventListener('click', (event) => {
    const button = event.target.closest('button.btn-like-comment');

    if (button) {
        console.log('Click en botón o sus hijos');
        console.log(button.dataset.commentId);
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

        const response = await API.get(API.COMMENTS.LIKE(commentId), headersData, params);
        console.log(response);
        if (response.success == true) {
            let likesSpan = document.querySelector(`#likes-span-${commentId}`);
            let dislikesSpan = document.querySelector(`#dislikes-span-${commentId}`);

            likesSpan.textContent = response.likesCount;
            dislikesSpan.textContent = response.dislikesCount;
            //console.log(likesSpan, '#likes-span-'+commentId);

            /* swal('Ok!', 'Comment liked!', 'success', {
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

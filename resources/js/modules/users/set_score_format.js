import { API, token, csrfToken } from '@/app.js';

const scoreFormatForm = document.querySelector('#score-format-form');
const submitScoreFormatFormBtn = document.querySelector('#score-format-form-btn');
let headersData = {};
let bodyData = {};

scoreFormatForm.addEventListener('submit', function (event) {
    event.preventDefault();
    setScoreFormat();
});

async function setScoreFormat() {
    let form = document.getElementById('score-format-form');
    let formData = new FormData(form);
    submitScoreFormatFormBtn.disabled = true;
    try {

        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        };
        bodyData = formData;

        const response = await API.post(API.USERS.SCORE_FORMAT, headersData, bodyData);

        //return console.log(response);

        if (response.success == true) {
            swal('Nice!', 'Rating format updated!', 'success', {
                timer: 1500,
                buttons: false
            })
        }

    } catch (error) {
        console.error('Error:', error);
    } finally {
        setTimeout(() => {
            submitScoreFormatFormBtn.disabled = false;
        }, 2000);

    }
}

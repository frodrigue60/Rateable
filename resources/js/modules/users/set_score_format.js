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
    submitScoreFormatFormBtn.disabled = true;
    let selectScoreFormat = scoreFormatForm.querySelector('#select-score-format');

    try {
        headersData = {
            "Content-Type": "application/json",
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        };

        bodyData = JSON.stringify({
            "score_format": selectScoreFormat.value,
        });

        const response = await API.post(API.USERS.SCORE_FORMAT, headersData, bodyData);

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

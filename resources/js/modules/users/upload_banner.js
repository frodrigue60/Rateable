import { API, token, csrfToken } from '@/app.js';

const uploadBannerForm = document.querySelector('#upload-banner-form');
const submitBannerFormBtn = document.querySelector('#submit-banner-form-btn');
const bannerDiv = document.querySelector('#banner-image');
let headersData = {};
let bodyData = {};

uploadBannerForm.addEventListener('submit', function (event) {
    event.preventDefault();
    uploadBanner();
});

async function uploadBanner() {
    let form = document.getElementById('upload-banner-form');
    let formData = new FormData(form);
    submitBannerFormBtn.disabled = true;

    try {
        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        }

        bodyData = formData

        const response = await API.post(API.USERS.BANNER, headersData, bodyData);

        if (response.success == true) {
            bannerDiv.style.backgroundImage = "url(" + response.banner_url + ")";
            document.getElementById('banner-file').value = '';

            swal('Nice banner!', 'Upload successfully!', 'success', {
                timer: 1500,
                buttons: false
            })
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {

        setTimeout(() => {
            submitBannerFormBtn.disabled = false;
        }, 2000);
    }
}

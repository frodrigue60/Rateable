import { API, token, csrfToken } from '@/app.js';

const uploadAvatarForm = document.querySelector('#upload-avatar-form');
const submitAvatarFormBtn = document.querySelector('#submit-avatar-form-btn');
const avatarImg = document.querySelector('#avatar-image');
let headersData = {};
let bodyData = {};

uploadAvatarForm.addEventListener('submit', function (event) {
    event.preventDefault();
    uploadAvatar();
});
async function uploadAvatar() {
    let form = document.getElementById('upload-avatar-form');
    let formData = new FormData(form);
    submitAvatarFormBtn.disabled = true;
    try {

        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        }

        bodyData = formData

        const response = await API.post(API.USERS.AVATAR, headersData, bodyData);

        if (response.success == true) {
            avatarImg.src = response.avatar_url;
            document.getElementById('profile-file').value = '';

            swal('Nice avatar!', 'Upload successfully!', 'success', {
                timer: 1500,
                buttons: false
            })
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        setTimeout(() => {
            submitAvatarFormBtn.disabled = false;
        }, 2000);
    }
}

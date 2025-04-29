import { API, csrfToken, token } from '@/app.js';

const favoriteBtn = document.querySelector('#favorite-button');
favoriteBtn.addEventListener("click", toggleFavorite);
let iFavorite = document.querySelector('#i-favorite');
let headersData = {};
let params = {};

async function toggleFavorite() {
    favoriteBtn.setAttribute('disabled', '');
    try {
        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,

        }

        const response = await API.get(API.SONGS.FAVORITE(favoriteBtn.dataset.songid), headersData, params);
        //console.log(data);
        //console.log(iFavorite.classList);

        if (response.favorite == true) {
            iFavorite.classList.replace('fa-regular', 'fa-solid');
            favoriteBtn.classList.replace('btn-primary', 'btn-danger');
            swal('Nice!', 'Added to favorites!', 'success', {
                buttons: false,
                timer: 2000,
            });
        } else {
            swal('Really?', 'Removed from favorites!', 'success', {
                buttons: false,
                timer: 2000,
            });
            iFavorite.classList.replace('fa-solid', 'fa-regular');
            favoriteBtn.classList.replace('btn-danger', 'btn-primary');
        }
    } catch (error) {
        console.log(error)
    } finally {
        setTimeout(function () {
            favoriteBtn.removeAttribute('disabled');
        }, 500);
    }
}

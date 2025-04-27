import API from '@api/index.js';
const videoSourceTag = document.querySelector('#video-source');
const videoContainer = document.querySelector('#video_container');
const buttons = document.querySelectorAll('.btnVersion');
let headersData = {};
let params = {};

const player = new Plyr('#player', {
    autoplay: true
});

const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.attributeName === 'src') {
            refreshPlayer();
        }
    });
});

const sourceElement = document.getElementById('video-source');
observer.observe(sourceElement, {
    attributes: true
});

function refreshPlayer() {
    const videoElement = document.getElementById('player');

    // Pausar y guardar tiempo actual (opcional)
    const currentTime = videoElement.currentTime;
    videoElement.pause();

    // Recargar
    videoElement.load();

    // Restaurar tiempo (si lo deseas)
    videoElement.currentTime = currentTime;

    // Forzar reinicio de Plyr
    player.source = player.source; // Truco para reiniciar

    // Reproducir
    player.play().catch(e => console.log('Auto-play prevented:', e));
}

buttons.forEach(button => {
    button.addEventListener('click', function () {

        getVariantVideo(button.dataset.variantId);

        buttons.forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-pressed', 'false');
        });

        this.classList.add('active');
        this.setAttribute('aria-pressed', 'true');
    });
});

if (buttons.length > 0) {
    buttons[0].click();
    buttons[0].classList.add('active');
    buttons[0].setAttribute('aria-pressed', 'true');
}

async function getVariantVideo(variantId) {
    try {
        const response = await API.get(API.VARIANTS.GETVIDEOS(variantId), headersData, params);

        if (response.video.type == 'file') {
            //console.log('is file');
            videoSourceTag.setAttribute('src', response.video.publicUrl);
        } else {
            //console.log('is embed');
            videoContainer.innerHTML = response.video.embed_code;
        }

    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {
        //console.log('finally');
    }
}

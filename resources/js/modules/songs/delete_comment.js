import { token, API, csrfToken } from '@/app.js'

let headersData = {};

document.body.addEventListener('click', (event) => {
    const button = event.target.closest('.btn-del-comment');

    if (button) {
        console.log('Click en botón o sus hijos');
        console.log(button.dataset.commentId);
        deleteComment(button.dataset.commentId);
    }
});

async function deleteComment(commentId) {

    const commentElement = document.querySelector(`.comment[data-id="${commentId}"]`);

    if (!commentElement) {
        return console.log('no comment');
    };

    try {
        headersData = {
            'Accept': 'application/json, text/html;q=0.9',
            'X-CSRF-TOKEN': csrfToken,
            'Authorization': 'Bearer ' + token,
        }

        const response = await API.delete(API.COMMENTS.DELETE(commentId), headersData);

        if (response.success == true) {
            await deleteCommentWithAnimation(commentElement);
        }

        //console.log(response);

    } catch (error) {
        error.message = `UserService: ${error.message}`;
        throw error;
    } finally {

    }
}

function deleteCommentWithAnimation(commentElement) {
    return new Promise((resolve) => {
        // Configurar transiciones
        commentElement.style.transition = 'all 0.3s ease-out';

        // Guardar estilos originales para resetear si falla
        const originalStyles = {
            height: commentElement.style.height,
            opacity: commentElement.style.opacity,
            margin: commentElement.style.margin,
            padding: commentElement.style.padding,
            overflow: commentElement.style.overflow
        };

        // Iniciar animación
        const startHeight = commentElement.offsetHeight;
        commentElement.style.height = `${startHeight}px`;
        void commentElement.offsetHeight; // Reflow

        commentElement.style.height = '0';
        commentElement.style.opacity = '0';
        commentElement.style.margin = '0';
        commentElement.style.padding = '0';
        commentElement.style.overflow = 'hidden';

        // Manejador para cuando termine la animación
        const handleTransitionEnd = () => {
            commentElement.remove();
            resolve(true);
        };

        // Timeout de respaldo por si transitionend no se dispara
        const fallbackTimeout = setTimeout(() => {
            commentElement.removeEventListener('transitionend', handleTransitionEnd);
            commentElement.remove();
            resolve(true);
        }, 500); // 200ms más que la duración de la transición

        commentElement.addEventListener('transitionend', handleTransitionEnd, { once: true });
    });
}

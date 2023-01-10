let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later.
    deferredPrompt = e;
    // Update UI notify the user they can install the PWA
    showInAppInstallPromotion();
});

installButton.addEventListener('click', async () => {
    // deferredPrompt is a global variable that is set to the captured install event
    deferredPrompt.prompt();
    // Find out whether the user confirmed the installation or not
    const { outcome } = await deferredPrompt.userChoice;
    // The prompt has been used and can't use it again, throw it away
    deferredPrompt = null;
    // Act on the user's choice
    if (outcome === 'accepted') {
        console.log('User accepted the install prompt.');
    } else if (outcome === 'dismissed') {
        console.log('User dismissed the install prompt');
    }
});

window.addEventListener('appinstalled', () => {
    // If visible, hide the install promotion
    hideInAppInstallPromotion();
    // Clear the deferredPrompt so it can be called again
    deferredPrompt = null;
    // Log install to analytics
    console.log('INSTALL: Success');
});

window.addEventListener('DOMContentLoaded', () => {
    let displayMode = 'browser tab';
    if (navigator.standalone) {
        displayMode = 'standalone-ios';
    }
    if (window.matchMedia('(display-mode: standalone)').matches) {
        displayMode = 'standalone';
    }
    // Log launch display mode to analytics
    console.log('DISPLAY_MODE_LAUNCH:', displayMode);
});

window.addEventListener('DOMContentLoaded', () => {
    window.matchMedia('(display-mode: standalone)').addEventListener('change', (e) => {
        let displayMode = 'browser tab';
        if (e.matches) {
            displayMode = 'standalone';
        }
        // Log display mode change to analytics
        console.log('DISPLAY_MODE_CHANGED', displayMode);
    });
});

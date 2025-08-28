console.log("Install script loaded...");

let deferredPrompt;
const installPopup = document.getElementById('installPopup');
const installBtn = document.getElementById('installBtn');
const closePopup = document.getElementById('closePopup');

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    // tampilkan popup dengan animasi
    installPopup.classList.remove('is-hidden');
    setTimeout(() => installPopup.classList.add('show'), 50);

    console.log('beforeinstallprompt event fired');
});

installBtn.addEventListener('click', async () => {
    installPopup.classList.remove('show');
    setTimeout(() => installPopup.classList.add('is-hidden'), 400);

    if (deferredPrompt) {
        deferredPrompt.prompt();
        const choice = await deferredPrompt.userChoice;
        console.log('User choice:', choice.outcome);
        deferredPrompt = null;
    }
});

closePopup.addEventListener('click', () => {
    installPopup.classList.remove('show');
    setTimeout(() => installPopup.classList.add('is-hidden'), 400);
});
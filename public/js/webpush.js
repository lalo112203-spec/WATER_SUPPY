function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function subscribeUser() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/sw.js')
        .then(function(swReg) {
            console.log('Service Worker is registered', swReg);

            // Ask for permission silently if not already granted/denied
            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        subscribeWithWorker(swReg);
                    }
                });
            } else if (Notification.permission === 'granted') {
                subscribeWithWorker(swReg);
            }
        })
        .catch(function(error) {
            console.error('Service Worker Error', error);
        });
    } else {
        console.warn('Push messaging is not supported');
    }
}

function subscribeWithWorker(swReg) {
    const vapidPublicKey = window.Laravel.vapidPublicKey;
    if (!vapidPublicKey) return;

    const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

    swReg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: convertedVapidKey
    })
    .then(function(subscription) {
        // Send subscription to backend
        fetch('/push-subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify(subscription)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Bad status code from server.');
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Successfully subscribed to web push', responseData);
        })
        .catch(function(error) {
            console.error('Failed to send subscription to server', error);
        });
    })
    .catch(function(err) {
        console.log('Failed to subscribe the user: ', err);
    });
}

// Automatically try to subscribe when page loads
window.addEventListener('load', function() {
    subscribeUser();
});

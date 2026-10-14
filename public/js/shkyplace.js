document.addEventListener('DOMContentLoaded', function () {
    initAlerts();
    initPaymentMethods();
    initCartQuantity();
    initMapIfPresent();
    initMobileNav();
});

function initAlerts() {
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
}

function initPaymentMethods() {
    document.querySelectorAll('.payment-method').forEach(function (el) {
        el.addEventListener('click', function () {
            document.querySelectorAll('.payment-method').forEach(function (m) {
                m.classList.remove('selected');
            });
            el.classList.add('selected');
            var input = el.querySelector('input[type=radio]');
            if (input) input.checked = true;
        });
    });
}

function initCartQuantity() {
    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.closest('.input-group').querySelector('input');
            var val = parseInt(input.value, 10) || 1;
            if (btn.dataset.action === 'plus') val++;
            else if (btn.dataset.action === 'minus' && val > 1) val--;
            input.value = val;
        });
    });
}

function initMapIfPresent() {
    var mapEl = document.getElementById('map');
    if (!mapEl || typeof google === 'undefined') return;

    var lat = parseFloat(mapEl.dataset.lat) || 4.0511;
    var lng = parseFloat(mapEl.dataset.lng) || 9.7679;

    var map = new google.maps.Map(mapEl, {
        center: { lat: lat, lng: lng },
        zoom: 13,
        styles: [
            { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
        ]
    });

    var shops = [];
    try {
        shops = JSON.parse(mapEl.dataset.shops || '[]');
    } catch (e) {}

    shops.forEach(function (shop) {
        if (!shop.latitude || !shop.longitude) return;
        new google.maps.Marker({
            position: { lat: parseFloat(shop.latitude), lng: parseFloat(shop.longitude) },
            map: map,
            title: shop.name,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: '#9b59b6',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            }
        });
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            new google.maps.Marker({
                position: { lat: pos.coords.latitude, lng: pos.coords.longitude },
                map: map,
                title: 'Ma position',
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: '#e91e8c',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                }
            });
        });
    }
}

function initMobileNav() {
    var toggler = document.querySelector('.navbar-toggler');
    if (toggler) {
        toggler.addEventListener('click', function () {
            document.querySelector('.navbar-collapse')?.classList.toggle('show');
        });
    }
}

function formatPrice(amount) {
    return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
}

function updateDeliveryTracking(orderId, lat, lng) {
    fetch('/livreur/localisation/' + orderId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
        },
        body: JSON.stringify({ latitude: lat, longitude: lng })
    });
}

if ('geolocation' in navigator) {
    setInterval(function () {
        var trackEl = document.getElementById('live-tracking');
        if (!trackEl) return;
        navigator.geolocation.getCurrentPosition(function (pos) {
            updateDeliveryTracking(trackEl.dataset.orderId, pos.coords.latitude, pos.coords.longitude);
        });
    }, 30000);
}

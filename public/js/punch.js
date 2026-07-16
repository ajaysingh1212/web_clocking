document.addEventListener('DOMContentLoaded', function () {
    const clockDateEl = document.getElementById('clock-date');
    const clockTimeEl = document.getElementById('clock-time');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locationInput = document.getElementById('location');
    const statusEl = document.getElementById('location-status');
    const refreshBtn = document.getElementById('refresh-location');
    const imageInput = document.getElementById('image');
    const photoPreview = document.getElementById('photo-preview');

    function setStatus(message, type) {
        if (!statusEl) return;
        statusEl.className = 'location-status' + (type ? ' ' + type : '');
        statusEl.innerHTML = '<i class="bi bi-geo-alt"></i> ' + message;
    }

    // Live running clock — display only, not submitted with the form
    function startLiveClock() {
        function tick() {
            const now = new Date();

            if (clockDateEl) {
                clockDateEl.textContent = now.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                });
            }
            if (clockTimeEl) {
                clockTimeEl.textContent = now.toLocaleTimeString('en-GB');
            }
        }

        tick();
        setInterval(tick, 1000);
    }

    // Build a clean, accurate address string from Nominatim's structured
    // address components instead of relying on display_name, which can
    // pull a mismatched / stale postcode from a wider boundary.
    function buildAddress(address) {
        if (!address) return null;

        const line1 = [
            address.house_number,
            address.road || address.pedestrian || address.footway,
        ].filter(Boolean).join(' ');

        const locality = address.suburb || address.neighbourhood || address.quarter;

        const city = address.city || address.town || address.village || address.county;

        const parts = [
            line1,
            locality,
            city,
            address.state,
            address.postcode,
        ].filter(Boolean);

        return parts.length ? parts.join(', ') : null;
    }

    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                { headers: { Accept: 'application/json' } }
            );

            if (!response.ok) {
                throw new Error('Reverse geocode request failed');
            }

            const data = await response.json();

            const structured = buildAddress(data && data.address);

            return structured || (data && data.display_name) || `${lat}, ${lng}`;
        } catch (error) {
            console.error('[punch] reverse geocode failed:', error);
            return `${lat}, ${lng}`;
        }
    }

    async function fetchLocation() {
        if (!window.isSecureContext) {
            setStatus('Location needs HTTPS. This page is loaded over HTTP, so the browser blocks it.', 'error');
            console.error('[punch] Blocked: not a secure context (window.isSecureContext is false). Serve the site over HTTPS.');
            return;
        }

        if (!navigator.geolocation) {
            setStatus('Geolocation is not supported on this device.', 'error');
            console.error('[punch] navigator.geolocation is undefined.');
            return;
        }

        if (navigator.permissions && navigator.permissions.query) {
            try {
                const permStatus = await navigator.permissions.query({ name: 'geolocation' });
                console.log('[punch] geolocation permission state:', permStatus.state);

                if (permStatus.state === 'denied') {
                    setStatus('Location permission is blocked for this site. Open your browser site settings and allow Location, then tap refresh.', 'error');
                    return;
                }
            } catch (e) {
                console.warn('[punch] permissions.query not supported, continuing anyway.', e);
            }
        }

        setStatus('Requesting location permission…');

        navigator.geolocation.getCurrentPosition(
            async function (position) {
                console.log('[punch] position received:', position.coords.latitude, position.coords.longitude);
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;

                setStatus('Fetching address…');

                const address = await reverseGeocode(lat, lng);

                if (locationInput) locationInput.value = address;

                setStatus('Location captured.', 'success');
            },
            function (error) {
                let message = 'Unable to fetch location.';

                if (error.code === error.PERMISSION_DENIED) {
                    message = 'Location permission denied. Please allow location access in your browser settings and try again.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    message = 'Location information is unavailable.';
                } else if (error.code === error.TIMEOUT) {
                    message = 'Location request timed out. Tap refresh to try again.';
                }

                console.error('[punch] geolocation error:', error.code, error.message);
                setStatus(message, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            }
        );
    }

    if (imageInput && photoPreview) {
        imageInput.addEventListener('change', function () {
            const file = imageInput.files && imageInput.files[0];

            if (file) {
                photoPreview.src = URL.createObjectURL(file);
            }
        });
    }

    if (refreshBtn) {
        refreshBtn.addEventListener('click', fetchLocation);
    }

    startLiveClock();
    fetchLocation();
});
window.initManualMapControls = function () {
    const mapBtn = document.getElementById('map-location');
    const mapModal = document.getElementById('manualMapModal');
    const mapContainer = document.getElementById('manual-map-container');
    const applyMapLocationBtn = document.getElementById('apply-map-location');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locationInput = document.getElementById('location');
    const locationBox = document.querySelector('.location-box');
    const manualLocationReadout = document.getElementById('manual-location-readout');
    const manualLatReadout = document.getElementById('manual-lat-readout');
    const manualLngReadout = document.getElementById('manual-lng-readout');

    if (!mapBtn || !mapModal || !mapContainer || !latInput || !lngInput || !locationInput || !window.google || !window.google.maps) {
        return;
    }

    if (window.manualMapControlsInitialized) {
        return;
    }

    const branchLat = parseFloat(locationBox && locationBox.dataset.branchLat || 0);
    const branchLng = parseFloat(locationBox && locationBox.dataset.branchLng || 0);

    const currentLat = parseFloat(latInput.value || branchLat || 28.6139);
    const currentLng = parseFloat(lngInput.value || branchLng || 77.2090);

    const initialCenter = {
        lat: Number.isFinite(currentLat) ? currentLat : 28.6139,
        lng: Number.isFinite(currentLng) ? currentLng : 77.2090,
    };

    const map = new google.maps.Map(mapContainer, {
        center: initialCenter,
        zoom: 15,
        mapTypeControl: false,
        fullscreenControl: true,
    });

    const marker = new google.maps.Marker({
        position: initialCenter,
        map,
        draggable: true,
        title: 'Choose location',
    });

    const geocoder = new google.maps.Geocoder();

    function syncManualReadout() {
        if (manualLocationReadout) {
            manualLocationReadout.textContent = locationInput.value || '--';
        }

        if (manualLatReadout) {
            manualLatReadout.textContent = latInput.value || '--';
        }

        if (manualLngReadout) {
            manualLngReadout.textContent = lngInput.value || '--';
        }
    }

    const statusEl = document.getElementById('location-status');
    const distanceEl = document.getElementById('location-distance');

    function getDistanceInMeters(lat1, lon1, lat2, lon2) {
        const earthRadius = 6371000;
        const lat1Rad = lat1 * Math.PI / 180;
        const lat2Rad = lat2 * Math.PI / 180;
        const deltaLat = (lat2 - lat1) * Math.PI / 180;
        const deltaLon = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2)
            + Math.cos(lat1Rad) * Math.cos(lat2Rad)
            * Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return earthRadius * c;
    }

    function updateLocationDistance(lat, lng) {
        if (!distanceEl || !statusEl) {
            return;
        }

        const branchLat = parseFloat(locationBox && locationBox.dataset.branchLat);
        const branchLng = parseFloat(locationBox && locationBox.dataset.branchLng);
        const radiusLimit = parseInt(locationBox && locationBox.dataset.radius, 10) || 0;

        if (!branchLat || !branchLng || radiusLimit <= 0) {
            distanceEl.style.display = 'none';
            return;
        }

        const distance = getDistanceInMeters(lat, lng, branchLat, branchLng);
        const roundedDistance = Math.round(distance);

        distanceEl.style.display = 'block';
        if (roundedDistance <= radiusLimit) {
            distanceEl.className = 'location-distance success';
            distanceEl.textContent = `Attendance allowed: within ${radiusLimit} m (${roundedDistance} m from branch).`;
            statusEl.className = 'location-status success';
            statusEl.innerHTML = '<i class="bi bi-geo-alt"></i> Selected location is within allowed radius.';
        } else {
            distanceEl.className = 'location-distance error';
            distanceEl.textContent = `Attendance not allowed: outside ${radiusLimit} m (${roundedDistance} m from branch).`;
            statusEl.className = 'location-status error';
            statusEl.innerHTML = '<i class="bi bi-geo-alt"></i> Selected location is outside the allowed radius.';
        }
    }

    function setLocationFromLatLng(latLng) {
        const lat = latLng.lat();
        const lng = latLng.lng();

        latInput.value = Number(lat).toFixed(6);
        lngInput.value = Number(lng).toFixed(6);

        geocoder.geocode({ location: { lat, lng } }, function (results, status) {
            if (status === 'OK' && results && results[0]) {
                locationInput.value = results[0].formatted_address;
            } else {
                locationInput.value = `${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
            }

            syncManualReadout();
            updateLocationDistance(lat, lng);
        });
    }

    mapBtn.addEventListener('click', function () {
        const currentLat = parseFloat(latInput.value || branchLat || 28.6139);
        const currentLng = parseFloat(lngInput.value || branchLng || 77.2090);
        const center = { lat: Number.isFinite(currentLat) ? currentLat : 28.6139, lng: Number.isFinite(currentLng) ? currentLng : 77.2090 };

        map.setCenter(center);
        marker.setPosition(center);
        google.maps.event.trigger(map, 'resize');
        mapModal.classList.add('show');
    });

    map.addListener('click', function (event) {
        marker.setPosition(event.latLng);
        map.panTo(event.latLng);
        setLocationFromLatLng(event.latLng);
    });

    marker.addListener('dragend', function () {
        const location = marker.getPosition();
        map.panTo(location);
        setLocationFromLatLng(location);
    });

    applyMapLocationBtn.addEventListener('click', function () {
        const position = marker.getPosition();
        const lat = position.lat();
        const lng = position.lng();

        latInput.value = Number(lat).toFixed(6);
        lngInput.value = Number(lng).toFixed(6);
        locationInput.value = locationInput.value || `${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
        syncManualReadout();

        mapModal.classList.remove('show');
    });

    const closeMapModalBtn = document.getElementById('closeMapModal');
    const refreshMapLocationBtn = document.getElementById('refreshMapLocation');

    if (closeMapModalBtn) {
        closeMapModalBtn.addEventListener('click', function () {
            mapModal.classList.remove('show');
        });
    }

    if (refreshMapLocationBtn) {
        refreshMapLocationBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const center = { lat, lng };

                map.setCenter(center);
                marker.setPosition(center);

                geocoder.geocode({ location: center }, function (results, status) {
                    if (status === 'OK' && results && results[0]) {
                        locationInput.value = results[0].formatted_address;
                    } else {
                        locationInput.value = `${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
                    }

                    syncManualReadout();
                });

                latInput.value = Number(lat).toFixed(6);
                lngInput.value = Number(lng).toFixed(6);
                syncManualReadout();
            }, function () {
                if (locationInput) {
                    locationInput.value = 'Unable to fetch current location.';
                    syncManualReadout();
                }
            }, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            });
        });
    }

    window.manualMapControlsInitialized = true;
};

document.addEventListener('DOMContentLoaded', function () {
    const clockDateEl = document.getElementById('clock-date');
    const clockTimeEl = document.getElementById('clock-time');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locationInput = document.getElementById('location');
    const statusEl = document.getElementById('location-status');
    const distanceEl = document.getElementById('location-distance');
    const locationBox = document.querySelector('.location-box');
    const refreshBtn = document.getElementById('refresh-location');
    const imageInput = document.getElementById('image');
    const photoPreview = document.getElementById('photo-preview');

    function setStatus(message, type) {
        if (!statusEl) return;
        statusEl.className = 'location-status' + (type ? ' ' + type : '');
        statusEl.innerHTML = '<i class="bi bi-geo-alt"></i> ' + message;
    }

    function setDistanceStatus(message, type) {
        if (!distanceEl) return;
        distanceEl.className = 'location-distance' + (type ? ' ' + type : '');
        distanceEl.textContent = message;
        distanceEl.style.display = 'block';
    }

    function getDistanceInMeters(lat1, lon1, lat2, lon2) {
        const earthRadius = 6371000;
        const lat1Rad = lat1 * Math.PI / 180;
        const lat2Rad = lat2 * Math.PI / 180;
        const deltaLat = (lat2 - lat1) * Math.PI / 180;
        const deltaLon = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2)
            + Math.cos(lat1Rad) * Math.cos(lat2Rad)
            * Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return earthRadius * c;
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

    // Build a clean, accurate address string using Nominatim's structured
    // address data so the punch entry includes the area, locality, district,
    // state, and postcode in a more complete and stable format.
    function buildAddress(address) {
        if (!address) return null;

        const line1 = [
            address.house_number,
            address.road || address.pedestrian || address.footway || address.path,
        ].filter(Boolean).join(' ');

        const area = [
            address.suburb,
            address.neighbourhood,
            address.quarter,
            address.hamlet,
            address.village,
            address.town,
            address.city,
            address.municipality,
        ].filter(Boolean).filter((value, index, list) => list.indexOf(value) === index);

        const city = address.city || address.town || address.village || address.county || address.municipality;
        const district = address.state_district || address.county;
        const state = address.state;
        const postcode = address.postcode;
        const country = address.country;

        const parts = [
            line1,
            ...area,
            city,
            district,
            state,
            postcode,
            country,
        ].filter(Boolean).filter((value, index, list) => list.indexOf(value) === index);

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

                const branchLat = parseFloat(locationBox && locationBox.dataset.branchLat);
                const branchLng = parseFloat(locationBox && locationBox.dataset.branchLng);
                const radiusLimit = parseInt(locationBox && locationBox.dataset.radius, 10) || 0;

                if (branchLat && branchLng && radiusLimit > 0) {
                    const distance = getDistanceInMeters(parseFloat(lat), parseFloat(lng), branchLat, branchLng);
                    const roundedDistance = Math.round(distance);

                    if (roundedDistance <= radiusLimit) {
                        setDistanceStatus(`Attendance allowed: you are within the ${radiusLimit} m radius (${roundedDistance} m from branch).`, 'success');
                    } else {
                        setDistanceStatus(`Attendance not allowed: you are outside the ${radiusLimit} m radius (${roundedDistance} m from branch).`, 'error');
                    }
                }

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

    if (window.google && window.google.maps) {
        window.initManualMapControls();
    }
});
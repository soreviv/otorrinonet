// Cookie Banner Management
// OtorrinoNet - Dr. Alejandro Viveros Domínguez

(function() {
    'use strict';

    const COOKIE_NAME = 'otorrinonet_cookie_consent';
    const COOKIE_EXPIRY_DAYS = 365;

    document.addEventListener('DOMContentLoaded', function() {
        initCookieBanner();
    });

    /**
     * Inicializar el banner de cookies
     */
    function initCookieBanner() {
        const cookieBanner = document.getElementById('cookie-banner');
        const cookieModal = document.getElementById('cookie-modal');
        
        if (!cookieBanner) return;

        // Verificar si el usuario ya aceptó las cookies
        if (!getCookie(COOKIE_NAME)) {
            // Mostrar banner después de 1 segundo
            setTimeout(() => {
                cookieBanner.classList.remove('hidden');
            }, 1000);
        }

        // Event listeners para los botones
        document.getElementById('accept-cookies')?.addEventListener('click', acceptAllCookies);
        document.getElementById('reject-cookies')?.addEventListener('click', rejectNonEssentialCookies);
        document.getElementById('customize-cookies')?.addEventListener('click', openCustomizeModal);
        document.getElementById('close-modal')?.addEventListener('click', closeCustomizeModal);
        document.getElementById('save-preferences')?.addEventListener('click', saveCustomPreferences);

        // Cerrar modal al hacer clic fuera
        cookieModal?.addEventListener('click', function(e) {
            if (e.target === cookieModal) {
                closeCustomizeModal();
            }
        });
    }

    /**
     * Aceptar todas las cookies
     */
    function acceptAllCookies() {
        const preferences = {
            necessary: true,
            functional: true,
            analytics: true,
            timestamp: new Date().toISOString()
        };

        setCookie(COOKIE_NAME, JSON.stringify(preferences), COOKIE_EXPIRY_DAYS);
        hideBanner();
        loadOptionalScripts(preferences);
        
        // Mostrar notificación
        if (window.OtorrinoNet && window.OtorrinoNet.showToast) {
            window.OtorrinoNet.showToast('Preferencias de cookies guardadas', 'success');
        }
    }

    /**
     * Rechazar cookies no esenciales
     */
    function rejectNonEssentialCookies() {
        const preferences = {
            necessary: true,
            functional: false,
            analytics: false,
            timestamp: new Date().toISOString()
        };

        setCookie(COOKIE_NAME, JSON.stringify(preferences), COOKIE_EXPIRY_DAYS);
        hideBanner();
        
        // Mostrar notificación
        if (window.OtorrinoNet && window.OtorrinoNet.showToast) {
            window.OtorrinoNet.showToast('Solo cookies necesarias activadas', 'info');
        }
    }

    /**
     * Abrir modal de personalización
     */
    function openCustomizeModal() {
        const modal = document.getElementById('cookie-modal');
        const banner = document.getElementById('cookie-banner');
        
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('body-no-scroll');
        }
        
        if (banner) {
            banner.classList.add('hidden');
        }

        // Cargar preferencias guardadas si existen
        const savedPreferences = getCookiePreferences();
        if (savedPreferences) {
            document.getElementById('functional-cookies').checked = savedPreferences.functional;
            document.getElementById('analytics-cookies').checked = savedPreferences.analytics;
        }
    }

    /**
     * Cerrar modal de personalización
     */
    function closeCustomizeModal() {
        const modal = document.getElementById('cookie-modal');
        const banner = document.getElementById('cookie-banner');
        
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('body-no-scroll');
        }
        
        // Si no hay preferencias guardadas, volver a mostrar el banner
        if (!getCookie(COOKIE_NAME) && banner) {
            banner.classList.remove('hidden');
        }
    }

    /**
     * Guardar preferencias personalizadas
     */
    function saveCustomPreferences() {
        const preferences = {
            necessary: true,
            functional: document.getElementById('functional-cookies')?.checked || false,
            analytics: document.getElementById('analytics-cookies')?.checked || false,
            timestamp: new Date().toISOString()
        };

        setCookie(COOKIE_NAME, JSON.stringify(preferences), COOKIE_EXPIRY_DAYS);
        closeCustomizeModal();
        loadOptionalScripts(preferences);
        
        // Mostrar notificación
        if (window.OtorrinoNet && window.OtorrinoNet.showToast) {
            window.OtorrinoNet.showToast('Preferencias guardadas exitosamente', 'success');
        }
    }

    /**
     * Ocultar el banner
     */
    function hideBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.classList.add('slide-down-animation');
            setTimeout(() => {
                banner.classList.add('hidden');
            }, 300);
        }
    }

    /**
     * Cargar scripts opcionales según preferencias
     */
    function loadOptionalScripts(preferences) {
        // Google Analytics
        if (preferences.analytics) {
            loadGoogleAnalytics();
        }

        // Scripts de funcionalidad
        if (preferences.functional) {
            loadFunctionalScripts();
        }
    }

    /**
     * Cargar Google Analytics
     */
    function loadGoogleAnalytics() {
        // Placeholder para Google Analytics
        // Reemplazar con tu ID de Google Analytics real
        const GA_ID = 'G-XXXXXXXXXX';
        
        if (window.gtag) {
            console.log('Google Analytics ya está cargado');
            return;
        }

        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${GA_ID}`;
        document.head.appendChild(script);

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', GA_ID);
        
        window.gtag = gtag;
        
        console.log('Google Analytics cargado');
    }

    /**
     * Cargar scripts de funcionalidad
     */
    function loadFunctionalScripts() {
        console.log('Scripts de funcionalidad cargados');
        // Aquí puedes cargar scripts adicionales de funcionalidad
    }

    /**
     * Obtener preferencias de cookies guardadas
     */
    function getCookiePreferences() {
        const cookie = getCookie(COOKIE_NAME);
        if (cookie) {
            try {
                return JSON.parse(cookie);
            } catch (e) {
                console.error('Error parsing cookie preferences:', e);
                return null;
            }
        }
        return null;
    }

    /**
     * Establecer una cookie
     */
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
    }

    /**
     * Obtener una cookie
     */
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    /**
     * Eliminar una cookie
     */
    function deleteCookie(name) {
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;";
    }

    // Exponer funciones públicas
    window.CookieConsent = {
        openPreferences: openCustomizeModal,
        getPreferences: getCookiePreferences,
        resetPreferences: function() {
            deleteCookie(COOKIE_NAME);
            location.reload();
        }
    };

})();

// Note: animation rules moved to external CSS (public/assets/css/css/styles.css) to comply with CSP

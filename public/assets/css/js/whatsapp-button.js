// WhatsApp Button Management
// OtorrinoNet - Dr. Alejandro Viveros Domínguez

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initWhatsAppButton();
    });

    /**
     * Inicializar el botón de WhatsApp
     */
    function initWhatsAppButton() {
        const whatsappBtn = document.querySelector('.whatsapp-float');
        
        if (!whatsappBtn) {
            console.warn('Botón de WhatsApp no encontrado en el DOM');
            return;
        }

        // Mostrar/ocultar según scroll
        handleScrollVisibility(whatsappBtn);

        // Analytics al hacer clic
        trackWhatsAppClick(whatsappBtn);

        // Efecto de hover mejorado
        enhanceHoverEffect(whatsappBtn);
    }

    /**
     * Manejar visibilidad según scroll
     */
    function handleScrollVisibility(button) {
        let lastScroll = 0;
        let ticking = false;

        window.addEventListener('scroll', function() {
            lastScroll = window.pageYOffset;

            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateButtonVisibility(button, lastScroll);
                    ticking = false;
                });

                ticking = true;
            }
        });

        // Verificar visibilidad inicial
        updateButtonVisibility(button, window.pageYOffset);
    }

    /**
     * Actualizar visibilidad del botón
     */
    function updateButtonVisibility(button, scrollPosition) {
        // Mostrar después de 300px de scroll
        if (scrollPosition > 300) {
            button.style.opacity = '1';
            button.style.visibility = 'visible';
            button.style.transform = 'scale(1)';
        } else {
            button.style.opacity = '0';
            button.style.visibility = 'hidden';
            button.style.transform = 'scale(0.8)';
        }
    }

    /**
     * Rastrear clics en WhatsApp
     */
    function trackWhatsAppClick(button) {
        button.addEventListener('click', function(e) {
            // Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'whatsapp_click', {
                    'event_category': 'engagement',
                    'event_label': 'WhatsApp Button',
                    'value': 1
                });
            }

            // Console log para debug
            console.log('WhatsApp button clicked');

            // Vibración en móviles (si está disponible)
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }

            // Mostrar notificación opcional
            if (window.OtorrinoNet && window.OtorrinoNet.showToast) {
                window.OtorrinoNet.showToast('Abriendo WhatsApp...', 'info');
            }
        });
    }

    /**
     * Mejorar efecto de hover
     */
    function enhanceHoverEffect(button) {
        let hoverTimeout;

        button.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            this.style.transform = 'scale(1.1) translateY(-5px)';
        });

        button.addEventListener('mouseleave', function() {
            hoverTimeout = setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
    }

    /**
     * Obtener mensaje personalizado según la página
     */
    function getContextualMessage() {
        const path = window.location.pathname;
        const messages = {
            '/agendar-cita.php': 'Hola, necesito ayuda para agendar una cita',
            '/servicios.php': 'Hola, me gustaría información sobre sus servicios',
            '/contacto.php': 'Hola, necesito contactar al consultorio',
            'default': 'Hola, me gustaría agendar una cita con el Dr. Alejandro Viveros Domínguez'
        };

        return messages[path] || messages['default'];
    }

    /**
     * Abrir WhatsApp con mensaje personalizado
     */
    function openWhatsApp(phoneNumber, customMessage) {
        const message = customMessage || getContextualMessage();
        const encodedMessage = encodeURIComponent(message);
        const url = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
        
        window.open(url, '_blank', 'noopener,noreferrer');
    }

    // Exponer funciones públicas
    window.WhatsAppButton = {
        open: function(phoneNumber, message) {
            openWhatsApp(phoneNumber, message);
        },
        getMessage: getContextualMessage
    };

})();

// Estilos adicionales para animaciones suaves
const style = document.createElement('style');
style.textContent = `
    .whatsapp-float {
        transition: opacity 0.3s ease, 
                    visibility 0.3s ease, 
                    transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @media (prefers-reduced-motion: reduce) {
        .whatsapp-float {
            transition: none;
        }
    }
`;
document.head.appendChild(style);

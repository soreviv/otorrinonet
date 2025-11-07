// Main JavaScript file for OtorrinoNet
// Author: Dr. Alejandro Viveros Domínguez
// Version: 1.0.0

(function() {
    'use strict';

    // Esperar a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initSmoothScroll();
        initFormValidation();
        initWhatsAppButton();
        initBackToTop();
        initAutoSubmitSelects();
    });

    /**
     * Inicializar menú móvil (para futura implementación)
     */
    function initMobileMenu() {
        // Placeholder para menú hamburguesa en móviles
        console.log('Mobile menu initialized');
    }

    /**
     * Inicializar scroll suave para enlaces internos
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // Ignorar enlaces vacíos
                if (href === '#') return;
                
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Validación de formularios en tiempo real
     */
    function initFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Validación de email
            const emailInputs = form.querySelectorAll('input[type="email"]');
            emailInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateEmail(this);
                });
            });

            // Validación de teléfono
            const telInputs = form.querySelectorAll('input[type="tel"]');
            telInputs.forEach(input => {
                input.addEventListener('input', function() {
                    formatPhone(this);
                });
                
                input.addEventListener('blur', function() {
                    validatePhone(this);
                });
            });

            // Validación de campos requeridos
            const requiredInputs = form.querySelectorAll('[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateRequired(this);
                });
            });
        });
    }

    /**
     * Validar email
     */
    function validateEmail(input) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const value = input.value.trim();
        
        if (value && !emailRegex.test(value)) {
            showError(input, 'Por favor, ingresa un email válido');
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    /**
     * Formatear teléfono mientras se escribe
     */
    function formatPhone(input) {
        let value = input.value.replace(/\D/g, '');
        
        // Limitar a 10 dígitos
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
        
        // Formatear: 55 1234-5678
        if (value.length > 6) {
            value = value.slice(0, 2) + ' ' + value.slice(2, 6) + '-' + value.slice(6);
        } else if (value.length > 2) {
            value = value.slice(0, 2) + ' ' + value.slice(2);
        }
        
        input.value = value;
    }

    /**
     * Validar teléfono
     */
    function validatePhone(input) {
        const value = input.value.replace(/\D/g, '');
        
        if (value && value.length !== 10) {
            showError(input, 'El teléfono debe tener 10 dígitos');
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    /**
     * Validar campo requerido
     */
    function validateRequired(input) {
        const value = input.value.trim();
        
        if (!value) {
            showError(input, 'Este campo es obligatorio');
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    /**
     * Mostrar error en campo
     */
    function showError(input, message) {
        clearError(input);
        
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-300');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-600 text-sm mt-1 error-message';
        errorDiv.textContent = message;
        
        input.parentNode.appendChild(errorDiv);
    }

    /**
     * Limpiar error de campo
     */
    function clearError(input) {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
        
        const errorMessage = input.parentNode.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }

    /**
     * Inicializar funcionalidad del botón de WhatsApp
     */
    function initWhatsAppButton() {
        const whatsappBtn = document.querySelector('.whatsapp-float');
        
        if (whatsappBtn) {
            // Mostrar/ocultar según scroll
            let lastScroll = 0;
            
            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > 300) {
                    whatsappBtn.style.opacity = '1';
                    whatsappBtn.style.visibility = 'visible';
                } else {
                    whatsappBtn.style.opacity = '0';
                    whatsappBtn.style.visibility = 'hidden';
                }
                
                lastScroll = currentScroll;
            });
        }
    }

    /**
     * Inicializar botón "Volver arriba"
     */
    function initBackToTop() {
        // Crear botón si no existe
        if (!document.getElementById('back-to-top')) {
            const btn = document.createElement('button');
            btn.id = 'back-to-top';
            btn.className = 'back-to-top';
            btn.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            `;
            btn.setAttribute('aria-label', 'Volver arriba');
            document.body.appendChild(btn);
        }

        const backToTopBtn = document.getElementById('back-to-top');
        
        // Mostrar/ocultar según scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 500) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        // Scroll suave al hacer clic
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Inicializar selects que deben enviar el formulario al cambiar
     */
    function initAutoSubmitSelects() {
        const selects = document.querySelectorAll('select.js-autosubmit');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                if (this.form) this.form.submit();
            });
        });
    }

    /**
     * Utilidades generales
     */
    window.OtorrinoNet = {
        /**
         * Mostrar notificación toast
         */
        showToast: function(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        },

        /**
         * Formatear fecha a español
         */
        formatDate: function(dateStr) {
            const date = new Date(dateStr);
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            return date.toLocaleDateString('es-MX', options);
        },

        /**
         * Validar fecha futura
         */
        isFutureDate: function(dateStr) {
            const date = new Date(dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return date >= today;
        }
    };

})();

// Styles moved to external CSS (public/assets/css/css/styles.css) to comply with CSP
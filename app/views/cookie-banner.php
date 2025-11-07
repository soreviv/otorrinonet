<!-- Cookie Consent Banner -->
<div id="cookie-banner" class="cookie-banner hidden">
    <div class="cookie-banner-content">
        <div class="cookie-banner-text">
            <h3 class="cookie-banner-title">🍪 Uso de Cookies</h3>
            <p class="cookie-banner-description">
                Utilizamos cookies para mejorar tu experiencia en nuestro sitio web. 
                Al continuar navegando, aceptas nuestra 
                <a href="/politica-cookies" class="cookie-link">Política de Cookies</a> y 
                <a href="/aviso-privacidad" class="cookie-link">Aviso de Privacidad</a>.
            </p>
        </div>
        <div class="cookie-banner-buttons">
            <button id="accept-cookies" class="cookie-btn cookie-btn-accept">
                Aceptar Todas
            </button>
            <button id="reject-cookies" class="cookie-btn cookie-btn-reject">
                Solo Necesarias
            </button>
            <button id="customize-cookies" class="cookie-btn cookie-btn-customize">
                Personalizar
            </button>
        </div>
    </div>
</div>

<!-- Cookie Customization Modal -->
<div id="cookie-modal" class="cookie-modal hidden">
    <div class="cookie-modal-content">
        <div class="cookie-modal-header">
            <h2 class="cookie-modal-title">Preferencias de Cookies</h2>
            <button id="close-modal" class="cookie-modal-close">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="cookie-modal-body">
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div>
                        <h3 class="cookie-category-title">Cookies Necesarias</h3>
                        <p class="cookie-category-description">
                            Estas cookies son esenciales para el funcionamiento del sitio web y no pueden ser desactivadas.
                        </p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" checked disabled>
                        <span class="cookie-slider"></span>
                    </label>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div>
                        <h3 class="cookie-category-title">Cookies de Funcionalidad</h3>
                        <p class="cookie-category-description">
                            Permiten recordar tus preferencias y mejorar tu experiencia de navegación.
                        </p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" id="functional-cookies" checked>
                        <span class="cookie-slider"></span>
                    </label>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div>
                        <h3 class="cookie-category-title">Cookies de Análisis</h3>
                        <p class="cookie-category-description">
                            Nos ayudan a entender cómo los visitantes interactúan con nuestro sitio web.
                        </p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" id="analytics-cookies" checked>
                        <span class="cookie-slider"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="cookie-modal-footer">
            <button id="save-preferences" class="cookie-btn cookie-btn-accept">
                Guardar Preferencias
            </button>
        </div>
    </div>
</div>

<style nonce="<?= defined('CSP_NONCE') ? CSP_NONCE : '' ?>">
/* Cookie Banner Styles */
.cookie-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    padding: 1.5rem;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    z-index: 9999;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.cookie-banner.hidden {
    display: none;
}

.cookie-banner-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

.cookie-banner-text {
    flex: 1;
    min-width: 300px;
}

.cookie-banner-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.cookie-banner-description {
    font-size: 0.95rem;
    opacity: 0.95;
}

.cookie-link {
    color: #fbbf24;
    text-decoration: underline;
    font-weight: 500;
}

.cookie-link:hover {
    color: #fcd34d;
}

.cookie-banner-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.cookie-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.95rem;
}

.cookie-btn-accept {
    background: white;
    color: #1e40af;
}

.cookie-btn-accept:hover {
    background: #f3f4f6;
    transform: translateY(-2px);
}

.cookie-btn-reject {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.cookie-btn-reject:hover {
    background: rgba(255, 255, 255, 0.1);
}

.cookie-btn-customize {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.cookie-btn-customize:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Cookie Modal Styles */
.cookie-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 1rem;
}

.cookie-modal.hidden {
    display: none;
}

.cookie-modal-content {
    background: white;
    border-radius: 1rem;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.cookie-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.cookie-modal-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
}

.cookie-modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.cookie-modal-close:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.cookie-modal-body {
    padding: 1.5rem;
}

.cookie-category {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.5rem;
}

.cookie-category-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.cookie-category-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.cookie-category-description {
    font-size: 0.9rem;
    color: #6b7280;
}

.cookie-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 30px;
    flex-shrink: 0;
}

.cookie-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.cookie-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 30px;
}

.cookie-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .cookie-slider {
    background-color: #3b82f6;
}

input:checked + .cookie-slider:before {
    transform: translateX(30px);
}

input:disabled + .cookie-slider {
    opacity: 0.5;
    cursor: not-allowed;
}

.cookie-modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    text-align: right;
}

@media (max-width: 768px) {
    .cookie-banner-content {
        flex-direction: column;
        align-items: stretch;
    }

    .cookie-banner-buttons {
        flex-direction: column;
    }

    .cookie-btn {
        width: 100%;
    }
}
</style>
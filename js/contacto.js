document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contact-form');
    const statusMessageContainer = document.getElementById('form-status-message');

    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Mostrar un mensaje de "enviando"
            statusMessageContainer.style.display = 'block';
            statusMessageContainer.className = 'confirmation-box info'; // Clase para estilo de "cargando"
            statusMessageContainer.innerHTML = '<p>Enviando su mensaje...</p>';

            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData.entries());

            // Validar que el token de hCaptcha no esté vacío
            if (!data['h-captcha-response']) {
                showStatusMessage('Por favor, complete la verificación de hCaptcha.', 'error');
                return;
            }

            try {
                const response = await fetch('/api/contacto', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.error || 'Ocurrió un error desconocido.');
                }

                // Éxito
                showStatusMessage('¡Gracias por su mensaje! Nos pondremos en contacto con usted pronto.', 'success');
                contactForm.reset(); // Limpiar el formulario
                // Asegurarse de que hCaptcha también se resetee si la librería lo soporta
                if (typeof hcaptcha !== 'undefined') {
                    hcaptcha.reset();
                }

            } catch (error) {
                console.error('Error al enviar el formulario:', error);
                showStatusMessage(`Error: ${error.message}`, 'error');
            }
        });
    }

    function showStatusMessage(message, type) {
        statusMessageContainer.style.display = 'block';
        // Asignar clase según el tipo de mensaje (para estilos CSS)
        statusMessageContainer.className = `confirmation-box ${type}`; // success, error, info
        statusMessageContainer.innerHTML = `<p>${message}</p>`;
    }
});
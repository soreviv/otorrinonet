<?php
// Obtener el número de WhatsApp desde las variables de entorno
$whatsappNumber = $_ENV['WHATSAPP_PHONE_NUMBER'] ?? '525512345678';
$whatsappMessage = urlencode('Hola, me gustaría agendar una cita con el Dr. Alejandro Viveros Domínguez');
?>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=<?php echo $whatsappMessage; ?>" 
   class="whatsapp-float" 
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Contactar por WhatsApp">
    <svg viewBox="0 0 32 32" class="whatsapp-icon">
        <path fill="currentColor" d="M16 0C7.164 0 0 7.164 0 16c0 2.824.737 5.487 2.023 7.778L0 32l8.422-2.022C10.714 31.263 13.277 32 16 32c8.836 0 16-7.164 16-16S24.836 0 16 0zm0 29.333c-2.578 0-5.024-.735-7.111-2l-.4-.244-4.133.978.978-4.044-.267-.422C3.735 21.024 3 18.578 3 16 3 8.82 8.82 3 16 3s13 5.82 13 13-5.82 13.333-13 13.333z"/>
        <path fill="currentColor" d="M23.024 19.511c-.378-.189-2.244-1.111-2.594-1.236-.35-.125-.605-.189-.86.189-.256.378-.992 1.236-1.217 1.489-.222.256-.444.289-.822.1-.378-.189-1.594-.589-3.036-1.878-1.122-.978-1.878-2.189-2.1-2.567-.222-.378-.024-.583.167-.772.167-.167.378-.444.567-.667.189-.222.256-.378.378-.633.125-.256.067-.478-.033-.667-.1-.189-.86-2.067-1.178-2.831-.311-.744-.622-.644-.86-.656-.222-.011-.478-.011-.733-.011s-.667.1-.878.478c-.311.378-1.189 1.167-1.189 2.844 0 1.678 1.217 3.3 1.389 3.522.167.222 2.389 3.644 5.778 5.111.806.35 1.433.556 1.922.711.806.256 1.544.222 2.122.133.644-.1 2.244-.922 2.561-1.811.311-.889.311-1.656.222-1.811-.089-.156-.344-.267-.722-.456z"/>
    </svg>
    <span class="whatsapp-pulse"></span>
</a>

document.addEventListener('DOMContentLoaded', function() {
    const cookieBanner = document.getElementById('cookieBanner');
    const acceptCookies = document.getElementById('acceptCookies');
    const declineCookies = document.getElementById('declineCookies');
    
    if (cookieBanner && acceptCookies && declineCookies) {
        // Show cookie banner after 2 seconds if not already accepted/declined
        const cookiesAccepted = localStorage.getItem('cookiesAccepted');
        if (cookiesAccepted === null) {
            setTimeout(() => {
                cookieBanner.classList.add('visible');
            }, 2000);
        }
        
        acceptCookies.addEventListener('click', () => {
            cookieBanner.classList.remove('visible');
            localStorage.setItem('cookiesAccepted', 'true');
        });
        
        declineCookies.addEventListener('click', () => {
            cookieBanner.classList.remove('visible');
            localStorage.setItem('cookiesAccepted', 'false');
        });
    }
});
// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // Form Validation
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');
        
        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const phoneError = document.getElementById('phoneError');
        const dateError = document.getElementById('dateError');
        const timeError = document.getElementById('timeError');
        
        const successNotification = document.getElementById('successNotification');
        const errorNotification = document.getElementById('errorNotification');
        
        // Set min date to today
        const today = new Date().toISOString().split('T')[0];
        if (dateInput) {
            dateInput.min = today;
        }
        
        function validateName() {
            if (nameInput && nameInput.value.trim() === '') {
                nameInput.classList.add('error');
                if (nameError) nameError.classList.add('show');
                return false;
            } else {
                if (nameInput) {
                    nameInput.classList.remove('error');
                    nameInput.classList.add('success');
                }
                if (nameError) nameError.classList.remove('show');
                return true;
            }
        }
        
        function validateEmail() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailInput && !emailRegex.test(emailInput.value)) {
                emailInput.classList.add('error');
                if (emailError) emailError.classList.add('show');
                return false;
            } else {
                if (emailInput) {
                    emailInput.classList.remove('error');
                    emailInput.classList.add('success');
                }
                if (emailError) emailError.classList.remove('show');
                return true;
            }
        }
        
        function validatePhone() {
            const phoneRegex = /^\+?[\d\s\-\(\)]{10,}$/;
            if (phoneInput && !phoneRegex.test(phoneInput.value)) {
                phoneInput.classList.add('error');
                if (phoneError) phoneError.classList.add('show');
                return false;
            } else {
                if (phoneInput) {
                    phoneInput.classList.remove('error');
                    phoneInput.classList.add('success');
                }
                if (phoneError) phoneError.classList.remove('show');
                return true;
            }
        }
        
        function validateDate() {
            if (dateInput && dateInput.value === '') {
                dateInput.classList.add('error');
                if (dateError) dateError.classList.add('show');
                return false;
            } else {
                if (dateInput) {
                    dateInput.classList.remove('error');
                    dateInput.classList.add('success');
                }
                if (dateError) dateError.classList.remove('show');
                return true;
            }
        }
        
        function validateTime() {
            if (timeInput && timeInput.value === '') {
                timeInput.classList.add('error');
                if (timeError) timeError.classList.add('show');
                return false;
            } else {
                if (timeInput) {
                    timeInput.classList.remove('error');
                    timeInput.classList.add('success');
                }
                if (timeError) timeError.classList.remove('show');
                return true;
            }
        }
        
        if (nameInput) nameInput.addEventListener('blur', validateName);
        if (emailInput) emailInput.addEventListener('blur', validateEmail);
        if (phoneInput) phoneInput.addEventListener('blur', validatePhone);
        if (dateInput) dateInput.addEventListener('blur', validateDate);
        if (timeInput) timeInput.addEventListener('blur', validateTime);
        
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPhoneValid = validatePhone();
            const isDateValid = validateDate();
            const isTimeValid = validateTime();
            
            if (isNameValid && isEmailValid && isPhoneValid && isDateValid && isTimeValid) {
                // In a real implementation, this would send data to the server
                if (successNotification) successNotification.classList.add('show');
                if (errorNotification) errorNotification.classList.remove('show');
                appointmentForm.reset();
                
                // Reset form styles
                const formControls = document.querySelectorAll('.form-control');
                formControls.forEach(control => {
                    control.classList.remove('error', 'success');
                });
                
                // Hide notification after 5 seconds
                setTimeout(() => {
                    if (successNotification) successNotification.classList.remove('show');
                }, 5000);
            } else {
                if (errorNotification) errorNotification.classList.add('show');
                if (successNotification) successNotification.classList.remove('show');
                
                // Hide error notification after 5 seconds
                setTimeout(() => {
                    if (errorNotification) errorNotification.classList.remove('show');
                }, 5000);
            }
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
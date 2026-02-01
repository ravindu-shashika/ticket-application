
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alert = document.getElementById('alert-message');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }

    // Setup CSRF token for all AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.content;
    }
});


function setButtonLoading(button, isLoading) {
    const btnText = button.querySelector('.btn-text');
    const btnLoader = button.querySelector('.btn-loader');
    
    if (isLoading) {
        if (btnText) btnText.style.display = 'none';
        if (btnLoader) btnLoader.style.display = 'inline-flex';
        button.disabled = true;
    } else {
        if (btnText) btnText.style.display = 'inline';
        if (btnLoader) btnLoader.style.display = 'none';
        button.disabled = false;
    }
}


function clearFormErrors(form) {
    form.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    form.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));
}


function displayFormErrors(errors) {
    Object.keys(errors).forEach(key => {
        const errorEl = document.getElementById(`error-${key}`);
        const inputEl = document.getElementById(key);
        if (errorEl && errors[key][0]) {
            errorEl.textContent = errors[key][0];
        }
        if (inputEl) {
            inputEl.classList.add('error');
        }
    });
}


function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}







// Export functions for use in other scripts
window.supportSystem = {
    setButtonLoading,
    clearFormErrors,
    displayFormErrors,
    showNotification,
    debounce,
};

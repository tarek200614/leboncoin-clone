document.addEventListener('DOMContentLoaded', () => {
    // Password Strength Indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const meter = document.getElementById('strength-meter');
        const text = document.getElementById('strength-text');
        
        passwordInput.addEventListener('input', (e) => {
            const val = e.target.value;
            let strength = 0;
            if (val.length >= 8) strength += 25;
            if (val.match(/[A-Z]/)) strength += 25;
            if (val.match(/[0-9]/)) strength += 25;
            if (val.match(/[^A-Za-z0-9]/)) strength += 25;

            meter.style.width = strength + '%';
            if (strength <= 25) { meter.style.background = '#dc2626'; text.textContent = 'Faible'; }
            else if (strength <= 50) { meter.style.background = '#f59e0b'; text.textContent = 'Moyen'; }
            else if (strength <= 75) { meter.style.background = '#3b82f6'; text.textContent = 'Bon'; }
            else { meter.style.background = '#16a34a'; text.textContent = 'Excellent'; }
        });
    }

    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

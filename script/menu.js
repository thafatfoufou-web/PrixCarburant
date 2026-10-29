document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    
    if (menuToggle) {
        menuToggle.addEventListener('change', function() {
            document.body.classList.toggle('is-open', this.checked);
        });
    }
});

<div class="theme-toggle">
    <button type="button" class="btn btn-sm btn-outline-secondary" id="themeToggle" title="Toggle Theme">
        <i class="bi bi-sun-fill theme-icon-light" id="lightIcon"></i>
        <i class="bi bi-moon-stars-fill theme-icon-dark d-none" id="darkIcon"></i>
    </button>
</div>

<script>
(function() {
    const themeToggle = document.getElementById('themeToggle');
    const lightIcon = document.getElementById('lightIcon');
    const darkIcon = document.getElementById('darkIcon');
    const htmlElement = document.documentElement;

    // Get saved theme from localStorage or default to 'light'
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply theme on page load
    setTheme(currentTheme);

    // Toggle theme on button click
    themeToggle.addEventListener('click', function() {
        const newTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    });

    function setTheme(theme) {
        if (theme === 'dark') {
            htmlElement.setAttribute('data-theme', 'dark');
            lightIcon.classList.add('d-none');
            darkIcon.classList.remove('d-none');
        } else {
            htmlElement.removeAttribute('data-theme');
            lightIcon.classList.remove('d-none');
            darkIcon.classList.add('d-none');
        }
    }
})();
</script>

<style>
.theme-toggle {
    display: inline-block;
}

.theme-toggle .btn {
    border-radius: 50%;
    width: 38px;
    height: 38px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-toggle .bi {
    font-size: 1.1rem;
}
</style>


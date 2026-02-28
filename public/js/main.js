document.addEventListener('DOMContentLoaded', () => {
    // === 1. THEME INITIALIZATION ===
    const body = document.body;
    const themeToggle = document.getElementById('theme-toggle');
    
    const initTheme = () => {
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.querySelector('i').className = 'ti ti-sun';
        }
    };
    initTheme();

    themeToggle.addEventListener('click', () => {
        const isDark = body.classList.toggle('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeToggle.querySelector('i').className = isDark ? 'ti ti-sun' : 'ti ti-moon';
    });

    // === 2. SIDEBAR TOGGLE (MOBILE & DESKTOP) ===
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('sidebar-toggle');

    menuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-open');
        } else {
            // Manual Expand Toggle for desktop if you click
            sidebar.classList.toggle('force-expand');
        }
    });

    // Close mobile sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && !sidebar.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
        }
    });

    // === 3. LOGIN VALIDATION (Your original logic) ===
    const loginForm = document.querySelector('.login-page form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = this.querySelector('#username');
            if (username && !username.value.trim()) {
                e.preventDefault();
                alert('Username is required');
            }
        });
    }

    // === 4. KEYBOARD SHORTCUTS (Your original logic) ===
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            themeToggle.click();
        }
    });
});

// Pagination 
document.addEventListener("DOMContentLoaded", function () {

    /* Restore scroll position after page reload */
    const scrollPosition = sessionStorage.getItem("pageScrollPosition");
    if (scrollPosition !== null) {
        window.scrollTo(0, parseInt(scrollPosition));
        sessionStorage.removeItem("pageScrollPosition");
    }

    /* Save scroll position when pagination is clicked */
    const paginationLinks = document.querySelectorAll(".pagination a");

    paginationLinks.forEach(link => {
        link.addEventListener("click", function () {
            sessionStorage.setItem("pageScrollPosition", window.scrollY);
        });
    });

});
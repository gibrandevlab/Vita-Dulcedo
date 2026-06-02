import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import * as bootstrap from 'bootstrap';

// Sidebar toggle logic
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('layout-sidebar');
    const contentWrapper = document.getElementById('layout-content-wrapper');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Check if mobile view
            if (window.innerWidth <= 991.98) {
                sidebar.classList.toggle('mobile-open');
                if (overlay) overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                if (contentWrapper) contentWrapper.classList.toggle('sidebar-collapsed');
            }
        });
    }

    if (overlay && sidebar) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

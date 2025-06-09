
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('sidebar-buttons-container');
        const fixedButtons = document.getElementById('fixed-buttons');
        const topBarHeight = 70.8; // exact topbar height

        window.addEventListener('scroll', () => {
            if (window.scrollY >= topBarHeight) {
                fixedButtons.classList.add('detached-fixed-buttons');
            } else {
                fixedButtons.classList.remove('detached-fixed-buttons');
            }
        });
    });

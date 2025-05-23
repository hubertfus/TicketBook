import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    const alert = document.getElementById("login-status");
    if (alert) {
        setTimeout(() => {
            alert.classList.add("opacity-0");
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    }
});

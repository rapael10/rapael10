document.addEventListener('DOMContentLoaded', function () {
    // Toggle menu mobile
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function () {
            const isOpen = navLinks.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // Preview gambar upload di form artikel admin
    const imageInput = document.getElementById('gambarInput');
    const imagePreview = document.getElementById('gambarPreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    imagePreview.src = ev.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

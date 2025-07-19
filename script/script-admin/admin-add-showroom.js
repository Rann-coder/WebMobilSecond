document.getElementById('showroom-photo-input').addEventListener('change', function(event) {
    const preview = document.getElementById('showroom-photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});

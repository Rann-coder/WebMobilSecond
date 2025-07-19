document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('staff-photo');
    const photoPreview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    
    if (!photoInput || !photoPreview || !placeholder) {
        console.error('Elemen untuk upload foto tidak ditemukan!');
        return;
    }

    photoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Harap pilih file gambar yang valid (jpg, png, webp).');
                photoInput.value = ''; 
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                photoInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
                placeholder.style.display = 'none';
            }

            reader.readAsDataURL(file);

        } else {
            photoPreview.src = '#';
            photoPreview.style.display = 'none';
            placeholder.style.display = 'flex';
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const photoInput = document.getElementById('showroom-photo-input');
    const photoPreview = document.getElementById('showroom-photo-preview');
    const photoPlaceholder = document.getElementById('photo-placeholder');
    
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                photoPreview.src = event.target.result;
                photoPreview.style.display = 'block';
                photoPlaceholder.style.display = 'none';
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Form validation
    const form = document.getElementById('add-showroom-form');
    form.addEventListener('submit', function(e) {
        const nameInput = document.getElementById('showroom-name');
        const addressInput = document.getElementById('showroom-address');
        
        if (!nameInput.value.trim()) {
            e.preventDefault();
            alert('Nama showroom harus diisi');
            nameInput.focus();
            return;
        }
        
        if (!addressInput.value.trim()) {
            e.preventDefault();
            alert('Alamat showroom harus diisi');
            addressInput.focus();
            return;
        }
    });
});
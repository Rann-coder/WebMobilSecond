document.addEventListener('DOMContentLoaded', function () {
    function initializeImageUploader(containerId, buttonId) {
        const imageUploadContainer = document.getElementById(containerId);
        const addImageSlotBtn = document.getElementById(buttonId);
        
        if (!imageUploadContainer || !addImageSlotBtn) return;

        let slotCounter = imageUploadContainer.querySelectorAll('.image-upload-slot').length;

        function createNewImageSlot() {
            slotCounter++;
            const slotDiv = document.createElement('div');
            slotDiv.classList.add('image-upload-slot');
            const baseId = containerId.includes('inspection') ? 'inspection' : 'gallery';
            const placeholderText = containerId.includes('inspection') ? '+ Tambah Foto Inspeksi' : '+ Tambah Foto Lain';
            const captionPlaceholder = containerId.includes('inspection') ? 'Caption hasil inspeksi...' : 'Caption untuk gambar...';
            
            slotDiv.innerHTML = `
                <label for="${baseId}-file-${slotCounter}" class="image-placeholder">
                    <img id="${baseId}-preview-${slotCounter}" src="" alt="Preview" class="image-preview" style="display: none;">
                    <span class="placeholder-text">${placeholderText}</span>
                    <button type="button" class="remove-image-btn" title="Hapus slot ini">×</button>
                </label>
                <input type="file" id="${baseId}-file-${slotCounter}" name="${baseId}_images[]" accept="image/*" class="file-input">
                <input type="text" name="${baseId}_captions[]" class="caption-input" placeholder="${captionPlaceholder}">
            `;
            imageUploadContainer.appendChild(slotDiv);
        }

        addImageSlotBtn.addEventListener('click', createNewImageSlot);

        imageUploadContainer.addEventListener('change', function(event) {
            if (event.target && event.target.classList.contains('file-input')) {
                const fileInput = event.target;
                const file = fileInput.files[0];
                const previewElement = fileInput.closest('.image-upload-slot').querySelector('.image-preview');
                const placeholderText = fileInput.closest('.image-placeholder').querySelector('.placeholder-text');

                if (file && previewElement) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewElement.src = e.target.result;
                        previewElement.style.display = 'block';
                        if (placeholderText) placeholderText.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            }
        });

        imageUploadContainer.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('remove-image-btn')) {
                event.target.closest('.image-upload-slot').remove();
            }
        });
    }

    initializeImageUploader('image-upload-container', 'add-image-slot-btn');
    initializeImageUploader('inspection-upload-container', 'add-inspection-slot-btn');
});

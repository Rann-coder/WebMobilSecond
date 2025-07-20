document.addEventListener('DOMContentLoaded', function () {
    const imageUploadContainer = document.getElementById('image-upload-container');
    const addImageSlotBtn = document.getElementById('add-image-slot-btn');
    
    function createNewImageSlot() {
        const existingSlots = document.querySelectorAll('.image-upload-slot');
        const slotNumber = existingSlots.length + 1;
        
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('image-upload-slot');
        slotDiv.innerHTML = `
            <label for="file-${slotNumber}" class="image-placeholder">
                <img id="preview-${slotNumber}" src="" alt="Preview" class="image-preview">
                <span class="placeholder-text">+ Tambah Gambar ${slotNumber}</span>
                <button type="button" class="remove-image-btn" title="Hapus slot ini">×</button>
            </label>
            <input type="file" id="file-${slotNumber}" name="gallery_images[]" accept="image/*" class="file-input">
            <input type="text" name="captions[]" class="caption-input" placeholder="Caption untuk gambar ${slotNumber}...">
        `;
        imageUploadContainer.appendChild(slotDiv);
        
        const newFileInput = slotDiv.querySelector('.file-input');
        newFileInput.addEventListener('change', handleImagePreview);
    }

    function handleImagePreview(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const slotId = fileInput.id.split('-')[1];
        const previewElement = document.getElementById(`preview-${slotId}`);
        const placeholder = fileInput.closest('.image-placeholder');

        if (file && previewElement) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.src = e.target.result;
                previewElement.style.display = 'block';
                placeholder.classList.add('has-image');
                const placeholderText = placeholder.querySelector('.placeholder-text');
                if (placeholderText) placeholderText.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    }

    document.querySelectorAll('#image-upload-container .file-input').forEach(input => {
        input.addEventListener('change', handleImagePreview);
    });

    if (addImageSlotBtn) {
        addImageSlotBtn.addEventListener('click', createNewImageSlot);
    }

    imageUploadContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-image-btn')) {
            const slotToRemove = event.target.closest('.image-upload-slot');
            const allSlots = imageUploadContainer.querySelectorAll('.image-upload-slot');
            const slotIndex = Array.from(allSlots).indexOf(slotToRemove);
            
            if (slotIndex < 2) {
                alert('Slot utama dan slot kedua tidak dapat dihapus!');
                return;
            }
            
            slotToRemove.remove();
            updateSlotNumbers();
        }
    });

    function updateSlotNumbers() {
        const allSlots = document.querySelectorAll('#image-upload-container .image-upload-slot');
        
        allSlots.forEach((slot, index) => {
            const slotNumber = index + 1;
            const isMainSlot = index === 0;
            
            const fileInput = slot.querySelector('.file-input');
            const previewImg = slot.querySelector('.image-preview');
            const label = slot.querySelector('label');
            
            if (fileInput) {
                fileInput.id = `file-${slotNumber}`;
            }
            
            if (previewImg) {
                previewImg.id = `preview-${slotNumber}`;
            }
            
            if (label) {
                label.setAttribute('for', `file-${slotNumber}`);
            }
            
            const placeholder = slot.querySelector('.placeholder-text');
            const caption = slot.querySelector('.caption-input');
            
            if (isMainSlot) {
                slot.classList.add('main-slot');
                if (placeholder) {
                    placeholder.textContent = `+ Tambah Gambar Utama`;
                }
                if (caption) {
                    caption.placeholder = `Caption gambar utama...`;
                }
            } else {
                slot.classList.remove('main-slot');
                if (placeholder) {
                    placeholder.textContent = `+ Tambah Gambar ${slotNumber}`;
                }
                if (caption) {
                    caption.placeholder = `Caption untuk gambar ${slotNumber}...`;
                }
            }
        });
    }

    function initSecondSlot() {
        const existingSlots = document.querySelectorAll('#image-upload-container .image-upload-slot');
        if (existingSlots.length < 2) {
            createSecondSlot();
        }
    }

    function createSecondSlot() {
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('image-upload-slot');
        slotDiv.innerHTML = `
            <label for="file-2" class="image-placeholder">
                <img id="preview-2" src="" alt="Preview" class="image-preview">
                <span class="placeholder-text">+ Tambah Gambar 2</span>
            </label>
            <input type="file" id="file-2" name="gallery_images[]" accept="image/*" class="file-input">
            <input type="text" name="captions[]" class="caption-input" placeholder="Caption untuk gambar 2...">
        `;
        imageUploadContainer.appendChild(slotDiv);
        
        const newFileInput = slotDiv.querySelector('.file-input');
        newFileInput.addEventListener('change', handleImagePreview);
    }

    initSecondSlot();

    const inspectionUploadContainer = document.getElementById('inspection-upload-container');
    const addInspectionSlotBtn = document.getElementById('add-inspection-slot-btn');
    let inspectionSlotCounter = inspectionUploadContainer.querySelectorAll('.image-upload-slot').length;

    function createNewInspectionSlot() {
        inspectionSlotCounter++;
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('image-upload-slot');
        slotDiv.innerHTML = `
            <label for="inspection-file-${inspectionSlotCounter}" class="image-placeholder">
                <img id="inspection-preview-${inspectionSlotCounter}" src="" alt="Preview" class="image-preview">
                <span class="placeholder-text">+ Tambah Foto Inspeksi</span>
                <button type="button" class="remove-image-btn" title="Hapus slot ini">×</button>
            </label>
            <input type="file" id="inspection-file-${inspectionSlotCounter}" name="inspection_images[]" accept="image/*" class="file-input">
            <input type="text" name="inspection_captions[]" class="caption-input" placeholder="Caption hasil inspeksi...">
        `;
        inspectionUploadContainer.appendChild(slotDiv);
        
        const newFileInput = slotDiv.querySelector('.file-input');
        newFileInput.addEventListener('change', handleInspectionImagePreview);
    }

    function handleInspectionImagePreview(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const slotId = fileInput.id.split('-')[2]; 
        const previewElement = document.getElementById(`inspection-preview-${slotId}`);
        const placeholder = fileInput.closest('.image-placeholder');

        if (file && previewElement) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.src = e.target.result;
                previewElement.style.display = 'block';
                placeholder.classList.add('has-image');
                const placeholderText = placeholder.querySelector('.placeholder-text');
                if (placeholderText) placeholderText.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    }

    if (addInspectionSlotBtn) {
        addInspectionSlotBtn.addEventListener('click', createNewInspectionSlot);
    }

    inspectionUploadContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-image-btn')) {
            const slotToRemove = event.target.closest('.image-upload-slot');
            slotToRemove.remove();
        }
    });

    inspectionUploadContainer.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', handleInspectionImagePreview);
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const imageUploadContainer = document.getElementById('image-upload-container');
    const addImageSlotBtn = document.getElementById('add-image-slot-btn');
    const deletedImagesInput = document.getElementById('deleted-images');
    let deletedImages = [];
    
    function createNewImageSlot() {
        const existingSlots = imageUploadContainer.querySelectorAll('.image-upload-slot');
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
        
        if (event.target.classList.contains('remove-existing-image-btn')) {
            const slot = event.target.closest('.image-upload-slot');
            const imageId = slot.dataset.imageId;
            
            if (imageId) {
                deletedImages.push(imageId);
                deletedImagesInput.value = JSON.stringify(deletedImages);
                
                const placeholder = slot.querySelector('.image-placeholder');
                const preview = slot.querySelector('.image-preview');
                const caption = slot.querySelector('.caption-input');
                
                preview.src = '';
                preview.style.display = 'none';
                placeholder.classList.remove('has-image');
                placeholder.querySelector('.placeholder-text').style.display = 'block';
                event.target.remove();
                
                slot.querySelector('input[name="existing_images[]"]').remove();
                
                const allSlots = imageUploadContainer.querySelectorAll('.image-upload-slot');
                const slotIndex = Array.from(allSlots).indexOf(slot);
                
                if (slotIndex >= 2) {
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.title = 'Hapus slot ini';
                    removeBtn.innerHTML = '×';
                    placeholder.appendChild(removeBtn);
                }
            }
        }
    });

    const inspectionUploadContainer = document.getElementById('inspection-upload-container');
    const addInspectionSlotBtn = document.getElementById('add-inspection-slot-btn');
    const deletedInspectionsInput = document.getElementById('deleted-inspections');
    let deletedInspections = [];
    
    function createNewInspectionSlot() {
        const existingSlots = inspectionUploadContainer.querySelectorAll('.image-upload-slot');
        const slotNumber = existingSlots.length + 1;
        
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('image-upload-slot');
        slotDiv.innerHTML = `
            <label for="inspection-file-${slotNumber}" class="image-placeholder">
                <img id="inspection-preview-${slotNumber}" src="" alt="Preview" class="image-preview">
                <span class="placeholder-text">+ Tambah Foto Inspeksi</span>
                <button type="button" class="remove-image-btn" title="Hapus slot ini">×</button>
            </label>
            <input type="file" id="inspection-file-${slotNumber}" name="inspection_images[]" accept="image/*" class="file-input">
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
        
        if (event.target.classList.contains('remove-existing-image-btn')) {
            const slot = event.target.closest('.image-upload-slot');
            const inspectionId = slot.dataset.inspectionId;
            
            if (inspectionId) {
                deletedInspections.push(inspectionId);
                deletedInspectionsInput.value = JSON.stringify(deletedInspections);
                
                const placeholder = slot.querySelector('.image-placeholder');
                const preview = slot.querySelector('.image-preview');
                const caption = slot.querySelector('.caption-input');
                
                preview.src = '';
                preview.style.display = 'none';
                placeholder.classList.remove('has-image');
                placeholder.querySelector('.placeholder-text').style.display = 'block';
                event.target.remove();
                
                slot.querySelector('input[name="existing_inspections[]"]').remove();
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-image-btn';
                removeBtn.title = 'Hapus slot ini';
                removeBtn.innerHTML = '×';
                placeholder.appendChild(removeBtn);
            }
        }
    });

    document.querySelectorAll('.file-input').forEach(input => {
        const containerId = input.closest('.image-upload-grid').id;
        if (containerId === 'image-upload-container') {
            input.addEventListener('change', handleImagePreview);
        } else if (containerId === 'inspection-upload-container') {
            input.addEventListener('change', handleInspectionImagePreview);
        }
    });
});
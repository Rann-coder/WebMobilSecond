document.addEventListener('DOMContentLoaded', function () {
            const fileInputs = document.querySelectorAll('.image-upload-grid .file-input');
            fileInputs.forEach(input => {
                input.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (!file) {
                        return; 
                    }

                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file.');
                        return;
                    }

                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size must be less than 5MB.');
                        return;
                    }

                    const inputId = this.id;
                    const previewId = 'preview-' + inputId.split('-')[1];
                    const previewElement = document.getElementById(previewId);
                    const placeholderElement = previewElement.closest('.image-placeholder');

                    if (previewElement) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewElement.src = e.target.result;
                            previewElement.classList.add('has-image');
                            placeholderElement.classList.add('has-image');
                            
                            const spanElement = placeholderElement.querySelector('span');
                            if (spanElement) {
                                spanElement.textContent = 'Gambar berhasil dipilih';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        });

        function removeImage(index) {
            const fileInput = document.getElementById('file-' + index);
            const previewElement = document.getElementById('preview-' + index);
            const placeholderElement = previewElement.closest('.image-placeholder');
            const captionInput = placeholderElement.closest('.image-upload-slot').querySelector('.caption-input');

            fileInput.value = '';
    
            previewElement.src = '';
            previewElement.classList.remove('has-image');
            placeholderElement.classList.remove('has-image');
            
            if (!captionInput.required) {
                captionInput.value = '';
            }
            
            const spanElement = placeholderElement.querySelector('span');
            if (spanElement) {
                if (index === 1) {
                    spanElement.textContent = '+ Tambah Gambar Utama';
                } else {
                    spanElement.textContent = '+ Tambah Gambar ' + index;
                }
            }
        }

      
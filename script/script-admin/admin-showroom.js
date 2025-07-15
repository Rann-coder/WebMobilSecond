document.addEventListener('DOMContentLoaded', function() {
    // Enhanced confirmation for delete
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm') || 'Anda yakin ingin menghapus?')) {
                e.preventDefault();
            }
        });
    });
});
window.previewDocument = function(url, title) {
    const previewModal = document.getElementById('previewModal');
    const documentPreview = document.getElementById('documentPreview');
    const previewTitle = document.getElementById('previewTitle');
    const previewLoading = document.getElementById('previewLoading');
    
    if (!previewModal || !documentPreview || !previewTitle || !previewLoading) {
        console.error('Required elements not found in the DOM');
        return;
    }
    
    previewTitle.textContent = title;
    
    previewLoading.style.display = 'flex';
    
    documentPreview.src = '';
    
    previewModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    documentPreview.src = url;
    
    documentPreview.onerror = function() {
        previewLoading.style.display = 'none';
        alert('Failed to load document preview');
        closePreviewModal();
    };
};

window.hidePreviewLoading = function() {
    const previewLoading = document.getElementById('previewLoading');
    if (previewLoading) {
        previewLoading.style.display = 'none';
    }
};

window.closePreviewModal = function() {
    const previewModal = document.getElementById('previewModal');
    const documentPreview = document.getElementById('documentPreview');
    const previewLoading = document.getElementById('previewLoading');
    
    if (!previewModal || !documentPreview || !previewLoading) {
        return;
    }
    
    documentPreview.src = '';
    previewModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    previewLoading.style.display = 'flex';
};

document.addEventListener('DOMContentLoaded', function() {
    const previewModal = document.getElementById('previewModal');
    if (previewModal) {
        previewModal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closePreviewModal();
            }
        });
    }
}); 
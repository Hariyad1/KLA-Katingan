/**
 * Konfigurasi kustom untuk CKEditor
 */
CKEDITOR.editorConfig = function(config) {
    CKEDITOR.on('dialogDefinition', function(ev) {
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;

        if (dialogName == 'image') {
            var infoTab = dialogDefinition.getContents('info');
            
            dialogDefinition.removeContents('Link');
            dialogDefinition.removeContents('advanced');

            var elements = infoTab.elements;
            
            for (var i = elements.length - 1; i >= 0; i--) {
                var element = elements[i];
                
                var keepElement = false;
                
                if (element.id === 'txtUrl' || element.id === 'txtAlt' || 
                    (element.type === 'hbox' && element.widths && element.widths[0] === '50%' && element.widths[1] === '50%' && 
                     element.children && element.children[0] && element.children[0].id === 'txtWidth') || 
                    element.id === 'cmbAlign') {
                    keepElement = true;
                }
                
                if (!keepElement) {
                    elements.splice(i, 1);
                }
            }

            var uploadTab = dialogDefinition.getContents('Upload');
            if (uploadTab) {
                uploadTab.elements = uploadTab.elements.filter(function(element) {
                    return element.type === 'file' || element.type === 'fileButton';
                });
            }
        }
    });

    config.filebrowserUploadMethod = 'form';
}; 
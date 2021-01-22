function initiallizePdfPreview(fileInputID) {
    const previewCanvasID = '#' + fileInputID + '_preview';

    var _PDF_DOC;                                             // will hold the PDF handle returned by PDF.JS API            
    var _CANVAS = document.querySelector(previewCanvasID);    // PDF.JS renders PDF in a <canvas> element
    var _OBJECT_URL;                                          // will hold object url of chosen PDF
    var _MIME_TYPES = [ 'application/pdf' ];

    // load the PDF
    const showPDF = (pdf_url) => {
        PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
            _PDF_DOC = pdf_doc;
            showPage(1);    // show the first page of PDF
            URL.revokeObjectURL(_OBJECT_URL);   // destroy previous object url
        })
        .catch((error) => {
            alert(error.message);   // error reason
        });;
    }

    // show page of PDF
    const showPage = (page_no) => {
        _PDF_DOC.getPage(page_no).then((page) => {
            var scale_required = _CANVAS.width / page.getViewport(1).width;     // set the scale of viewport
            var viewport = page.getViewport(scale_required);                    // get viewport of the page at required scale

            _CANVAS.height = viewport.height;   // set canvas height

            var renderContext = {
                canvasContext: _CANVAS.getContext('2d'),
                viewport: viewport
            };
            
            // render the page contents in the canvas
            page.render(renderContext).then(() => {
                document.querySelector(previewCanvasID).style.display = 'inline-block';
                document.querySelector(previewCanvasID + '_default').style.display = 'none';
            });
        });
    }

    const loadPreview = (file) => {
        // validate whether PDF
        if(_MIME_TYPES.indexOf(file.type) == -1) {
            alert('Error : Incorrect file type');
            return;
        }

        // validate file size
        if(file.size > 10*1024*1024) {
            alert('Error : Exceeded size 10MB');
            return;
        }

        _OBJECT_URL = URL.createObjectURL(file) // object url of PDF 
        showPDF(_OBJECT_URL);                   // send the object url of the pdf to the PDF preview function
    }

    /* when users selects a file */
    document.querySelector("#" + fileInputID).addEventListener('change', function() {
        var file = this.files[0];
        loadPreview(file);      
    });

    const currentPDF = document.querySelector(previewCanvasID).getAttribute('pdf');

    if(currentPDF) { showPDF(currentPDF) }
}
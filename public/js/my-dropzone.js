Dropzone.autoDiscover = false;
let zone = null;
let editzone = null;
let image_data = [];


//p_images = category.images;

function renderHiddenImages(images){
	$('#hidden-images').html('');

	if(images != null && images.length > 0){
		let html = '';
		images.map((image,index) => {
			html += `
				<input type="hidden" form="${form_id}" name="images[${index}][name]" value="${image.name}" />
				<input type="hidden" form="${form_id}" name="images[${index}][path]" value="${image.path}" />
				<input type="hidden" form="${form_id}" name="images[${index}][size]" value="${image.size}" />
			`;
		});
		$('#hidden-images').html(html);
	}
}


$(document).ready(function() {

    let options = {
        paramName: "image", // The name that will be used to transfer the file
        url: image_upload_path,
        acceptedFiles: 'image/*',
        maxFilesize: 4, // MB
        addRemoveLinks: true,
        capture: true,
        dictRemoveFile: 'Remove',
        parallelUploads: 10,
        uploadMultiple: false,
        maxFiles: maxFiles != undefined ? maxFiles : 10,
        dictRemoveFileConfirmation: 'Are you sure you want to remove this image?',
        accept: function(file, done){
	    	done();
	    },
        success: function(file , response){
	        if (response.path) {
	            file.path = response.path;
	            image_data.push(response);
	            let $button = $('<a href="#" class="js-open-cropper-modal" data-url="' + file.path + '" data-file-name="' + file.name + '">Crop</a>');
	            $(file.previewElement).append($button);
	            renderHiddenImages(image_data);
	        } else {
	            this.removeFile(file);
	            toastr.error(response.image[0], 'Error');
	        }        	
        },
        init: function(){
			this.on("removedfile", deleteFile);
	        myzone = this;
	        if (p_images != null && p_images.length > 0) {
	            setTimeout(() => {
	                initFileUpload();
	            }, 100);
	        }        	
        }
    };

    zone = $('#my-awesome-dropzone').dropzone(options);

    $('#myModal').on('shown.bs.modal', function(e) {
        editmode = false;
        image_data = [];
        if (zone == null) {
            zone = $('#my-awesome-dropzone').dropzone(options);
        }
    });

    $('#editModal').on('shown.bs.modal', function(e) {
        editmode = true;
        if (editzone == null) {
            editzone = $('#my-awesome-dropzone-edit').dropzone(options);
        }
    });

});

$(function() {
    $('#my-awesome-dropzone , #my-awesome-dropzone-edit').on('click', '.js-open-cropper-modal', function(e) {
        e.preventDefault();
        var fileUrl = $(this).data('url');

        var modalTemplate =
            '<div class="modal fade" tabindex="-1" role="dialog">' +
            '<div class="modal-dialog modal-lg" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h4 class="modal-title">Crop</h4>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '</div>' +
            '<div class="modal-body">' +
            '<div class="image-container">' +
            '<img id="img-' + ++c + '" src="' + fileUrl + '">' +
            '</div>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" class="btn btn-warning rotate-left"><span class="fa fa-rotate-left"></span></button>' +
            '<button type="button" class="btn btn-warning rotate-right"><span class="fa fa-rotate-right"></span></button>' +
            '<button type="button" class="btn btn-warning scale-x" data-value="-1"><span class="fa fa-arrows-h"></span></button>' +
            '<button type="button" class="btn btn-warning scale-y" data-value="-1"><span class="fa fa-arrows-v"></span></button>' +
            '<button type="button" class="btn btn-warning reset"><span class="fa fa-refresh"></span></button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-primary crop-upload">Crop & Upload</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        var $cropperModal = $(modalTemplate);

        $cropperModal.modal('show').on("shown.bs.modal", function() {
            var cropper = new Cropper(document.getElementById('img-' + c), {
                autoCropArea: 1,
                movable: true,
                cropBoxResizable: true,
                rotatable: true,
                aspectRatio: 16 / 9,
                minCropBoxWidth: 825,
                minCropBoxHeight: 340,
            });
            var $this = $(this);
            $this
                .on('click', '.crop-upload', function() {
                    // get cropped image data
                    var blob = cropper.getCroppedCanvas().toDataURL('image/jpeg');
                    // transform it to Blob object
                    var croppedFile = dataURItoBlob(blob);
                    croppedFile.name = fileUrl;

                    var files = myzone.getAcceptedFiles();

                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        if (file.path === fileUrl) {
                            myzone.removeFile(file);
                            deleteFile(file);
                        }
                    }

                    myzone.addFile(croppedFile);
                    // image_data.push(croppedFile);
                    $this.modal('hide');
                })
                .on('click', '.rotate-right', function() {
                    cropper.rotate(90);
                })
                .on('click', '.rotate-left', function() {
                    cropper.rotate(-90);
                })
                .on('click', '.reset', function() {
                    cropper.reset();
                })
                .on('click', '.scale-x', function() {
                    var $this = $(this);
                    cropper.scaleX($this.data('value'));
                    $this.data('value', -$this.data('value'));
                })
                .on('click', '.scale-y', function() {
                    var $this = $(this);
                    cropper.scaleY($this.data('value'));
                    $this.data('value', -$this.data('value'));
                });
        });
    });
})

function deleteFile(file) {
    let items = image_data.filter(e => e.path != file.path);
    image_data = [];
    image_data = items;
    renderHiddenImages(image_data);
}
var c = 0;

var dataURItoBlob = function(dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {
        type: 'image/jpeg'
    });
};

function initFileUpload() {
    if (p_images != null && p_images.length > 0) {
        $.each(p_images, function(key, file) {
            let mockFile = {
                name: file.name,
                size: file.size,
                accepted: true,
                kind: 'image',
                upload: {
                    filename: file.name,
                },
                dataURL: file.url,
                path: file.url
            };

            image_data.push({
                name: mockFile.name,
                path: mockFile.dataURL,
                size: mockFile.size
            });

            myzone.files.push(mockFile);
            myzone.emit("addedfile", mockFile);
            myzone.createThumbnailFromUrl(mockFile,
                myzone.options.thumbnailWidth, myzone.options.thumbnailHeight,
                myzone.options.thumbnailMethod, true,
                function(thumbnail) {
                    myzone.emit('thumbnail', mockFile, thumbnail);
                    myzone.emit("complete", mockFile);
                }
            );

            let $button = $('<a href="#" class="js-open-cropper-modal" data-url="' + mockFile.path + '" data-file-name="' + mockFile.name + '">Crop</a>');
            $(mockFile.previewElement).append($button);
        });
    }
}
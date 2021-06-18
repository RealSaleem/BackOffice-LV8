function resizeImage(url, width, height, callback) {
    var sourceImage = new Image();
    sourceImage.onload = function() {
        // Create a canvas with the desired dimensions
        var canvas = document.createElement("canvas");
        canvas.width = width;
        canvas.height = height;
        // Scale and draw the source image to the canvas
        canvas.getContext("2d").drawImage(sourceImage, 0, 0, width, height);
        // Convert the canvas to a data URL in PNG format
        callback(canvas.toDataURL());
    }
       sourceImage.src = url;
}

$('.image-editor').cropit({
    exportZoom: 1.25,
    imageBackground: true,
    imageBackgroundBorderWidth: 20,
    width : '400',
    height : '300'
    // imageState: {
    //     src: 'http://lorempixel.com/500/400/',
    // },
});

$('.rotate-cw').click(function(e) {
    e.preventDefault();
    $('.image-editor').cropit('rotateCW');
});

$('.rotate-ccw').click(function(e) {
    e.preventDefault();
    $('.image-editor').cropit('rotateCCW');
});

var cropImage = function(){
    var imageData = $('.image-editor').cropit('export',{
            type: 'image/jpeg',
            quality: .9,
            originalSize: true
        });
     return imageData;       
}

$('.cropit-preview').css({width : '400px', height: '300px'});
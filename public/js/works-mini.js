(function() {

    var listImagesWrapId = 'photo-fox-images-list';

    var mainImageWrapId  = 'photo-fox-main-img-wrap';

    var worksList = document.getElementById(listImagesWrapId);

    var currentImage = getCurrentImage();

    console.log(currentImage);

    var data = {};

    newReel(currentImage, data);

    if(worksList.children.length > 0){
        worksList.addEventListener('click', function(e) {

            if(e.target.nodeName === 'IMG'){

                deleteReel(data.img.id);

                addImageToList(data.img);

                newReel(e.target);
            }

        }, false);
    }

    function newReel(currentImage){

        data = getData(currentImage);

        addImageToReel(currentImage);

        createReel(data);
    }

    function createReel(data){
        $("#"+data.img.id).reel(data.reel);
    }

    function deleteReel(id){
        $("#" + id).unreel();
    }

    function getData(currentImage){
        return {
            'img'   : currentImage,
            'reel'  : {
                'frames'  : currentImage.dataset.frames,
                'images'  : currentImage.dataset.images,
                'speed'   : 0,
                'entry'   : 0.5,
                'opening' : 2
            }
        }
    }

    function getCurrentImage(){
        return $('#' + mainImageWrapId + ' img')[0];
    }

    function addImageToReel(currentImage){
        if(currentImage.parentNode.id !== mainImageWrapId){
            currentImage.parentNode.remove();
            currentImage.className = 'img-fluid';
            $('#' + mainImageWrapId).append( currentImage );
        }
    }

    function addImageToList(currentImage){
        var list = $('#' + listImagesWrapId).append('<div class="col-md-2"></div>');
        currentImage.className = 'img-thumbnail';
        list[0].lastChild.append( currentImage );
    }

})();
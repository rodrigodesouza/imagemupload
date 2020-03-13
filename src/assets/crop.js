import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css'

let imageUpload = document.getElementsByClassName("preview");
if(imageUpload[0]) {
    imageUpload[0].onchange = function() {
        handleFileSelect(imageUpload[0]);
    };
}

function handleFileSelect(event) {
    //Check File API support
    if (window.File && window.FileList && window.FileReader) {

        var files = event.files; //FileList object
        var output = document.getElementById("preview");
        output.innerHTML = "";

        var div = document.createElement("div");
        div.classList.add("row");
        // var i = -1
        for (var i = 0; i <= files.length; i++) {
            // console.log(i)
            var file = files[i];
            //Only pics
            if (!file.type.match('image')) continue;

            var picReader = new FileReader();
            picReader.addEventListener("load", function (event) {
                var picFile = event.target;

                // div.innerHTML = "<div class='col-4'><div class='card'><img class='thumbnail upload-cropper' src='" + picFile.result + "'" + "title='" + file.name + "'/></div></div>";
                div.innerHTML += makeCard(picFile.result);
                // let card = makeCard(picFile.result);
                // div.append(card)
                output.insertBefore(div, null);
            });
            //Read the image
            picReader.readAsDataURL(file);

            // picReader.onloadend = (a) => startCrop(i)
            picReader.onloadend = function (a) {
                // console.log('DONE', picReader.readyState); // readyState will be 2
                startCrop();
            };
            // startCrop();
        }


        // setTimeout(function(){
        //     startCrop();
        // }, 3000)
            //const image = document.getElementById('image');

    } else {
        console.log("Your browser does not support File API");
    }
}

function makeCard(file)
{
    let card =  "<div class=\"col-md-4\"><div class=\"card mb-4 shadow-sm\"><img src=\"" + file + "\" class=\"image-crop\" /></div></div>";
    return card;
}

function startCrop(){
    const image = document.getElementsByClassName('image-crop')
    // if (image.cropper) {
    //     image.cropper.destroy();
    // }

    for(var i =0; i < image.length; i++) {
        var cropper = new Cropper(image[i], {
            aspectRatio: 16 / 9,
            crop: function crop(event) {
                console.log(event.detail.x);
                console.log(event.detail.y);
                console.log(event.detail.width);
                console.log(event.detail.height);
                console.log(event.detail.rotate);
                console.log(event.detail.scaleX);
                console.log(event.detail.scaleY);
            }
        });
    }
    // // console.log(image[i], i)
    // var cropper = new Cropper(image[i], {
    //     aspectRatio: 16 / 9,
    //     crop: function crop(event) {
    //         console.log(event.detail.x);
    //         console.log(event.detail.y);
    //         console.log(event.detail.width);
    //         console.log(event.detail.height);
    //         console.log(event.detail.rotate);
    //         console.log(event.detail.scaleX);
    //         console.log(event.detail.scaleY);
    //     }
    // });
}

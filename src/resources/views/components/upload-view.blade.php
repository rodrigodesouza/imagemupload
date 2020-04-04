<label for="{{ $name }}">{{ isset($label) ? $label : 'Imagens' }}</label>
<div id="preview">
    {{-- <img id="preview" src="" alt="your image" style="display:none" /> --}}
</div>
@if(isset($multiple) and $multiple == true)
    <input type="file" name="{{ $name }}[]" class="form-control upload-cropper" onchange="" multiple>
@else
    <input type="file" name="{{ $name }}" class="form-control upload-cropper" onchange="">
@endif

<input type="hidden" name="size_image">
@section('scripts')
<link rel="stylesheet" href="/jqcropper/cropper.css">
<script src="/jqcropper/jqcropper.js"></script>
<script>
$(document).ready(function(){
    $('.upload-cropper').change(function(event){
        handleFileSelect(event);
    });
    function handleFileSelect(event) {
        //Check File API support
        if (window.File && window.FileList && window.FileReader) {

            var files = event.target.files; //FileList object
            var output = document.getElementById("preview");

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image')) continue;

                var picReader = new FileReader();
                picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    div.innerHTML = "<img class='thumbnail upload-cropper' src='" + picFile.result + "'" + "title='" + file.name + "'/>";
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);


            }

            // setTimeout(function(){
            //     startCrop();
            // }, 3000)
             //const image = document.getElementById('image');


        } else {
            console.log("Your browser does not support File API");
        }
    }

    function startCrop()
    {
        $('.upload-cropper').cropper({
                    aspectRatio: 1 / 1,
                    crop: function(event) {
                        console.log(event.detail.x);
                        console.log(event.detail.y);
                        console.log(event.detail.width);
                        console.log(event.detail.height);
                        console.log(event.detail.rotate);
                        console.log(event.detail.scaleX);
                        console.log(event.detail.scaleY);
                        console.log(JSON.stringify(event.detail));
                        $("input[name='size_image']").val(JSON.stringify(event.detail))
                        //document.getElementById("size_image").value = JSON.stringify(event.detail)
                    }
                });
    }

})

    //document.getElementById("{{ $name }}").addEventListener('change', handleFileSelect, false);

</script>
@endsection

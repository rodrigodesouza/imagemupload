{{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAADK0lEQVR4nO2cPWsUQRyHH+MFFF8w2BoEmyRYaGPhBwhWfocDX+p8BO2EGPMFhCvFELCKL4WlnZ1aWChoJ75A7oKFJ8RiT6KX2525mdmbmd3fA/9qh/+8PHczs7e3A0IIIYQQQgghhBBCCCGEEIdZAR4Ab4E9YL/lsQe8ATaAZY9xNTIPbALDBDqdagwpRHQcx7iUeeBZAh3MJZ4SWMJmAp3KLTacRnoCK2jacYkhsGQa3DlTAeA2NcxpLaAD3DIVshGw6t+W1nLNVOCIRZIBcNK/La1kAJyuKmAjYN9w3SZHk/EaH5spSNSIBERGAiIjAZGRgMhIQGQkIDKpClgEtoH+KJ5g8btKUzH96BSaReD7hHp+jK6lRu3jM2sB2xV1bdVQny+NE9CvqGu3hvp88RqfVNeAMuoQHpUUBbysuPZiZq1IiFlPQUsUC+54Pd+AczXU50vj1gAodjtbFHP+LvCYNAcfPMdHzwP80fOAnJGAyEhAZNoiYA3oxm6EKzF2QSG5Dvym+BPtxRryN3IbGoor/P8P7nfAicB1SEAJF4AvHG5vL3A9EjCBs8B7ytvcDViXBIxxHHhFdZtDrgeNEHATuBcgzxzVzxP+jVDrQfYCLgE/R7l8JUz7HkOI9SBrAQvAh7F8rhLWLNpax3qQrYA5ild5JuWcVsLfvb6LAN/1IFsBdw15bSWM7/Vdwmc9yFLAKnafWJOEsr2+S/Qc+5KdgPPAV4u8Jgmmvb5LdB36k5WAY8Bri5wmCTZ7fZdwWQ+yEvDQIp9JwjR7fZeYdj3IRsANi1ymuMNs3lnuTdGvLARc5uBmK5foznB8aq1gAfhokSe1sF0PkhZQdbOVQ9isB0kLMN1s5RC9GsdH/wsKgNf4tOWhfLJIQGQkIDISEBkJiIwEREYCImNzFNkAOFVxPcjdXkPpmwrYfAM+B2hIW/lkKmAjoJUvxgXieYgky+jYSpf4hcXxCkdNBSjeTjwDXLUoKw7YBB6FStYBdoj/qcoldqjhrNUOxXG8mo7KYwisU/NBt8vAfYoj2wcJdDp2DEZjsU6Lj9QRQgghhBBCCCGEEEIIIUQ5fwDsuDJXvbnHPgAAAABJRU5ErkJggg==" width="100%"/> --}}

<div id="preview"></div>
@section('scripts')
<script>
    // window.document.onload = function(e){
    //     handleFileSelect()
    // }

(function() {
// your page initialization code here
// the DOM will be available here
// handleFileSelect()
})();

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

        for (var i = 0; i < files.length; i++) {
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
        }

        setTimeout(function(){
            // startCrop();
        }, 3000)
            //const image = document.getElementById('image');


    } else {
        console.log("Your browser does not support File API");
    }
}

function makeCard(file)
{
    let card =  "<div class=\"col-md-4\"><div class=\"card mb-4 shadow-sm\"><img src=\"" + file + "\" /></div></div>";
    return card;
}
</script>
@endsection

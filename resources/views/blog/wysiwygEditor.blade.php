@extends('layouts.app')

@section('content')

<div class='w-4/5 m-auto text-left'>
    <div class='py-15 h-40'>
        <h1 class='text-6xl'>
            Create Post
        </h1>
    </div>
</div>

@if ($errors->any())
    <div class='w-4/5 m-auto'>
        <ul class='m-1/5 mb-4 text-gray-50 bg-red-700 rounded-2xl py-4'>
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </ul>
    </div>
@endif

<div class='w-4/5 m-auto pt-20'>
    <form action='/blog' method='POST' enctype='multipart/form-data'>
        @csrf
        <input type='text' name='title' placeholder='Title...' class='bg-transparent block border-b-2 w-full h-20 text-6xl outline-none mb-20' />

        <textarea id='mytextarea' name='description' placeholder='Description...' class='py-20 bg-transparent block border-b-2 w-full h-60 text-xl outline-none' ></textarea>
        <input name=image type=file id="upload" onchange="" style="display: none" >


        <button type='submit' class='uppercase bg-blue-500 text-gray-100 text-lg font-extrabold mt-16 py-4 px-8 rounded-3xl'>
            Submit Post
        </button>
    </form>
</div>

<!-- <script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script> -->
<script src="https://cdn.tiny.cloud/1/olj1nova6t1wfadnx2vxzzlo871mmwnupxd1zipthgcys8ar/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            paste_data_images: true,
            height: '800',
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons",
            image_advtab: true,
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                    callback(e.target.result, {
                        alt: ''
                    });
                    };
                    reader.readAsDataURL(file);
                });
                }
            }
        })
    </script>


@endsection

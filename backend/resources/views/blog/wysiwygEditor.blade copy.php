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

    <section class='pt-14'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-12'>
                    <div clas='card-header'>
                        Tiny MCE Html Editor
                    </div>
                    <div class='card-body'>
                        <form method='POST'>
                            @csrf
                            <textarea id='mytextarea' name='mytextarea'>
                            </textarea>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.tiny.cloud/1/olj1nova6t1wfadnx2vxzzlo871mmwnupxd1zipthgcys8ar/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea'
        })
    </script>
@endsection

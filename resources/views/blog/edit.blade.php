@extends('layouts.app')

@section('content')

<div class='w-4/5 m-auto text-left'>
    <div class='py-15 h-40'>
        <h1 class='text-6xl'>
            Update Post
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
    <form action='/blog/{{ $post->slug }}' method='POST' enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        <input type='text' name='title' value="{{ $post->title }}" class='bg-transparent block border-b-2 w-full h-20 text-6xl outline-none' />

        <textarea name='description' placeholder='Description...' class='py-20 bg-transparent block border-b-2 w-full h-60 text-xl outline-none' > {{ $post->description }} </textarea>


        <button type='submit' class='uppercase bg-blue-500 text-gray-100 text-lg font-extrabold mt-16 py-4 px-8 rounded-3xl'>
            Submit Post
        </button>
    </form>
</div>

@endsection

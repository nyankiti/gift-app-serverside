@extends('layouts.app')

@section('header')
<script src="https://cdn.jsdelivr.net/gh/alpine-collective/alpine-magic-helpers@0.3.x/dist/index.js"></script>
@endsection

@section('content')

@if ($errors->any())
    <div class='w-4/5 m-auto'>
        <ul class='m-1/5 mb-4 text-gray-50 bg-red-700 rounded-2xl py-4'>
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </ul>
    </div>
@endif

<div x-data="{photoName: null, photoPreview: null}" class="col-span-6 ml-2 sm:col-span-4 md:mr-3">
    <!-- Photo File Input -->
    <form action='/profile' method='POST' enctype='multipart/form-data'>
    @csrf
        <input type="file" name='image' class="hidden" x-ref="photo" x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
        ">

        <label class="block text-gray-700 text-sm font-bold mb-2 text-center" for="photo">
            Profile Photo <span class="text-red-600"> </span>
        </label>

        <div class="text-center">
            <!-- Current Profile Photo -->
            <div class='mb-5'>
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ \Session::get('userImg') }}" class="w-40 h-40 m-auto rounded-full shadow">
                </div>
                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block w-40 h-40 rounded-full m-auto shadow" x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'" style="background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url('null');">
                    </span>
                </div>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-400 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 mt-2 ml-3" x-on:click.prevent="$refs.photo.click()">
                    Select New Photo
                </button>
            </div>


            <div class="mb-5 ml-auto mr-auto w-3/5">
                <label for="name" class="font-bold mb-1 text-gray-700 block">name</label>
                <input type="text" name='name'
                    class="w-full px-4 py-3 rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                    placeholder="Enter your firstname...">
            </div>

            <div class="mb-5 ml-auto mr-auto w-3/5">
                <label for="email" class="font-bold mb-1 text-gray-700 block">Email</label>
                <input type="email" name='email'
                    class="w-full px-4 py-3 rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                    placeholder="Enter your email address...">
            </div>

            <div class="mb-5 ml-auto mr-auto w-3/5">
                <label for="comments" class="font-bold mb-1 text-gray-700 block">Comments</label>
                <input type="comment" name='comment'
                    class="w-full px-4 py-3 rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                    placeholder="Enter your email address...">
            </div>
            <button type='submit' class="bg-blue-500 hover:bg-blue-400 text-white font-semibold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">Submit</button>
        </div>
    </form>
</div>

@endsection

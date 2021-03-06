@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-center">
        <div class='py-15 border-b border-gray-200'>
            <h1 class='text-6xl'>
                Gift 公式ブログ
            </h1>
        </div>
    </div>

@if(session()->has('message'))
    <div class='w-4/5 m-auto mt-10 pl-2'>
        <p class='w-2/6 mb-4 text-gray-50 bg-green-500 rounded-2xl py-4'>
            {{ session()->get('message') }}
        </p>
    </div>
@endif

@if(Auth()->user())
    <div class='pt-15 w-4/5 m-auto'>
        <a href="/blog/create" class='uppercase bg-blue-500 text-gray-100 font-extrabold py-4 px-8 rounded-3xl z-1'>
            Create Post
        </a>
    </div>
@endif

@foreach($posts as $post)
    <div class='sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b border-gray-200'>
        <div>
            <img src="{{ $post['imageUrl'] }}" alt='' />
        </div>
        <div>
            <h2 class='text-gray-700 font-bold text-5xl pb-4'>
                {{ $post['title'] }}
            </h2>

            <span class='text-gray-500'>
                By <span class='font-bold italic text-gray-800'>{{$post['author']}}</span>, Created on {{ date('jS M Y', strtotime($post['updated_at'])) }}
            </span>

            <p class='text-xl text-gray-700 pt-8 pb-10 leading-8'>

            </p>

            <a href="/blog/{{ $post['slug'] }}" class='uppercase bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl'>
                Keep Reading
        </a>

        @if ( Auth::user() && Auth::user()->getAuthIdentifier() == $post['author'] )
            <span class='float-right'>
                <a href="/blog/{{ $post['slug'] }}/edit" class='text-gray-700 italic hover:text-gray-900 pb-1 border-b-2'>
                    Edit
                </a>
            </span>

            <span class='float-right'>
                <form action="blog/{{ $post['slug'] }}" method='POST'>
                    @csrf
                    @method('delete')
                    <button class='text-red-500 pr-3' type='submit'>
                        Delete
                    </button>
                </form>
            </span>
        @endif

        </div>

    </div>

@endforeach
    {{ $current_page }}
    <div class='flex items-center justify-center'>
        <div class='text-gray-300 flex items-center space-x-2 select-none'>
        @if($current_page != 1)
            <button onclick="location.href='/blog?page={{ $current_page-1 }}'" class='h-8 w-8 p-1 hover:bg-gray-700 rounded page-control'><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg></button>
        @endif

        @if(!$onLastPage)
            <button onclick="location.href='/blog?page={{ $current_page+1 }}'" class='h-8 w-8 p-1 hover:bg-gray-700 rounded page-control'><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
            </svg></button>
        @endif
        </div>
    </div>

@endsection




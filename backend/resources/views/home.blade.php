@extends('layouts.app')

@section('content')

@if(session()->has('message'))
    <div class='w-4/5 m-auto mt-10 pl-2'>
        <p class='w-2/6 mb-4 text-gray-50 bg-green-500 rounded-2xl py-4'>
            {{ session()->get('message') }}
        </p>
    </div>
@endif


    <div class='background-image grid grid-cols-1 m-auto'>
        <div class='flex text-gray-100 pt-10'>
            <div class='m-auto pt-4 pb-16 sm:m-auto w-4/5 block text-center'>
                <h1 class='sm:text-white text-5xl uppercase font-bold text-shadow-md pb-14'>
                    Gift Inovvation 合同会社
                </h1>
                <a href='/blog' class='text-center bg-gray-50 text-gray-700 py-2 px-4 font-bold text-xl uppercase'>
                    Read more
                </a>
            </div>
        </div>
    </div>


    <div class='sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b border-grat-200'>
        <div>
            <img src='https://cdn-ak.f.st-hatena.com/images/fotolife/s/seiproject/20200714/20200714214745.jpg' width='700' alt='' />
        </div>
        <div class='m-auto sm:m-auto text-left w-4/5 block'>
            <h2 class='text-3xl font-extrabold text-gray-600'>
                Gift 王寺駅前自習室
            </h2>
            <p class='py-8 text-gray-500 text-l'>
                ～志のある人を応援するプロジェクト～
            </p>
            <p class='font-extrabold text-gray-600 text-l pb-9'>
                Lurem ipsum, dolor sit amet consectetur adipisicing elit. Sapiente magnam vero nostrum! Perferendis eos molestias porrp vero. Vel alias.
            </p>
            <a href='/blog' class='uppercase bg-blue-500 text-gray-100 text-s font-extrabold py-3 px-8 rounded-3xl'>
                Find Out More
            </a>
        </div>
    </div>

    <div class='text-center p-15 bg-black text-white'>
        <h2 class='text-2xl pb-5 text-l'>
            I'm an expert in ...
        </h2>

        <span class='font-extrabold block text-4xl py-1'>
            Ux Design
        </span>
        <span class='font-extrabold block text-4xl py-1'>
            Project Management
        </span>
        <span class='font-extrabold block text-4xl py-1'>
            Digital Strategy
        </span>
        <span class='font-extrabold block text-4xl py-1'>
            Backend Development
        </span>
    </div>

    <div class='text-center p-15'>
        <h2 class='text-4xl font-bold py-10'>Recent Post</h2>
    </div>

    <div class='sm:grid grid-cols-2 w-4/5 m-auto'>
        <div class='flex bg-gray-400 text-gray-100 pt-10'>
            <div class='m-auto pt-4 pb-16 sm:m-auto w-4/5 block'>
                <h2 class='text-gray-700 font-bold text-5xl pb-16'>
                    {{ $post['title'] }}
                </h2>
                <a href='blog?page=1' class='uppercase bg-transparent border-2 border-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl'>
                    Find Out More
                </a>

            </div>
        </div>
        <div>
            <img src="{{ $post['imageUrl'] }}" width='700' alt='' />
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')

@if(session()->has('message'))
    <div class='w-4/5 m-auto mt-10 pl-2'>
        <p class='w-2/6 mb-4 text-gray-50 bg-green-500 rounded-2xl py-4'>
            {{ session()->get('message') }}
        </p>
    </div>
@endif

    <div class='card'>
        <div class='card-body'>
        <h4 class='card-title' >Book the Appointoment</h4>

        <form action="{{ route('booking.store') }}"  method='POST'>
            @csrf

            <label for='name'>Appointment For:</label>
            <br>
            <textarea name='name' id='' cols='60' rows='3' ></textarea>

            <br>

            <label for='meeting_time' >Choose a time for your appointoment:</label>

            <br>

            <input type='date' name='meeting_date' />
            <input type='time' name='meeting_time' />

            <br>
            <br>

            <input type='submit' value='Submit' />
        </form>
        </div>
    </div>




@endsection

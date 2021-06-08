<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;


class CalendarController extends Controller
{
    public function index()
    {
        $event = new Event;
        $event->name = 'test from app';
        $event->startDateTime = Carbon::now();
        $event->endDateTime = Carbon::now()->addHour();

        $event->save();

        // $e = Event::get();
        dd($event);
    }
}

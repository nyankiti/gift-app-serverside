<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $firstArticle = app('firebase.firestore')->database()->collection('news')->limit(1)->orderBy('updated_at', 'DESC')->documents()->rows()[0]->data();

        // $pattern = '@<p(?:.*?)>(.*?)</p>@s';
        // preg_match_all($pattern, $firstArticle['html'], $result);
        // dd($result);
        return view('home')->with('post', $firstArticle);
    }
}

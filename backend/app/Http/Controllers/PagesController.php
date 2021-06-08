<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        // 画像などのユーザーのデータをSessionに保存する
        if(\Session::get('user') == null or \Session::get('userImg') == null){
            if(\Auth::user() == null){
                // continue
            }else{
                $user = app('firebase.firestore')->database()->collection('users')->document(\Auth::user()->localId)->snapshot()->data();
                \Session::put('user', $user['displayName']);
                \Session::put('userImg', $user['userImg']);
            }
        }


        $firstArticle = app('firebase.firestore')->database()->collection('news')->limit(1)->orderBy('updated_at', 'DESC')->documents()->rows()[0]->data();

        // $pattern = '@<p(?:.*?)>(.*?)</p>@s';
        // preg_match_all($pattern, $firstArticle['html'], $result);
        // dd($result);
        return view('home')->with('post', $firstArticle);
    }
}

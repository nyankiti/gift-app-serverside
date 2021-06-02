<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditProfileController extends Controller
{
    public function index()
    {
        return view('editProfile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048'
        ]);

        // firestorageへの保存
        // // 画像範囲はblobに変換してuploadする  $request->image->get() でblog形式の画像を取得できる

        $bucket = app('firebase.storage')->getBucket();

        // 画像の名前を決定
        $now = new Carbon('now');
        $dateString = str_replace(" ","" ,$now->toDateTimeString());

        $newImageName = $dateString.'-'.$request->title.'.'.$request->image->clientExtension();
        // 画像をfirestorageにupload
        $object = $bucket->upload($request->image->get(), ['name' => 'news/'.$newImageName]);

        // dd($bucket->object('news/'.$newImageName));
        // 署名付きURLで参照できるがいつか期限が切れてしまう
        // dd($object->signedUrl(new Timestamp(new Carbon('2050-01-01'))));
        $downloadUrl = "https://firebasestorage.googleapis.com/v0/b/gift-app-project.appspot.com/o/news%2F".$newImageName."?alt=media";






        // 新しい記事をfirestoreへ保存  imageUrlへには上でアップしたfirestorageのurlを格納する
        $timestamp = new Timestamp($now);

        $newsRef = app('firebase.firestore')->database()->collection('news')->Newdocument();
        $newsRef->set([
            'title' => $request->input('title'),
            // authorはwebアプリでユーザー情報入力ゾーンを実装してから
            'author' => '',
            'imageUrl' => $downloadUrl,
            'html' => $request->input('description'),
            'slug' => $dateString,
            'user_id' =>  \Auth::user()->getAuthIdentifier(),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            ]);
        // -------------------------------------------------------------------

        return redirect('/blog')
            ->with('message', 'Your post has been added!');
    }

}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Core\Timestamp;
use Carbon\Carbon;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('editProfile');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            // 'comments' =>
            'image' => 'mimes:jpg,png,jpeg,svg|max:5048'
        ]);

        $uid = \Auth::user()->localId;

        if($request->image){
            // firestorageへの保存
            // // 画像範囲はblobに変換してuploadする  $request->image->get() でblog形式の画像を取得できる

            $bucket = app('firebase.storage')->getBucket();

            // 画像名はuserid
            $newImageName = $uid.'.'.$request->image->clientExtension();
            // 画像をfirestorageにupload
            $object = $bucket->upload($request->image->get(), ['name' => 'users/'.$newImageName]);

            // 本番環境用
            // $storageUrl = "https://firebasestorage.googleapis.com/v0/b/gift-app-project.appspot.com/o/users%2F".$newImageName."?alt=media";
            // テスト環境用
            $storageUrl = "https://firebasestorage.googleapis.com/v0/b/gift-app-project-test.appspot.com/o/users%2F".$newImageName."?alt=media";

        }

        // dd($storageUrl);


        // 新しいuser情報をfirestoreへ保存  imageUrlへには上でアップしたfirestorageのurlを格納する
        $now = new Carbon('now');
        $timestamp = new Timestamp($now);

        $userRef = app('firebase.firestore')->database()->collection('users')->document($uid);

        if($request->image){
            $userRef->update([
                ['path' => 'displayName', 'value' => $request->input('name')],
                ['path' => 'imageUrl', 'value' => $storageUrl],
                ['path' => 'about', 'value' => $request->input('comment')],
                ['path' => 'updated_at', 'value' => $timestamp,]
            ]);

            \Session::put('userImg', $storageUrl);

        }else{
            $userRef->update([
                ['path' => 'displayName', 'value' => $request->input('name')],
                ['path' => 'about', 'value' => $request->input('comment')],
                ['path' => 'updated_at', 'value' => $timestamp,]
            ]);
        }
        // -------------------------------------------------------------------


        return redirect('/home')
            ->with('message', 'Your Profile has been updated!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

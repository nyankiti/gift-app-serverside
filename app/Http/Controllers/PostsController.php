<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class PostsController extends Controller
{

    public function __construct()
    {
        // このControllerを通る処理でindexとshowメソッドくぉ除いて、auth middlewareを通すことで、
        // ログインしていないユーザーのリクエストを制限する
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $snapShots = app('firebase.firestore')->database()->collection('news')->offset(6)->limit(6)->orderBy('updated_at', 'DESC')->documents()->rows();
        $snapShots = app('firebase.firestore')->database()->collection('news')->limit(6)->orderBy('updated_at', 'DESC')->documents()->rows();

        foreach ($snapShots as $key=>$snapShot){
            $articles[$key] = $snapShot->data();
        }
        // dd(Post::orderBy('updated_at', 'DESC')->get());

        // return view('blog.index')
        //     ->with('posts', Post::orderBy('updated_at', 'DESC')->get());
        return view('blog.index')->with('posts', $articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.wysiwygEditor');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->input('description'));

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048'
        ]);

        // $uniqid() でユニークな16進数文字列を生成する
        $newImageName = uniqid().'-'.$request->title.'.'.$request->image->extension();

        //以下のコードで受け取った画像をサーバーに保存できる
        $request->image->move(public_path('images'), $newImageName);

        // $slug = SlugService::createSlug(Post::class, 'slug', $request->title);
        // titleが英語でないとslugが生成されない、、、
        $slug = SlugService::createSlug(Post::class, 'slug', $request->title);
        // dd($slug);

        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => $slug,
            'image_path' => $newImageName,
            'user_id' => \Auth::user()->getAuthIdentifier()
        ]);

        return redirect('/blog')
            ->with('message', 'Your post has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  istrign  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return view('blog.show')
            ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  istring  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return view('blog.edit')
            ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  strign  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        Post::where('slug', $slug)->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => $slug,
            // 'image_path' => $newImageName,
            'user_id' => \Auth::user()->getAuthIdentifier()
        ]);

        return redirect('/blog')->with('message', 'Your post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $post = Post::where('slug', $slug);
        $post->delete();

        return redirect('/blog')->with('message', 'You post has been deleted!' );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Carbon\Carbon;


class PostsController extends Controller
{

    // protectedのメンバー変数はこのクラスとこのクラスを継承したクラスのみから使える

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
    public function index(Request $request)
    {
        // クエリパラメータは以下の様に参照できる
        // dd($request->page);
        $onLastPage = null;

        if($request->page == 1 || !isset($request->page)){
            $request->page = 1;
            $snapShots = app('firebase.firestore')->database()->collection('news')->limit(6)->orderBy('updated_at', 'DESC')->documents()->rows();

            foreach ($snapShots as $key=>$snapShot){
                $articles[$key] = $snapShot->data();
            }

        }else{
            $snapShots = app('firebase.firestore')->database()->collection('news')->offset(($request->page-1)*6)->limit(6)->orderBy('updated_at', 'DESC')->documents()->rows();

            foreach ($snapShots as $key=>$snapShot){
                $articles[$key] = $snapShot->data();
            }
            // 2017年6月22日に投稿された記事が最初から6番目の記事。その時より前の日付の記事が一番目に取れた場合は次のページが存在しなくなる
            $borderTime = new Carbon('2017-06-23');
            $fetchedTime = new Carbon($articles[0]['updated_at']);
            if($fetchedTime->lte($borderTime)){
                $onLastPage = true;
            }

        }
        // dd(Post::orderBy('updated_at', 'DESC')->get());

        // return view('blog.index')
        //     ->with('posts', Post::orderBy('updated_at', 'DESC')->get());
        return view('blog.index')
            ->with('posts', $articles)
            ->with('current_page', $request->page)
            ->with('onLastPage', $onLastPage);

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
        // 入力された画像ファイルはinputとは別で扱われており、$request->image 独自のインスタンスにアクセスできる
        // ----firestore への保存---------------------------------------------
        // dd($request->input());

        // $newsRef = app('firebase.firestore')->database()->collection('news')->document();

        // // $stuRef = app('firebase.firestore')->database()->collection('student')->newDocument();
        // $newsRef->set([
        //     'title' => $input('title'),
        //     'author' => '',
        //     'imageUrl' => $request,
        //     'html' => $value['html'],
        //     'slug' => $value['slug'],
        //     'user_id' => $value['user_id'],
        //     'created_at' => $value['created_at'],
        //     'updated_at' => $value['updated_at'],
        // ]);


        // -------------------------------------------------------------------
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
        $article = app('firebase.firestore')->database()->collection('news')->where('slug', '=', $slug)->documents()->rows()[0]->data();


        return view('blog.show')
            ->with('post', $article);
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

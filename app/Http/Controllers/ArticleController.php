<?php

namespace App\Http\Controllers;

use App\Article;
use App\Events\SomeEvent;
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use phpDocumentor\Reflection\File;

class ArticleController extends Controller
{

    public $dataTest;
    /**
     * ArticleController constructor.
     */
    public function __construct () {
        $this->middleware('articleGuest', ['only' => ['create', 'edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $articles = Article::paginate(5);
        //$articles->make();
        //$articles = $articles->make(4, 100);

        $data['articles'] = $articles;

        return view('pages.article', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.create_article', ['metod' => 'POST']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $file = $request->file('image');
        //$file->move('/home/vitalik/laravel-site2/www/public/upload/image');
        //dd($file);

        $filename = uniqid('img_', false).'.jpg';

        \Intervention\Image\Facades\Image::make($file)->resize(700, 480)->save('/home/vagrant/Code/Site1/www/public/upload/image/'.$filename, 60);
        \Intervention\Image\Facades\Image::make($file)->resize(300, 200)->save('/home/vagrant/Code/Site1/www/public/upload/image/pre/'.$filename, 60);

        $article = new Article();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->image = $filename;
        $article->save();

        flash('Все ок', 'success');

        return redirect('/articles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $data['item_article'] = \App\Article::findOrFail($id);
        $this->dataTest = $data;
        //test собитий
    //    event(new SomeEvent($this));

        return view('pages.article', $this->dataTest);
    }

//    public function upDataTest () {
//        //dd($this->dataTest['item_article']->title);
//        $this->dataTest['item_article']->title = 'asdaasdasdad';
//    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('pages.edit_article', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {
        $model = Article::findOrFail($id);
        $model->update();
        return redirect('/articles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Article::find($id);
        $filename = '/home/vagrant/Code/Site1/www/public/upload/image/'.$model->image;
        if (File::exists($filename)) {
            File::delete($filename);
        }
        $model->delete();

        return redirect('/articles');
    }
}

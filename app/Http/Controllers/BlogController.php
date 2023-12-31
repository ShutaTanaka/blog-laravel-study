<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller
{
    /**
     *ブログ一覧を表示する
     * @return view
     */
    public function showList()
    {
        $blogs = Blog::all();
        return view('blog.list', ['blogs' => $blogs]);
    }

    /**
     *ブログ詳細を表示する
     *@param int $id
     * @return view
     */
    public function showDetail($id)
    {
        $blog = Blog::find($id);

        if(is_null($blog)) {
            session(['err_msg' => 'データがありません。']);
            return redirect(route('blogs'));
        }

        return view('blog.detail', ['blog' => $blog]);
    }

    /**
     * ブログ登録画面を表示する
     *
     * @return view
     */
    public function showCreate()
    {
        return view('blog.form');
    }

    /**
     * ブログ登録画面を登録する
     *
     * @return view
     */
    public function exeStore(BlogRequest $Request)
    {
        $inputs = $Request->all();

        \DB::beginTransaction();
        try {
            //ブログを登録
            Blog::create($inputs);
            \DB::commit();
        } catch(\Throwable $e) {
            \DB::rollback();
            //var_dump($e);
            abort(500);
        }

        session(['err_msg' => 'ブログを登録しました']);
        return redirect(route('blogs'));
    }

    /**
     *ブログ詳細フォームを表示する
     *@param int $id
     * @return view
     */
    public function showEdit($id)
    {
        $blog = Blog::find($id);

        if(is_null($blog)) {
            session(['err_msg' => 'データがありません。']);
            return redirect(route('blogs'));
        }

        return view('blog.edit', ['blog' => $blog]);
    }


    /**
     * ブログ登録画面を更新する
     *
     * @return view
     */
    public function exeUpdate(BlogRequest $Request)
    {

        $inputs = $Request->all();

        \DB::beginTransaction();
        try {
            //ブログを登録
            $blog = Blog::find($inputs['id']);
            $blog->fill([
                'title' => $inputs['title'],
                'content' => $inputs['content'],
            ]);
            $blog->save();
            \DB::commit();
        } catch(\Throwable $e) {
            \DB::rollback();
            //var_dump($e);
            abort(500);
        }

        session(['err_msg' => 'ブログを更新しました']);
        return redirect(route('blogs'));
    }


    /**
     *ブログ削除
     *@param int $id
     * @return view
     */
    public function exeDelete($id)
    {
        if(empty($id)) {
            session(['err_msg' => 'データがありません。']);
            return redirect(route('blogs'));
        }

        try {
            Blog::destroy($id);
        } catch(\Throwable $e) {
            \DB::rollback();
            //var_dump($e);
            abort(500);
        }

        session(['err_msg' => 'ブログを削除しました']);
        return redirect(route('blogs'));
    }
}

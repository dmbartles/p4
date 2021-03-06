<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\Comment;



class FormController extends Controller
{
  public function main()
  {
    $results = Post::orderBy('updated_at','desc')->with('comments')->with('tags')->get();
    $tags = Tag::orderBy('tag')->get();
    return view('main')->with(['results' => $results,'tags' => $tags]);
  }

  public function index()
  {
    $results = Post::orderBy('updated_at','desc')->with('comments')->with('tags')->get();
    $tags = Tag::orderBy('tag')->get();
    return view('index')->with(['results' => $results,'tags' => $tags]);
  }

  public function create()
  {
    return view('create');
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'user_name' => 'required|min:3|max:25',
      'topic'    => 'required|min:3|max:25',
      'subtopic' => 'required|min:3|max:25',
      'post_text' => 'required|min:3|max:255',
    ]);

    $newpost = new Post();

    $newpost->user_name = $request->input('user_name');
    $newpost->topic     = $request->input('topic');
    $newpost->subtopic  = $request->input('subtopic');
    $newpost->post_text = $request->input('post_text');
    $newpost->save();

    return redirect('/index');
  }

  public function comment(Request $request, $id)
  {
    $this->validate($request, [
      'user_name' => 'required|min:3|max:25',
      'comment_text' => 'required|min:3|max:255',
    ]);

    $newcomment = new Comment();

    $newcomment->post_id = $id;
    $newcomment->user_name = $request->input('user_name');
    $newcomment->comment_text = $request->input('comment_text');
    $newcomment->save();

    return redirect('/index');
  }


  public function edit($id)
  {
    $post = Post::find($id);
    return view('edit')->with([
      'post' => $post
    ]);
  }

  public function tag(Request $request, $id)
  {
    $post = post::find($id);
    $post->tags()->sync($request->input('tag'));
    #$post->tags()->attach($request->input('tag'));
    return redirect('/');
  }

  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'user_name' => 'required|min:3|max:25',
      'topic'    => 'required|min:3|max:25',
      'subtopic' => 'required|min:3|max:25',
      'post_text' => 'required|min:3|max:255',
    ]);

    $updatepost = Post::find($id);

    $updatepost->user_name = $request->input('user_name');
    $updatepost->topic     = $request->input('topic');
    $updatepost->subtopic  = $request->input('subtopic');
    $updatepost->post_text = $request->input('post_text');

    $updatepost->save();

    return redirect('/');
  }

  public function show($id)
  {
    $results = Post::where('id','=',$id)->with('comments')->with('tags')->get();
    $tags = Tag::orderBy('tag')->get();
    return view('index')->with(['results' => $results,'tags' => $tags]);
  }

  public function delete($id)
  {
    $results = Post::where('id','=',$id)->with('comments')->with('tags')->get();
    return view('delete')->with(['results' => $results]);
  }

  public function destroy($id)
  {
    $destroypost = Post::find($id);
    $destroypost->tags()->detach();
    $destroypost->comments()->delete();
    $destroypost->delete();
    return redirect('/');
  }


  public function DumpAll()
  {


    $courses = Comment::all();
    foreach($courses as $course) {
        dump($course->comment_text);
    }

    $courses = Post::with('comments')->get();
    foreach($courses as $course) {
       dump($course->comment);
    }


/*
    echo 'user posts';
    $posts = Post::orderBy('updated_at','desc')->get();
    dump($posts->toArray());

    echo 'user comments';
    $comments = Comment::orderBy('updated_at','desc')->get();
    dump($comments->toArray());

    echo 'tags';
    $tags = Tag::orderBy('tag')->get();
    dump($tags->toArray());

    echo 'all';
    $results = Post::orderBy('updated_at','desc')->with('comments')->with('tags')->get();
    dump($results->toArray());
*/


  }
}

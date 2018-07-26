<?php

namespace App\Http\Controllers;
use App\Page;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    function index ()
    {
        $this->middleware('admin');
        $pages = Page::orderBy('id','desc')->paginate(10);
        return view('admin.pages.pages_list', compact('pages'));
    }

    function view($pageId)
    {
        //setting the search session empty
        session()->forget(['resource1','resource2','resource3','search']);
        session()->save();
        
        $page = Page::find($pageId);

        $translation_id = $page->tnid;
        if($translation_id){
            $translations = Page::where('tnid',$translation_id)->get();
        }else{
            $translations = array();
        }

        return view('pages.pages_view', compact('page','translations'));
    }

    public function create()
    {
        //setting the search session empty
        session()->forget(['resource1','resource2','resource3','search']);
        session()->save();

        return view('pages.page_create');
    }

    public function store(Request $request, Page $page)
    {
        $this->validate($request, [
            'title'      => 'required',
            'language'   => 'required',
            'summary'    => 'required',
            'body'       => 'required',
            'published'  => 'integer'
        ]);

        $page->title = $request->input('title');
        $page->summary = $request->input('summary');
        $page->body = $request->input('body');
        $page->language = $request->input('language');
        $page->user_id = Auth::id();
        $page->status = $request->input('published');
        //inserting
        $page->save();

        $page = Page::find($page->id);
        $page->tnid = $page->id;
        //updating with tnid
        $page->save();

        return redirect('page/'.$page->id)->with('success', 'Item successfully created!');
    }

    public function edit(Page $page, $id)
    {
        $page = $page->find($id);
        return view('pages.page_edit', compact('page'));
    }

    public function update(Request $request, Page $page, $id)
    {
        $this->validate($request, [
            'title'      => 'required',
            'language'   => 'required',
            'summary'    => 'required',
            'body'       => 'required',
            'published'  => 'integer'
        ]);

        $page = Page::find($id);
        $page->title = $request->input('title');
        $page->summary = $request->input('summary');
        $page->body = $request->input('body');
        $page->language = $request->input('language');
        $page->user_id = Auth::id();
        $page->status = $request->input('published');
        //inserting
        $page->save();

        return redirect('page/'.$id)->with('success', 'Item successfully updated!');
    }
}

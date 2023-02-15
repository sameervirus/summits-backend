<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Tag::all();
        $title = 'Tag';
        $titles = 'Tags';
        return view('admin.tags.index', compact('items', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Tag';
        $titles = 'Tags';
        return view('admin.tags.edit', compact('title', 'titles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_english' => ['required', 'string', 'max:255', 'unique:'. Tag::class],
            'name_arabic' => ['required', 'string', 'max:255',],
        ]);

        // return $request->all();

        try {

            $data = $request->except(['_token']);
            $data['slug'] = Str::slug($request->name_english);
            Tag::create($data);

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', ['item' => $tag, 'title' => 'Tag']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name_english' => ['required', 'string', 'max:255', Rule::unique('tags')->ignore($tag->id)],
            'name_arabic' => ['required', 'string', 'max:255',],
        ]);

        try {
            $data = $request->except(['_token', 'image']);
            $data['slug'] = Str::slug($request->name_english);
            $tag->update($data);

            flash('Successfully Updated')->overlay()->success();

        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if($tag->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('admin.tags.index');
    }
}

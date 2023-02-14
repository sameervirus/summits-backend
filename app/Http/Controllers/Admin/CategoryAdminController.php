<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Category::all();
        $title = 'Category';
        $titles = 'Categories';
        return view('admin.categories.index', compact('items', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Category';
        $titles = 'Categories';
        return view('admin.categories.edit', compact('title', 'titles'));
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
            'name' => ['required', 'string', 'max:255', 'unique:'. Category::class],
        ]);

        // return $request->all();

        try {

            DB::beginTransaction();
            $data = $request->except(['_token', 'image']);
            $data['slug'] = Str::slug($request->name);
            $item = Category::create($data);

            DB::commit();

            $item->addMedia($request->image)
            ->toMediaCollection('image');


            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', ['item' => $category, 'title' => 'Category']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
        ]);

        if($category->childs->count() > 0 && $request->parent_id != null) {
            flash('This parent is a child parent must haven\'t a parent')->overlay()->error();
            return redirect()->back()->withInput();
        }



        try {
            DB::beginTransaction();
            $data = $request->except(['_token', 'image']);
            $data['slug'] = Str::slug($request->name);
            $category->update($data);

            DB::commit();

            if($request->has('image')) {
                $category->getFirstMedia('image')->delete();
                $category->addMedia($request->image)
                    ->toMediaCollection('image');
            }

            flash('Successfully Updated')->overlay()->success();

        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->childs()->count() > 0) {
            flash('This parent have child/s category must haven\'t a child/s to delete')->overlay()->error();
            return redirect()->back();
        }
        if($category->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('admin.categories.index');
    }
}

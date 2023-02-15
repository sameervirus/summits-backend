<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ApplicationAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Application::all();
        $title = 'Application';
        $titles = 'Applications';
        return view('admin.applications.index', compact('items', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Application';
        $titles = 'Applications';
        return view('admin.applications.edit', compact('title', 'titles'));
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
            'name_english' => ['required', 'string', 'max:255', 'unique:'. Application::class],
            'name_arabic' => ['required', 'string', 'max:255',],
        ]);

        // return $request->all();

        try {

            $data = $request->except(['_token']);
            $data['slug'] = Str::slug($request->name_english);
            Application::create($data);

            flash('Successfully Added')->overlay()->success();
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.applications.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        return view('admin.applications.edit', ['item' => $application, 'title' => 'Application']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'name_english' => ['required', 'string', 'max:255', Rule::unique('applications')->ignore($application->id)],
            'name_arabic' => ['required', 'string', 'max:255',],
        ]);

        try {
            $data = $request->except(['_token', 'image']);
            $data['slug'] = Str::slug($request->name_english);
            $application->update($data);

            flash('Successfully Updated')->overlay()->success();

        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('admin.applications.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        if($application->delete())
        {
            flash('Successfully Deleted')->overlay();
        } else {
            flash('Something went worng please try again')->overlay()->success();
        }

        return redirect()->route('admin.applications.index');
    }
}

<?php

namespace App\Http\Controllers\Admin\SiteContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\SiteContent\Sitecontent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SitecontentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sitecontent = Sitecontent::all();

        return view('admin.sitecontent.sitecontent', [
            'sitecontent' => $sitecontent
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $return = [];
        $arabic = App::getLocale() == 'ar' ? true : false;
        $sitecontent = Sitecontent::all();
        foreach ($sitecontent as $item) {
            if(Str::contains($item->code, '.')) {
                $parts = explode('.', $item->code);
                if($parts[0] == 'social') {
                    $return[$parts[0]][] = $this->soical($item);
                } else{
                    $return[$parts[0]][$parts[1]] = $arabic ? $item->content_arabic : $item->content_english;
                }
            } else {
                $return[$item->code] = $arabic ? $item->content_arabic : $item->content_english;
            }
        }
        return $return;
    }

    private function soical($item) {
        $parts = explode('.', $item->code);
        return [
            'id'=> $item->id,
            'path'=> $item->content_english,
            'image'=> '/assets/images/social/'. $parts[1] .'.svg',
            'name'=> $parts[1],
            'width'=> 20,
            'height'=> 20,
        ];
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
            'code' => ['required', 'string', 'max:255', 'unique:'. Sitecontent::class],
            'content_english' => ['required'],
            'content_arabic' => ['required'],
        ]);

        $massage = 'Successfully Added';

        SiteContent::create([
            'content_english' => $request->content_english,
            'content_arabic' => $request->content_arabic,
            'code' => $request->code,
        ]);

        flash('Successfully Added')->overlay()->success();

        return redirect()->route('admin.sitecontent.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\SiteContent\Sitecontent  $sitecontent
     * @return \Illuminate\Http\Response
     */
    public function show(Sitecontent $sitecontent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\SiteContent\Sitecontent  $sitecontent
     * @return \Illuminate\Http\Response
     */
    public function edit(Sitecontent $sitecontent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\SiteContent\Sitecontent  $sitecontent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sitecontent $sitecontent)
    {
        DB::beginTransaction();

        try {
            foreach ($request->except(['_token', '_method']) as $key => $value) {
                $key = str_replace('_', '.', $key);
                $ef = SiteContent::where('code','=', $key)
                            ->update([
                                'content_english' => $value[0],
                                'content_arabic' => $value[1]
                            ]);
                if($ef == 0) return $key;
            }
            DB::commit();
            flash('Successfully update')->overlay()->success();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error')->overlay()->error();
            return $th;
        }

        return redirect()->route('admin.sitecontent.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\SiteContent\Sitecontent  $sitecontent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sitecontent $sitecontent)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GroupAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Group::all();
        $title = 'Group';
        $titles = 'Groups';
        return view('admin.groups.index', compact('items', 'title', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Group';
        $titles = 'Groups';
        return view('admin.groups.edit', compact('title', 'titles'));
    }

    public function store(Request $request) {

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'. Group::class],
            'title_english' => ['required', 'string', 'max:255'],
            'title_arabic' => ['required', 'string', 'max:255',],
            'description_english' => ['required'],
            'description_arabic' => ['required'],
            'file' => ['required']
        ]);

        if($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            if($this->checkUploadedFileProperties($extension, $fileSize)) {
                //Where uploaded file will be stored on the server
                $location = 'uploads'; //Created an "uploads" folder for that
                // Upload file
                $file->move($location, $filename);
                // In case the uploaded file path is to be stored in the database
                $filepath = public_path($location . "/" . $filename);
                // Reading file
                $flattened = $this->extractData($filepath);
                if(count($flattened) > 0) {
                    try {
                        DB::beginTransaction();

                        $group = Group::create([
                            'name' => $request->name,
                            'slug' => Str::slug($request->name),
                            'title_english' => $request->title_english,
                            'title_arabic' => $request->title_arabic,
                            'description_english' => $request->description_english,
                            'description_arabic' => $request->description_arabic,
                        ]);

                        $group->products()->attach($flattened);

                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $th;
                    }

                } else {
                    flash('No data on file')->overlay()->error();
                }
            }
        } else {
            flash('No file was uploaded')->overlay()->error();
        }

        return redirect()->route('admin.groups.index');
    }

    public function edit(Group $group) {
        $title = 'Group';
        $titles = 'Groups';
        $item = $group;
        return view('admin.groups.edit', compact('item','title', 'titles'));
    }

    public function update(Request $request, Group $group) {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('groups')->ignore($group->id)],
            'title_english' => ['required', 'string', 'max:255'],
            'title_arabic' => ['required', 'string', 'max:255',],
            'description_english' => ['required'],
            'description_arabic' => ['required'],
        ]);

        $group->slug = Str::slug($request->name);
        $group->name = $request->name;
        $group->title_english = $request->title_english;
        $group->title_arabic = $request->title_arabic;
        $group->description_english = $request->description_english;
        $group->description_arabic = $request->description_arabic;
        $group->save();

        if($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            if($this->checkUploadedFileProperties($extension, $fileSize)) {
                //Where uploaded file will be stored on the server
                $location = 'uploads'; //Created an "uploads" folder for that
                // Upload file
                $file->move($location, $filename);
                // In case the uploaded file path is to be stored in the database
                $filepath = public_path($location . "/" . $filename);
                // Reading file
                $flattened = $this->extractData($filepath);
                if(count($flattened) > 0) {
                    $group->products()->sync($flattened);
                } else {
                    flash('No data on file')->overlay()->error();
                }
            }
        } else {
            flash('No file was uploaded')->overlay()->error();
        }

        return redirect()->route('admin.groups.index');

    }

    public function destroy(Group $group) {
        try {
            DB::beginTransaction();
            $group->products()->detach();
            $group->delete();
            DB::commit();
            flash('Successfully Deleted')->overlay();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Something went worng please try again')->overlay()->success();
            return $th;
        }

        return redirect()->route('admin.groups.index');
    }

    private function checkUploadedFileProperties($extension, $fileSize) {
        $valid_extension = array("csv"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
                return true;
            } else {
                flash('No file was uploaded')->overlay()->error();
                return false;
            }
        } else {
            flash('Invalid file extension')->overlay()->error();
            return false;
        }
    }

    private function extractData($filepath) {
        if (($file = fopen($filepath, "r")) !== FALSE) {
            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0;$c < $num;$c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            $flattened = Arr::flatten($importData_arr);
            return $flattened;

        } else {
            flash('Invalid file')->overlay()->error();
        }

        return [];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.dashboard');
    }

    public function upload_img(Request $request)
    {
        $path = $request->file('file')->storeAs('files', $request->file('file')->getClientOriginalName(),'public_uploads');

        return json_encode(['location' => $path]);
    }

    public function reorder(Request $request)
    {
        $i = 1;
        foreach ($request->item as $value) {
            DB::table($request->table)
                ->where('id', $value)
                ->update(['sort_order' => $i]);
            $i++;
        }

        return 1;
    }

    public function preorder(Request $request)
    {
        $order = str_replace('^','-', json_encode($request->product));
        DB::table('pages')
                ->where('page', 'product_order')
                ->update(['content' => $order]);

        return 1;
    }

    public function delimg(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $product->deleteMedia($request->imgs);

        flash('Successfully Deleted')->success();

        return 'ok';
    }

    public function feedbacks()
    {
        $items = DB::table('feedbacks')->paginate(15);
        return view('admin.feedbacks.index', compact('items'));
    }

}

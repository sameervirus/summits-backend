<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $salesReport = $this->salesReport();
        $compareMonths = $this->compareMonths();
        $registerUsers = $this->registerUsers();
        $userCount = User::count();
        $productsCount = Product::count();
        $orderCount = Order::where('status_id', '<>', 0)->count();
        $sales = Order::where('status_id', '<>', 0)->sum('total');
        return view('admin.dashboard', compact('salesReport', 'compareMonths', 'registerUsers',
                    'userCount', 'productsCount', 'orderCount', 'sales'
                ));
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

        return 'ok';
    }

    public function favimg(Request $request)
    {
        $product = Product::findOrFail($request->id);
        foreach($product->getMedia('images') as $item)
        {
            $item->forgetCustomProperty('fav');
            $item->save();
        }

        $image = Media::find($request->imgs);

        $image->setCustomProperty('fav', true);

        $image->save();

        return 'ok';
    }

    public function feedbacks()
    {
        $items = DB::table('form_submissions')->paginate(15);
            return view('admin.feedbacks.index', compact('items'));
        
    }

    private function salesReport() {
        
        // Get the date range for the last 8 months
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $monthlySales = DB::table('orders')
                        ->select(DB::raw('MONTH(created_at) as month_num, YEAR(created_at) as year_num, SUM(total) as sales'))
                        ->where('created_at', '>=', $sixMonthsAgo)
                        ->groupBy('year_num', 'month_num')
                        ->get();

        // Create an array of all months within the last 6 months
        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $months[] = Carbon::now()->subMonths($i)->format('m/Y');
        }

        // Initialize an empty array to hold the monthly sales data
        $monthlySalesData = [];

        // Loop through each month and get the corresponding sales data
        foreach ($months as $month) {
            $monthYear = explode('/', $month);
            $found = false;
            foreach ($monthlySales as $sales) {
                if ($sales->year_num == $monthYear[1] && $sales->month_num == $monthYear[0]) {
                    $monthlySalesData[] = [
                        'month' => $month,
                        'sales' => $sales->sales
                    ];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $monthlySalesData[] = [
                    'month' => $month,
                    'sales' => 0
                ];
            }
        }

        // Sort the monthly sales data by month and year
        usort($monthlySalesData, function($a, $b) {
            $aDate = Carbon::createFromFormat('m/Y', $a['month']);
            $bDate = Carbon::createFromFormat('m/Y', $b['month']);
            return $aDate->diffInMonths($bDate);
        });


        // Fetch weekly sales data
        $eightWeeksAgo = Carbon::now()->subWeeks(8);

        $weeklySales = DB::table('orders')
                        ->select(DB::raw('WEEK(created_at) as week_num, SUM(total) as sales'))
                        ->where('created_at', '>=', $eightWeeksAgo)
                        ->groupBy('week_num')
                        ->get();

        // Create an array of all weeks within the last 8 weeks
        $weeks = [];
        for ($i = 0; $i < 8; $i++) {
            $weeks[] = Carbon::now()->subWeeks($i)->week;
        }

        // Initialize an empty array to hold the weekly sales data
        $weeklySalesData = [];

        // Loop through each week and get the corresponding sales data
        foreach ($weeks as $week) {
            $found = false;
            foreach ($weeklySales as $sales) {
                if ($sales->week_num == $week) {
                    $weeklySalesData[] = [
                        'week' => $week,
                        'sales' => $sales->sales
                    ];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $weeklySalesData[] = [
                    'week' => $week,
                    'sales' => 0
                ];
            }
        }

        // Sort the weekly sales data by week number
        usort($weeklySalesData, function($a, $b) {
            return $a['week'] - $b['week'];
        });




        // Fetch daily sales data
        // Get the date range for the last 7 days
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        // Get the daily sales data for the date range
        $dailySales = DB::table('orders')
            ->select(DB::raw('COALESCE(DATE(orders.created_at), d.date) as date'), DB::raw('COALESCE(SUM(total), 0) as total_sales'))
            ->rightJoin(DB::raw("(SELECT '$startDate' + INTERVAL n DAY AS date FROM (SELECT 0 n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6) v) d"), function($join) use ($startDate, $endDate) {
                $join->on(DB::raw('DATE(orders.created_at)'), '=', 'd.date')
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->groupBy('date')
            ->get();
        $dailySalesData = [];
        foreach ($dailySales as $sale) {
            $dailySalesData[] = ['day' => date('Y-m-d', strtotime($sale->date)), 'sales' => $sale->total_sales];
        }

        $result = [
            'monthlySales' => $monthlySalesData,
            'weeklySales' => $weeklySalesData,
            'dailySales' => $dailySalesData,
        ];

        return $result;
    }

    private function compareMonths()
    {
        // This year
        $thisYearSales = [];
        $thisYearLabels = [];
        for ($i = 6; $i >= 1; $i--) {
            $month = date('M', strtotime("-$i month")); // Get month name for the i-th previous month
            $thisYearLabels[] = $month;
            $sales = DB::table('orders')
                        ->whereRaw('YEAR(created_at) = YEAR(NOW())')
                        ->whereRaw('MONTH(created_at) = MONTH(NOW())-'.$i)
                        ->sum('total');
            $thisYearSales[] = $sales;
        }

        // Last year
        $lastYearSales = [];
        $lastYearLabels = [];
        for ($i = 6; $i >= 1; $i--) {
            $month = date('M', strtotime("-$i month")); // Get month name for the i-th previous month
            $lastYearLabels[] = $month;
            $sales = DB::table('orders')
                        ->whereRaw('YEAR(created_at) = YEAR(NOW())-1')
                        ->whereRaw('MONTH(created_at) = MONTH(NOW())-'.$i)
                        ->sum('total');
            $lastYearSales[] = $sales;
        }

        // Fill in missing months with 0 sales
        $labels = ['6 months ago', '5 months ago', '4 months ago', '3 months ago', '2 months ago', 'Last month'];
        for ($i = 0; $i < count($labels); $i++) {
            if (!isset($thisYearSales[$i])) {
                $thisYearSales[$i] = 0;
            }
            if (!isset($lastYearSales[$i])) {
                $lastYearSales[$i] = 0;
            }
        }

        // Final data
        $data = [
            'labels' => $thisYearLabels, // Use this year's labels, which should be the same as last year's
            'thisYear' => $thisYearSales,
            'lastYear' => $lastYearSales,
        ];

        return $data;

    }

    private function registerUsers() {
        // Get the start and end dates from the request
        $startDate =  Carbon::now()->subMonth();
        $endDate = Carbon::now();

        // Query the database to get the number of registered users for each day
        $users = User::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Calculate the total number of registered users for each day
        $totalUsers = [];
        $runningTotal = 0;
        foreach ($users as $user) {
            $runningTotal += $user->count;
            $totalUsers[] = $runningTotal;
        }

        // Format the data for Chart.js
        return [
            'labels' => $users->pluck('date')->toArray(),
            'values' => $users->pluck('count')->toArray(),
            'total_users' => $totalUsers
        ];
    }

}

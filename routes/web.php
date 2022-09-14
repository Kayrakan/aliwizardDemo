<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTagController;
use App\Http\Controllers\StoreController;
use App\Pts;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => ['auth', 'web', 'auth:sanctum']], function () {
    Route::get('stores/callback',[StoreController::class,'store'])->name('stores.callback');
    Route::resource('stores', StoreController::class);
    Route::resource('order_tags', OrderTagController::class);
    Route::resource('orders', OrderController::class);

    Route::post('orders/{order}/updateTag/', [OrderController::class, 'update'])->name('updateTag');

    //get all user stores
    Route::get('user_stores', function () {

        return response()->json(Auth::user()->stores()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'store_id'=>$item->store_id,
                'store_url' =>$item->store_url,
                'name' => $item->store_name,
            ];
        }));
    })->name('user_stores');


    Route::get('/app', function () {
        return view('prime');
    })->name('app');
    Route::get('test', function () {
        return redirect()->intended('stores');
    })->name('test');



    Route::group([], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/orders_count', [DashboardController::class, 'orders_count'])->name('orders_count');
        Route::get('dashboard/orders_not_shipped_count', [DashboardController::class, 'orders_not_shipped_count'])->name('orders_not_shipped_count');
        Route::get('dashboard/orders_not_shipped_amount', [DashboardController::class, 'orders_not_shipped_amount'])->name('orders_not_shipped_amount');
        Route::get('dashboard/orders_total_amount', [DashboardController::class, 'orders_total_amount'])->name('orders_total_amount');
        Route::get('dashboard/orders_waiting_buyer_accept_count', [DashboardController::class, 'orders_waiting_buyer_accept_count'])->name('orders_waiting_buyer_accept_count');
        Route::get('dashboard/orders_waiting_buyer_accept_amount', [DashboardController::class, 'orders_waiting_buyer_accept_amount'])->name('orders_waiting_buyer_accept_amount');
        Route::get('dashboard/customers_count', [DashboardController::class, 'customers_count'])->name('customers_count');
        Route::get('dashboard/last_orders', [DashboardController::class, 'last_orders'])->name('last_orders');
        Route::get('dashboard/get_stores', [DashboardController::class, 'get_stores'])->name('get_stores');
        Route::get('dashboard/country_totalorder_with_revenue', [DashboardController::class, 'country_totalorder_with_revenue'])->name('country_totalorder_with_revenue');
        Route::get('dashboard/orders_based_on_tags', [DashboardController::class, 'orders_based_on_tags'])->name('orders_based_on_tags');
        Route::get('dashboard/top_ten', [DashboardController::class, 'top_ten'])->name('top_ten');
        Route::get('dashboard/monthly_sell', [DashboardController::class, 'monthly_sell'])->name('monthly_sell');
        Route::get('dashboard/number_of_products_sold', [DashboardController::class, 'number_of_products_sold'])->name('number_of_products_sold');


    });

//    Route::get('api/dashboard', function () {
//        return response()->json([
//            'orders_count' => Auth::user()->orders->count(),
//            'orders_not_shipped_count' => Auth::user()->orders()->where(['order_status' => 'WAIT_SELLER_SEND_GOODS'])->count(),
//            'orders_not_shipped_amount' => Auth::user()->orders()
//                ->where(['order_status' => 'WAIT_SELLER_SEND_GOODS'])->sum('amount'),
//            'orders_total_amount' => Auth::user()->orders()->
//            whereIn('end_reason',['','buyer_confirm_goods'])->sum('amount'),
//            'orders_waiting_buyer_accept_count' => Auth::user()->orders()
//                ->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->count(),
//            'orders_waiting_buyer_accept_amount' => Auth::user()->orders()
//                ->where(['order_status' => 'WAIT_BUYER_ACCEPT_GOODS'])->sum("amount"),
//            'customers_count' => Auth::user()->orders()->distinct("name")->count(),
//            'last_orders' => Auth::user()->orders()->where(['order_status'=>'WAIT_SELLER_SEND_GOODS'])
//                ->orderBy("order_date",'desc')->limit(50)->get(),
//            'stores' => Auth::user()->stores()->select(['store_name','id'])->get()
//        ]);
//    })->name('api.dashboard');

    Route::get('api/stores', function (){
        $rv = [];
        foreach (Auth::user()->stores as $store){
            $rv[] = [
                'id' => $store->id,
                'store_id' => $store->store_id,
                'name' => $store->store_name,
                'total_orders' => $store->orders->whereIn('end_reason',['','buyer_confirm_goods'])->sum('amount'),
                'url' => $store->store_url
            ];
        }
        return response()->json($rv);
    })->name('api.stores');


    //For getting user name and email
    Route::get('api/userinfo', function() {
        return response()->json([
            'user' => Auth::user()->name,
            'email' => Auth::user()->email
        ]);
    })->name('userinfo');;

    //for changing user name and email
    Route::post('api/changeuserinfo', function(Request $request ) {

        try {
            error_log('trying');

            Auth::user()->update([
                'name' =>$request->get('name'),
                'email' => $request->get('email'),

            ]);


        } catch (Exception $e) {
            error_log('error occured');

            report($e);
            return response()->json($e);

        }

        return response()->json('success', 200);

    })->name('changeuserinfo');


//    for password confirmation
    Route::post('api/confirmUserPassword', function(Request $request) {

        error_log('password confirmation requested');

        if(!Hash::check($request->get('passwordToConfirm'), Auth::user()->getAuthPassword()) ) {

            return response()->json('fail', 401);

        }

        return response()->json('success', 200);

    })->name('confirmUserPassword');

    //for reseting user password
    Route::post('api/resetUserPassword', function(Request $request) {

        error_log('password reset is requested');

        try {

            //password confirmation kisminda kontrol edip tekrar burda kontrol etmemin sebebi belki bir ihtimal guvenlikle alakali bi durum olur :P
            if(Hash::check($request->get('oldPassword'), Auth::user()->getAuthPassword()) ) {

                $user = Auth::user();
                $user->password = Hash::make($request->get('password'));
                $user->save();

            } else {

                return response()->json('fail', 401);

            }


        } catch (Exception $e) {
            error_log('error occured');

            report($e);
            return response()->json($e);

        }

        return response()->json('success', 200);

    })->name('resetUserPassword');


    Route::get('createProforma', function(Request $request ) {


        $proform = new Pts(
            $request->pts_email,
            $request->pts_password,
            'x'
        );

        $data = $proform->create_proforma($request->order_id);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('proforma', ['data'=>$data]);

        return $pdf->stream('proforma.pdf');

    });

});
require __DIR__ . '/auth.php';

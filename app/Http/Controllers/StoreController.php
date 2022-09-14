<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        abort('403');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        return redirect(sprintf('https://oauth.aliexpress.com/authorize?response_type=code&client_id=%s&state=%s&view=web&sp=ae',
            env('AE_APP_KEY'), Auth::id()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreStoreRequest $request): RedirectResponse
    {
//        abort(404);
        $code = $request->get('code');
        $userId = $request->get('state');
        if ($userId != Auth::id()) {
            abort(403, 'You didnt made this request');
        }
        $c = new Client();
        $response = $c->post('https://oauth.aliexpress.com/token', ['form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => env('AE_APP_KEY'),
            'client_secret' => env('AE_APP_SECRET'),
            'code' => $code,
            'sp' => 'ae',
            'redirect_uri' => 'https://wizard.ihsanerdem.com/stores/callback'
        ]]);

        $data = json_decode($response->getBody()->getContents(), true);
        if (array_key_exists('error_msg', $data) || !array_key_exists('access_token', $data)) {
            abort(403, $data['error_msg'] . ' | ' . $data['error_code']);
        }

        $mProfile = getMerchantProfile($data['access_token']);
        $store = Store::updateOrCreate([
            'store_id' => $mProfile->shop_id,
            'user_id' => Auth::id()
        ], [
            'store_url' => $mProfile->shop_url,
            'store_name' => $mProfile->shop_name,
            'access_token' => $data['access_token'],
        ]);

        return redirect(route('app') . '#/stores');//
    }

    /**
     * Display the specified resource.
     *
     * @param Store $store
     * @return Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Store $store
     * @return Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStoreRequest $request
     * @param Store $store
     * @return Response
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Store $store
     * @return Response
     */
    public function destroy(Store $store)
    {
        //
    }
}

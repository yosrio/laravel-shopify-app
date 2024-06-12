<?php
/**
 * AppController
 *
 * PHP version 8.2
 *
 * @package  App\Http\Controllers\Shopify
 * @category Controllers
 * @license  http://opensource.org/licenses/MIT MIT License
 */
namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ShopifyStore;
use Illuminate\Support\Facades\Http;

/**
 * AppController
 *
 * This controller handles app for shopify.
 */
class AppController extends Controller
{
    /**
     * Method install
     *
     * @param Request $request
     *
     * @return void
     */
    public function install(Request $request)
    {
        $apiKey = env('SHOPIFY_CLIENT_ID');
        $host = $request->getHttpHost();
        $shop = $request->input('shop');
        $isEmbedded = $request->input('embedded');
        $scopes = 'read_products,write_products,read_orders,write_orders';
        $nonce = bin2hex(random_bytes(12));
        $accessMode = 'offline';
        $redirectUri = urlencode("https://$host/shop/redirect");

        if (preg_match('/^https?\:\/\/[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com\/?/', "https://$shop")) {
            try {
                $path = "https://{$shop}/admin/oauth/authorize?client_id={$apiKey}&scope={$scopes}&redirect_uri={$redirectUri}&state={$nonce}&grant_options[]={$accessMode}";
                $storeId = ShopifyStore::where('shop_url', $shop)->first();
                $store = ShopifyStore::find($storeId);
                $params = $request->all();

                if ($store && $isEmbedded) {
                    return redirect()->route('shop.home', $params);
                } else {
                    return redirect()->to($path)->send();
                }
            } catch (\Exception $e) {
                Log::error('Install:: ' . $e->getMessage());
            }

            return redirect()->to($path);
        }
    }

    /**
     * Method redirect
     *
     * @param Request $request
     *
     * @return void
     */
    public function redirect(Request $request)
    {
        $shop = $request->input('shop');
        $storeId = ShopifyStore::where('shop_url', $shop)->first();
        $store = ShopifyStore::find($storeId);
        $params = $request->all();

        try {
            if (!$store) {
                $key = env('SHOPIFY_CLIENT_ID');
                $secret = env('SHOPIFY_CLIENT_SECRET');
                $code = $request->input('code');
    
                $data = [
                    'client_id' => $key,
                    'client_secret' => $secret,
                    'code' => $code,
                ];
    
                $response = Http::withHeaders($data)
                    ->post("https://{$shop}/admin/oauth/access_token", $data);
    
                $responseData = $response->json();
    
                $store = new ShopifyStore();
                $store->id = $storeId;
                $store->shop_url = $shop;
                $store->shop_token = $responseData['access_token'];
                $store->save();
            }
        } catch (\Exception $e) {
            Log::error('Redirect:: ' . $e->getMessage());
        }

        return redirect()->route('shop.home', $params);
    }
}

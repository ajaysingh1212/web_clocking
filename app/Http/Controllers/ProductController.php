<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $id, ApiService $api): View
    {
        $product = [];
        $apiError = null;

        try {
            $product = $api->product($id);
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 401) {
                session()->forget('api.auth');
                abort(redirect()->route('login')->withErrors(['email' => 'Session expired. Please sign in again.']));
            }

            $apiError = data_get($exception->response?->json(), 'message', 'Unable to load product details.');
        } catch (ConnectionException) {
            $apiError = 'Network error while loading product details.';
        }

        return view('products.show', compact('product', 'apiError'));
    }
}

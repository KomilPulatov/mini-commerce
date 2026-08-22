<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ServiceProxy;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GatewayController extends Controller
{
    public function __construct(
        private readonly ServiceProxy $proxy,
    ) {}

    public function user(Request $request): Response
    {
        return $this->proxy->forward(
            $request,
            'user',
        );
    }

    public function product(Request $request): Response
    {
        return $this->proxy->forward(
            $request,
            'product',
        );
    }

    public function order(Request $request): Response
    {
        return $this->proxy->forward(
            $request,
            'order',
        );
    }

    public function payment(Request $request): Response
    {
        return $this->proxy->forward(
            $request,
            'payment',
        );
    }
}

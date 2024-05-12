<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    // User
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with(['user', 'items'])->paginate(10);
        if ($orders->isEmpty()) return ApiResponse::not_found('Orders');
        return ApiResponse::response(200, 'Orders Retrived Successfully', $orders);
    }
    public function show($id)
    {
        $order = Order::find($id)->with(['user', 'items'])->firstOrFail();
        if ($order->user_id !== Auth::id() && Auth::user()->is_admin) {
            return ApiResponse::response(403, 'Unauthorized');
        }
        if (!$order) return ApiResponse::not_found('Order');
        $order->load(['user', 'items']);
        return ApiResponse::response(200, 'Order Retrived Successfully', $order);
    }
    public function store(StoreOrderRequest $request)
    {
        try {
            $request->validated();
            $validatedData = $request->validated();

            $order = new Order();
            $order->user_id = Auth::id();
            $order->location_id = $validatedData['location_id'];
            $order->quantity = $validatedData['quantity'];
            $order->total_price = $validatedData['total_price'];
            $order->status = 'pending';
            $order->date_of_delivery = now()->addDays(3);
            $order->save();
            Log::info('Request Items:', ['items' => $request->order_items]);
            $order->items()->createMany($request->order_items);

            return ApiResponse::response(201, 'Order Created Successfully', $order);
        } catch (\Exception $e) {
            return ApiResponse::response(500, 'Error creating order: ' . $e->getMessage());
        }
    }

    public function getOrderItems($id)
    {
        $order_items = Order::where('id', $id)->with('items')->get();
        if ($order_items->isEmpty()) return ApiResponse::not_found('Order items');
        return ApiResponse::response(200, 'Items Retrived Successfully', $order_items);
    }
    public function getUserOrders($id)
    {
        $orders = Order::where('user_id', $id)->with('items')->orderBy('updated_at', 'desc')->get();
        if ($orders->isEmpty()) return ApiResponse::not_found('Orders');
        return ApiResponse::response(200, 'Orders Retrived Successfully', $orders);
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) return ApiResponse::not_found('Order');
        $order->update(['status' => $request->status]);
        return ApiResponse::response(200, 'Status Updated Successfully', $order);
    }
    // Admin
    // public function getAllOrders()
    // {
    //     $orders = Order::with(['user', 'items'])->paginate(10);
    //     if ($orders->isEmpty()) return ApiResponse::not_found('Orders');
    //     return ApiResponse::response(200, 'Orders Retrived Successfully', $orders);
    // }
}

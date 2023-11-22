<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'price' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid input!',
                'error' => $validator->errors(),
            ]);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Success!',
            'data' => $product,
        ]);
    }

    function update(Request $request)
    {
        $validator = Validator::make(array_merge($request->all(), ['id' => $request->id]), [
            'id' => 'numeric|required|exists:products,id',
            'name' => 'string|required',
            'price' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid input!',
                'error' => $validator->errors(),
            ], 422);
        }

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Success!',
            'data' => $product,
        ]);
    }

    function show(Request $request)
    {
        $product = Product::find($request->id);
        
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!',
                'error' => [],
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Success!',
            'data' => $product,
        ]);
    }

    function delete(Request $request)
    {
        $product = Product::find($request->id);
        $deleted = $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Success!',
            'data' => $deleted,
        ]);
    }

    public function login(Request $request)
    {
        // dd($request->only('email', 'password'));
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}

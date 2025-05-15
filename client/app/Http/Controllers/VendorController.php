<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'corporate_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return response([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $validated->errors(),
            ], 403);
        }

        $validatedData = $validated->validated();

        try {
            $vendor = Vendor::create($validatedData);
            Cache::forget('vendors_list');
            Log::info('Vendor created', ['id' => $vendor->id]);

            $response = [
                'status' => 'success',
                'message' => 'Vendor created successfully',
                'data' => [
                    'vendor' => $vendor,
                ],
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Vendor creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to create vendor'], 500);
        }
    }

    public function index()
    {
        try {
            $vendors = Cache::remember('vendors_list', 3600, function () {
                return Vendor::all();
            });

            $response = [
                'status' => 'success',
                'message' => 'Vendors fetched successfully',
                'data' => [
                    'vendors' => $vendors,
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Vendor fetch failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to fetch vendors'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => "email|unique:vendors,email,$id",
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return response([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $validated->errors(),
            ], 403);
        }

        $validatedData = $validated->validated();

        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->update($validatedData);
            Cache::forget('vendors_list');
            Cache::forget("vendor_{$id}");
            Log::info('Vendor updated', ['id' => $id]);

            $response = [
                'status' => 'success',
                'message' => 'Vendor fetched successfully',
                'data' => [
                    'vendor' => $vendor,
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Vendor update failed', ['id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to update vendor'], 500);
        }
    }
}

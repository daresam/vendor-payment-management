<?php

namespace App\Http\Controllers;

use App\Models\Corporate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CorporateController extends Controller
{
    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:corporates',
            'email' => 'required|email|unique:corporates',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return response([
                'status' => 'error',
                'message' => 'Validation error',
                'error' =>  $validated->errors(),
            ], 403);
        }

        try {
            $corporate = Corporate::create($validated->validate());
            Cache::forget('corporates_list');
            Log::info('Corporate created', ['id' => $corporate->id]);

            $response = [
                'status' => 'success',
                'message' => 'Corporate created successfully',
                'data' => [
                    'corporate' => $corporate,
                ],
            ];

            return response()->json($response, 201);

        } catch (\Exception $e) {
            Log::error('Corporate creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to create corporate'], 500);
        }
    }

    public function index()
    {
        try {
            $corporates = Cache::remember('corporates_list', 3600, function () {
                return Corporate::all();
            });

            if (empty($corporates)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No corporates found'], 404);
            }

            $response = [
                'status' => 'success',
                'message' => 'Corporates fetched successfully',
                'data' => [
                    'corporates' => $corporates,
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Corporate fetch failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to fetch corporates'], 500);
        }
    }

    public function show($id)
    {
        try {
            $corporate = Cache::remember("corporate_{$id}", 3600, function () use ($id) {
                return Corporate::findOrFail($id);
            });

            $response = [
                'status' => 'success',
                'message' => 'Corporate fetched successfully',
                'data' => [
                    'corporate' => $corporate,
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Corporate fetch failed', ['id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Corporate not found'], 404);
        }
    }
}

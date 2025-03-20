<?php

namespace App\Http\Controllers;

use App\Models\MamaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Mama Data",
 *     description="API Endpoints for managing mama's pregnancy data"
 * )
 */
class MamaDataController extends Controller
{
    /**
     * Create a new MamaDataController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Remove or comment out this line
        // $this->middleware('auth:api');
    }

    /**
     * Store mama data
     * 
     * @OA\Post(
     *     path="/mama-data",
     *     operationId="storeMamaData",
     *     tags={"Mama Data"},
     *     summary="Store new mama's pregnancy data",
     *     description="Creates a new pregnancy record for the mama user",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Mama's pregnancy information",
     *         @OA\JsonContent(
     *             required={"user_id", "age_group"},
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1,
     *                 description="ID of the user"
     *             ),
     *             @OA\Property(
     *                 property="first_child",
     *                 type="boolean",
     *                 example=true,
     *                 description="Whether this is the first child"
     *             ),
     *             @OA\Property(
     *                 property="age_group",
     *                 type="string",
     *                 enum={"18-24 years old", "25-34 years old", "35-44 years old", "44 years old or above"},
     *                 example="25-34 years old",
     *                 description="Mother's age group"
     *             ),
     *             @OA\Property(
     *                 property="due_date",
     *                 type="string",
     *                 format="date",
     *                 nullable=true,
     *                 example="2024-12-31",
     *                 description="Expected delivery date"
     *             ),
     *             @OA\Property(
     *                 property="first_day_circle",
     *                 type="string",
     *                 format="date",
     *                 nullable=true,
     *                 example="2024-03-01",
     *                 description="First day of last menstrual period"
     *             ),
     *             @OA\Property(
     *                 property="gestational_period",
     *                 type="integer",
     *                 nullable=true,
     *                 minimum=1,
     *                 maximum=42,
     *                 example=28,
     *                 description="Current pregnancy week (1-42 weeks)"
     *             ),
     *             @OA\Property(
     *                 property="baby_gender",
     *                 type="string",
     *                 enum={"boy", "girl", "i don't know yet"},
     *                 example="i don't know yet",
     *                 description="Known or expected gender of the baby"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pregnancy data successfully recorded",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Mama data created successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/MamaData"
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'first_child' => 'boolean',
                'age_group' => 'required|string|in:18-24 years old,25-34 years old,35-44 years old,44 years old or above',
                'due_date' => 'nullable|date|after:today',
                'first_day_circle' => 'nullable|date|before:today',
                'gestational_period' => 'nullable|integer|between:1,42',
                'baby_gender' => 'string|in:boy,girl,i don\'t know yet'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $mamaData = new MamaData($validator->validated());
            $mamaData->save();

            return response()->json([
                'message' => 'Mama data created successfully',
                'data' => $mamaData
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Get mama data by user ID
     * 
     * @OA\Get(
     *     path="/mama-data/{user_id}",
     *     operationId="getMamaData",
     *     tags={"Mama Data"},
     *     summary="Get mama's pregnancy data",
     *     description="Retrieves the most recent pregnancy data for a specific user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/MamaData"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mama data not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Mama data not found"
     *             )
     *         )
     *     )
     * )
     */
    public function show($user_id)
    {
        try {
            $mamaData = MamaData::where('user_id', $user_id)
                               ->orderBy('created_at', 'desc')
                               ->first();
            
            if (!$mamaData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mama data not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $mamaData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving mama data'
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\api;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Intefaces\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="API CRUD Students"
 * )
 *
 * @OA\Server(url="http://localhost:8000")
 */


class StudentController extends Controller
{

    private StudentRepositoryInterface $studentRepositoryInterface;

    public function __construct(StudentRepositoryInterface $studentRepositoryInterface)
    {
        $this->studentRepositoryInterface = $studentRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/students",
     *     tags={"Students"},
     *     summary="Get list of students",
     *     description="Return list of students",
     *     @OA\Response(
     *         response=200,
     *         description="Succesful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/StudentResource")
     *         )
     *      )
     * )
     */
    public function index()
    {
        $data = $this->studentRepositoryInterface->getAll();
        return ApiResponseHelper::sendResponse(StudentResource::collection($data), '', 200);
    }

     /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Get student information",
     *     description="Get student details by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StudentResource")
     *     )
     * )
     */
    public function show(string $id)
    {
        $student = $this->studentRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new StudentResource($student), '', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/students",
     *     tags={"Students"},
     *     summary="Create new student",
     *     description="Create a new student record",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "age"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="age", type="integer", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StudentResource")
     *     )
     * )
    */

    public function store(StoreStudentRequest $request)
    {
        $data = [
            'name' => $request->name,
            'age' => $request->age,
        ];
        DB::beginTransaction();
        try {
            $student = $this->studentRepositoryInterface->store($data);
            DB::commit();
            return ApiResponseHelper::sendResponse(new StudentResource($student), 'Record create succesful', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }


     /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Update student information",
     *     description="Update student record by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "age"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="age", type="integer", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StudentResource")
     *     )
     * )
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'age' => $request->age,
        ];
        DB::beginTransaction();
        try {
            $this->studentRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse(null, 'Record updated succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Delete student record",
     *     description="Delete student by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->studentRepositoryInterface->delete($id);
        return ApiResponseHelper::sendResponse(null, 'Record deleted succesful', 200);
    }
}

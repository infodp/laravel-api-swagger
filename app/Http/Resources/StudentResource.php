<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="StudentResource",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Id of the student"
     *     ),
     * *   @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Name of the student"
     *     ),
     * *   @OA\Property(
     *         property="age",
     *         type="integer",
     *         description="Age of the student"
     *     )
     * )
    */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
        ];
    }
}

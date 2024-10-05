<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * @OA\Schema(
     *     schema="UserResource",
     *     type="object",
     *     title="User Resource",
     *     description="User details for task assignment",
     * 
     *     @OA\Property(property="id", type="integer", example=1, description="User ID"),
     *     @OA\Property(property="name", type="string", example="John Doe", description="Full name of the user"),
     *     @OA\Property(property="email", type="string", example="johndoe@example.com", description="User's email address")
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
        ];
    }
}

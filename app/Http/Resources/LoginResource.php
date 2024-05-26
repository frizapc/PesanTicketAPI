<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LoginResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        [$data] = $this->collection;
        return [
            'data' => [
                        'user_id'=> $data->accessToken->tokenable_id,
                        'token' => explode('|', $data->plainTextToken)[1],
                      ],
            'message' => "Berhasil login",
            'status' => 200,
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->setStatusCode(200);
    }
}

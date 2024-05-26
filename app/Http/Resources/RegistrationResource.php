<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RegistrationResource extends ResourceCollection
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
                        'id'=> $data['id'],
                        'name'=> $data['name'],
                        'email'=> $data['email'],
                      ],
            'message' => "Akun anda berhasil dibuat",
            'status' => 201,
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->setStatusCode(201);
    }
}

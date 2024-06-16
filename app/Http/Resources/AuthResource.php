<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthResource extends ResourceCollection
{
    private $message;
    private $status;
    private $responses;
    public function __construct($message, $status, $responses = '') {
        $this->message = $message; 
        $this->status = $status;    
        $this->responses = $responses; 
    }


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->responses,
            'message' => $this->message,
            'status' => $this->status,
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->setStatusCode($this->status);
    }
}

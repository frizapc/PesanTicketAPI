<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Route;

class EventResource extends ResourceCollection
{
    private $message;
    private $status;
    private $data;
    public function __construct(string $message, int $status, $data = []){
        $this->message = $message;
        $this->status = $status;
        $this->data = $data;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'data' =>  $this->data,
            'message' => $this->message,
            'status' => $this->status,
        ];

        return $result;
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->setStatusCode($this->status);
    }
}

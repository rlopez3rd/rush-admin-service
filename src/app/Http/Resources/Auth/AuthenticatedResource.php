<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'firstname' => $this->firstname ?? null,
            'lastname' => $this->lastname ?? null,
            'username' => $this->username ?? null,
            'email' => $this->email ?? null,
            'phone_number' => $this->phone_number ?? null,
            'address' => $this->address ?? null,
            'postcode' => $this->postcode ?? null,
            'roles' => $this->roles->pluck('name')
        ];;
    }
}

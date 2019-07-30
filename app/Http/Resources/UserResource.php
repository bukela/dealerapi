<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'avatar' => $this->avatar ? asset('/uploads/avatars/'.$this->avatar) : 'no avatar',
            'last_login' => $this->last_login,
            'applications' => $this->applications->where('updated', 1)->count(),
            // 'permissions' => !empty($this->permissions) ? $this->boo($this->permissions) : 'not set',
            'permissions' => $this->permissions,
            'permissions_edit' => $this->permissions_edit,
            'parent_id' => $this->parent_id,
            'isChecked' => false
        ];
    }

    protected function boo($value) {
        
        foreach($value as $val) {

            $woo[] = ucwords(str_replace('-', ' ',  $val));

        }
            return $woo;
    }
}

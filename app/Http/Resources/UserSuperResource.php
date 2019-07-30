<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSuperResource extends JsonResource
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
            'avatar' => $this->avatar ? asset('/uploads/avatars/'.$this->avatar) : 'no avatar',
            'last_login' => $this->last_login,
            'created_at' => date('m/d/y', strtotime($this->created_at)),
            'applications' => $this->applications->where('updated', 1)->count(),
            'role' => ucfirst($this->role),
            'permissions' => !empty($this->permissions) ? $this->boo($this->permissions) : 'not set',
            // 'permissions' => $this->permissions,
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

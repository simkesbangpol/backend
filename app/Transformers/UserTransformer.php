<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'address' => (string) $user->address,
            'phone_number' => (string) $user->phone_number,
            'village_id' => (int) $user->village_id,
            'roles' => $user->getRoleNames(),
            'village' => $user->village,
            'district' => $user->district
        ];
    }
}

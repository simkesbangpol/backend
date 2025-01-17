<?php

namespace App\Models;

use App\Traits\AttributeHashable;
use App\Traits\ModelValidatable;
use App\Traits\QueryFilterable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, QueryFilterable, ModelValidatable, AttributeHashable, HasFactory, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'username', 'phone_number', 'village_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'email', 'address', 'username', 'phone_number', 'village_id'
    ];

    /**
     * The fields that should be filterable by query.
     *
     * @var array
     */
    protected $filterable = [
        'name', 'email',
    ];

    /**
     * Hash the attributes before saving.
     *
     * @var array
     */
    protected $hashable = [
        'password',
    ];

    protected $appends = [
        'roles',
        'village',
        'district'
    ];

    /**
     * Validation rules for the model.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*' => [
                'name' => 'required',
            ],
            'CREATE' => [
                'email' => 'required|unique:users,email',
                'password' => 'required|min:6',
            ],
            'UPDATE' => [
                'email' => 'required|unique:users,email,' . $this->id,
                'password' => 'sometimes|min:6',
            ],
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getRolesAttribute(){
        return $this->roles()->get();
    }

    public function getDistrictAttribute(){
        return $this->village()->first()->district()->first();
    }

    public function getVillageAttribute(){
        return $this->village()->first();
    }

    public function reports(){
        return $this->hasMany(Report::class, 'user_id', 'id');
    }

    public function village(){
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }
}

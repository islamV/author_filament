<?php

namespace App\Repositories\v1\Implementation\Author\Auth;

use App\Models\User;
use App\Repositories\v1\Interface\Author\Auth\IUser;

class UserRepository implements IUser
{

    public function get($limit = 10)
    {
        return User::paginate($limit);
    }
    public function getByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function show($model)
    {
        return User::findOrFail($model->id);
    }

    public function store($model)
    {
        return User::create($model);
    }

    public function update($model)
    {
        return $model->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }
}

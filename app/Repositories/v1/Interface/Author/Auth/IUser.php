<?php

namespace App\Repositories\v1\Interface\Author\Auth;

interface IUser
{
    public function get($limit);
    public function getByEmail($email);
    public function show($model);
    public function store($model);
    public function update($model);
    public function delete($model);

}

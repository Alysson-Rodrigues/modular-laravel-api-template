<?php

namespace App\Modules\Users\Repositories;

use App\Bootstrap\Repositories\Repository;
use App\Modules\Users\Models\User;

class UserRepository extends Repository
{
    /**
     * UsersRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function fetchAll($search = null, $perPage = null)
    {
        return $this->model
            ->newQuery()
            ->orderByDesc('created_at')
            ->where('id', '!=', auth()->user()->id)
            ->paginate($perPage ?? 10);
    }
}

<?php

namespace App\Modules\Comments\Repositories;

use App\Bootstrap\Repositories\Repository;
use App\Modules\Comments\Models\Comment;

class CommentRepository extends Repository
{
    /**
     * CommentsRepository constructor.
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function fetchFromUser($user)
    {
        return $this->model
            ->newQuery()
            ->where('user_id', $user)
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}

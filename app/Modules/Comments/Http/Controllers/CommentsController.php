<?php

namespace App\Modules\Comments\Http\Controllers;

use App\Bootstrap\Http\Controllers\Controller;
use App\Modules\Users\Models\User;
use App\Modules\Comments\Models\Comment;
use App\Modules\Comments\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(User $user)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $comments = $this->commentRepository->fetchFromUser(
            $user->id,
        );

        return response(
            $comments,
            200
        );

    }
    public function update(Comment $comment, Request $request)
    {
        $inputs = $request->all();

        $this->commentRepository->update($comment, $inputs);

        return response(
            json_encode(
                [
                    'message' => 'Comment updated successfully',
                    'comment' => $comment
                ]
            ),
            200
        );
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $inputs['user_id'] = auth()->user()->id;

        $comment = $this->commentRepository->create($inputs);

        return response(
            json_encode(
                [
                    'message' => 'Comment created successfully',
                    'comment' => $comment
                ]
            ),
            200
        );
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('comments.destroy');
        $this->commentRepository->destroy($comment);
        return redirect()->back();
    }
}

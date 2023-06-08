<?php

namespace App\Modules\Users\Http\Controllers;

use App\Bootstrap\Http\Controllers\Controller;
use App\Modules\Users\Models\User;
use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        if (!auth()->user()) {
            abort(403);
        }

        $users = $this->userRepository->fetchAll(
            $request->get('search'),
            $request->get('perPage')
        );

        return response(
            $users,
            200
        );
    }

    public function store(Request $request)
    {
        if (!auth()->user()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        return response(
            json_encode(
                [
                    'message' => 'User created successfully',
                    'user' => $user
                ]
            ),
            200
        );
    }

    public function destroy(User $user)
    {
        if (!auth()->user()) {
            abort(403);
        }

        $user->delete();

        return response(
            json_encode(
                [
                    'message' => 'User deleted successfully'
                ]
            ),
            200
        );
    }

    public function me()
    {
        if (!auth()->user()) {
            abort(403);
        }

        return response(
            auth()->user()
        );
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,id,' . $user->id,
        ]);

        $user->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        return response(
            $user,
            200
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                return redirect()->route('dashboard')->with('error','Sin acceso.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return Inertia::render('Admin/User/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => new UserCollection(
                User::orderBy('id')
                    ->filter(Request::only('search', 'role', 'trashed'))
                    ->paginate()
                    ->appends(Request::all())
            ),
            'can' => [
                'users_viewAny' => $this->authorize('viewAny', User::class),
            ]
        ]);
    }

    public function create()
    {

        return Inertia::render('Admin/User/Create', [
            'roles' => UserRole::TYPES,
            'can' => [
                'users_create' => $this->authorize('create', User::class),
            ]
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        User::create(
            $request->validated()
        );

        return Redirect::route('users')->with('success', 'Usuario registrado.');
    }

    public function edit(User $user)
    {
        $this->authorize('view', $user);
        
        return Inertia::render('Admin/User/Edit', [
            'user' => new UserResource($user),
            'roles' => UserRole::TYPES,
        ]);
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $this->authorize('update', $user);
        $user->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $this->authorize('restore', $user);
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}

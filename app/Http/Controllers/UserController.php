<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserShowResouce;
use App\Models\Role;
use App\Services\UserService;
use App\Traits\ResponseFormatterTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseFormatterTrait;


    public function __construct(
        private UserService $userService
    )
    {}

    public function index(Request $request) {
        return $this->responseWithPagination(
            message: 'Users retrieved successfully',
            data: UserShowResouce::
                collection($this->userService->index($request))
        );
    }

    public function show(int $id) 
    {
        return $this->responseSuccess(
            message: 'User retrieved successfully',
            data: ['user' => UserShowResouce::make($this->userService->show($id))]
        );
    }

    public function store(UserStoreRequest $request) {
        return $this->responseSuccess(
            message: 'User created successfully',
            data: ['user' => UserShowResouce::make($this->userService->create($request))]
        );
    }

    public function update(UserUpdateRequest $request, int $id) {
        return $this->responseSuccess(
            message: 'User updated successfully',
            data: ['user' =>UserShowResouce::make($this->userService->update($request, $id))]
        );
    }

    public function delete(int $id) 
    {
        $isDeleted = $this->userService->delete($id);
        if ($isDeleted) {
            return $this->responseSuccess(
                message: 'User deleted successfully',
            );
        } else {
            return $this->responseError(
                message: 'Delete was unsuccessful',
            );
        }
    }

    public function deleteMany(Request $request) {
        $isDeleted = $this->userService->deleteUsers($request);
        if ($isDeleted) {
            return $this->responseSuccess(
                message: 'Users deleted successfully',
            );
        } else {
            return $this->responseError(
                message: 'Delete was unsuccessful',
            );
        }
    }
}

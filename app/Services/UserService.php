<?php
namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private User $user
    )
    {}

    public function index(Request $request) 
    {
        $data = $this->user->with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->search.'%';
                $query->where(function ($query) use ($search) {
                    $query->where('firstname', 'like', $search)
                    ->orWhere('lastname', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('address', 'like', $search)
                    ->orWhere('phone_number', 'like', $search)
                    ->orWhere('postcode', 'like', $search)
                    ->orWhere(DB::raw("CONCAT(firstname,' ', lastname)"), 'like', $search)
                    ->orWhere(DB::raw("CONCAT(lastname,' ', firstname)"), 'like', $search);
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate((int) $request->per_page);

        return $data;
    }

    public function show(int $id) 
    {
        $data = $this->user->findOrFail($id);
        
        return $data;
    }

    public function create(Request $request) 
    {
        $validated = $request->validated();
        $data = $this->user->create($validated);
        $randomRole = rand(1, 2); 
        $data->roles()->attach();

        return $data;
    }

    public function update(Request $request, int $id) 
    {
        $validated = $request->validated();

        $data = $this->user->findOrFail($id);

        $data = tap($data)->update($validated);

        return $data;
    }

    public function delete(int $userId) 
    {
        $this->deleteUserValidation($userId);
        $isDeleted = $this->user->where('id', $userId)->delete();

        return $isDeleted;
    }

    public function deleteUsers(Request $request) 
    {
        $isDeleted = $this->user->whereIn('id', $request->user_ids)->delete();

        return $isDeleted;
    }

    public function deleteUserValidation(int $userId)
    {
       $authUser = Auth::user();

       if ($authUser->id === $userId) {
            throw BusinessRuleException::invoke(message: 'Deleting own account is forbidden.', status: Response::HTTP_FORBIDDEN );
       }

       $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })->get();

       if ($adminUsers->count() === 1) {
            throw BusinessRuleException::invoke(message: 'Deleting the only admin is forbidden.');
       }
    }

    public function deleteUsersValidation(array $userIds)
    {
       $authUser = Auth::user();
       
       if (in_array($authUser->id, $userIds)) {
            throw BusinessRuleException::invoke(message: 'Deleting own account is forbidden.', status: Response::HTTP_FORBIDDEN );
       }

       $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })->get();

       if ($adminUsers->count() === 1) {
            throw BusinessRuleException::invoke(message: 'Deleting the only admin is forbidden.');
       }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        if (request()->header('HX-Request')) {
            return view('admin.users.partials.user-form');
        }

        return view('admin.users.create');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'role':
                $query->orderBy('role', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $users = $query->paginate(15)->withQueryString();

        if ($request->header('HX-Request')) {
            return view('admin.users.partials.table-rows', compact('users'));
        }

        return view('admin.users.index', compact('users'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 'active',
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        } else {
            $userData['password'] = Hash::make('password');
        }

        $user = User::create($userData);

        if ($request->header('HX-Request')) {
            return response('<div id="success-trigger" data-message="Đã tạo người dùng thành công" hx-get="' . route('admin.users') . '?' . http_build_query($request->only(['search', 'role', 'status', 'sort'])) . '" hx-target="#rows" hx-swap="innerHTML" hx-trigger="load" hx-headers=\'{"HX-Request": "true"}\'></div><script>document.getElementById("modal").classList.add("hidden"); document.getElementById("modal").classList.remove("flex"); const toast = document.getElementById("toast-success"); const message = document.getElementById("toast-message"); if(toast && message) { message.textContent = "Đã tạo người dùng thành công"; toast.classList.remove("hidden"); setTimeout(() => { toast.classList.add("hidden"); }, 3000); }</script>');
        }

        return redirect()->route('admin.users')->with('success', 'Đã tạo người dùng thành công');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if ($request->header('HX-Request')) {
            return response('<div id="success-trigger" data-message="Đã cập nhật người dùng thành công" hx-get="' . route('admin.users') . '?' . http_build_query($request->only(['search', 'role', 'status', 'sort'])) . '" hx-target="#rows" hx-swap="innerHTML" hx-trigger="load" hx-headers=\'{"HX-Request": "true"}\'></div><script>document.getElementById("modal").classList.add("hidden"); document.getElementById("modal").classList.remove("flex"); const toast = document.getElementById("toast-success"); const message = document.getElementById("toast-message"); if(toast && message) { message.textContent = "Đã cập nhật người dùng thành công"; toast.classList.remove("hidden"); setTimeout(() => { toast.classList.add("hidden"); }, 3000); }</script>');
        }

        return redirect()->route('admin.users')->with('success', 'Đã cập nhật người dùng thành công');
    }

    public function edit(User $user)
    {
        if (request()->header('HX-Request')) {
            return view('admin.users.partials.user-form', compact('user'));
        }

        return view('admin.users.edit', compact('user'));
    }

    public function show(User $user)
    {
        if (request()->header('HX-Request')) {
            return view('admin.users.partials.user-detail', compact('user'));
        }

        return view('admin.users.show', compact('user'));
    }

    public function disable(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->user_ids)->update(['status' => 'disabled']);

        if ($request->header('HX-Request')) {
            $query = User::query();
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'role':
                    $query->orderBy('role', 'asc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
            $users = $query->paginate(15)->withQueryString();
            return view('admin.users.partials.table-rows', compact('users'))->with('success', 'Đã vô hiệu hóa người dùng thành công');
        }

        return redirect()->route('admin.users')->with('success', 'Đã vô hiệu hóa người dùng thành công');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'disabled' : 'active'
        ]);

        if (request()->header('HX-Request')) {
            $request = request();
            $query = User::query();
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'role':
                    $query->orderBy('role', 'asc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
            $users = $query->paginate(15)->withQueryString();
            return view('admin.users.partials.table-rows', compact('users'))->with('success', 'Đã cập nhật trạng thái thành công');
        }

        return redirect()->route('admin.users')->with('success', 'Đã cập nhật trạng thái thành công');
    }
}

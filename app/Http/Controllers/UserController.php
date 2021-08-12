<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Http\Requests\UserRequest;
use App\Mail\ChangedPasswordMail;
use App\Mail\ResetPasswordMail;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use DataTables;

class UserController extends Controller
{
    protected $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): View
    {
        return view('user.index', [
            'page_title' => 'Users',
            'breadcrumb_parent' => 'Users',
        ]);
    }

    public function create(): View
    {
        return view('user.create', [
            'page_title' => 'Add User',
            'breadcrumb_parent' => 'Users',
            'breadcrumb_child' => 'Add'
        ]);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        try {
            if ($request->ajax()) {
                if ($request->isMethod('POST')) {
                    $input = $request->only(['firstname', 'lastname', 'email', 'password']);

                    $temporary_password = Str::random(8);

                    $user = $this->userRepository->create([
                        'firstname' => $input['firstname'],
                        'lastname' => $input['lastname'],
                        'email' => $input['email'],
                        'password' => Hash::make($temporary_password)
                    ]);

                    if ($user) {
                        Mail::to($user->email)->send(new ResetPasswordMail($user, $temporary_password));

                        $response['success'] = true;
                        $http_code = 200;
                    }
                }
            }
        } catch (\Exception $exception) {
            $http_code = $exception->getCode();
        }


        return response()->json($response, $http_code);
    }

    public function edit($code): View
    {
        if (empty($code)) {
            abort(404);
        }

        $user = $this->userRepository->findByUserCode($code);

        if (! $user->exists) {
            abort(404);
        }

        return view('user.edit', [
            'page_title' => 'Update User',
            'breadcrumb_parent' => 'Users',
            'breadcrumb_child' => 'Update',
            'user' => $user
        ]);
    }

    public function update(UserRequest  $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        try {
            if ($request->ajax()) {
                $user = $this->userRepository->findByUserCode($code);

                if ($user->exists) {
                    $user->firstname = $request->get('firstname');
                    $user->lastname = $request->get('lastname');
                    $user->email = $request->get('email');

                    if ($user->save()) {
                        $response['success'] = true;
                        $http_code = 200;
                    }
                }
            }
        } catch (\Exception $exception) {
            $http_code = $exception->getCode();
        }

        return response()->json($response, $http_code);
    }

    public function delete(CommonRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;
        $code = $request->all();

        try {
            if ($request->ajax()) {
                $user = $this->userRepository->findByUserCode($code);

                if ($this->userRepository->delete($user)) {
                    $response['success'] = true;
                    $http_code = 200;
                }
            }
        } catch (\Exception $exception) {
            $http_code = $exception->getCode();
        }

        return response()->json($response, $http_code);
    }

    public function reset_password(CommonRequest $request, $code): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        try {
            if ($request->ajax()) {
                $user = $this->userRepository->findByUserCode($code);

                if (!empty($user)) {
                    $temporary_password = Str::random(10);
                    $hashed_temporary_password = Hash::make($temporary_password);

                    $user->password = $hashed_temporary_password;

                    if ($user->save()) {
                        Mail::to($user->email)->send(new ResetPasswordMail($user, $temporary_password));

                        $response['success'] = true;
                        $http_code = 200;
                    }
                }
            }
        } catch (\Exception $exception) {
            $http_code = $exception->getCode();
        }

        return response()->json($response, $http_code);
    }

    public function change_password(): View
    {
        return view('user.change_password', [
            'page_title' => 'Change Password',
            'breadcrumb_parent' => 'Users',
            'breadcrumb_child' => 'Change Password'
        ]);
    }

    public function save_change_password(CommonRequest $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        try {
            if ($request->ajax()) {
                $input = $request->validate([
                    'old_password' => ['required'],
                    'new_password' => ['required', 'min:8'],
                    're_type_password' => ['required', 'same:new_password']
                ]);

                $user = $this->userRepository->find(Auth::id());

                if ($user) {
                    if (! Hash::check($input['old_password'], $user->password)) {
                        $response['message'] = "Old password field doesn't matched to your current password";

                        return response()->json($response, $http_code);
                    }

                    $new_password = Hash::make($input['new_password']);

                    $user->password = $new_password;

                    if ($user->save()) {
                        Mail::to($user->email)->send(new ChangedPasswordMail($user));

                        $response['success'] = true;
                        $http_code = 200;
                    }
                }
            }
        } catch (\Exception $exception) {
            $http_code = $exception->getCode();
        }

        return response()->json($response, $http_code);
    }

    public function get_data(CommonRequest $request): JsonResponse
    {
        $data = $this->userRepository->all();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return trim("{$row->firstname} {$row->lastname}", " ");
            })
            ->addColumn('active', function ($row) {
                return is_null($row->deleted_at) ? "<span class='text-success'><i class='fa fa-check-square' aria-hidden='true'></i></span>" : "<span class='text-secondary'><i class='fa fa-check-square-o' aria-hidden='true'></i></span>";
            })
            ->addColumn('actions', function ($row) {
                $buttons = "<button class='btn btn-link toggle-edit-user' title='Edit' data-code='". $row->user_code ."'><i class='fa fa-eye' aria-hidden='true'></i></button>";
                $buttons .= "<button class='btn btn-link text-secondary toggle-remove-user mr-3' title='Remove' data-code='". $row->user_code ."'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                $buttons .= "<button class='btn btn-link text-info toggle-reset-password' title='Reset Password' data-code='". $row->user_code ."'><i class='fa fa-window-restore' aria-hidden='true'></i></button>";

                return $buttons;
            })
            ->escapeColumns([])
            ->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FormController extends Controller
{
    /**
     * Display a paginated list of all users.
     */
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Display the user creation form.
     */
    public function create(): View
    {
        return view('createUser');
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // Password is automatically hashed by the User model cast.
        User::create($validatedData);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the user edit form.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update an existing user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): RedirectResponse {
        $validatedData = $request->validated();

        /*
         * If password is empty, remove it from the update data
         * so the existing password remains unchanged.
         */
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }

        // Password is automatically hashed by the User model cast.
        $user->update($validatedData);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Validate create form fields through AJAX.
     */
    public function validateAjax(Request $request): JsonResponse
    {
        $input = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        $formRequest = new StoreUserRequest;

        $rules = $formRequest->rules();
        $messages = $formRequest->messages();

        /*
         * During real-time validation, confirmed is handled
         * separately by the frontend.
         */
        $rules['password'] = array_values(
            array_filter(
                $rules['password'],
                fn ($rule) => $rule !== 'confirmed'
            )
        );

        $validator = Validator::make(
            $input,
            $rules,
            $messages
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Validate edit form fields through AJAX.
     */
    public function validateUpdateAjax(
        Request $request,
        User $user
    ): JsonResponse {
        $input = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        $formRequest = new UpdateUserRequest;

        $rules = $formRequest->rulesForUser($user);
        $messages = $formRequest->messages();

        /*
         * Password confirmation is checked when the complete
         * form is submitted.
         */
        $rules['password'] = array_values(
            array_filter(
                $rules['password'],
                fn ($rule) => $rule !== 'confirmed'
            )
        );

        $validator = Validator::make(
            $input,
            $rules,
            $messages
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
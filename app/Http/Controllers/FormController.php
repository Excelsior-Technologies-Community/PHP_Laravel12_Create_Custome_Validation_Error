<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
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
     * Store a newly created user in database.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
     * Validate form fields via AJAX (real-time, no page refresh).
     *
     * Returns JSON with field-level validation errors.
     * The "confirmed" rule is skipped here because it is better
     * handled client-side during typing; the full validation
     * still runs on actual form submission.
     */
    public function validateAjax(Request $request): JsonResponse
    {
        $input = $request->only(['name', 'email', 'password', 'password_confirmation']);

        $rules = (new StoreUserRequest)->rules();
        $messages = (new StoreUserRequest)->messages();

        // Remove the 'confirmed' rule for real-time validation;
        // password confirmation is validated on final form submit.
        $passwordRules = [];
        foreach ($rules['password'] as $rule) {
            if (is_string($rule) && $rule === 'confirmed') {
                continue;
            }
            $passwordRules[] = $rule;
        }
        $rules['password'] = $passwordRules;

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json(['success' => true]);
    }
}

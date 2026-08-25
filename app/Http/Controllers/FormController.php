<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FormController extends Controller
{
    /**
     * Display users.
     */
    public function index(): View
    {
        $users = User::orderBy(
            'created_at',
            'desc'
        )->paginate(10);

        return view(
            'users.index',
            compact('users')
        );
    }

    /**
     * Create user form.
     */
    public function create(): View
    {
        return view('createUser');
    }

    /**
     * Store user.
     */
    public function store(
        StoreUserRequest $request
    ): RedirectResponse {
        User::create(
            $request->validated()
        );

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    /**
     * Edit user.
     */
    public function edit(User $user): View
    {
        return view(
            'users.edit',
            compact('user')
        );
    }

    /**
     * Update user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): RedirectResponse {
        $validatedData =
            $request->validated();

        if (
            empty($validatedData['password'])
        ) {
            unset(
                $validatedData['password']
            );
        }

        $user->update($validatedData);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    /**
     * Delete user.
     */
    public function destroy(
        User $user
    ): RedirectResponse {
        $name = $user->name;

        $user->delete();

        return back()->with(
            'success',
            "User {$name} deleted successfully."
        );
    }

    /**
     * AJAX create validation.
     */
    public function validateAjax(
        Request $request
    ): JsonResponse {
        $input = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        $formRequest =
            new StoreUserRequest;

        $rules =
            $formRequest->rules();

        $messages =
            $formRequest->messages();

        $validator = Validator::make(
            $input,
            $rules,
            $messages
        );

        $response = [
            'success' =>
            ! $validator->fails(),
            'errors' =>
            $validator->errors(),
        ];

        if (
            ! empty($input['name'])
        ) {
            $duplicateName =
                User::where(
                    'name',
                    $input['name']
                )->exists();

            $response['duplicate_name'] =
                $duplicateName;
        }

        if ($validator->fails()) {
            return response()->json(
                $response,
                422
            );
        }

        return response()->json(
            $response
        );
    }

    /**
     * AJAX update validation.
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

        $formRequest =
            new UpdateUserRequest;

        $rules =
            $formRequest->rulesForUser($user);

        $messages =
            $formRequest->messages();

        $validator = Validator::make(
            $input,
            $rules,
            $messages
        );

        $response = [
            'success' =>
            ! $validator->fails(),
            'errors' =>
            $validator->errors(),
        ];

        if (
            ! empty($input['name'])
        ) {
            $duplicateName =
                User::where(
                    'name',
                    $input['name']
                )
                ->where(
                    'id',
                    '!=',
                    $user->id
                )
                ->exists();

            $response['duplicate_name'] =
                $duplicateName;
        }

        if ($validator->fails()) {
            return response()->json(
                $response,
                422
            );
        }

        return response()->json(
            $response
        );
    }

    /**
     * Export users to CSV.
     */
    public function exportCsv(): Response
    {
        $users = User::orderBy(
            'created_at',
            'desc'
        )->get();

        $filename =
            'users-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        $handle = fopen(
            'php://temp',
            'w+'
        );

        fputcsv($handle, [
            'ID',
            'Name',
            'Email',
            'Created At',
        ]);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at
                    ? $user->created_at
                    ->format(
                        'Y-m-d H:i:s'
                    )
                    : '',
            ]);
        }

        rewind($handle);

        $csv = stream_get_contents(
            $handle
        );

        fclose($handle);

        return response(
            $csv,
            200,
            [
                'Content-Type' =>
                'text/csv; charset=UTF-8',

                'Content-Disposition' =>
                'attachment; filename="' .
                    $filename .
                    '"',
            ]
        );
    }
}

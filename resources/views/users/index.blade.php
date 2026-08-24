<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users - Laravel 12 Custom Validation</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />

    @vite(['resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
            border: none;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header i {
            color: #f8b400;
            margin-right: 0.5rem;
        }

        .card-body {
            padding: 2rem;
            background: #fafafa;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 0.6rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .table {
            border-radius: 0.6rem;
            overflow: hidden;
        }

        .table thead th {
            background: #e5e7eb;
            border: none;
            font-weight: 600;
            color: #374151;
        }

        .table tbody tr {
            transition: background 0.15s ease;
        }

        .table tbody tr:hover {
            background: #f3f4f6;
        }

        .table td {
            vertical-align: middle;
            border-bottom: 1px solid #e5e7eb;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 0.4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin-left: 0.3rem;
            transition: all 0.15s ease;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-edit:hover {
            background: #dbeafe;
            transform: scale(1.08);
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fee2e2;
            transform: scale(1.08);
        }

        .pagination .page-link {
            border-radius: 0.4rem !important;
            margin: 0 2px;
            color: #667eea;
            border-color: #d1d5db;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
        }

        .alert {
            border-radius: 0.6rem;
            margin-bottom: 1.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <div class="card-header">

            <span>
                <i class="fa fa-users"></i>
                Registered Users
            </span>

            <a
                href="{{ route('users.create') }}"
                class="btn btn-success"
            >
                <i class="fa fa-plus"></i>
                Add New User
            </a>

        </div>

        <div class="card-body">

            @if (session('success'))
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >
                    <i class="fa fa-check-circle me-2"></i>
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

            @if ($users->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($users as $user)

                                <tr>

                                    <td>
                                        {{
                                            $users->currentPage() == 1
                                                ? $loop->iteration
                                                : ($users->perPage() * ($users->currentPage() - 1))
                                                    + $loop->iteration
                                        }}
                                    </td>

                                    <td>
                                        <div class="avatar">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                        {{ $user->email }}
                                    </td>

                                    <td>
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>

                                    <td class="text-end">

                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('users.edit', $user) }}"
                                            class="action-btn btn-edit"
                                            title="Edit User"
                                        >
                                            <i class="fa fa-pen"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            style="display:inline-block;"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn btn-delete"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete {{ $user->name }}?')"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>

            @else

                <div class="empty-state">

                    <i class="fa fa-user-slash"></i>

                    <h5
                        class="mb-3"
                        style="color:#9ca3af;"
                    >
                        No users found
                    </h5>

                    <a
                        href="{{ route('users.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="fa fa-plus"></i>
                        Create first user
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
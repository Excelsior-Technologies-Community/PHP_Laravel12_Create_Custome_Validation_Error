<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Users - Laravel 12 Custom Validation</title>

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            min-height: 100vh;

            padding: 35px 20px;

            background:
                radial-gradient(circle at top left,
                    rgba(255, 255, 255, .18),
                    transparent 30%),
                linear-gradient(135deg,
                    #667eea,
                    #764ba2);

            font-family:
                Inter,
                system-ui,
                sans-serif;
        }

        .container-main {
            max-width: 1150px;
            margin: auto;
        }

        .card {
            border: 0;

            border-radius: 22px;

            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .20);
        }

        .card-header {
            padding: 22px 25px;

            background:
                linear-gradient(135deg,
                    #1e3c72,
                    #2a5298);

            color: white;

            border: 0;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 45px;
            height: 45px;

            border-radius: 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                rgba(255, 255, 255, .15);

            color: #ffd166;

            font-size: 1.2rem;
        }

        .header-title h4 {
            margin: 0;
            font-weight: 700;
        }

        .header-title small {
            opacity: .7;
        }

        .card-body {
            padding: 25px;

            background: #fff;
        }

        .top-actions {
            display: flex;

            flex-wrap: wrap;

            gap: 10px;
        }

        .action-main {
            border: 0;

            border-radius: 10px;

            font-weight: 700;

            padding:
                9px 15px;
        }

        .btn-add {
            background:
                linear-gradient(135deg,
                    #10b981,
                    #059669);

            color: white;
        }

        .btn-export {
            background:
                linear-gradient(135deg,
                    #f59e0b,
                    #d97706);

            color: white;
        }

        .btn-add:hover,
        .btn-export:hover {
            color: white;

            transform: translateY(-1px);

            box-shadow:
                0 6px 15px rgba(0, 0, 0, .15);
        }

        .stats-row {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 15px;

            margin-bottom: 25px;
        }

        .stat-card {
            padding: 17px;

            border-radius: 15px;

            background: #f8fafc;

            border: 1px solid #e5e7eb;
        }

        .stat-icon {
            width: 40px;
            height: 40px;

            border-radius: 11px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef2ff;

            color: #667eea;

            margin-bottom: 10px;
        }

        .stat-label {
            font-size: .78rem;

            color: #6b7280;
        }

        .stat-value {
            font-size: 1.4rem;

            font-weight: 800;

            color: #111827;
        }

        .table-wrapper {
            border:
                1px solid #e5e7eb;

            border-radius: 15px;

            overflow: hidden;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8fafc;

            color: #374151;

            font-size: .8rem;

            text-transform: uppercase;

            letter-spacing: .03em;

            border: 0;

            padding: 15px;
        }

        .table tbody td {
            padding: 15px;

            vertical-align: middle;

            border-color: #f1f5f9;
        }

        .table tbody tr {
            transition: .15s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .avatar {
            width: 42px;
            height: 42px;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;

            font-weight: 800;

            background:
                linear-gradient(135deg,
                    #667eea,
                    #764ba2);
        }

        .user-name {
            font-weight: 700;
            color: #111827;
        }

        .user-email {
            color: #6b7280;
            font-size: .85rem;
        }

        .date-text {
            color: #6b7280;
            font-size: .85rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;

            border: 0;

            border-radius: 9px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            transition: .15s ease;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-edit:hover {
            background: #dbeafe;
            transform: scale(1.07);
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fee2e2;
            transform: scale(1.07);
        }

        .empty-state {
            text-align: center;

            padding: 65px 20px;

            color: #6b7280;
        }

        .empty-icon {
            width: 75px;
            height: 75px;

            margin: 0 auto 20px;

            border-radius: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f3f4f6;

            color: #9ca3af;

            font-size: 1.8rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 9px !important;

            margin: 0 3px;

            border-color: #e5e7eb;

            color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background:
                linear-gradient(135deg,
                    #667eea,
                    #764ba2);

            border-color: #667eea;
        }

        .modal-content {
            border: 0;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .25);
        }

        .modal-header {
            border: 0;
            padding: 20px 22px;
        }

        .modal-body {
            padding: 5px 22px 20px;
        }

        .modal-footer {
            border: 0;
            padding: 15px 22px 20px;
        }

        @media(max-width:768px) {

            .stats-row {
                grid-template-columns: 1fr;
            }

            .card-body {
                padding: 18px;
            }

        }
    </style>

</head>

<body>

    <div class="container-main">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">

                    <div class="header-title">

                        <div class="header-icon">
                            <i class="fa fa-users"></i>
                        </div>

                        <div>

                            <h4>Registered Users</h4>

                            <small>
                                Manage and validate your users
                            </small>

                        </div>

                    </div>


                    <div class="top-actions">

                        <a
                            href="{{ route('users.export.csv') }}"
                            class="btn action-main btn-export">
                            <i class="fa fa-file-csv me-1"></i>
                            Export CSV
                        </a>

                        <a
                            href="{{ route('users.create') }}"
                            class="btn action-main btn-add">
                            <i class="fa fa-plus me-1"></i>
                            Add New User
                        </a>

                    </div>

                </div>

            </div>


            {{-- BODY --}}
            <div class="card-body">


                @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fa fa-circle-check me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>

                </div>

                @endif


                {{-- STATISTICS --}}
                <div class="stats-row">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fa fa-users"></i>
                        </div>

                        <div class="stat-label">
                            Total Users
                        </div>

                        <div class="stat-value">
                            {{ $users->total() }}
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fa fa-user-plus"></i>
                        </div>

                        <div class="stat-label">
                            Current Page
                        </div>

                        <div class="stat-value">
                            {{ $users->count() }}
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fa fa-file-lines"></i>
                        </div>

                        <div class="stat-label">
                            Current Page
                        </div>

                        <div class="stat-value">
                            {{ $users->currentPage() }}
                        </div>

                    </div>

                </div>


                @if($users->count() > 0)

                <div class="table-wrapper">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>User</th>

                                    <th>Email</th>

                                    <th>Created</th>

                                    <th class="text-end">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($users as $user)

                                <tr>

                                    <td>

                                        {{
                                                ($users->currentPage() - 1)
                                                * $users->perPage()
                                                + $loop->iteration
                                            }}

                                    </td>


                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar">

                                                {{
                                                        strtoupper(
                                                            substr(
                                                                $user->name,
                                                                0,
                                                                2
                                                            )
                                                        )
                                                    }}

                                            </div>

                                            <div>

                                                <div class="user-name">
                                                    {{ $user->name }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="user-email">
                                            {{ $user->email }}
                                        </div>

                                    </td>


                                    <td>

                                        <div class="date-text">

                                            <i class="fa fa-calendar me-1"></i>

                                            {{ $user->created_at->format('M d, Y') }}

                                        </div>

                                    </td>


                                    <td class="text-end">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('users.edit', $user) }}"
                                            class="action-btn btn-edit me-1"
                                            title="Edit User">

                                            <i class="fa fa-pen"></i>

                                        </a>


                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            class="delete-form d-inline"
                                            data-user-name="{{ $user->name }}">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="action-btn btn-delete delete-button"
                                                title="Delete User">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- PAGINATION --}}
                <div class="d-flex justify-content-center mt-4">

                    {{ $users->links() }}

                </div>


                @else

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="fa fa-user-slash"></i>

                    </div>

                    <h5 class="fw-bold">
                        No users found
                    </h5>

                    <p>
                        Start by creating your first user.
                    </p>

                    <a
                        href="{{ route('users.create') }}"
                        class="btn btn-success">

                        <i class="fa fa-plus me-1"></i>

                        Create First User

                    </a>

                </div>

                @endif

            </div>

        </div>

    </div>


    {{-- DELETE MODAL --}}
    <div
        class="modal fade"
        id="deleteModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold">

                        <i
                            class="fa fa-triangle-exclamation text-danger me-2"></i>

                        Delete User

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <p class="mb-0 text-secondary">

                        Are you sure you want to delete

                        <strong
                            id="deleteUserName"
                            class="text-dark"></strong>?

                        <br>

                        <small>
                            This action cannot be undone.
                        </small>

                    </p>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger"
                        id="confirmDelete">

                        <i class="fa fa-trash me-1"></i>

                        Delete User

                    </button>

                </div>

            </div>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                let deleteForm = null;

                const deleteModalElement =
                    document.getElementById(
                        'deleteModal'
                    );

                const deleteModal =
                    new bootstrap.Modal(
                        deleteModalElement
                    );


                /*
                |--------------------------------------------------------------------------
                | Open delete modal
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll(
                        '.delete-button'
                    )
                    .forEach(
                        button => {

                            button.addEventListener(
                                'click',
                                function() {

                                    deleteForm =
                                        this.closest(
                                            '.delete-form'
                                        );


                                    const userName =
                                        deleteForm.dataset
                                        .userName;


                                    document
                                        .getElementById(
                                            'deleteUserName'
                                        )
                                        .textContent =
                                        userName;


                                    deleteModal.show();

                                }
                            );

                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | Confirm delete
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById(
                        'confirmDelete'
                    )
                    .addEventListener(
                        'click',
                        function() {

                            if (!deleteForm) {
                                return;
                            }


                            this.disabled = true;


                            this.innerHTML =
                                '<i class="fa fa-spinner fa-spin me-1"></i> Deleting...';


                            deleteForm.submit();

                        }
                    );

            }
        );
    </script>

</body>

</html>
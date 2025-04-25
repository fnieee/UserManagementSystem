@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">User Management Dashboard</h3>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New User
                    </a>
                    <a href="{{ route('users.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Users
                    </a>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="search" class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text"
                                           id="search"
                                           name="search"
                                           class="form-control"
                                           placeholder="Name, email or phone..."
                                           value="{{ request('search') }}"
                                           aria-label="Search users">
                                    <button class="btn btn-outline-secondary" type="submit" aria-label="Search button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" aria-label="Filter by status">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Apply Filters
                                    </button>

                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Status Messages -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Add Bulk Delete Form HERE -->
                    <form id="bulkDeleteForm" method="POST" action="{{ route('users.bulk-delete') }}">
                        @csrf

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select all</label>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" disabled>
                                    <i class="fas fa-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
                                </button>
                            </div>
                        </div>

                        <!-- Hidden input for selected user IDs -->
                        <input type="hidden" name="ids" id="selectedIds" value="">
                    </form>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" aria-label="Users list">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50px">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="user-checkbox" name="users[]" value="{{ $user->id }}">
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group" aria-label="User actions">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        @if(request()->hasAny(['search', 'status']))
                                            <h4 class="text-muted">No users found matching your criteria</h4>
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mt-3">
                                                <i class="fas fa-undo"></i> Clear Filters
                                            </a>
                                        @else
                                            <h4 class="text-muted">No users found in the system</h4>
                                            <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus"></i> Create First User
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    // Toggle all checkboxes
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Individual checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
        bulkDeleteBtn.disabled = checkedCount === 0;
        selectedCount.textContent = checkedCount;
        selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
    }

    // Form submission handler
    bulkDeleteForm.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);

        if (ids.length === 0) {
            e.preventDefault();
            alert('Please select at least one user to delete');
            return false;
        }

        // Set the hidden input value
        document.getElementById('selectedIds').value = JSON.stringify(ids);

        return confirm(`Are you sure you want to delete ${ids.length} selected users?`);
    });
});
    </script>
    @endpush
@endsection

<div class="container mt-4">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" wire:click="create">Add Contact</button>
        <input type="text" class="form-control w-50" placeholder="Search..." wire:model.debounce.500ms="search">
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th wire:click="sortBy('name')" style="cursor:pointer">Name
                @if ($sortField === 'name')
                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                @endif
            </th>
            <th wire:click="sortBy('email')" style="cursor:pointer">Email
                @if ($sortField === 'email')
                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                @endif
            </th>
            <th wire:click="sortBy('phone')" style="cursor:pointer">Phone
                @if ($sortField === 'phone')
                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                @endif
            </th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    <button wire:click="edit({{ $contact->id }})" class="btn btn-success btn-sm">Edit</button>
                    <button wire:click="confirmDelete({{ $contact->id }})" class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No contacts found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $contacts->links('vendor.pagination.tailwind') }}
    </div>

    {{-- Create/Edit Modal --}}
    <div wire:ignore.self class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="store">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" wire:model="name" class="form-control">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" wire:model="email" class="form-control">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-2">
                            <label>Phone</label>
                            <input type="text" wire:model="phone" class="form-control">
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetForm">Cancel</button>
                        <button class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div wire:ignore.self class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content p-3 text-center">
                <h5>Are you sure you want to delete this contact?</h5>
                <div class="mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="cancelDelete">Cancel</button>
                    <button class="btn btn-danger" wire:click="delete">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Toast --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast bg-success text-white" data-bs-delay="3000">
            <div class="toast-body" id="successToastMessage"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('show-modal', () => {
            new bootstrap.Modal(document.getElementById('contactModal')).show();
        });

        window.addEventListener('hide-modal', () => {
            bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
        });

        window.addEventListener('show-delete-modal', () => {
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        });

        window.addEventListener('hide-delete-modal', () => {
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        });

        window.addEventListener('show-success', event => {
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            document.getElementById('successToastMessage').textContent = event.detail.message;
            toast.show();
        });
    </script>
@endpush

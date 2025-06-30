<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">👥 All Users</h2>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>

        @if($users->isEmpty())
            <div class="alert alert-warning text-center">No users found.</div>
        @else
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered table-hover table-striped bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Profile Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                              <td>
    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-profile.png') }}"
         alt="Profile Image" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <div class="btn-group btn-group-md">

                                        <button class="btn btn-primary edit-user"
                                                data-id="{{ $user->id }}" 
                                                data-name="{{ $user->name }}" 
                                                data-email="{{ $user->email }}"
                                                data-image="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-profile.png') }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#addUserModal"
                                                data-mode="edit">
                                            <i class="fas fa-edit"></i>
                                        </button>    

                                        {{-- <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a> --}}
                                        <button class="btn btn-danger delete-user" data-id="{{ $user->id }}" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addUserForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="formMode" name="mode" value="create">
                    <input type="hidden" id="userId" name="id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       value="{{ old('name') }}" placeholder="Enter full name">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="{{ old('email') }}" placeholder="Enter email">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="Enter password (min 8 characters)">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" required placeholder="Confirm password">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image"
                                       accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG)</small>
                                @error('profile_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preview</label>
                                <div class="border p-2 text-center">
                                    <img id="imagePreview" src="{{ asset('images/default-profile.png') }}" 
                                         alt="Profile Preview" class="img-fluid rounded-circle" 
                                         style="width: 100px; height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (optional but useful for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    
        // Handle form submission for adding/editing users
        $(document).ready(function() {
            $('addUserModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var mode = button.data('mode'); // Extract info from data-* attributes
                var modal = $(this);

                // Set form action based on mode
                if (mode === 'edit') {
                   // Edit mode
                    modal.find('.modal-title').text('Edit User');
                    modal.find('#formMode').val('edit');
                    modal.find('#userId').val(button.data('id'));
                    modal.find('#name').val(button.data('name'));
                    modal.find('#email').val(button.data('email'));
                    modal.find('#imagePreview').attr('src', button.data('image'));
                    
                    // Make password fields optional for edit
                    modal.find('#password').removeAttr('required');
                    modal.find('#password_confirmation').removeAttr('required');
                    modal.find('#passwordFields .text-danger').text('');
                } else {
                  // Create mode
                    modal.find('.modal-title').text('Add New User');
                    modal.find('#formMode').val('create');
                    modal.find('#userId').val('');
                    modal.find('#userForm')[0].reset();
                    modal.find('#imagePreview').attr('src', '{{ asset("images/default-profile.png") }}');
                    
                    // Make password fields required for create
                    modal.find('#password').attr('required', 'required');
                    modal.find('#password_confirmation').attr('required', 'required');
                }
            });

            $('#addUserForm').submit(function(e){


                console.log('Form submitted');

                e.preventDefault();
                var mode = $('#formMode').val();
                var userId = $('#userId').val();
                var url = mode === 'edit' 
                    ? '{{ route("user.edit", ":id") }}'.replace(':id', userId)
                    : '{{ route("user.create") }}';

                var formData = new FormData(this); 
                
                     // For edit, we need to add the PUT method
                if (mode === 'edit') {
                    formData.append('_method', 'PUT');
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorHtml = '<div class="alert alert-danger"><ul>';
                            
                            $.each(errors, function(key, value) {
                                errorHtml += '<li>' + value[0] + '</li>';
                            });
                            
                            errorHtml += '</ul></div>';
                            $('#userModal .modal-body').prepend(errorHtml);
                        }
                    }
                });
                
            })

        });

        // Image preview functionality
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Delete user confirmation
        $(document).ready(function() {
            console.log('Document is ready');
            
            $('.delete-user').click(function() {
                var userId = $(this).data('id');
                var url = '{{ route("user.delete", ":id") }}';
                url = url.replace(':id', userId);
                
                $('#deleteUserForm').attr('action', url);
                $('#deleteUserModal').modal('show');
            });

            // Reset the form when modal is closed
            $('#addUserModal').on('hidden.bs.modal', function () {
                $('#addUserForm')[0].reset();
                $('#imagePreview').attr('src', '{{ asset("images/default-profile.png") }}');
            });
        });



    </script>

</body>
</html>
<x-layout.admin title="User Management">
    <div class="mt-5">
        <h2>Users</h2>

        <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#addModal">
            Add
        </button>

        <table class="table table-striped" id="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-create-user" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div id="select-role" class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role_id" required></select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Edit User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-edit-user" method="POST">
                            @csrf
                            <input type="hidden" name="id">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div id="select-role-edit" class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role_id" required></select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script src="/js/api.js"></script>
        <script>
            let globalRoles = [];

            $(document).ready(function() {
                getRole(function(res) {
                    const roles = Array.isArray(res) ? res : (res.data || res.data.data || []);
                    globalRoles = roles;
                    populateRoleSelects(roles);
                });

                reloadUsers();

                $("#form-create-user").on("submit", function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const formData = serializeForm(form);
                    clearFormMessages(form);

                    storeUser(
                        formData,
                        function() {
                            showFormMessage(form, "User created successfully ✅", "success");
                            setTimeout(() => {
                                clearFormMessages(form);
                                form[0].reset();
                                $("#addModal").modal('hide');
                            }, 1500);
                            reloadUsers();
                        },
                        function(xhr) {
                            handleErrorResponse(xhr, form);
                        }
                    );
                });

                $("#form-edit-user").on("submit", function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const id = form.find("input[name='id']").val();
                    const formData = serializeForm(form);
                    clearFormMessages(form);

                    updateUser(
                        id,
                        formData,
                        function() {
                            showFormMessage(form, "User updated successfully ✅", "success");
                            setTimeout(() => {
                                clearFormMessages(form);
                                form[0].reset();
                                $("#editModal").modal('hide');
                            }, 1500);
                            reloadUsers();
                        },
                        function(xhr) {
                            handleErrorResponse(xhr, form);
                        }
                    );
                });
            });

            function removeUser(id) {
                if (confirm("Are you sure you want to delete this user?")) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                    });

                    deleteUser(
                        id,
                        function() {
                            showToast("User deleted successfully ✅", "success");
                            reloadUsers();
                        },
                        function(xhr) {
                            showToast("Failed to delete user: " + (xhr.responseJSON?.message || "Unknown error"), "danger");
                        }
                    );
                }
            }

            function populateRoleSelects(roles) {
                const options = roles.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
                $("#select-role select[name='role_id']").html(options);
                $("#select-role-edit select[name='role_id']").html(options);
            }

            function serializeForm($form) {
                const formArray = $form.serializeArray();
                return formArray.reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
            }

            function reloadUsers(page = 1) {
                const tbody = $("#users-table tbody");
                tbody.html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

                getUser(function(res) {
                    const users = res.data?.data || res.data || [];
                    renderUserTable(users);
                    renderPagination(res);
                }, page);
            }


            function renderUserTable(users) {
                const tbody = $("#users-table tbody");
                tbody.empty();

                if (users.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center">No users found</td></tr>');
                    return;
                }

                users.forEach(user => {
                    tbody.append(`
            <tr>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.role?.name || '-'}</td>
                <td>${user.created_at}</td>
                <td>${user.updated_at}</td>
                <td>
                    <button class="btn btn-sm btn-warning me-1" onclick="editUser(${JSON.stringify(user).replace(/"/g, '&quot;')})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="removeUser('${user.id}')">Delete</button>
                </td>
            </tr>
        `);
                });
            }

            function renderPagination(paginatedData) {
                $("#pagination").remove();

                const pagination = $('<div id="pagination" class="d-flex justify-content-center mt-3"></div>');
                const ul = $('<ul class="pagination"></ul>');

                paginatedData.links.forEach(link => {
                    const active = link.active ? 'active' : '';
                    const disabled = link.url === null ? 'disabled' : '';
                    const label = link.label.replace('&laquo;', '«').replace('&raquo;', '»');

                    const li = $(`<li class="page-item ${active} ${disabled}">
            <button class="page-link">${label}</button>
        </li>`);

                    if (link.url) {
                        li.on('click', function() {
                            const url = new URL(link.url);
                            const page = url.searchParams.get("page");
                            reloadUsers(page);
                        });
                    }

                    ul.append(li);
                });

                pagination.append(ul);
                $("#users-table").after(pagination);
            }

            function editUser(user) {
                const modal = $("#editModal");
                modal.find("input[name='id']").val(user.id);
                modal.find("input[name='name']").val(user.name);
                modal.find("input[name='email']").val(user.email);

                if (globalRoles.length > 0) {
                    populateRoleSelects(globalRoles);
                    modal.find("select[name='role_id']").val(user.role_id);
                }

                modal.modal("show");
            }

            function showFormMessage(form, message, type) {
                let msgBox = form.find(".form-message");
                if (msgBox.length === 0) {
                    msgBox = $('<div class="form-message mt-2"></div>');
                    form.prepend(msgBox);
                }
                msgBox.html(`<div class="alert alert-${type}">${message}</div>`);
            }

            function clearFormMessages(form) {
                form.find(".form-message").remove();
            }

            function handleErrorResponse(xhr, form) {
                const res = xhr.responseJSON;
                let message = "An error occurred.";
                if (res && res.errors) {
                    message = Object.values(res.errors).flat().join("<br>");
                } else if (res && res.message) {
                    message = res.message;
                }
                showFormMessage(form, message, "danger");
            }

            function showToast(message, type = "info") {
                const toast = $(`
        <div class="toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
                $("body").append(toast);
                const bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();
                toast.on('hidden.bs.toast', () => toast.remove());
            }
        </script>
    @endsection


</x-layout.admin>

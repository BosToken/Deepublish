<x-layout.admin title="Task Management">
    <div class="mt-5">
        <h2>Tasks</h2>

        <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#addModal">
            Add
        </button>
        {{-- <input type="text" id="search-input" class="form-control mb-3" placeholder="Search task..."> --}}

        <table class="table table-striped" id="tasks-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>User</th>
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
                        <h1 class="modal-title fs-5">Add Task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-create-task" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="to-do">to-do</option>
                                    <option value="in-progress">in-progress</option>
                                    <option value="done">done</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" name="description"></textarea>
                            </div>
                            <div id="select-user" class="mb-3">
                                <label class="form-label">Assign to User</label>
                                <select class="form-select" name="user_id" required></select>
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
                        <h1 class="modal-title fs-5">Edit Task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-edit-task" method="POST">
                            @csrf
                            <input type="hidden" name="id">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="to-do">to-do</option>
                                    <option value="in-progress">in-progress</option>
                                    <option value="done">done</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" name="description"></textarea>
                            </div>
                            <div id="select-user-edit" class="mb-3">
                                <label class="form-label">Assign to User</label>
                                <select class="form-select" name="user_id" required></select>
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
            let globalUsers = [];

            $(document).ready(function() {
                getUser(function(res) {
                    const users = res.data || [];
                    globalUsers = users;
                    populateUserSelects(users);
                });

                reloadTasks();

                $("#search-input").on("keyup", function() {
                    const keyword = $(this).val();
                    reloadTasks(1, keyword);
                });

                $("#form-create-task").on("submit", function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const formData = serializeForm(form);
                    clearFormMessages(form);

                    storeTask(
                        formData,
                        function() {
                            showFormMessage(form, "Task created successfully ✅", "success");
                            setTimeout(() => {
                                clearFormMessages(form);
                                form[0].reset();
                                $("#addModal").modal('hide');
                            }, 1500);
                            reloadTasks();
                        },
                        function(xhr) {
                            handleErrorResponse(xhr, form);
                        }
                    );
                });

                $("#form-edit-task").on("submit", function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const id = form.find("input[name='id']").val();
                    const formData = serializeForm(form);
                    clearFormMessages(form);

                    updateTask(
                        id,
                        formData,
                        function() {
                            showFormMessage(form, "Task updated successfully ✅", "success");
                            setTimeout(() => {
                                clearFormMessages(form);
                                form[0].reset();
                                $("#editModal").modal('hide');
                            }, 1500);
                            reloadTasks();
                        },
                        function(xhr) {
                            handleErrorResponse(xhr, form);
                        }
                    );
                });
            });

            function populateUserSelects(users) {
                const options = users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
                $("#select-user select[name='user_id']").html(options);
                $("#select-user-edit select[name='user_id']").html(options);
            }

            function reloadTasks(page = 1, search = "") {
                const tbody = $("#tasks-table tbody");
                tbody.html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');

                getTask(function(res) {
                    const tasks = res.data?.data || res.data || [];
                    renderTaskTable(tasks);
                    renderPagination(res, search);
                }, page, search);
            }

            function renderTaskTable(tasks) {
                const tbody = $("#tasks-table tbody");
                tbody.empty();

                if (tasks.length === 0) {
                    tbody.html('<tr><td colspan="7" class="text-center">No tasks found</td></tr>');
                    return;
                }

                tasks.forEach(task => {
                    tbody.append(`
                        <tr>
                            <td>${task.title}</td>
                            <td>${task.description || '-'}</td>
                            <td>${task.status}</td>
                            <td>${task.user?.name || '-'}</td>
                            <td>${task.created_at}</td>
                            <td>${task.updated_at}</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" onclick="editTask(${JSON.stringify(task).replace(/"/g, '&quot;')})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="removeTask('${task.id}')">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            }

            function renderPagination(paginatedData, search = '') {
                $("#pagination").remove();
                const pagination = $('<div id="pagination" class="d-flex justify-content-center mt-3"></div>');
                const ul = $('<ul class="pagination"></ul>');

                if (!paginatedData.links) return;

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
                            reloadTasks(page, search);
                        });
                    }

                    ul.append(li);
                });

                pagination.append(ul);
                $("#tasks-table").after(pagination);
            }

            function editTask(task) {
                const modal = $("#editModal");
                modal.find("input[name='id']").val(task.id);
                modal.find("input[name='title']").val(task.title);
                modal.find("textarea[name='description']").val(task.description);
                modal.find("select[name='status']").val(task.status);
                modal.find("select[name='user_id']").val(task.user_id);
                modal.modal("show");
            }

            function removeTask(id) {
                if (confirm("Are you sure you want to delete this task?")) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                    });

                    deleteTask(
                        id,
                        function() {
                            showToast("Task deleted successfully ✅", "success");
                            reloadTasks();
                        },
                        function(xhr) {
                            showToast("Failed to delete task: " + (xhr.responseJSON?.message || "Unknown error"), "danger");
                        }
                    );
                }
            }

            function serializeForm($form) {
                const formArray = $form.serializeArray();
                return formArray.reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
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

<x-layout.admin title="Task Management">
    <div class="mt-5">
        <h2>Task</h2>

        <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#addModal">
            Add
        </button>

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
                                <label>Description</label>
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
                                <label>Description</label>
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
            $(document).ready(function() {
                getUser(function(res) {
                    const users = res.data || [];
                    const options = users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
                    $("#select-user select[name='user_id']").html(options);
                    $("#select-user-edit select[name='user_id']").html(options);
                });

                reloadTasks();

                $("#form-create-task").on("submit", function(e) {
                    e.preventDefault();
                    const formData = serializeForm($(this));
                    storeTask(formData, function() {
                        $("#addModal").modal('hide');
                        reloadTasks();
                    });
                });

                $("#form-edit-task").on("submit", function(e) {
                    e.preventDefault();
                    const id = $(this).find("input[name='id']").val();
                    const formData = serializeForm($(this));
                    updateTask(id, formData, function() {
                        $("#editModal").modal('hide');
                        reloadTasks();
                    });
                });
            });

            function serializeForm($form) {
                const formArray = $form.serializeArray();
                return formArray.reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
            }

            function renderTaskTable(tasks) {
                if ($.fn.DataTable.isDataTable("#tasks-table")) {
                    $("#tasks-table").DataTable().destroy();
                }

                $("#tasks-table").DataTable({
                    data: tasks,
                    columns: [{
                            data: "title"
                        },
                        {
                            data: "description"
                        },
                        {
                            data: "status"
                        },
                        {
                            data: "user.name",
                            defaultContent: "-"
                        },
                        {
                            data: "created_at"
                        },
                        {
                            data: "updated_at"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-sm btn-warning me-1" onclick="editTask(${JSON.stringify(row).replace(/"/g, '&quot;')})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="removeTask('${row.id}')">Delete</button>
                                `;
                            },
                            orderable: false,
                            searchable: false
                        }
                    ],
                    pageLength: 5,
                    responsive: true,
                });
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
                            alert("Task deleted successfully");
                            reloadTasks();
                        },
                        function(xhr) {
                            alert("Failed to delete task:\n" + xhr.responseText);
                        }
                    );
                }
            }

            function reloadTasks() {
                getTask(function(res) {
                    const tasks = res.data?.data || res.data || res;
                    renderTaskTable(tasks);
                });
            }
        </script>
    @endsection
</x-layout.admin>
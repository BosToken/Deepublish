<x-layout.user title="Dashboard User">
    <div class="mt-5">
        <h2>Your Tasks</h2>

        <table class="table table-striped" id="tasks-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Edit -->
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
                                <input type="text" class="form-control" name="title" readonly>
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
            $(document).ready(function () {
                reloadTasks();

                $("#form-edit-task").on("submit", function (e) {
                    e.preventDefault();
                    const id = $(this).find("input[name='id']").val();
                    const formData = serializeForm($(this));
                    updateTask(id, formData, function () {
                        $("#editModal").modal("hide");
                        reloadTasks();
                    });
                });
            });

            function reloadTasks(page = 1) {
                const tbody = $("#tasks-table tbody");
                tbody.html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

                getTask(function (res) {
                    const tasks = Array.isArray(res)
                        ? res
                        : res.data?.data || res.data || res;

                    if (!Array.isArray(tasks) || tasks.length === 0) {
                        tbody.html('<tr><td colspan="6" class="text-center">No tasks found.</td></tr>');
                        return;
                    }

                    renderTaskTable(tasks);
                }, page);
            }

            function renderTaskTable(tasks) {
                if ($.fn.DataTable.isDataTable("#tasks-table")) {
                    $("#tasks-table").DataTable().destroy();
                }

                $("#tasks-table").DataTable({
                    data: tasks,
                    columns: [
                        { data: "title" },
                        { data: "description" },
                        { data: "status" },
                        { data: "created_at" },
                        { data: "updated_at" },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-sm btn-warning" onclick="editTask(${JSON.stringify(row).replace(/"/g, '&quot;')})">
                                        Edit
                                    </button>
                                `;
                            },
                            orderable: false,
                            searchable: false,
                        },
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
                modal.modal("show");
            }

            function serializeForm($form) {
                const formArray = $form.serializeArray();
                return formArray.reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
            }
        </script>
    @endsection
</x-layout.user>

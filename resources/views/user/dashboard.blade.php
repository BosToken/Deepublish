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

    @section('script')
        <script src="/js/api.js"></script>
        <script>
            $(document).ready(function () {
                reloadTasks();

                $("#search-input").on("keyup", function () {
                    const keyword = $(this).val();
                    reloadTasks(1, keyword);
                });

                $("#form-edit-task").on("submit", function (e) {
                    e.preventDefault();
                    const id = $(this).find("input[name='id']").val();
                    const formData = serializeForm($(this));
                    updateTask(id, formData, function () {
                        $("#editModal").modal("hide");
                        showToast("Task updated successfully ✅", "success");
                        reloadTasks();
                    });
                });
            });

            function reloadTasks(page = 1, search = "") {
                const tbody = $("#tasks-table tbody");
                tbody.html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

                getTask(function (res) {
                    const tasks = res.data?.data || res.data || [];
                    renderTaskTable(tasks);
                    renderPagination(res, search);
                }, page, search);
            }

            function renderTaskTable(tasks) {
                const tbody = $("#tasks-table tbody");
                tbody.empty();

                if (tasks.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center">No tasks found</td></tr>');
                    return;
                }

                tasks.forEach(task => {
                    tbody.append(`
                        <tr>
                            <td>${task.title}</td>
                            <td>${task.description || '-'}</td>
                            <td>${task.status}</td>
                            <td>${task.created_at}</td>
                            <td>${task.updated_at}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editTask(${JSON.stringify(task).replace(/"/g, '&quot;')})">
                                    Edit
                                </button>
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
                        li.on('click', function () {
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
                modal.modal("show");
            }

            function serializeForm($form) {
                const formArray = $form.serializeArray();
                return formArray.reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
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
</x-layout.user>

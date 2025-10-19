function ajaxRequest(url, type, data = {}, successCallback, errorCallback) {
    $.ajax({
        url: url,
        type: type,
        dataType: "json",
        data: data,
        success: function (response) {
            if (typeof successCallback === "function")
                successCallback(response);
        },
        error: function (xhr, status, error) {
            if (typeof errorCallback === "function")
                errorCallback(xhr, status, error);
            else console.error("AJAX Error:", error);
        },
    });
}

function getTask(success, error) {
    ajaxRequest(
        "/api/task",
        "GET",
        {},
        function (res) {
            if (typeof success === "function") success(res);
        },
        function (xhr, status, err) {
            if (typeof error === "function") error(xhr, status, err);
        }
    );
}

function getIdTask(id, success, error) {
    ajaxRequest(
        "/api/task/" + id,
        "GET",
        {},
        function (res) {
            if (typeof success === "function") success(res);
        },
        function (xhr, status, err) {
            if (typeof error === "function") error(xhr, status, err);
        }
    );
}

function getTaskByUser(success, page = 1) {
    ajaxRequest(
        `/api/task/byUser?page=${page}`,
        "GET",
        {},
        function (res) {
            if (typeof success === "function") success(res);
        },
        function (xhr, status, err) {
            console.error(err)
        }
    );
}

function storeTask(request, success, error) {
    ajaxRequest("/api/task", "POST", request, success, error);
}

function updateTask(id, request, success, error) {
    ajaxRequest("/api/task/" + id, "PUT", request, success, error);
}

function deleteTask(id, success, error) {
    ajaxRequest("/api/task/" + id, "DELETE", {}, success, error);
}

function getUser(success, page = 1) {
    ajaxRequest(`/api/user?page=${page}`, "GET", {}, success, console.error);
}

function getIdUser(id, success, error) {
    ajaxRequest("/api/user/" + id, "GET", {}, success, error);
}

function storeUser(request, success, error) {
    ajaxRequest("/api/user", "POST", request, success, error);
}

function updateUser(id, request, success, error) {
    ajaxRequest("/api/user/" + id, "PUT", request, success, error);
}

function deleteUser(id, success, error) {
    ajaxRequest("/api/user/" + id, "DELETE", {}, success, error);
}

function getRole(success, error) {
    ajaxRequest("/api/role", "GET", {}, success, error);
}

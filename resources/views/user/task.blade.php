<x-layout.user title="Task">
    <div class="mt-5">
        <h2>Task</h2>
        @if ($method === 'GET')
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="name" value="{{ $task->title }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" name="name" value="{{ $task->status }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" disabled>{{ $task->description }}</textarea>
            </div>
            <x-button.bootstrap label="Edit Profile" href="{{ route('task.edit.user', ['id' => $task->id]) }}" />
        @endif
        @if ($method === 'EDIT')
            <x-form.bootstrap action="{{ route('task.update.user', ['id' => $task->id]) }}" method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" aria-label="Default select example" name="status" required>
                        <option value="{{$task->status}}">{{$task->status}}</option>
                        <option value="to-do">to-do</option>
                        <option value="in-progress">in-progress</option>
                        <option value="done">done</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $task->description }}</textarea>
                </div>
            </x-form.bootstrap>
        @endif
    </div>
</x-layout.user>

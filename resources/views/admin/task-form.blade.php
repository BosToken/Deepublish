<x-layout.admin title="Task">
    <div class="mt-5">
        <h2>Task</h2>
        @if ($method === 'CREATE')
            <x-form.bootstrap action="{{ route('task.store.admin') }}" method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" aria-label="Default select example" name="status" required>
                        <option value="to-do">to-do</option>
                        <option value="in-progress">in-progress</option>
                        <option value="done">done</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assign to User</label>
                    <select class="form-select" aria-label="Default select example" name="user_id" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </x-form.bootstrap>
        @endif

        @if ($method === 'EDIT')
            <x-form.bootstrap action="{{ route('task.update.admin', ['id' => $task->id]) }}" method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" aria-label="Default select example" name="status" required>
                        <option value="{{ $task->status }}">{{ $task->status }}</option>
                        <option value="to-do">to-do</option>
                        <option value="in-progress">in-progress</option>
                        <option value="done">done</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $task->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assign to User</label>
                    <select class="form-select" aria-label="Default select example" name="user_id" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </x-form.bootstrap>

        @endif
    </div>
</x-layout.admin>

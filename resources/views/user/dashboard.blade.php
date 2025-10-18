<x-layout.user title="Dashboard User">
    <div class="mt-5">
        <h2>Your Tasks</h2>
        <x-tabel.bootstrap>
            <x-slot name="thead">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Create At</th>
                    <th>Update At</th>
                    <th>Action</th>
                </tr>
            </x-slot>
            <x-slot name="tbody">
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->created_at }}</td>
                        <td>{{ $task->updated_at }}</td>
                        <td><x-button.bootstrap label="View" href="{{route('task.user', ['id' => $task->id])}}"/></td>
                    </tr>
                @endforeach
            </x-slot>
        </x-tabel.bootstrap>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-layout.user>

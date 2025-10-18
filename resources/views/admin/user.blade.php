<x-layout.admin title="User Management">
    <div class="mt-5">
        <h2>Users</h2>
        <x-tabel.bootstrap>
            <x-slot name="thead">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </x-slot>

            <x-slot name="tbody">
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>
                            <x-button.bootstrap label="Edit"
                                href="{{ route('user.edit.admin', ['id' => $user->id]) }}" />

                            <x-button.bootstrap label="Delete" class="btn btn-danger"
                                href="{{ route('user.delete.admin', ['id' => $user->id]) }}" />
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-tabel.bootstrap>

        <div class="d-flex justify-content-center mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-layout.admin>

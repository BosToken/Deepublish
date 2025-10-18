<x-layout.user title="Profile">
    <div class="mt-5">
        <h2>User Profile</h2>

        @if ($method === 'GET')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="{{ $user->email }}" disabled>
            </div>
            <x-button.bootstrap label="Edit Profile" href="{{route ('profile.edit.user')}}" />
        @endif
        @if ($method === 'EDIT')
            <x-form.bootstrap action="{{ route('profile.update.user') }}" method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $user->email }}" required>
                </div>
            </x-form.bootstrap>
        @endif
    </div>
</x-layout.user>

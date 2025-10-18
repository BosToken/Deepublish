<x-layout.guest title="Login Page" class="d-flex align-items-center py-4 bg-light h-100">
    <div class="mt-5 w-100 m-auto" style="max-width: 330px; padding: 1rem;">
        <img class="mb-4" src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo.svg" alt="bootstrap-logo" width="72" height="57">
        <x-form.bootstrap action="{{ route('login') }}" method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password">
            </div>
        </x-form.bootstrap>
    </div>
</x-layout.guest>


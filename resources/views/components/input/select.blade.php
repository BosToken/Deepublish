{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<select class="form-select" id="user_id" name="user_id" required></select>

<script>
$('#user_id').select2({
    placeholder: 'Search user...',
    ajax: {
        url: '{{ route("users.search") }}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return { q: params.term }; // parameter search
        },
        processResults: function (data) {
            return {
                results: data.data.map(user => ({
                    id: user.id,
                    text: user.name
                })),
                pagination: {
                    more: data.next_page !== null // masih ada page berikutnya
                }
            };
        }
    }
});
</script> --}}

@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => 'Select an option...',
    'url' => null,
    'required' => false,
])

<div class="mb-3">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <select
        class="form-select select2"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
    ></select>
</div>

@once
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endonce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#{{ $id }}').select2({
        placeholder: '{{ $placeholder }}',
        ajax: {
            url: '{{ $url }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: item.name
                    })),
                    pagination: {
                        more: data.next_page !== null
                    }
                };
            }
        }
    });
});
</script>
@endpush

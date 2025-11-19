@php
    // Determine layout based on user role
    $layout = Auth::user() && Auth::user()->role
        ? strtolower(str_replace(' ', '_', Auth::user()->role->name))
        : 'admin';
@endphp

@extends($layout === 'admin' ? 'layouts.admin' : 'layouts.section_manager')

@section('title', 'Register Driver')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="mb-4">Register Driver</h2>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $layout === 'admin' ? route('admin.drivers.store') : route('section_manager.drivers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Username*</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email (for login)*</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control">
        </div>

        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" placeholder="e.g. 0711234567 or +94711234567">
            @error('mobile')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="id_number" class="form-label">ID Number*</label>
            <input type="text" id="id_number" name="id_number" maxlength="12" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" inputmode="numeric" required>
            @error('id_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="idFeedback" class="form-text text-danger" style="display:none;">This ID number is already registered.</div>
        </div>

        <button type="submit" class="btn btn-primary">Register Driver</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const input = document.getElementById('id_number');
        const feedback = document.getElementById('idFeedback');
        let timeout = null;

        if (!input) return;

        input.addEventListener('input', function () {
            feedback.style.display = 'none';
            input.classList.remove('is-invalid');
            input.setCustomValidity('');

            const val = this.value.trim();
            if (val.length === 0) return; // empty -- nothing to check

            // debounce
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetch("{{ route('admin.drivers.checkId') }}?q=" + encodeURIComponent(val), { credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.style.display = 'block';
                            input.classList.add('is-invalid');
                            input.setCustomValidity('This ID number is already registered.');
                        } else {
                            feedback.style.display = 'none';
                            input.classList.remove('is-invalid');
                            input.setCustomValidity('');
                        }
                    })
                    .catch(err => {
                            // enforce maxlength client-side (extra safety)
                            if (this.value.length > 12) {
                                this.value = this.value.slice(0, 12);
                            }
                        // silent fail; server may be unreachable during dev
                        console.error('ID check failed', err);
                    });
            }, 450);
        });
        
        // Auto-complete email domain when user types '@'
        const emailInput = document.querySelector('input[name="email"]');
        if (emailInput) {
            emailInput.addEventListener('keyup', function (e) {
                try {
                    // Trigger only when user typed the '@' character and the value currently ends with '@'
                    if (e.key === '@' && this.value.endsWith('@')) {
                        this.value = this.value + 'gmail.com';
                        // put caret at end
                        this.setSelectionRange(this.value.length, this.value.length);
                    }
                } catch (ex) {
                    // ignore any selection errors on older browsers
                    console.error('Email autocomplete error', ex);
                }
            });
        }
    })();
</script>
@endpush

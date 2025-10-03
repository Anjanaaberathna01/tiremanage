@extends('layouts.mechanic_officer')

@section('title', 'Edit Request')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="page-title">✏️ Edit Request</h2>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="alert-box">
            <ul class="list-disc ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Allow editing only if mechanic already approved/rejected --}}
    @if($request->approvals->where('level', 'mechanic_officer')
                          ->whereIn('status', ['approved','rejected'])
                          ->where('approved_by', auth()->id())->isNotEmpty())

        <form id="editRequestForm" action="{{ route('mechanic_officer.requests.update', $request->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Damage Description --}}
            <div class="form-group">
                <label class="form-label">Damage Description</label>
                <textarea id="damageDescription"
                          name="damage_description"
                          maxlength="500"
                          class="form-input"
                          rows="4">{{ old('damage_description', $request->damage_description) }}</textarea>
                <small id="charCounter" class="char-counter">0 / 500</small>
            </div>

            {{-- Status (Mechanic Officer can only change status) --}}
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                    <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    <option value="pending"  {{ $request->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    @else
        <div class="alert-box">
            ⚠️ This request can only be edited after you approve or reject it.
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Title */
.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2563eb;
    text-align: center;
    margin-bottom: 2rem;
}

/* Error Alert */
.alert-box {
    background: #fee2e2;
    border: 1px solid #ef4444;
    color: #991b1b;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    animation: fadeIn 0.4s ease;
}

/* Form Elements */
.form-group {
    margin-bottom: 1.5rem;
}
.form-label {
    font-weight: 600;
    color: #374151;
    display: block;
    margin-bottom: 0.5rem;
}
.form-input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: border 0.3s, box-shadow 0.3s;
}
.form-input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37,99,235,0.3);
}

/* Character Counter */
.char-counter {
    display: block;
    font-size: 0.85rem;
    color: #6b7280;
    margin-top: 0.25rem;
    text-align: right;
}

/* Buttons */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}
.btn {
    display: inline-block;
    font-weight: 600;
    padding: 0.6rem 1.2rem;
    border-radius: 0.5rem;
    transition: all 0.25s ease;
    text-decoration: none;
    cursor: pointer;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
    box-shadow: 0 4px 10px rgba(37,99,235,0.3);
}
.btn-primary:hover {
    background: #1e40af;
    transform: translateY(-2px);
}
.btn-secondary {
    background: #9ca3af;
    color: #fff;
}
.btn-secondary:hover {
    background: #6b7280;
    transform: translateY(-2px);
}

/* Animations */
@keyframes fadeIn {
    from {opacity:0; transform:translateY(-10px);}
    to {opacity:1; transform:translateY(0);}
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Character counter for description
    const desc = document.getElementById('damageDescription');
    const counter = document.getElementById('charCounter');

    const updateCounter = () => {
        counter.textContent = `${desc.value.length} / 500`;
        counter.style.color = desc.value.length > 450 ? 'red' : '#6b7280';
    };

    desc.addEventListener('input', updateCounter);
    updateCounter();

    // Scroll to top if errors exist
    if (document.querySelector('.alert-box')) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
</script>
@endpush

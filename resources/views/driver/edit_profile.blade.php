@extends('layouts.driver')

@section('title', 'Edit Profile')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Update Profile</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('driver.profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-4 align-items-center">
        @csrf

        {{-- Left column: input fields --}}
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ $driver->full_name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile" class="form-control" value="{{ $driver->mobile }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email (Read Only)</label>
                <input type="email" class="form-control" value="{{ $driver->user->email }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">ID Number (Read Only)</label>
                <input type="text" class="form-control" value="{{ $driver->id_number }}" readonly>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
        </div>

        {{-- Right column: profile photo + camera & remove icons --}}
        <div class="col-md-4 text-center">
            <div class="position-relative d-inline-block profile-photo-wrapper">
                <img id="photoPreview"
                     src="{{ $profilePhoto }}"
                     alt="Profile Photo"
                     class="rounded-circle shadow"
                     style="width:250px; height:250px; object-fit:cover; border:5px solid #2563eb;">

                {{-- Camera icon --}}
                <label for="profilePhotoInput" class="camera-icon">
                    <i class="bi bi-camera-fill"></i>
                </label>

                {{-- Remove icon --}}
                <label class="remove-icon" onclick="removePhoto()">
                    <i class="bi bi-x-circle-fill"></i>
                </label>
            </div>
            <input type="file" id="profilePhotoInput" name="profile_photo" accept="image/*" onchange="previewPhoto(event)" style="display:none;">
            <input type="hidden" id="removePhotoFlag" name="remove_photo" value="0">
        </div>
    </form>
</div>

{{-- JS for live preview + remove --}}
<script>
function previewPhoto(event) {
    const output = document.getElementById('photoPreview');
    output.src = URL.createObjectURL(event.target.files[0]);
    document.getElementById('removePhotoFlag').value = 0; // reset remove flag if new photo uploaded
}

function removePhoto() {
    const output = document.getElementById('photoPreview');
    output.src = "{{ asset('images/default-profile.jpg') }}"; // fallback default
    document.getElementById('removePhotoFlag').value = 1;
    // clear file input if user previously selected a file
    document.getElementById('profilePhotoInput').value = "";
}
</script>

{{-- CSS for icons --}}
<style>
.profile-photo-wrapper {
    position: relative;
    display: inline-block;
}

.camera-icon {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 45px;
    height: 45px;
    background-color: #2563eb;
    color: white;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.3rem;
    border: 2px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}
.camera-icon:hover { transform: scale(1.2); }

.remove-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 35px;
    height: 35px;
    background-color: #dc2626;
    color: white;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.1rem;
    border: 2px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}
.remove-icon:hover { transform: scale(1.2); background-color: #b91c1c; }
</style>

{{-- Bootstrap Icons --}}
<link rel="stylesheet"
 href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection

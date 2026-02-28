@include('components.header')
@include('components.sidebar')

<div class="main-container">
    @if(session('error'))
    <div class="alert alert-danger">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()">×</button>
    </div>
@endif

@if(session('success'))
<div class="alert alert-success">
    <span>{{ session('success') }}</span>
    <button type="button" onclick="this.parentElement.remove()">×</button>
</div>
@endif

    <div class="page-header">
        <h1>My Profile</h1>
    </div>

    <div class="form-section" style="display:flex; align-items:center; gap:25px;">
        <div class="profile-avatar-editor">
            @if($member && $member->profile_photo)
            <img src="{{ asset('storage/profile/'.$member->profile_photo) }}" id="avatarPreview" class="header-avatar" style="cursor:pointer;">
            @else
            <img src="{{ asset('img/Profile.png') }}" id="avatarPreview" class="header-avatar">
            @endif
        </div>
  
        <div style="flex:1;">
            <h2>{{ $member->full_name }}</h2>
            <p style="color:var(--primary);">{{ $member->role_name }}</p>
        </div>

        <div>
            <form id="photoForm" method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" id="imageInput" name="photo" accept="image/*">
                <input type="hidden" name="cropped_image" id="croppedImage">
                @if($member->profile_photo)
                <button type="button" id="saveBtn" class="btn-primary">Save Photo</button>
                
                <a href="{{ route('profile.photo.remove') }}" class="btn-secondary">Remove</a>
                @endif
            </form>
        </div>


            <!-- @if($member->profile_photo)
            <form action="{{ route('profile.photo.remove') }}" method="POST" style="margin-top:5px;">
                @csrf
                <button type="submit" class="btn-secondary">Remove</button>
            </form>
            @endif
        </div> -->

    </div>

    <!-- ACCOUNT INFORMATION -->
    <div class="form-section">

        <div class="section-header">
            <h2>Account Information</h2>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
@csrf

<div class="grid-container" style="grid-template-columns: 1fr 1fr;">

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="full_name" value="{{ $member->full_name }}">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ $member->email }}">
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ $member->phone }}">
    </div>

    <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" value="{{ $member->address }}">
    </div>

</div>

<div class="form-actions">
    <button type="submit" class="btn-primary">
        Update Profile
    </button>
</div>

</form>

    </div>


    <!-- SECURITY SECTION -->
    <div class="form-section">

        <div class="section-header">
            <h2>Security Settings</h2>
        </div>

        <form method="POST" action="{{ route('profile.changePassword') }}">
@csrf

<div class="grid-container" style="grid-template-columns: 1fr 1fr;">

   <div class="form-group">
    <label>Current Password</label>
    <input type="password" name="current_password" required>
    @error('current_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label>New Password</label>
    <input type="password" name="new_password" required>
    @error('new_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group" style="grid-column: span 2;">
    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" required>
    @error('confirm_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

</div>
<!-- 
<p style="font-size:12px; color:var(--text); opacity:0.6; margin-top:6px;">
    For security reasons, use a strong password.
</p> -->

<div class="form-actions">
    <button type="submit" class="btn-primary">
        Change Password
    </button>
</div>

</form>

    </div>
</div>

<!-- CROP MODAL -->
<div id="cropModal" class="crop-modal">
    <div class="crop-box">
        <img id="cropImage">
        <div class="crop-actions">
            <button id="zoomIn" class="btn-secondary">Zoom +</button>
            <button id="zoomOut" class="btn-secondary">Zoom −</button>
            <button id="cropSave" class="btn-secondary">Save</button>
            <button id="cropCancel" class="btn-secondary">Cancel</button>
        </div>
    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal">
    <span id="closePreview" class="close-preview">&times;</span>
    <img id="previewLargeImage">
</div>

<script>
let cropper;
const input = document.getElementById('imageInput');
const modal = document.getElementById('cropModal');
const cropImage = document.getElementById('cropImage');
const preview = document.getElementById('avatarPreview');
const croppedInput = document.getElementById('croppedImage');

input.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {

        modal.style.display = "flex";
        cropImage.src = event.target.result;

if (cropper) cropper.destroy();

cropImage.onload = function () {
    cropper = new Cropper(cropImage, {
        aspectRatio: 1,
        viewMode: 2,
        dragMode: 'move',
        autoCropArea: 1,
        responsive: true,
        background: false,
        movable: true,
        zoomable: true,
        rotatable: false,
        scalable: false,
        cropBoxMovable: false,
        cropBoxResizable: false,
        wheelZoomRatio: 0.1
    });
};
    };
    reader.readAsDataURL(file);
});

// Zoom buttons
document.getElementById('zoomIn').onclick = () => cropper.zoom(0.1);
document.getElementById('zoomOut').onclick = () => cropper.zoom(-0.1);

// Cancel
document.getElementById('cropCancel').onclick = () => {
    modal.style.display = "none";
    cropper.destroy();
};

// Save
document.getElementById('cropSave').onclick = () => {
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300
    });

    const dataUrl = canvas.toDataURL('image/png');
    preview.src = dataUrl;
    croppedInput.value = dataUrl;

    modal.style.display = "none";
    document.getElementById('photoForm').submit();
};
</script>
<script>
const avatar = document.getElementById('avatarPreview');
const previewModal = document.getElementById('imagePreviewModal');
const previewLarge = document.getElementById('previewLargeImage');
const closePreview = document.getElementById('closePreview');

avatar.addEventListener('click', function(){
    previewLarge.src = avatar.src;
    previewModal.style.display = "flex";
});

closePreview.addEventListener('click', function(){
    previewModal.style.display = "none";
});

previewModal.addEventListener('click', function(e){
    if(e.target === previewModal){
        previewModal.style.display = "none";
    }
});
</script>
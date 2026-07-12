@extends('layouts.app')

@section('title', 'Setting')
@section('page-title', 'SETTING')

@section('content')
<div class="setting-container">

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="setting-alert setting-alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error (dari password salah) --}}
    @if($errors->has('current_password'))
        <div class="setting-alert setting-alert-error">
            <i class="fas fa-times-circle"></i>
            {{ $errors->first('current_password') }}
        </div>
    @endif

    <div class="setting-tabs">
        <button class="setting-tab-btn {{ session('open_tab') !== 'security' ? 'active' : '' }}" onclick="openTab(event, 'profile')" id="tab-profile">Edit Profile</button>
        <button class="setting-tab-btn" onclick="openTab(event, 'preferences')" id="tab-preferences">Preferences</button>
        <button class="setting-tab-btn {{ session('open_tab') === 'security' ? 'active' : '' }}" onclick="openTab(event, 'security')" id="tab-security">Security</button>
    </div>

    {{-- =============================== --}}
    {{-- TAB 1: EDIT PROFILE --}}
    {{-- =============================== --}}
    <div id="profile" class="tab-content {{ session('open_tab') !== 'security' ? 'active' : '' }}">

        {{-- Upload Foto Profil --}}
        <div class="profile-header">
            <form class="avatar-upload-form" method="POST" action="{{ route('setting.avatar') }}" enctype="multipart/form-data" id="avatarForm">
                @csrf
                <div class="avatar-wrapper">
                    <img
                        src="{{ auth()->user()->avatar
                            ? asset('storage/avatars/' . auth()->user()->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=1814F3&color=fff&size=100' }}"
                        alt="Profile"
                        class="avatar-img"
                        id="avatarPreview"
                    >
                    <label for="avatarInput" class="edit-avatar-btn" title="Ganti Foto Profil">
                        <i class="fas fa-pencil-alt"></i>
                    </label>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="setting-hidden-input" onchange="previewAndUploadAvatar(this)">
                </div>
                @error('avatar')
                    <p class="setting-field-error">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- Form Data Profil --}}
        <form class="setting-form" method="POST" action="{{ route('setting.profile') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" class="setting-input" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')<p class="setting-field-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" class="setting-input" name="username" value="{{ old('username', auth()->user()->username) }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="setting-input" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')<p class="setting-field-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" class="setting-input" name="date_of_birth"
                        value="{{ old('date_of_birth', auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label>Present Address</label>
                    <input type="text" class="setting-input" name="present_address" value="{{ old('present_address', auth()->user()->present_address) }}">
                </div>
                <div class="form-group">
                    <label>Permanent Address</label>
                    <input type="text" class="setting-input" name="permanent_address" value="{{ old('permanent_address', auth()->user()->permanent_address) }}">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" class="setting-input" name="city" value="{{ old('city', auth()->user()->city) }}">
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" class="setting-input" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code) }}">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="setting-input" name="country" value="{{ old('country', auth()->user()->country) }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>

    {{-- =============================== --}}
    {{-- TAB 2: PREFERENCES --}}
    {{-- =============================== --}}
    <div id="preferences" class="tab-content" style="display: none;">
        <form class="setting-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Currency</label>
                    <input type="text" class="setting-input" value="IDR">
                </div>
                <div class="form-group">
                    <label>Time Zone</label>
                    <input type="text" class="setting-input" value="UTC+07:00, WIB">
                </div>
            </div>

            <div class="notification-section">
                <h4 class="section-subtitle">Notification</h4>

                <div class="toggle-row">
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="toggle-label">I send or receive digital currency</span>
                </div>

                <div class="toggle-row">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                    <span class="toggle-label">I receive merchant order</span>
                </div>

                <div class="toggle-row">
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="toggle-label">There are recommendation for my account</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-save">Save</button>
            </div>
        </form>
    </div>

    {{-- =============================== --}}
    {{-- TAB 3: SECURITY (Ganti Password) --}}
    {{-- =============================== --}}
    <div id="security" class="tab-content" style="{{ session('open_tab') === 'security' ? 'display: block;' : 'display: none;' }}">
        <form class="setting-form" method="POST" action="{{ route('setting.password') }}">
            @csrf

            <h4 class="section-subtitle">Two-factor Authentication</h4>
            <div class="toggle-row" style="margin-bottom: 30px;">
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
                <span class="toggle-label">Enable or disable two factor authentication</span>
            </div>

            <h4 class="section-subtitle">Change Password</h4>

            <div class="setting-password-grid">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" class="setting-input" name="current_password" placeholder="Masukkan password saat ini" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="setting-input" name="new_password" placeholder="Password baru (min. 8 karakter)" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" class="setting-input" name="new_password_confirmation" placeholder="Ulangi password baru" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Simpan Password</button>
            </div>
        </form>
    </div>

</div>

<script>
    function openTab(evt, tabName) {
        // Sembunyikan semua konten tab
        let tabContent = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabContent.length; i++) {
            tabContent[i].style.display = "none";
        }

        // Hapus class 'active' dari semua tombol
        let tabBtns = document.getElementsByClassName("setting-tab-btn")
        for (let i = 0; i < tabBtns.length; i++) {
            tabBtns[i].className = tabBtns[i].className.replace(" active", "");
        }

        // Tampilkan tab yang diklik dan tambahkan class 'active' ke tombolnya
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Preview foto sebelum upload, lalu auto-submit form
    function previewAndUploadAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
            // Auto submit form avatar
            document.getElementById('avatarForm').submit();
        }
    }
</script>
@endsection
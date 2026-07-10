@extends('layouts.app')

@section('title', 'Setting')
@section('page-title', 'SETTING')

@section('content')
<div class="setting-container">
    
    <div class="setting-tabs">
        <button class="setting-tab-btn active" onclick="openTab(event, 'profile')">Edit Profile</button>
        <button class="setting-tab-btn" onclick="openTab(event, 'preferences')">Preferences</button>
        <button class="setting-tab-btn" onclick="openTab(event, 'security')">Security</button>
    </div>

    <div id="profile" class="tab-content active">
        <div class="profile-header">
            <div class="avatar-wrapper">
                <img src="https://via.placeholder.com/100" alt="Profile" class="avatar-img">
                <button class="edit-avatar-btn">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            </div>
        </div>

        <form class="setting-form" method="POST" action="{{ route('setting.save') }}">
            @csrf
            @if(session('success'))
                <div style="background-color: #00A651; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            <div class="form-grid">
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" class="setting-input" value="Azizi Shafaa Asadel">
                </div>
                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" class="setting-input" value="Zee">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="setting-input" value="Zee@gmail.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="setting-input" value="**********">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" class="setting-input" name="date_of_birth" value="2004-05-16">
                </div>
                <div class="form-group">
                    <label>Present Address</label>
                    <input type="text" class="setting-input" value="Jakarta, Indonesia">
                </div>
                <div class="form-group">
                    <label>Permanent Address</label>
                    <input type="text" class="setting-input" value="Jakarta, Indonesia">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" class="setting-input" value="DKI Jakarta">
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" class="setting-input" value="45962">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="setting-input" value="Indonesia">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>

    <div id="preferences" class="tab-content" style="display: none;">
        <form class="setting-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Currency</label>
                    <input type="text" class="setting-input" value="IDR">
                </div>
                <div class="form-group">
                    <label>Time Zone</label>
                    <input type="text" class="setting-input" value="UTC+07:00, Dengan Bujur Tolok 105°BT. WIB">
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

            <div class="form-actions" style="justify-content: flex-end;">
                <button type="button" class="btn-save">Save</button>
            </div>
        </form>
    </div>

    <div id="security" class="tab-content" style="display: none;">
        <form class="setting-form">
            <h4 class="section-subtitle">Two-factor Authentication</h4>
            <div class="toggle-row" style="margin-bottom: 30px;">
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
                <span class="toggle-label">Enable or disable two factor authentication</span>
            </div>

            <h4 class="section-subtitle">Change Password</h4>
            <div class="form-group" style="max-width: 50%;">
                <label>Current Password</label>
                <input type="password" class="setting-input" value="**********">
            </div>
            <div class="form-group" style="max-width: 50%;">
                <label>New Password</label>
                <input type="password" class="setting-input" value="**********">
            </div>

            <div class="form-actions" style="justify-content: flex-end; margin-top: 30px;">
                <button type="button" class="btn-save">Save</button>
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
</script>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ASgor</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body style="background:#F5F7FA; display:flex; justify-content:center; align-items:center; height:100vh;">

    <div style="width:380px; background:white; padding:35px; border-radius:20px; box-shadow:0 5px 20px rgba(0,0,0,.1);">

        <div style="text-align:center; margin-bottom:25px;">
            <h1 style="color:#1814F3;">ASgor</h1>
            <p>Login Administrator / Kasir</p>
        </div>

        @if(session('status'))
            <div style="margin-bottom:15px;color:green;">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div style="margin-bottom:15px;color:red;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:15px;">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>

            <div style="margin-bottom:20px;">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>

            <div style="margin-bottom:20px;">
                <label>
                    <input type="checkbox" name="remember">
                    Remember Me
                </label>
            </div>

            <button
                type="submit"
                style="width:100%;padding:12px;background:#1814F3;color:white;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">
                LOGIN
            </button>

        </form>

    </div>

</body>
</html>
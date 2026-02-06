<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Tools Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    @vite('resources/css/app.css')
</head>

<body
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 relative overflow-y-auto">

    {{-- Background Blur Decoration --}}
    <div class="absolute inset-0 pointer-events-none">
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-400/30 to-indigo-500/30 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-indigo-400/30 to-blue-500/30 rounded-full blur-3xl">
        </div>
    </div>

    <div
        class="w-full max-w-[500px] bg-white/30 backdrop-blur-lg border border-white/20 shadow-2xl rounded-xl p-8 z-10">

        {{-- Logo & Header --}}
        <div class="text-center mb-6">
           <div
                class="mx-auto w-16 h-16 flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-lg">
                <i class="bi bi-tools text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mt-4">Tools Management</h1>
            <p class="text-gray-500">Sign in to your account</p>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-green-800 hover:text-green-900" data-bs-dismiss="alert">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
                <div class="relative">
                    <input type="text" name="name" id="name" placeholder="Enter your username"
                        value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 cursor-pointer"
                        onclick="togglePassword()">
                        <i id="eyeIcon" class="bi bi-eye"></i>
                    </div>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="text-sm text-gray-700">Remember Me</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 rounded-lg font-medium hover:opacity-90 transition">
                Sign In
            </button>
        </form>
        {{-- <p class="text-center text-sm text-gray-600 mt-4">
            Don't have an account?
            <a href="{{ route('register.show') }}" class="text-blue-600 hover:underline">Register here!</a>
        </p> --}}
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        }
    </script>

</body>

</html>

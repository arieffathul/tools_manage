<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Booking Ruangan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    @vite('resources/css/app.css')
</head>

<body
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 relative overflow-y-auto">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 pointer-events-none">
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-400/30 to-indigo-500/30 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-indigo-400/30 to-blue-500/30 rounded-full blur-3xl">
        </div>
    </div>

    <div
        class="w-full max-w-[500px] m-5 bg-white/30 backdrop-blur-lg border border-white/20 shadow-2xl rounded-xl p-8 z-10">

        {{-- Logo & Header --}}
        <div class="text-center mb-6">
            <div
                class="mx-auto w-16 h-16 flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-lg">
                <i class="bi bi-tools text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mt-4">Tools Management</h1>
            <p class="text-gray-500">Create your account</p>
        </div>

        {{-- Register Form --}}
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <div>
                <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Full Name</label>
                <div class="relative">
                    <input type="text" name="name" id="name" placeholder="Your full name"
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

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                <div class="relative">
                    <input type="email" name="email" id="email" placeholder="Email address"
                        value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Password" required
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 cursor-pointer"
                        onclick="togglePassword('password', 'eyeIcon1')">
                        <i id="eyeIcon1" class="bi bi-eye"></i>
                    </div>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">Confirm
                    Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Confirm password" required
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 cursor-pointer"
                        onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                        <i id="eyeIcon2" class="bi bi-eye"></i>
                    </div>
                </div>
            </div>

            {{-- Terms & Submit --}}

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 rounded-lg font-medium hover:opacity-90 transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account?
            <a href="{{ route('login.show') }}" class="text-blue-600 hover:underline">Login Here!</a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId, eyeId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(eyeId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

</body>

</html>

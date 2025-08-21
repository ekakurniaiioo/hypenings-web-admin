<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-yellow-400 to-violet-500 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
    <!-- Judul -->
    <h2 class="text-3xl font-extrabold mb-2 text-gray-800 text-center">Login</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Sign in to continue to your account</p>

    <!-- Error -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
      <strong>Oops!</strong> {{ $errors->first() }}
    </div>
  @endif

    <!-- Form -->
    <form action="/login" method="POST" class="space-y-5">
      @csrf

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" placeholder="you@example.com"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 focus:outline-none transition"
          required />
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="relative">
          <input type="password" id="loginPassword" name="password" placeholder="••••••••"
            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 focus:outline-none transition"
            required />
          <button type="button" onclick="togglePassword('loginPassword', 'eyeLogin')"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
            <!-- Eye Icon -->
            <svg id="eyeLogin" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Tombol Login -->
      <div>
        <button type="submit"
          class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md">
          Login
        </button>
      </div>
    </form>

    <!-- Link ke register -->
    <p class="mt-6 text-center text-sm text-gray-500">
      don't have account?
      <a href="/register" class="text-yellow-500 hover:underline font-medium">Register</a>
    </p>
  </div>

  <!-- Script Toggle Password -->
  <script>
    function togglePassword(inputId, eyeId) {
      const input = document.getElementById(inputId);
      const eye = document.getElementById(eyeId);

      if (input.type === "password") {
        input.type = "text";
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.963 9.963 0 012.615-4.442m4.95-2.162A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.959 9.959 0 01-4.133 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
      } else {
        input.type = "password";
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
      }
    }
  </script>

</body>

</html>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login — Hypenings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="min-h-screen flex items-center justify-center bg-[#dff7f1]">

  <div
    class="relative w-full max-w-5xl h-[560px] card rounded-3xl overflow-hidden bg-white/80 backdrop-blur-sm font-sans flex">
    <div class="hidden md:flex w-1/2 relative mint-arcs left-panel items-center justify-center p-8">
      <div class="relative w-full h-full flex flex-col justify-center pl-8">
        <div class="absolute -left-10 top-8 w-[320px] h-[320px] rounded-l-full bg-white/10"></div>

        <div class="z-20 max-w-[340px]">
          <h1 class="text-3xl font-extrabold text-[#0e3b36]">Hypenings</h1>
          <p class="mt-2 text-sm text-[#0e3b36]/80">Manage and publish news effortlessly with Hypenings</p>
        </div>

        <div class="absolute right-6 bottom-6 w-[300px] h-[300px]">
          <div class="w-full h-full object-cover placeholder-3d rounded-lg"></div>
        </div>
      </div>
    </div>

    <div class="w-full md:w-1/2 p-10  flex flex-col justify-center bg-white">

      @if ($errors->any())
      <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm">
      {{ $errors->first() }}
      </div>
    @endif
      @if (session('error'))
      <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm">
      {{ session('error') }}
      </div>
    @endif

      <div class="px-6">
        <h2 class="text-3xl font-extrabold text-[#0e3b36] text-center ">Login</h2>
        <p class="text-sm text-[#0e3b36]/70 mt-2 text-center">Sign in to get started</p>

        <div class="mt-6 flex flex-col gap-4 items-center md:items-stretch">

          <form action="/login" method="POST" class="w-full max-w-md mx-auto md:mx-0 space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-[#0e3b36]/85 mb-1">Email Address</label>
              <input type="email" name="email" placeholder="you@example.com"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#58bdac] focus:border-[#58bdac] transition" />
            </div>

            <div>
              <label class="block text-sm font-medium text-[#0e3b36]/85 mb-1">Password</label>
              <div class="relative">
                <input id="pwd" type="password" name="password" placeholder="••••••••"
                  class="w-full px-4 py-2 pr-10 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#58bdac] focus:border-[#58bdac] transition" />
                <button type="button" onclick="togglePassword('pwd','eye')"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                  <svg id="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>

            <button type="submit"
              class="w-full bg-[#58bdac] hover:bg-[#47a693] text-white font-semibold py-2.5 rounded-lg shadow">Login</button>
          </form>

          <p class="text-xs text-[#0e3b36]/70 mt-3">Don't have an account? <a href="/register"
              class="text-[#0b6f64] font-medium">Register</a></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePassword(inputId, eyeId) {
      const input = document.getElementById(inputId);
      const eye = document.getElementById(eyeId);
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.963 9.963 0 012.615-4.442m4.95-2.162A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.959 9.959 0 01-4.133 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
      } else {
        input.type = 'password';
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
      }
    }
  </script>
</body>

</html>
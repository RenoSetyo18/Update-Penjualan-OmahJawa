<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Login Page
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 </head>
 <body class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
   <div class="w-1/2 bg-cover" style="background-image: url('https://placehold.co/600x800');">
    <img src="dataset/dist/img/omahjawa.jpg" class="w-full h-full object-cover" height="800" src="https://storage.googleapis.com/a1aa/image/q80T7VT2KQbNMwnl2WVW6lzQ78oNnR9O_95X3HI7qZc.jpg" width="600"/>
   </div>
   <div class="w-1/2 p-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
     Login to continue
    </h2>
    <form class="mt-4" method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label class="block text-gray-700">Email</label>
            <input class="w-full px-4 py-2 mt-2 border rounded-md" type="email" name="email" required autofocus />
        </div>
        <div class="mt-4">
            <label class="block text-gray-700">Password</label>
            <input class="w-full px-4 py-2 mt-2 border rounded-md" type="password" name="password" required />
        </div>
     <div class="flex items-center justify-between mt-4">
      <label class="flex items-center">
       <input class="form-checkbox" type="checkbox" name="remember"/>
       <span class="ml-2 text-gray-700">
        Remember me
       </span>
      </label>
     </div>
     <div class="mt-6">
        <button class="w-full px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700" type="submit">
            LOGIN
        </button>
    </div>
</form>
   </div>
  </div>
  @if(session('error-unauth'))
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        title: "Error!",
        text: "{{ session('error-unauth') }}",
        icon: "error"
      });
    });
  </script>
@endif
 </body>
</html>

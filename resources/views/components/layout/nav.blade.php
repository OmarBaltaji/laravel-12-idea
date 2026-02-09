<nav class="border-b border-border px-6">
  <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
      <div>
        <a href="/">
          <img src="/images/idea-logo.png" alt="Idea logo" width="50" > 
        </a>
      </div>

      <div class="flex gap-x-5 items-center">
        @auth
          <a href="/profile">Edit Profile</a>

          <form method="POST" action="/logout" >
            @csrf

            <button data-test="logout-button">Log out</button>
          </form>
        @endauth

        @guest
          <a href="/login">Sign in</a>
          <a href="/register" class="btn">Register</a>
        @endguest
      </div>
  </div>
</nav>
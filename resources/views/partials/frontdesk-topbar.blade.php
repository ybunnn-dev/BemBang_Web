<nav class="navbar navbar-light bg-light" id="frontdesk-nav">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="{{ asset('images/logo.svg') }}"><p>FRONTDESK<p></a>
  </div>
  <div class="dropdown" id="profile-icon">
      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        Receptionist
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="#">Account Settings</a></li>
        <li>
          <form method="POST" action="{{ route('logout') }}" id="logout-form">
              @csrf
              <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                  Log Out
              </button>
          </form>
        </li>
      </ul>
  </div>
</nav>
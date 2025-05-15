<!--
OLD HORIZONTAL NAV DESIGN

<div class="container py-3">
  <nav class="navbar navbar-expand-lg bg-primary rounded">
    <div class="container-fluid">
      <img src="https://patchwiki.biligame.com/images/lol/7/75/mehqw0rd2xc1ddfl5famkomd026qh77.png" alt="TEBS" width="24" height="24">
      <a class="navbar-brand ms-1" href="home">TEBS</a>
      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse" aria-controls="collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="cameras">Cameras</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Database
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="matches">Matches</a></li>
              <li><a class="dropdown-item" href="teams">Teams</a></li>
              <li><a class="dropdown-item" href="players">Players</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Other
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Installation</a></li>
              <li><a class="dropdown-item" href="#">API</a></li>
              <li><a class="dropdown-item" href="logout">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
-->

<?php 
$current_page = basename($_SERVER['REQUEST_URI']);
?>

<div class="row" style="height: 100vh;">
  <div class="col-md-2 bg-dark text-white p-3">
    <div class="sidebar-heading text-center py-4 h1">
      <img src="img/logo.png" width="60" height="60">
      TEBS
    </div>

    <div class="list-group list-group-flush">
      <a href="cameras" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'cameras') ? 'active' : '' ?>">Cameras</a>
      <a href="schedule" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'schedule') ? 'active' : '' ?>">Schedule</a>
    </div>

    <br>
    <div class="list-group list-group-flush">
      <h5 class="px-2">Graphics</h5>
      <a href="highlightplayer" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'highlightplayer') ? 'active' : '' ?>">Player Highlight</a>
      <a href="highlightoperator" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'highlightoperator') ? 'active' : '' ?>">Operator Highlight</a>
      <a href="customgraphic" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'customgraphic') ? 'active' : '' ?>">Custom Graphics</a>
    </div>

    <br>
    <div class="list-group list-group-flush">
      <h5 class="px-2">Database</h5>
      <a href="matches" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], 'mapveto') !== false || strpos($_SERVER['REQUEST_URI'], 'matches') !== false) ? 'active' : '' ?>">Matches</a>
      <a href="teams" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'teams') ? 'active' : '' ?>">Teams</a>
      <a href="players" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'players') ? 'active' : '' ?>">Players</a>
    </div>

    <br>
    <div class="list-group list-group-flush">
      <h5 class="px-2">Other</h5>
      <a href="settings" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'settings') ? 'active' : '' ?>">Settings</a>
      <a href="installation" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'installation') ? 'active' : '' ?>">Installation</a>
      <a href="https://r6-game-data-api.onrender.com/" target="_blank" class="list-group-item list-group-item-action bg-dark text-white">API</a>
      <a href="logout" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'logout') ? 'active' : '' ?>">Logout</a>
    </div>

  </div>
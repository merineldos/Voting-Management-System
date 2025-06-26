<style>
    .nav-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        margin: 0 20px 20px 20px;
        padding: 10px 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    .navbar {
        background: transparent !important;
        padding: 0;
    }
    .navbar-nav .nav-link {
        color: white !important;
        font-weight: 500;
        padding: 10px 20px !important;
        margin: 0 5px;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .navbar-nav .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .navbar-toggler {
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 12px;
        border-radius: 8px;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="nav-container">
            <nav class="navbar navbar-expand-lg">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?homepage=1">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?AddElectionPage=1">Add Election</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?AddCandidatePage=1">Add Candidate</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
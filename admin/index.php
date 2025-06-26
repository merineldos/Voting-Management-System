<?php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   
   require_once("./inc/header.php");
    require_once("./inc/navigation.php");
    if (isset($_GET['homepage'])) 
    {
        require_once("./inc/homepage.php");
    } 
    
    else if (isset($_GET['AddElectionPage'])) 
    {
        require_once("./inc/add_election.php");
    } 
    else if (isset($_GET['AddCandidatePage']))
     {
        require_once("./inc/add_candidate.php");
    }
?>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }
    
    .container-fluid {
        background: transparent;
        padding: 0;
    }
</style>

<?php
   require_once("./inc/footer.php");
?>
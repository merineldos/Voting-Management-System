<?php
session_start();
require_once('../admin/inc/config.php');

// Access Control: Only voters allowed
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'voter') {
    header('Location: ../index.php');
    exit();
}

$username = $_SESSION['username'];
$userQuery = "SELECT * FROM users WHERE username = ?";
$stmt = $db->prepare($userQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows === 0) {
    die("User not found.");
}

$voter = $userResult->fetch_assoc();

// Voting Logic
if (isset($_POST['vote_candidate'])) {
    $cid = mysqli_real_escape_string($db, $_POST['candidate_id']);
    $eid = mysqli_real_escape_string($db, $_POST['election_id']);
    $vid = $voter['id'];

    $checkVoteQuery = "SELECT * FROM votings WHERE voters_id = '$vid' AND election_id = '$eid'";
    $hasVotedResult = mysqli_query($db, $checkVoteQuery);

    if (!$hasVotedResult) {
        die("Error checking vote: " . mysqli_error($db));
    }

    $hasVoted = mysqli_num_rows($hasVotedResult) > 0;

    if (!$hasVoted) {
        $insertVoteQuery = "INSERT INTO votings (voters_id, candidate_id, election_id, vote_date, vote_time) VALUES ('$vid', '$cid', '$eid', CURDATE(), CURTIME())";
        if (mysqli_query($db, $insertVoteQuery)) {
            $success_message = "Your vote has been recorded successfully!";
        } else {
            $error_message = "Error recording vote: " . mysqli_error($db);
        }
    } else {
        $error_message = "You have already voted in this election!";
    }
}

// Fetch all active elections
$electionsQuery = "SELECT * FROM elections WHERE status = 'active' ORDER BY id DESC";
$elections = mysqli_query($db, $electionsQuery);
if (!$elections) {
    die("Error fetching elections: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Example minimal styles */
        body { padding: 20px; background: #f9f9f9; }
        .main-container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 6px; }
        .header-section { margin-bottom: 20px; }
        .candidate_photo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
        .candidate-row { padding: 10px 0; border-bottom: 1px solid #ddd; }
        .btn-vote { background-color: #007bff; color: white; }
        .btn-vote:disabled { background-color: #6c757d; }
        .vote-count { font-weight: bold; }
        .election-card { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #007bff; }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- for icons -->
</head>
<body>
<div class="main-container">
    <div class="header-section d-flex justify-content-between align-items-center">
        <h3><i class="fas fa-vote-yea"></i> Welcome, <?= htmlspecialchars($username) ?>!</h3>
        <a href="../logout.php" class="logout-btn btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php elseif (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($elections) > 0): ?>
        <?php
        $anyCandidates = false;
        while ($election = mysqli_fetch_assoc($elections)):
            $eid = $election['id'];
            $candidatesQuery = "SELECT c.*, 
                (SELECT COUNT(*) FROM votings v WHERE v.candidate_id = c.id) AS vote_count 
                FROM candidate_details c WHERE c.election_id = '$eid' ORDER BY vote_count DESC";
            $candidates = mysqli_query($db, $candidatesQuery);
            if (!$candidates) {
                die("Error fetching candidates: " . mysqli_error($db));
            }

            $hasVotedResult = mysqli_query($db, "SELECT * FROM votings WHERE voters_id = '{$voter['id']}' AND election_id = '$eid'");
            if (!$hasVotedResult) {
                die("Error checking voting status: " . mysqli_error($db));
            }
            $hasVoted = mysqli_num_rows($hasVotedResult) > 0;
        ?>
            <div class="election-card">
                <h2><?= htmlspecialchars($election['election_topic']) ?></h2>
                <div><small>Starting: <?= date('M d, Y', strtotime($election['starting_date'])) ?> | Ending: <?= date('M d, Y', strtotime($election['ending_date'])) ?></small></div>

                <?php if (mysqli_num_rows($candidates) > 0): ?>
                    <?php $anyCandidates = true; ?>
                    <?php while ($c = mysqli_fetch_assoc($candidates)): ?>
                        <div class="candidate-row d-flex align-items-center">
                            <img src="<?= htmlspecialchars($c['candidate_photo']) ?>" class="candidate_photo" onerror="this.src='../assets/images/default-avatar.png'">
                            <div class="candidate-info ml-3">
                                <div class="candidate-name"><?= htmlspecialchars($c['candidate_name']) ?></div>
                                <div class="candidate-party"><?= htmlspecialchars($c['candidate_details']) ?></div>
                                <span class="vote-count"><?= $c['vote_count'] ?> votes</span>
                            </div>
                            <div class="ml-auto">
                                <?php if ($hasVoted): ?>
                                    <button class="btn btn-vote" disabled><i class="fas fa-check"></i> Voted</button>
                                <?php else: ?>
                                    <form method="POST" onsubmit="return confirm('Vote for <?= htmlspecialchars(addslashes($c['candidate_name'])) ?>?');">
                                        <input type="hidden" name="candidate_id" value="<?= $c['id'] ?>">
                                        <input type="hidden" name="election_id" value="<?= $eid ?>">
                                        <button type="submit" name="vote_candidate" class="btn btn-vote">
                                            <i class="fas fa-vote-yea"></i> Vote
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-muted text-center">No candidates for this election yet.</div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <?php if (!$anyCandidates): ?>
            <div class="no-elections text-center mt-4">
                <i class="fas fa-inbox fa-3x"></i>
                <h3>No Candidates Available</h3>
                <p>Please check back later.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="no-elections text-center">
            <i class="fas fa-inbox fa-3x"></i>
            <h3>No Active Elections</h3>
            <p>Check back later.</p>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    setTimeout(() => $('.alert').fadeOut('slow'), 5000);
    $('.logout-btn').click(e => {
        if (!confirm('Logout?')) e.preventDefault();
    });
</script>
</body>
</html>

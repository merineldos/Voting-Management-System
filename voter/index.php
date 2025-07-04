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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Voting Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/voter.css">
</head>
<body>
    <div class="background-pattern"></div>
    <div class="floating-particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 6s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 8s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 10s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 12s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 14s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 16s;"></div>
    </div>

    <div class="container">
        <div class="header">
            <div class="welcome-section">
                <div class="user-avatar">
                    <?= strtoupper(substr($username, 0, 2)) ?>
                </div>
                <div class="welcome-text">
                    <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
                    <p>Your voice matters. Make it count.</p>
                </div>
            </div>
            <a href="../logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
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
                $hasVotedResult = mysqli_query($db, "SELECT * FROM votings WHERE voters_id = '{$voter['id']}' AND election_id = '$eid'");
                $hasVoted = mysqli_num_rows($hasVotedResult) > 0;
            ?>
            <div class="election-card">
                <h2 class="election-title"><?= htmlspecialchars($election['election_topic']) ?></h2>
                <div class="election-dates">
                    <i class="fas fa-calendar-alt"></i>
                    <?= date('M d, Y', strtotime($election['starting_date'])) ?> - <?= date('M d, Y', strtotime($election['ending_date'])) ?>
                </div>

                <?php if (mysqli_num_rows($candidates) > 0): ?>
                    <?php $anyCandidates = true; ?>
                    <div class="candidates-grid">
                        <?php while ($c = mysqli_fetch_assoc($candidates)): ?>
                            <div class="candidate-card">
                                <div class="candidate-info">
                                    <img src="<?= htmlspecialchars($c['candidate_photo']) ?>" 
                                         class="candidate-photo"
                                         onerror="this.src='../assets/images/default-avatar.png'">
                                    <div class="candidate-details">
                                        <h3 class="candidate-name"><?= htmlspecialchars($c['candidate_name']) ?></h3>
                                        <p class="candidate-description"><?= htmlspecialchars($c['candidate_details']) ?></p>
                                        <div class="vote-count">
                                            <i class="fas fa-chart-bar"></i>
                                            <?= $c['vote_count'] ?> votes
                                        </div>
                                    </div>
                                </div>
                                <div class="vote-actions">
                                    <?php if ($hasVoted): ?>
                                        <button class="vote-btn voted-btn" disabled>
                                            <i class="fas fa-check"></i>
                                            Already Voted
                                        </button>
                                    <?php else: ?>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to vote for <?= htmlspecialchars(addslashes($c['candidate_name'])) ?>?');">
                                            <input type="hidden" name="candidate_id" value="<?= $c['id'] ?>">
                                            <input type="hidden" name="election_id" value="<?= $eid ?>">
                                            <button type="submit" name="vote_candidate" class="vote-btn">
                                                <i class="fas fa-vote-yea"></i>
                                                Cast Vote
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>No Candidates Available</h3>
                        <p>Candidates for this election haven't been announced yet.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>

            <?php if (!$anyCandidates): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Candidates Available</h3>
                    <p>Please check back later when candidates are announced.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-vote-yea"></i>
                <h3>No Active Elections</h3>
                <p>There are no active elections at the moment. Check back later!</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Logout confirmation
        document.querySelector('.logout-btn').addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });

        // Add more floating particles dynamically
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
            document.querySelector('.floating-particles').appendChild(particle);
            
            setTimeout(() => particle.remove(), 25000);
        }

        // Create particles periodically
        setInterval(createParticle, 3000);
    </script>
</body>
</html>
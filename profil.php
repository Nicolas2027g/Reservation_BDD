<?php
    session_start();
    include "asset/php/head.php";
    include "asset/php/header_c.php";
    require_once "asset/php/config.php";

    if (!isset($_SESSION['userID'])) {
        header("Location: connexion.php");
        exit();
    }
    
    $userID = $_SESSION['userID'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main class="flex-grow-1 container mt-5">
    <h2 class="text-center mb-4">Bienvenue, <?= htmlspecialchars($user['firstname']) ?> !</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <h4>Nom</h4>
            <p><?= htmlspecialchars($user['lastname']) ?></p>
        </div>
        <div class="col-md-4">
            <h4>Email</h4>
            <p><?= htmlspecialchars($user['email']) ?></p>
        </div>
        <div class="col-md-4">
            <h4>Date de naissance</h4>
            <p><?= htmlspecialchars($user['date_naissance']) ?></p>
        </div>
    </div>

    <div class="card shadow-lg p-4">
        <h4 class="text-center mb-4">Sélectionnez une date</h4>
        <div id="calendar"></div>
    </div>
</main>

<?php
    include "asset/php/footer.php";
    include "asset/php/foot.php";
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/fr.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            selectable: true,
            validRange: {
                start: new Date()
            },
            dateClick: function(info) {
                window.location.href = "reserver.php?date=" + info.dateStr;
            }
        });

        calendar.render();
    });
</script>

<?php
    session_start();
    require_once "asset/php/config.php";
    include "asset/php/head.php";
    include "asset/php/header_c.php";

    if (!isset($_SESSION['userID'])) {
        header("Location: connexion.php");
        exit();
    }

    $userId = $_SESSION['userID'];
    $error_message = "";
    $success_message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        try {
            $sql = "DELETE FROM creneau WHERE id = :creneau_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':creneau_id', $delete_id, PDO::PARAM_INT);
            $stmt->execute();

            $success_message = "Le créneau a bien été annulé.";
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'annulation : " . $e->getMessage();
        }
    }

    try {
        $sql = "SELECT id, date_jour, heure FROM creneau WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $CreneauReserves = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Erreur SQL : " . $e->getMessage();
    }

    if (!empty($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
?>

<main class="flex-grow-1 container mt-5">
    <h2 class="mb-4">Vos créneaux réservés</h2>

    <?php if ($success_message): ?>
        <div class='alert alert-success'><?= $success_message; ?></div>
    <?php endif ?>

    <?php if (!empty($CreneauReserves)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($CreneauReserves as $creneau): ?>
                    <tr>
                        <td><?= htmlspecialchars($creneau['date_jour']) ?></td>
                        <td><?= htmlspecialchars($creneau['heure']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="delete_id" value="<?= $creneau['id'] ?>">
                                <button type="submit" class="btn btn-danger">Annuler</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun créneau réservé pour le moment.</p>
    <?php endif; ?>
</main>

<?php
    include "asset/php/footer.php";
    include "asset/php/foot.php";
?>

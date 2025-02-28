<?php
session_start();
require_once "asset/php/config.php";
include "asset/php/head.php";
include "asset/php/header_c.php";

if (!isset($_SESSION['userID'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['date'])) {
    header("Location: profil.php");
    exit();
}

$userId = $_SESSION['userID'];
$selectedDate = htmlspecialchars($_GET['date']);

$horairesDisponibles = [
    "08:00:00", "09:00:00", "10:00:00", "11:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00", "18:00:00"
];

try {
    $sql = "SELECT heure FROM creneau WHERE date_jour = :selectedDate";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selectedDate', $selectedDate, PDO::PARAM_STR);
    $stmt->execute();
    $horairesReserves = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $horairesRestants = array_diff($horairesDisponibles, $horairesReserves);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur SQL : " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['horaire'])) {
        $_SESSION['error_message'] = "Veuillez sélectionner un horaire.";
    } else {
        $horaire = $_POST['horaire'] . ":00";
        try {
            $sql = "INSERT INTO creneau (user_id, date_jour, heure) VALUES (:user_id, :date_jour, :heure)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':date_jour', $selectedDate, PDO::PARAM_STR);
            $stmt->bindParam(':heure', $horaire, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['success_message'] = "Créneau réservé avec succès !";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur SQL : " . $e->getMessage();
        }
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

$success_message = $_SESSION['success_message'] ?? "";
$error_message = $_SESSION['error_message'] ?? "";
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<main class="container mt-5">
    <h2 class="mb-4">Réserver un créneau pour le <?= htmlspecialchars($selectedDate) ?></h2>

    <form action="" method="POST">
        <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate) ?>">

        <div class="mb-3">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= $success_message; ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?= $error_message; ?></div>
            <?php endif; ?>

            <label for="horaire" class="form-label">Choisissez un horaire :</label>
            <select class="form-select" name="horaire" id="horaire" required>
                <?php if (empty($horairesRestants)): ?>
                    <option disabled>Aucun créneau disponible</option>
                    <?php else: ?>
                        <?php foreach ($horairesRestants as $horaire): ?>
                            <?php $horaireFormate = date("H:i", strtotime($horaire)); ?>
                            <option value="<?= $horaireFormate ?>"><?= $horaireFormate ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Réserver</button>
    </form>
</main>

<?php
include "asset/php/footer.php";
include "asset/php/foot.php";
?>

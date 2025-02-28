<?php
    session_start();
    require_once "asset/php/config.php";
    include "asset/php/head.php";
    include "asset/php/header_c.php";

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if (!isset($_SESSION['userID'])) {
        header("Location: connexion.php");
        exit();
    }

    $userId = $_SESSION['userID'];
    $error_message = "";
    $success_message = "";

    try {
        $sql = "SELECT * FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la récupération des informations : " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!(isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token'])){
            $error_message = "Erreur token csrf";
        }
        elseif (isset($_POST['update'])) {
            $firstname = trim($_POST['firstname']) ?: $user['firstname'];
            $lastname = trim($_POST['lastname']) ?: $user['lastname'];
            $adresse = trim($_POST['adresse']) ?: $user['adresse'];
            $telephone = trim($_POST['telephone']) ?: $user['telephone'];
            $email = trim($_POST['email']) ?: $user['email'];
            $date_naissance = $_POST['date_naissance'] ?: $user['date_naissance'];

            if (!preg_match('/^[0-9]{10,15}$/', $telephone)) {
                $error_message = "Le numéro de téléphone n'est pas valide.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "L'email n'est pas valide.";
            }

            if (empty($error_message)) {
                try {
                    $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, adresse = :adresse, telephone = :telephone, email = :email, date_naissance = :date_naissance WHERE id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':adresse', $adresse);
                    $stmt->bindParam(':telephone', $telephone);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':date_naissance', $date_naissance);
                    $stmt->bindParam(':user_id', $userId);
                    $stmt->execute();
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    $success_message = "Les informations ont été mises à jour avec succès.";
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $error_message = "Erreur : L'email est déjà utilisé.";
                    } else {
                        $error_message = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
                    }
                }
            }
        }

        elseif (isset($_POST['delete_account'])) {
            try {
                $sql = "DELETE FROM users WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                session_unset();
                session_destroy();
                header("Location: index.php");
                exit();
            } catch (PDOException $e) {
                $error_message = "Erreur lors de la suppression du compte : " . $e->getMessage();
            }
        }

        elseif (isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit();
        }
    }
?>

<main class="container mt-5">
    <h2 class="mb-4">Modifier votre profil</h2>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <div class="mb-3">
            <label for="firstname" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? $user['firstname']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Nom</label>
            <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? $user['lastname']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? $user['adresse']) ?>">
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Numéro de téléphone</label>
            <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? $user['telephone']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($_POST['date_naissance'] ?? $user['date_naissance']) ?>" required>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Mettre à jour</button>
    </form>

    <hr>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <button type="submit" name="logout" class="btn btn-secondary w-100 w-md-auto">Se déconnecter</button>
        </form>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <button type="submit" name="delete_account" class="btn btn-danger w-100 w-md-auto">Supprimer mon compte</button>
        </form>
    </div>
</main>

<?php
    include "asset/php/footer.php";
    include "asset/php/foot.php";
?>

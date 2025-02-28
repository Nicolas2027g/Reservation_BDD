<?php
    session_start();
    require_once "asset/php/config.php";
    include "asset/php/head.php";
    include "asset/php/header_d.php";

    $error_message = "";
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            try {
                $sql = "SELECT id, hashpassword, verify FROM users WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    if (password_verify($password, $user['hashpassword'])) {
                        $_SESSION['userID'] = $user['id'];
                        header("Location: profil.php");
                        exit();
                    } else {
                        $_SESSION['error_message'] = "Mot de passe incorrect.";
                    }
                } else {
                    $_SESSION['error_message'] = "Aucun compte trouvé avec cet email.";
                }
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Erreur SQL : " . $e->getMessage();
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = "Tous les champs doivent être remplis.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_SESSION['error_message'])) {
        $error_message = htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8');
        unset($_SESSION['error_message']);
    }
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg p-4">
                    <h2 class="fw-bold mb-4">Connexion</h2>
                    <form action="" method="POST">
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label fw-bold">Adresse Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                        </div>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?= $error_message; ?></div>
                        <?php endif; ?>


                        <button type="submit" class="btn btn-primary w-100 fw-bold">Se connecter</button>
                    </form>

                    <div class="mt-3">
                        <p class="mb-0">Si vous n'avez pas de compte : <a href="inscription.php" class="text-primary fw-bold">Inscrivez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
    include "asset/php/footer.php";
    include "asset/php/foot.php";
?>

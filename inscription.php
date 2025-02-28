<?php
    session_start();
    include "asset/php/head.php";
    include "asset/php/header_d.php";
    require_once "asset/php/config.php";

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!(isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token'])){
            $error_message = "Erreur token csrf";
        }
        elseif (
            isset($_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['adresse'],
                $_POST['telephone'], $_POST['email'], $_POST['password'])
        ) {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $date_naissance = trim($_POST['dateNaissance']);
            $adresse = trim($_POST['adresse']);
            $telephone = trim($_POST['telephone']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (!empty($nom) && !empty($prenom) && !empty($date_naissance) && !empty($adresse) &&
                !empty($telephone) && !empty($email) && !empty($password)) {

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error_message = "L'email est invalide.";
                }

                $hash_password = password_hash($password, PASSWORD_DEFAULT);

                try {
                    $sql = "INSERT INTO users (firstname, lastname, adresse, telephone, email, date_naissance, hashpassword) 
                            VALUES (:prenom, :nom, :adresse, :telephone, :email, :date_naissance, :hash_password)";
                    
                    $stmt = $pdo->prepare($sql);

                    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                    $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR);
                    $stmt->bindParam(':hash_password', $hash_password, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        header("Location: connexion.php");
                        exit();
                    } else {
                        $error_message = "Erreur lors de l'inscription.";
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $error_message = "Erreur : L'email est déjà utilisé.";
                    } elseif ($e->getCode() == "HY000") {
                        $error_message = "Erreur : Numéro de téléphone invalide.";
                    } else {
                        $error_message = "Erreur SQL : " . $e->getMessage();
                    }
                }
            } else {
                $error_message = "Tous les champs doivent être remplis.";
            }
        } else {
            $error_message = "Formulaire invalide.";
        }
    }
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg p-4">
                    <h2 class="fw-bold mb-4">Inscription</h2>

                    <form action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3 text-start">
                                <label for="nom" class="form-label fw-bold">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                            </div>
                            <div class="col-md-6 mb-3 text-start">
                                <label for="prenom" class="form-label fw-bold">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" required>
                            </div>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="dateNaissance" class="form-label fw-bold">Date de naissance</label>
                            <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="adresse" class="form-label fw-bold">Adresse postale</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Votre adresse" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="telephone" class="form-label fw-bold">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Votre numéro" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="email" class="form-label fw-bold">Adresse Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                        </div>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">S'inscrire</button>
                    </form>

                    <div class="mt-3">
                        <p class="mb-0">Déjà un compte ? <a href="connexion.php" class="text-primary fw-bold">Connectez-vous</a></p>
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

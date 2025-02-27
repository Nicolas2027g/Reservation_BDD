<?php
    include "asset/php/head.php";
    include "asset/php/header_d.php";
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center text-center">
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg p-4">
                    <h2 class="fw-bold mb-4">Inscription</h2>

                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3 text-start">
                                <label for="nom" class="form-label fw-bold">Nom</label>
                                <input type="text" class="form-control" id="nom" placeholder="Votre nom" required>
                            </div>
                            <div class="col-md-6 mb-3 text-start">
                                <label for="prenom" class="form-label fw-bold">Prénom</label>
                                <input type="text" class="form-control" id="prenom" placeholder="Votre prénom" required>
                            </div>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="dateNaissance" class="form-label fw-bold">Date de naissance</label>
                            <input type="date" class="form-control" id="dateNaissance" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="adresse" class="form-label fw-bold">Adresse postale</label>
                            <input type="text" class="form-control" id="adresse" placeholder="Votre adresse" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="telephone" class="form-label fw-bold">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="telephone" placeholder="Votre numéro" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="email" class="form-label fw-bold">Adresse Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Votre email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <input type="password" class="form-control" id="password" placeholder="Votre mot de passe" required>
                        </div>

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
    require_once "asset/php/config.php";
?>
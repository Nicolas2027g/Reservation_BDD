<?php   include "asset/php/head.php"    ?>
<?php   include "asset/php/header_d.php"  ?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg p-4">
                    <h2 class="fw-bold mb-4">Connexion</h2>
                    
                    <form>
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label fw-bold">Adresse Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Votre email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <input type="password" class="form-control" id="password" placeholder="Votre mot de passe" required>
                        </div>

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

<?php   include "asset/php/footer.php"  ?>
<?php   include "asset/php/foot.php"    ?>
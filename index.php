<?php
    include "asset/php/head.php";
    include "asset/php/header_d.php";
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <h1 class="display-4 mb-4">Bienvenue sur notre plateforme de gestion des rendez-vous médicaux</h1>
        <p class="lead mb-4">Réservez, gérez et suivez vos rendez-vous médicaux facilement et rapidement, où que vous soyez.</p>
        <a href="connexion.php" class="btn btn-primary btn-lg">Connectez-vous</a>

        <div class="mt-5 d-flex justify-content-center">
            <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
                <h3 class="text-center mb-4">Demande de renseignement</h3>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Votre message (facultatif)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php   
    include "asset/php/footer.php";
    include "asset/php/foot.php";
?>
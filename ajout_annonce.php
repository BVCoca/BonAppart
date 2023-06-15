<?php
require_once 'inc/init.php';

$errors = [];

$showMessage = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Protection contre les failles XSS 
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars(addslashes($value));
    }

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $postal_code = isset($_POST['postal_code']) ? $_POST['postal_code'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $type = strtolower($type);

    // Validation du formulaire
    if (empty($title)) {
        $errors['titre'] = "Le titre est obligatoire";
    }

    if (empty($type)) {
        $errors['type'] = "Le type de l'annonce est obligatoire";
    } elseif ($type !== "vente" && $type !== "location") {
        $errors['type'] = "Le type de l'annonce doit être Vente ou Location";
    }

    if (empty($description)) {
        $errors['description'] = "La description est obligatoire";
    } elseif (strlen($description) < 20) {
        $errors['description'] = "La description doit faire au moins 20 caractères";
    }

    if (empty($city)) {
        $errors['ville'] = "La ville est obligatoire";
    }

    if (empty($postal_code)) {
        $errors['code_postale'] = "Le code postal est obligatoire";
    } elseif (!is_numeric($postal_code)) {
        $errors['code_postale'] = "Le code postal doit être numérique";
    }

    if (empty($price)) {
        $errors['prix'] = "Le prix est obligatoire";
    } elseif (!is_numeric($price)) {
        $errors['prix'] = "Le prix doit être numérique";
    }

    if (empty($errors)) {
        $query = $db->prepare('INSERT INTO appartement (title, description , postal_code, city, type, price) VALUES (:title,:description,:postal_code,:city,:type,:price)');

        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':description', $description, PDO::PARAM_STR);
        $query->bindValue(':postal_code', $postal_code, PDO::PARAM_INT);
        $query->bindValue(':city', $city, PDO::PARAM_STR);
        $query->bindValue(':type', $type, PDO::PARAM_STR);
        $query->bindValue(':price', $price, PDO::PARAM_INT);
        if ($query->execute()) {
            $showMessage .= '<div class="alert alert-success">L\'article a été ajouté</div>';
        } else {
            $showMessage .= '<div class="alert alert-danger">Une erreur est survenue</div>';
        }
    }
}
?>

<?php require_once 'Common/header.php' ?>

<div class="container">

    <div class="row text-center">
        <h1 class="display-1 my-3">Ajout d'une annonce</h1>
        <?= $showMessage ?>
    </div>

    <div class="row">
        <div class="col-md-9 m-auto">
            <?php foreach ($errors as $error) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endforeach; ?>

            <form action="" method="post" enctype="multipart/form-data">

                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Ajouter un titre" name="title">
                </div>

                <div class="input-group mb-3">
                    <textarea name="description" class="form-control" placeholder="Ajouter une description" rows="10"></textarea>
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Ajouter une ville" name="city">
                    <input type="text" class="form-control" placeholder="Code Postal" name="postal_code">
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Location ou Vente" name="type">
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Ajouter un prix" name="price">
                </div>

                <div class="d-grid gap-2 col-6 mx-auto">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>

        </div>
    </div>

</div>

<?php require_once 'Common/footer.php'; ?>
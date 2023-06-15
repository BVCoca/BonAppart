<?php
require_once 'inc/init.php';

$showMessage = '';
$errors = [];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $data = $db->prepare('SELECT * FROM appartement WHERE id = :id');
    $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $data->execute();
}

if ($data->rowCount() <= 0) {
    header('Location: index.php');
    exit();
}

$errors = [];

$showMessage = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Protection contre les failles XSS 
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars(addslashes($value));
    }

    $reservation = isset($_POST['reservation_message']) ? $_POST['reservation_message'] : '';

    // Validation du formulaire
    if (empty($reservation)) {
        $errors['reservation'] = "Le message de réservation est obligatoire";
    } elseif (strlen($reservation) < 20) {
        $errors['reservation'] = "Le message de réservation ne peut pas faire moins de 20 caractères";
    }

    if (empty($errors)) {
        $query = $db->prepare('UPDATE appartement SET reservation_message = :reservation WHERE id = :id');
        $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $query->bindValue(':reservation', $reservation, PDO::PARAM_STR);
        if ($query->execute()) {
            $showMessage .= '<div class="alert alert-success">Réservation confirmée</div>';
            header('Location: annonces.php');
            exit();
        } else {
            $showMessage .= '<div class="alert alert-danger">Une erreur est survenue</div>';
        }
    }
}

$annonce = $data->fetch(PDO::FETCH_ASSOC);

$content = '';
$content .= '<div class="card mb-3">';
$content .= '<div class="card-body">';
$content .= '<h5 class="card-title">' . $annonce['title'] . '</h5>';
$content .= '<p class="card-text">' . $annonce['description'] . '</p>';
$content .= '<p class="card-text">' . $annonce['city'] . ' ' . $annonce['postal_code']   . '</p>';
$content .= '<p class="card-text"> Type : ' . $annonce['type'] . '</p>';
$content .= '<p class="card-text">' . $annonce['price'] . ' €</p>';
$content .= '<p class="card-text">Réservation : ' . $annonce['reservation_message'] . '</p>';
$content .= ' <form action="" method="post">';
$content .= '<div class="input-group mb-3"><textarea name="reservation_message" class="form-control" placeholder="Message de réservation" rows="5"></textarea></div>';
$content .= '<button type="submit" class="btn btn-primary">Réserver</button>';
$content .= '<a href="annonces.php" class="btn btn-primary" style="margin-left:25px;">Retour</a>';
$content .= '</div>';
$content .= '</div>';

?>
<?php require_once 'Common/header.php'; ?>

<div class="container">
    <h1 class="text-center">
        Détail de l'annonce : <?= $annonce['title'] ?>
    </h1>
    <?= $showMessage ?>
    <div class="row">
        <div class="col-md-10 m-auto ">
            <?php foreach ($errors as $error) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endforeach; ?>
            <?php echo $content; ?>
        </div>
    </div>
</div>

<?php require_once 'Common/footer.php'; ?>
<?php require_once 'inc/init.php' ?>

<?php require_once 'Common/header.php' ?>

<div class="container">
    <div class="container text-center">
        <h1>
            Bienvenue sur Le Bon Appart !
        </h1>
        <h3>
            Nos annonces :
        </h3>
    </div>
    <div class="container d-flex justify-content-between flex-wrap">

        <?php
        $data = $db->prepare('SELECT * FROM appartement');
        $data->execute();

        while ($annonces = $data->fetch(PDO::FETCH_ASSOC)) {

            $title = $db->prepare('SELECT title, city, postal_code FROM appartement WHERE id = :id');
            $title->bindValue(':id', $annonces['id'], PDO::PARAM_INT);
            $title->execute();
            $annonce = $title->fetch(PDO::FETCH_ASSOC);
            $annonceTitle = strtoupper($annonce['title']);

            $ville = $annonce['city'] . ' ' . $annonce['postal_code'];

            $card = '';
            $card = '';
            $card .= '<div class="card my-2" style="width: 18rem;">';
            $card .= '<div class="card-body">';
            $card .= '<h5 class="card-title">' . $annonceTitle . '</h5>';
            $card .= '<p class="card-text">' . $annonces['description'] . '</p>';
            $card .= '<p class="card-text">' . $ville . '</p>';
            $card .= '<p class="card-text"> Type : ' . $annonces['type'] . '</p>';
            $card .= '<p class="card-text">' . $annonces['price'] . ' €</p>';
            $card .= '</div>';
            $card .= '</div>';
            echo $card;
        }
        ?>

    </div>
</div>

<?php require_once 'Common/footer.php' ?>
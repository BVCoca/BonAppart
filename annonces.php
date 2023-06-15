<?php require_once 'inc/init.php' ?>

<?php require_once 'Common/header.php' ?>

<div class="container">
    <div class="container text-center">
        <h3>
            Toutes les annonces :
        </h3>
    </div>
    <div class="container d-flex justify-content-between flex-wrap">
        <!--  <ul> -->
        <?php
        $data = $db->prepare('SELECT * FROM appartement');
        $data->execute();

        while ($annonces = $data->fetch(PDO::FETCH_ASSOC)) {

            $title = $db->prepare('SELECT title FROM appartement WHERE id = :id');
            $title->bindValue(':id', $annonces['id'], PDO::PARAM_INT);
            $title->execute();
            $annonce = $title->fetch(PDO::FETCH_ASSOC);
            $annonceTitle = strtoupper($annonce['title']);

            $card = '';
            $card .= '<div class="card my-2" style="width: 18rem;">';
            $card .= $annonces['reservation_message'] !== null ? '<div style="background-color:red; text-align:center; position:absolute; right:-15px; top:-15px; border-radius:100%; padding:17px 2px">Réservé</div>' : '';
            $card .= '<div class="card-body">';
            $card .= '<h5 class="card-title">' . $annonceTitle . '</h5>';
            $card .= '<p class="card-text">' . $annonces['description'] . '</p>';
            $card .= '<p class="card-text">' . $annonces['city'] . ' ' . $annonces['postal_code']   . '</p>';
            $card .= '<p class="card-text"> Type : ' . $annonces['type'] . '</p>';
            $card .= '<p class="card-text">' . $annonces['price'] . ' €</p>';
            $card .= '<a href="detail_annonce.php?id=' . $annonces['id'] . '" class="btn btn-primary">Voir l\'annonce</a>';
            $card .= '</div>';
            $card .= '</div>';
            echo $card;
        }
        ?>
    </div>
</div>


<?php require_once 'Common/footer.php' ?>
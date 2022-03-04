<?php

$titre = 'Accueil';

require 'header.php';

$sql = "SELECT livre.*, auteur.nom, auteur.prenom, genre.genre_nom FROM livre JOIN auteur ON livre.auteur_id = auteur.id JOIN genre ON livre.genre_id = genre.id";

if (!empty($_POST['request'])) {
    $sql .= " WHERE titre LIKE :bookRequest OR nom LIKE :bookRequest OR prenom LIKE :bookRequest";
}

$sql .= " ORDER BY RAND()";

$querySearch = $pdo->prepare($sql);

if (!empty($_POST['request'])) {
    $bookRequest = $_POST['request'];

    $querySearch->bindValue(':bookRequest', "%" . $bookRequest . "%", PDO::PARAM_STR);
}

$querySearch->execute();
$books = $querySearch->fetchAll(PDO::FETCH_OBJ);

?>
<p>|</p>
<h1 class="m-top">Liste des livres</h1>
<div>
    <?php foreach ($books as $book) : ?>
        <h2 class="m-bot"><?= htmlentities($book->titre); ?></h2>
        <div class="d-flex border">
            <span>
                <p>Auteur : </p>
            </span>
            <span>
                <p class="margin-right">
                    <?= htmlentities($book->prenom) . ' ' .  htmlentities($book->nom); ?>
                </p>
            </span>
            <span>
                <p>Genre : </p>
            </span><span>
                <p class="margin-right"><?= htmlentities($book->genre_nom); ?></p>
            </span>
            <span>
                <p>Édition : </p>
            </span><span>
                <p class="margin-right"><?= htmlentities($book->edition); ?></p>
            </span>
            <span>
                <p>Parution : </p>
            </span><span>
                <p class="margin-right"><?= htmlentities($book->publication); ?></p>
            </span>
            <span>
                <p>Prix : </p>
            </span><span>
                <p class="margin-right"><?= htmlentities($book->prix); ?> €</p>
            </span>
        </div>
        <div class="border">
            <p>Résumé :</p>
            <p><?= htmlentities($book->resume); ?></p>
        </div>
        <div class="border">
            <span><button class="button a" type="submit" name="modify"><a href="modification.php?id=<?= $book->id; ?>">Modifier</a></button></span>
            <span><button class="button a" type="submit" name="delete"><a href="delete.php?id=<?= $book->id; ?>">Supprimer</a></button></span>
        </div>
    <?php endforeach ?>
</div>



<?php

require 'footer.php';

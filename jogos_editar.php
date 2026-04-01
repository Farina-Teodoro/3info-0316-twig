<?php
require('carregar_pdo.php');

$id = (int) $_GET["id"] ?? false;

if (!$id) {
    header('location:jogos.php');
    die;
}
require('carregar_twig.php');

$dados = $pdo->prepare('SELECT * FROM jogos WHERE id = :id');
$dados->execute([':id' => $id]);

if ($dados->rowCount() != 1) {
    header('location:jogos.php');
    die;
}

$jogos = $dados->fetch(PDO::FETCH_ASSOC);

echo $twig->render('jogos_editar.html', [
    'jogo' => $jogos,
]);
<?php
$erro = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? false;
    $estilo = $_POST['estilo'] ?? false;

    if (!$nome || !$estilo) {
        $erro = 'Preencha todos os campos';
    } else {

        $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
        $capa = uniqid().'.'.$ext;

        move_uploaded_file($_FILES['capa']['tmp_name'], "img/{$capa}");

        require('carregar_pdo.php');
        $dados = $pdo->prepare('INSERT INTO jogos (nome, estilo, capa) VALUES (?, ?, ?)');

        $dados->bindParam(1, $nome);
        $dados->bindParam(2, $estilo);
        $dados->bindParam(3, $capa);
        $dados->execute();

        header('location:jogos.php');
        die;
    }
}
require('carregar_twig.php');

echo $twig->render('jogos_inserir.html', [
    'erro' => $erro,
]);
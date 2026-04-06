<?php
require('carregar_pdo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST["id"] ?? false;
    $nome = $_POST["nome"] ?? false;
    $estilo = $_POST["estilo"] ?? false;
    $capa = $_FILES['capa'] ?? false;
    if($capa && !$_FILES['capa']['error']) {
        $dados = $pdo->prepare('SELECT capa FROM jogos WHERE id = :id');
        $dados->execute([':id' => $id]);
        $capa_velha = $dados->fetch(PDO::FETCH_ASSOC)['capa'];
        $capa_velha = __DIR__.'/img/'. $capa_velha;
        if(file_exists($capa_velha)) {
            unlink($capa_velha);
        }
        $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
        $capa = uniqid().'.'.$ext;
        move_uploaded_file($_FILES['capa']['tmp_name'], "img/{$capa}");      
    }
    $sql = 'UPDATE jogos SET nome = :nome, estilo = :estilo' . (isset($capa) ? ', capa = :capa' : '') . ' WHERE id = :id';
    $dados = $pdo->prepare($sql);
    $params = [
        ':id' => $id,
        ':nome' => $nome,
        ':estilo' => $estilo,
    ];
    if(isset($capa)) $params[':capa'] = $capa;
    $dados->execute($params);

    header('location:jogos.php');
    die;
}


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
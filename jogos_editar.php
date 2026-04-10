<?php
require('carregar_pdo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST["id"] ?? false;
    $nome = $_POST["nome"] ?? false;
    $estilo = $_POST["estilo"] ?? false;
    $lancamento = $_POST["lancamento"] ?? false;
    $file_capa = $_FILES['capa'] ?? false;
    if (!$id || !$nome || !$estilo || !$lancamento) {
        header('location:jogos.php');
        die;
    }
    $capa_nome = null;
    if($file_capa && $file_capa['error'] == UPLOAD_ERR_OK) {
        $dados = $pdo->prepare('SELECT capa FROM jogos WHERE id = :id');
        $dados->execute([':id' => $id]);
        $capa_velha = $dados->fetch(PDO::FETCH_ASSOC)['capa'];
        $capa_velha = __DIR__.'/img/'. $capa_velha;
        if(file_exists($capa_velha)) {
            unlink($capa_velha);
        }
        $ext = pathinfo($file_capa['name'], PATHINFO_EXTENSION);
        $capa_nome = uniqid().'.'.$ext;
        move_uploaded_file($file_capa['tmp_name'], "img/{$capa_nome}");      
    }
    $sql = 'UPDATE jogos SET nome = :nome, estilo = :estilo, lancamento = :lancamento' . ($capa_nome ? ', capa = :capa' : '') . ' WHERE id = :id';
    $dados = $pdo->prepare($sql);
    $params = [
        ':id' => $id,
        ':nome' => $nome,
        ':estilo' => $estilo,
        ':lancamento' => $lancamento,
    ];
    if($capa_nome) $params[':capa'] = $capa_nome;
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
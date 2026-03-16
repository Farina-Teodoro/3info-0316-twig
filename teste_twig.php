<?php
require('carregar_twig.php');

$nome = 'Théo Método';
$disciplinas = [
    'Programação',
    'Banco de Dados',
    'Interface Web',
    'Desenvolvimento de Sistemas',
];

echo $twig->render('teste_twig.html', [
    'nome' => $nome,
    'legal' => true,
    'disciplinas' => $disciplinas,
]);
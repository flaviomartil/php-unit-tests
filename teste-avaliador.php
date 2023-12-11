<?php
require 'vendor/autoload.php';
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use Alura\Leilao\Model\Lance;

//Arrange - Given
$leilao = new Leilao('Fiat 147 0km');
$maria = new Usuario('maria');
$joao = new Usuario('Joao');

$leilao->recebeLance(new Lance($joao,2000));
$leilao->recebeLance(new Lance($maria,2500));

//Act - When
$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();
//Assert - Then
$valorEsperado = 2500;

if ($maiorValor == $valorEsperado) {
    echo "teste ok" . PHP_EOL;
} else {
    echo "teste falhou" . PHP_EOL;
}
echo $maiorValor;
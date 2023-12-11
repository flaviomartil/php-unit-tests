<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ) {

        $this->assertCount($qtdLances,$leilao->getLances());

        foreach ($valores as $i => $valorEsperado) {
            $this->assertEquals($valorEsperado,$leilao->getLances()[$i]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor dois lances consecutivos.');
        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');
        $leilao->recebeLance(new Lance($ana,1000));
        $leilao->recebeLance(new Lance($ana,1500));
        $this->assertCount(1,$leilao->getlances());
        $this->assertEquals(1000,$leilao->getLances()[0]->getValor());

    }

    public function testeLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
      $this->expectException(\DomainException::class);
      $this->expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

      $leilao = new Leilao('Brasilia Amarela');
      $joao = new Usuario('João');
      $maria = new Usuario('Maria');

      $leilao->recebeLance(new Lance($joao, 1000));
      $leilao->recebeLance(new Lance($maria, 1500));
      $leilao->recebeLance(new Lance($joao, 2000));
      $leilao->recebeLance(new Lance($maria, 2500));
      $leilao->recebeLance(new Lance($joao, 3000));
      $leilao->recebeLance(new Lance($maria, 3500));
      $leilao->recebeLance(new Lance($joao, 4000));
      $leilao->recebeLance(new Lance($maria, 4500));
      $leilao->recebeLance(new Lance($joao, 5000));
      $leilao->recebeLance(new Lance($maria, 5500));
      $leilao->recebeLance(new Lance($joao, 6000));

      $this->assertCount(10,$leilao->getLances());
      $this->assertEquals(5500,$leilao->getLances()[array_key_last($leilao->getLances())]->getValor());


    }

    public static function geraLances()
    {
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');
        $leilaoCom2Lances = new Leilao('Fiat 147 0km');
        $leilaoCom2Lances->recebeLance(new Lance($joao,1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria,2000));

        $leilaoCom1Lances = new Leilao('Fusca 1972 0km');
        $leilaoCom1Lances->recebeLance(new Lance($maria,5000));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            '1-lance' => [1, $leilaoCom1Lances, [5000]]
        ];
    }
}
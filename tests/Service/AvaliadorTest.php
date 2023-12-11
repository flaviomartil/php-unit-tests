<?php

namespace Alura\Leilao\Tests\Service;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use Alura\Leilao\Model\Lance;

class AvaliadorTest extends  TestCase
{
    /** @var Avaliador  */
    private $leiloeiro;
    protected function setUp() : void
    {
        echo "Executando setup" . PHP_EOL;
        $this->leiloeiro = new Avaliador();
    }
    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDeCrescente
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances (Leilao $leilao)
    {
        //Act - When
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        //Assert - Then
        $this->assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDeCrescente
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances (Leilao $leilao)
    {
        //Act - When
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        //Assert - Then
        $this->assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDeCrescente
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemDeCrescente (Leilao $leilao)
    {
        //Act - When
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        //Assert - Then
        $this->assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDeCrescente
     */
    public function testAvaliadorDeveBuscar3MaioresValores (Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);
        $maiores = $this->leiloeiro->getMaioresLances();
        $this->assertCount(3,$maiores);
        $this->assertEquals(2500,$maiores[0]->getValor());
        $this->assertEquals(2000,$maiores[1]->getValor());
        $this->assertEquals(1700,$maiores[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
            $this->expectException(\DomainException::class);
            $this->expectExceptionMessage('Não é possível avaliar leilão vazio');

            $leilao = new Leilao('Fusca azul');
            $this->leiloeiro->avalia($leilao);
    }

    public function testeLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado.');
        $leilao = new Leilao('Fiat 147 0km');
        $leilao->recebeLance(new Lance(new Usuario('Teste'),2000));
        $leilao->finaliza();
        $this->leiloeiro->avalia($leilao);
    }

    public static function leilaoEmOrdemCrescente()
    {
        echo "Criando em ordem crescente" . PHP_EOL;

        $leilao = new Leilao('Fiat 147 0km');
        $maria = new Usuario('maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana,1700));
        $leilao->recebeLance(new Lance($joao,2000));
        $leilao->recebeLance(new Lance($maria,2500));

        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    public static function leilaoEmOrdemDeCrescente()
    {
        echo "Criando em ordem decrescente" . PHP_EOL;
        $leilao = new Leilao('Fiat 147 0km');
        $maria = new Usuario('maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria,2500));
        $leilao->recebeLance(new Lance($joao,2000));
        $leilao->recebeLance(new Lance($ana,1700));

        return [
            'ordem-decrescente' => [$leilao]
        ];
    }

    public static function leilaoEmOrdemAleatoria()
    {
        echo "Criando em ordem aleatoria" . PHP_EOL;
        $leilao = new Leilao('Fiat 147 0km');
        $maria = new Usuario('maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao,2000));
        $leilao->recebeLance(new Lance($maria,2500));
        $leilao->recebeLance(new Lance($ana,1700));

        return [
            'ordem-aleatoria' => [$leilao]
        ];
    }

    public function entregaLeiloes()
    {
         return [
            [ $this->leilaoEmOrdemCrescente()],
             [$this->leilaoEmOrdemDeCrescente()],
             [$this->leilaoEmOrdemAleatoria()]
         ];
    }
    }
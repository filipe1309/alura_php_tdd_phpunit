<?php

namespace Alura\Leilao\Tests\Model;

use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\{ Leilao, Usuario, Lance };

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $leilao = new Leilao('Variante');

        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

        static::assertCount(1, $leilao->getLances());
        static::assertEquals(1000, $leilao->getLances()[0]->getValor());
    }

    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances($qtdLances, Leilao $leilao, array $valores)
    {
        static::assertCount($qtdLances, $leilao->getLances());

        foreach ($valores as $i => $valorEsperado) {
            static::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $leilao = new Leilao('Brasilia amarela');

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

        static::assertCount(10, $leilao->getLances());
        static::assertEquals(5500, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat 147 0km');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria, 2000));
        
        $leilaoCom1Lance = new Leilao('Fusca 1970 0km');
        $leilaoCom1Lance->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            '1-lance' => [1, $leilaoCom1Lance, [5000]],
        ];
    }
}
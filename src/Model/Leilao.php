<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function quantidadeLancesPorUsuario(Usuario $usuario): int
    {
        $totalDeLancesPorUsuario = array_reduce(
            $this->lances,
            function(int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            }, 0);

        return $totalDeLancesPorUsuario;
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance)) {
            throw new \DomainException('Usuario nao pode propor 2 lances consecutivos');
        }

        $totalDeLancesPorUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());
        if ($totalDeLancesPorUsuario >= 5) {
            throw new \DomainException('Usuario nao pode propor mais de 5 lances por leilao');
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}

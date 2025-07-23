<?php
class CalculoViagem {
    private float $valorDiario = 175;
    private float $descontoVr = 30;
    private float $valorCafe = 20;

    private int $totalDias = 0;
    private int $diasFimSemana = 0;
    private int $diasFeriados = 0;
    private bool $empresaPagaCafePrimeiroDia = false;

    public ?string $erro = null;
    public ?float $total = null;
    public ?float $valorDiasNormais = null;
    public ?float $valorDiasEspeciais = null;
    public ?int $diasNormais = null;
    public ?float $valorCafeManha = null;

    public function __construct(int $totalDias, int $diasFimSemana, int $diasFeriados, bool $empresaPagaCafePrimeiroDia) {
        $this->totalDias = max(0, $totalDias);
        $this->diasFimSemana = max(0, $diasFimSemana);
        $this->diasFeriados = max(0, $diasFeriados);
        $this->empresaPagaCafePrimeiroDia = $empresaPagaCafePrimeiroDia;
    }

    public function calcular(): void {
        $this->diasNormais = $this->totalDias - $this->diasFimSemana - $this->diasFeriados;

        if ($this->diasNormais < 0) {
            $this->erro = "Erro: a soma de finais de semana e feriados não pode ser maior que o total de dias.";
            return;
        }

        $this->valorDiasNormais = $this->diasNormais * ($this->valorDiario - $this->descontoVr);
        $this->valorDiasEspeciais = ($this->diasFimSemana + $this->diasFeriados) * $this->valorDiario;

        // Café da manhã apenas se empresa pagar, e não no primeiro dia
        $qtdDiasCafe = $this->empresaPagaCafePrimeiroDia ? max(0, $this->totalDias - 1) : 0;

        $this->valorCafeManha = $qtdDiasCafe * $this->valorCafe;

        $this->total = $this->valorDiasNormais + $this->valorDiasEspeciais + $this->valorCafeManha;
    }

    public function toArray(): array {
        return [
            'erro' => $this->erro,
            'total' => $this->total,
            'valorDiasNormais' => $this->valorDiasNormais,
            'valorDiasEspeciais' => $this->valorDiasEspeciais,
            'diasNormais' => $this->diasNormais,
            'diasFeriados' => $this->diasFeriados,
            'diasFimSemana' => $this->diasFimSemana,
            'valorDiario' => $this->valorDiario,
            'descontoVr' => $this->descontoVr,
            'valorCafe' => $this->valorCafe,
            'valorCafeManha' => $this->valorCafeManha,
            'empresaPagaCafePrimeiroDia' => $this->empresaPagaCafePrimeiroDia,
        ];
    }
}
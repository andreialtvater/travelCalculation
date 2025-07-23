<?php if ($resultado['erro']): ?>
    <div class="erro"><?= htmlspecialchars($resultado['erro']) ?></div>
<?php elseif ($resultado['total'] !== null): ?>
    <div class="resultado">
        <strong>Resumo da viagem:</strong><br>
        Data de início: <?= htmlspecialchars($dataInicio ?? 'N/A') ?><br>
        Data de retorno: <?= htmlspecialchars($dataFim ?? 'N/A') ?><br>
        Total de dias da viagem: <?= htmlspecialchars($totalDias ?? 0) ?><br><br>

        Dias normais (<?= $resultado['diasNormais'] ?>) × R$ <?= number_format($resultado['valorDiario'] - $resultado['descontoVr'], 2, ',', '.') ?>
        = R$ <?= number_format($resultado['valorDiasNormais'], 2, ',', '.') ?><br>

        Feriados e finais de semana (<?= ($resultado['diasFimSemana'] + $resultado['diasFeriados']) ?>) × R$ <?= number_format($resultado['valorDiario'], 2, ',', '.') ?>
        = R$ <?= number_format($resultado['valorDiasEspeciais'], 2, ',', '.') ?><br>

        <?php if ($resultado['valorCafeManha'] > 0): ?>
            Café da manhã (<?= ($totalDias - 1) ?> dias × R$ <?= number_format($resultado['valorCafe'], 2, ',', '.') ?>)
            = R$ <?= number_format($resultado['valorCafeManha'], 2, ',', '.') ?><br>
        <?php endif; ?>

        <hr>
        <strong>Total da viagem: R$ <?= number_format($resultado['total'], 2, ',', '.') ?></strong>
    </div>
<?php endif; ?>

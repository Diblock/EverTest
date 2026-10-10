<?php $attempts = $storage->attempts(); ?>
<section class="section-title">
    <span class="eyebrow">HISTORIAL</span>
    <h2>Todos los intentos</h2>
    <p>Cada intento queda guardado para medir tu evolución real.</p>
</section>
<section class="panel">
<?php if (!$attempts): ?>
    <p class="muted">No hay intentos todavía.</p>
<?php else: ?>
<div class="table-wrap">
<table>
<thead><tr><th>#</th><th>Examen</th><th>Asignatura</th><th>Nota</th><th>Aciertos</th><th>Tiempo</th><th>Fecha</th><th></th></tr></thead>
<tbody>
<?php foreach ($attempts as $a): ?>
<tr>
<td><?= (int)$a['id'] ?></td>
<td><?= e($a['exam_title']) ?></td>
<td><span class="tag"><?= e($a['module_code'].' · '.$a['unit_code']) ?></span></td>
<td><strong class="<?= e(score_class((float)$a['score'])) ?>"><?= number_format((float)$a['score'],1,',','') ?></strong></td>
<td><?= (int)$a['correct_count'] ?>/<?= (int)$a['total_count'] ?></td>
<td><?= e(format_duration((int)$a['duration_seconds'])) ?></td>
<td><?= e(date('d/m/Y H:i', strtotime($a['finished_at']))) ?></td>
<td><a href="?page=result&id=<?= (int)$a['id'] ?>">Revisar</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>
</section>

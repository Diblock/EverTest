<?php
$examId = (int)($_GET['id'] ?? 0);
$exam = $storage->getExam($examId);
if (!$exam) {
    echo '<div class="alert danger">Examen no encontrado.</div>';
    return;
}
$onlyMissed = isset($_GET['missed_from']) ? (int)$_GET['missed_from'] : 0;
$questions = $exam['questions'];

if ($onlyMissed) {
    $previous = $storage->attemptDetail($onlyMissed);
    if ($previous && (int)$previous['exam_id'] === $examId) {
        $missedIds = [];
        foreach ($previous['review'] as $r) {
            if (!(int)$r['is_correct']) $missedIds[] = (int)$r['id'];
        }
        if ($missedIds) $questions = array_values(array_filter($questions, fn($q)=>in_array((int)$q['id'],$missedIds,true)));
    }
}
?>
<section class="exam-header">
    <div>
        <span class="tag"><?= e($exam['module_code'].' · '.$exam['unit_code']) ?></span>
        <h2><?= e($exam['title']) ?></h2>
        <p><?= e($exam['description']) ?></p>
    </div>
    <div class="timer-card">
        <span>Tiempo</span>
        <strong id="timer">00:00</strong>
    </div>
</section>

<form method="post" action="?page=submit" id="examForm" class="exam-layout">
    <input type="hidden" name="exam_id" value="<?= (int)$examId ?>">
    <input type="hidden" name="started_at" id="startedAt" value="<?= e(date('c')) ?>">
    <input type="hidden" name="duration_seconds" id="durationSeconds" value="0">

    <div class="question-stack">
        <?php foreach ($questions as $index=>$q): ?>
        <article class="question-card" data-question="<?= $index+1 ?>" id="q<?= (int)$q['id'] ?>">
            <div class="question-kicker">Pregunta <?= $index+1 ?> de <?= count($questions) ?></div>
            <h3><?= e($q['text']) ?></h3>
            <div class="options">
            <?php foreach ($q['options'] as $letter=>$text): ?>
                <label class="option">
                    <input type="radio" name="answers[<?= (int)$q['id'] ?>]" value="<?= e($letter) ?>">
                    <span class="option-letter"><?= e($letter) ?></span>
                    <span><?= e($text) ?></span>
                </label>
            <?php endforeach; ?>
            </div>
        </article>
        <?php endforeach; ?>

        <div class="exam-actions">
            <button class="btn primary" type="submit">Finalizar y corregir</button>
            <a class="btn secondary" href="?page=dashboard">Salir sin guardar</a>
        </div>
    </div>

    <aside class="question-nav">
        <strong>Navegación</strong>
        <div class="question-grid">
            <?php foreach ($questions as $index=>$q): ?>
                <a href="#q<?= (int)$q['id'] ?>" data-nav-for="<?= (int)$q['id'] ?>"><?= $index+1 ?></a>
            <?php endforeach; ?>
        </div>
        <div class="legend"><span><i class="answered"></i> Respondida</span><span><i></i> Pendiente</span></div>
    </aside>
</form>

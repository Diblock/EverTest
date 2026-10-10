<?php
declare(strict_types=1);
require_once __DIR__ . '/app/bootstrap.php';

if (current_page() === 'submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $examId = (int)($_POST['exam_id'] ?? 0);
    $answers = $_POST['answers'] ?? [];
    $duration = max(0, min(86400, (int)($_POST['duration_seconds'] ?? 0)));
    $startedAt = (string)($_POST['started_at'] ?? date('c'));
    try {
        $attemptId = $storage->saveAttempt($examId, $answers, $duration, $startedAt);
        redirect('?page=result&id='.$attemptId);
    } catch (Throwable $e) {
        $_SESSION['flash_error'] = $e->getMessage();
        redirect('?page=dashboard');
    }
}

require __DIR__ . '/pages/header.php';

if (!empty($_SESSION['flash_error'])) {
    echo '<div class="alert danger">'.e((string)$_SESSION['flash_error']).'</div>';
    unset($_SESSION['flash_error']);
}

switch (current_page()) {
    case 'exam': require __DIR__ . '/pages/exam.php'; break;
    case 'result': require __DIR__ . '/pages/result.php'; break;
    case 'history': require __DIR__ . '/pages/history.php'; break;
    case 'create': require __DIR__ . '/pages/create.php'; break;
    case 'import': require __DIR__ . '/pages/import.php'; break;
    default: require __DIR__ . '/pages/dashboard.php'; break;
}

require __DIR__ . '/pages/footer.php';

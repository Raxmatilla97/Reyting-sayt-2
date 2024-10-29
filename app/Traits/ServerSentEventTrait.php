<?php

namespace App\Traits;

trait ServerSentEventTrait
{
    protected function sendUpdate($message, $progress)  // private emas, protected qilamiz
    {
        \Log::info("Yangilanish yuborilmoqda: $message, Progress: $progress");

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        echo 'data: ' . json_encode([
            'message' => $message,
            'progress' => $progress
        ]) . "\n\n";

        if (ob_get_level() == 0) ob_start();

        ob_flush();
        flush();
    }
}

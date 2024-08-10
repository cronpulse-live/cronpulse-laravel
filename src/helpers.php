<?php

if (! function_exists('Cronpulse\LaravelMonitor\wrap')) {
    function wrap($jobKey, callable $jobFunction)
    {
        $monitor = new Cronpulse\LaravelMonitor\Monitor($jobKey);
        $startTime = microtime(true);
        $errorOccurred = false;
        $errorMessage = '';

        try {
            $monitor->ping(['state' => 'start']);

            $jobFunction();
        } catch (\Exception $e) {
            $errorOccurred = true;
            $errorMessage = $e->getMessage();
        } finally {
            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            $finalState = $errorOccurred ? 'fail' : 'success';
            $monitor->ping(['state' => $finalState, 'message' => $errorMessage]);

            echo "Job execution time: {$executionTime} ms\n";
        }
    }
}

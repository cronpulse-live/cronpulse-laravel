<?php

namespace Cronpulse\LaravelMonitor;

use Illuminate\Support\Facades\Http;

class Monitor
{
    protected $jobKey;
    protected $baseUrl;

    public function __construct($jobKey = null, $baseUrl = 'https://app.cronpulse.live')
    {
        $this->jobKey = $jobKey ?? config('monitor.job_key') ?? env('MONITOR_JOB_KEY');
        $this->baseUrl = $baseUrl;

        if (!$this->jobKey) {
            throw new \InvalidArgumentException('Job key is required.');
        }
    }



    public function getJobKey()
    {
        return $this->jobKey;
    }

    public function ping($stateOrOptions)
    {
        $state = '';
        $message = '';

        if (is_string($stateOrOptions)) {
            $state = $stateOrOptions;
        } elseif (is_array($stateOrOptions)) {
            $state = $stateOrOptions['state'];
            $message = $stateOrOptions['message'] ?? '';
        } else {
            throw new \InvalidArgumentException('Invalid argument: stateOrOptions must be a string or an array.');
        }

        $endpoint = '';
        $queryParams = [
            'client' => 'cronpulse laravel',
        ];

        switch ($state) {
            case 'beat':
                $endpoint = "/api/ping/{$this->jobKey}";
                break;
            case 'start':
                $endpoint = "/api/ping/{$this->jobKey}/start";
                break;
            case 'success':
                $endpoint = "/api/ping/{$this->jobKey}/success";
                break;
            case 'fail':
                $endpoint = "/api/ping/{$this->jobKey}/fail";
                $queryParams['errorMessage'] = $message ?: true;
                break;
            default:
                throw new \InvalidArgumentException("Invalid state: {$state}");
        }

        return $this->sendRequest($endpoint, $queryParams);
    }

    protected function sendRequest($endpoint, $queryParams = [])
    {
        $url = "{$this->baseUrl}{$endpoint}";

        $response = Http::get($url, $queryParams);

        if ($response->failed()) {
            throw new \Exception('Failed to ping monitor service: ' . $response->body());
        }

        return $response->body();
    }
}

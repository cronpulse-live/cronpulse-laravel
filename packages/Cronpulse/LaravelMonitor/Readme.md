# Cronpulse Laravel Monitor

Cronpulse Laravel Monitor is a monitoring library for Laravel applications that allows you to easily send heartbeat pings, start/stop job pings, and wrap jobs for monitoring. This helps ensure your scheduled tasks are running as expected and provides insights into job failures or successes.

## Installation

To install the package, add it to your Laravel project's `composer.json` and run `composer update`:

```bash
composer require cronpulse/laravel-monitor
```

## Configuration

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Cronpulse\\LaravelMonitor\\MonitorServiceProvider"
```

Add your job key to your `.env` file:

```bash
MONITOR_JOB_KEY=your-real-job-key
```

This job key is essential for authenticating your pings with cronpulse.

## Usage

### Basic Monitoring

You can manually ping cronpulse by using the `Monitor` class. This can be useful for sending status updates about your scheduled jobs.

#### Starting a Job

To start monitoring a job:

```php
use Cronpulse\LaravelMonitor\Monitor;

$monitor = new Monitor();
$monitor->ping('start');
```

#### Marking a Job as Successful

After your job completes successfully:

```php
$monitor->ping('success');
```

#### Marking a Job as Failed

If your job encounters an error and fails:

```php
$dynamicError = 'This is a dynamic error message';
$monitor->ping(['state' => 'fail', 'message' => $dynamicError]);
```

#### Sending Heartbeat Pings

You can also send heartbeat pings to monitor the regular execution of a task:

```php
$monitor->ping('beat');
```

### Wrapping a Job

The `wrap` function provides a convenient way to monitor a job's start and completion, including error handling.

#### Example with a Successful Job

To wrap a job function that should complete successfully:

```php
use function Cronpulse\LaravelMonitor\wrap;

wrap('your-job-key', function() {
    // Your job logic here
    return true;
});
```

#### Example with a Failing Job

To wrap a job function that might fail:

```php
wrap('your-job-key', function() {
    // Simulate job failure
    throw new \Exception('Something went wrong');
});
```

The `wrap` function will automatically handle sending pings for the job start, success, or failure.

## Testing

The library includes tests to ensure its functionality. You can run the tests using Laravel’s test runner:

```bash
php artisan test
```

Ensure that your `.env` file contains the correct `MONITOR_JOB_KEY` for the tests to run against your monitoring service.

<?php

namespace Modules\Logs\Logging;

use Illuminate\Foundation\Application;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;

class CustomizeFormatter
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct(
        public Application $app
    ) {
    }

    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $logFormat = "[%datetime%] | %level_name%[%channel%] | %message% | %context% %extra%\n";

        $dateFormat = "Y-m-d H:i:s";

        foreach ($logger->getHandlers() as $handler) {
            if (config('logging.verbose')) {
                $handler->pushProcessor(new IntrospectionProcessor());
                $handler->pushProcessor(new MemoryUsageProcessor());
                $handler->pushProcessor(new MemoryPeakUsageProcessor());
            }

            $handler->setFormatter(new LineFormatter(
                format: $logFormat,
                dateFormat: $dateFormat,
                allowInlineLineBreaks: true,
                ignoreEmptyContextAndExtra: true,
                includeStacktraces: true,
            ));
        };
    }
}
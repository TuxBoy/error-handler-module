<?php
declare(strict_types = 1);

use function DI\get;
use Stratify\ErrorHandlerModule\ErrorResponder\ErrorResponder;
use Stratify\ErrorHandlerModule\ErrorResponder\WhoopsResponder;

return [

    ErrorResponder::class => get(WhoopsResponder::class),

];

<?php
declare(strict_types=1);

namespace Integer;

use Throwable;

class MatcherException extends \LogicException
{
    public function __construct($message = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

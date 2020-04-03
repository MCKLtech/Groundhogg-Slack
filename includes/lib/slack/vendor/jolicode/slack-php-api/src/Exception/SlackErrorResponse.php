<?php

declare(strict_types=1);

/*
 * This file is part of JoliCode's Slack PHP API project.
 *
 * (c) JoliCode <coucou@jolicode.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JoliCode\Slack\Exception;

class SlackErrorResponse extends \Exception
{
    private $errorCode;

    public function __construct(string $errorCode, \Throwable $previous = null)
    {
        $this->errorCode = $errorCode;

        parent::__construct(sprintf('Slack returned error code "%s"', $errorCode), 0, $previous);
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}

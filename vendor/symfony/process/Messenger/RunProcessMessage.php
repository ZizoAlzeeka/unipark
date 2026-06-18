<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Process\Messenger;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RunProcessMessage implements \Stringable
{
    public function __construct(
        public array $command,
        public ?string $cwd = null,
        public ?array $env = null,
        public mixed $input = null,
        public ?float $timeout = 60.0,
    ) {
    }

    public function __toString(): string
    {
        return implode(' ', $this->command);
    }
}

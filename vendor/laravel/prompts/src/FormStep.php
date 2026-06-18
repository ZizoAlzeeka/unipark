<?php

namespace Laravel\Prompts;

use Closure;

class FormStep
{
    protected Closure $condition;

    public function __construct(
        protected Closure $step,
        bool|Closure $condition,
        public ?string $name,
        protected bool $ignoreWhenReverting,
    ) {
        $this->condition = is_bool($condition)
            ? fn () => $condition
            : $condition;
    }

    /**
     * Execute this step.
     *
     * @param  array<mixed>  $responses
     */
    public function run(array $responses, mixed $previousResponse): mixed
    {
        if (! $this->shouldRun($responses)) {
            return null;
        }

        return ($this->step)($responses, $previousResponse);
    }

    /**
     * Whether the step should run based on the given condition.
     *
     * @param  array<mixed>  $responses
     */
    protected function shouldRun(array $responses): bool
    {
        return ($this->condition)($responses);
    }

    /**
     * Whether this step should be skipped over when a subsequent step is reverted.
     *
     * @param  array<mixed>  $responses
     */
    public function shouldIgnoreWhenReverting(array $responses): bool
    {
        if (! $this->shouldRun($responses)) {
            return true;
        }

        return $this->ignoreWhenReverting;
    }
}

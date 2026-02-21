<?php

namespace Fyre\Utility\Traits;

use BadMethodCallException;
use Closure;

use function array_key_exists;

/**
 * MacroTrait
 */
trait MacroTrait
{
    protected static array $macros = [];

    /**
     * Clear all macros.
     */
    public static function clearMacros(): void
    {
        static::$macros = [];
    }

    /**
     * Determine whether a macro is registered.
     *
     * @param string $name The name of the macro.
     * @return bool TRUE if the macro is registered, otherwise FALSE.
     */
    public static function hasMacro(string $name): bool
    {
        return array_key_exists($name, static::$macros);
    }

    /**
     * Register a macro.
     *
     * @param string $name The name of the macro.
     * @param callable $macro The macro callback.
     */
    public static function macro(string $name, callable $macro): void
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Call a registered macro on an instance.
     *
     * @param string $name The name of the macro.
     * @param array $args The arguments to pass to the macro.
     * @return mixed The result of the macro call.
     *
     * @throws BadMethodCallException If the macro is not registered.
     */
    public function __call(string $name, array $args = [])
    {
        if (!array_key_exists($name, static::$macros)) {
            throw new BadMethodCallException('Macro '.$name.' is not registered.');
        }

        return Closure::bind(static::$macros[$name](...), $this, static::class)(...$args);
    }
}

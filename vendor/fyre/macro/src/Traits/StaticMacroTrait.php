<?php

namespace Fyre\Utility\Traits;

use BadMethodCallException;
use Closure;

use function array_key_exists;

/**
 * StaticMacroTrait
 */
trait StaticMacroTrait
{
    protected static array $staticMacros = [];

    /**
     * Clear all static macros.
     */
    public static function clearStaticMacros(): void
    {
        static::$staticMacros = [];
    }

    /**
     * Determine whether a static macro is registered.
     *
     * @param string $name The name of the macro.
     * @return bool TRUE if the macro is registered, otherwise FALSE.
     */
    public static function hasStaticMacro(string $name): bool
    {
        return array_key_exists($name, static::$staticMacros);
    }

    /**
     * Register a static macro.
     *
     * @param string $name The name of the macro.
     * @param callable $macro The callback.
     */
    public static function staticMacro(string $name, callable $macro): void
    {
        static::$staticMacros[$name] = $macro;
    }

    /**
     * Call a registered static macro.
     *
     * @param string $name The name of the macro.
     * @param array $args The arguments to pass to the macro.
     * @return mixed The result of the macro call.
     *
     * @throws BadMethodCallException If the macro is not registered.
     */
    public static function __callStatic(string $name, array $args = [])
    {
        if (!array_key_exists($name, static::$staticMacros)) {
            throw new BadMethodCallException('Macro '.$name.' is not registered.');
        }

        return Closure::bind(static::$staticMacros[$name](...), null, static::class)(...$args);
    }
}

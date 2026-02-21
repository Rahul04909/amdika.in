# FyreMacro

**FyreMacro** is a free, open-source macro utility library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Macros](#macros)
    - [Calling Macros](#calling-macros)
- [Static Macros](#static-macros)
    - [Calling Static Macros](#calling-static-macros)



## Installation

**Using Composer**

```
composer require fyre/macro
```


## Macros

You can attach `Fyre\Utility\Traits\MacroTrait` to any class.

```php
use Fyre\Utility\Traits\MacroTrait;

// in any class
class MyClass {
    use MacroTrait;
}
```

**Clear Macros**

Clear all macros.

```php
MyClass::clearMacros();
```

**Has Macro**

Determine whether a macro is registered.

- `$name` is a string representing the name of the macro.

```php
$hasMacro = MyClass::hasMacro($name);
```

**Macro**

Register a macro.

- `$name` is a string representing the name of the macro.
- `$callback` is the macro callback.

```php
MyClass::macro($name, $callback);
```

### Calling Macros

```php
MyClass::macro('myMethod', function(): bool {
    return true;
});

new MyClass()->myMethod(); // true
```


## Static Macros

You can attach `Fyre\Utility\Traits\StaticMacroTrait` to any class.

```php
use Fyre\Utility\Traits\StaticMacroTrait;

// in any class
class MyClass {
    use StaticMacroTrait;
}
```

**Clear Static Macros**

Clear all static macros.

```php
MyClass::clearStaticMacros();
```

**Has Static Macro**

Determine whether a static macro is registered.

- `$name` is a string representing the name of the macro.

```php
$hasStaticMacro = MyClass::hasStaticMacro($name);
```

**Static Macro**

Register a static macro.

- `$name` is a string representing the name of the macro.
- `$callback` is the macro callback.

```php
MyClass::staticMacro($name, $callback);
```

### Calling Static Macros

```php
MyClass::staticMacro('myMethod', function(): bool {
    return true;
});

MyClass::myMethod(); // true
```
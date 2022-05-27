<?php

abstract class ArgErrCheck
{
    const msg = 'Error in parsing constructor arguments';

    // Use this to check if array arguments have all necessary keys
    // If false it should throw InvalidArgumentException
    abstract protected function argErrCheck(...$data): bool|InvalidArgumentException;

    // Checks for keys in specific array
    protected function keysExist(array $array, int|string ...$keys): bool {
        foreach ($keys as $key)
            if (!array_key_exists($key, $array))
                return false;
        return true;
    }

    // Checks if string is one of the given ones
    protected function strOneOf(string $string, string ...$keys): bool {
        return in_array($string, $keys);
    }
}
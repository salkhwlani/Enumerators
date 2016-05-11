<?php namespace CupOfTea\Enums;

use BadMethodCallException;

trait HasMagicEnums
{
    use HasEnums;
    
    /**
     * Check if the method can be parsed as an isEnum call.
     *
     * @param  string $method
     * @return bool
     */
    protected static function canCallEnum($method)
    {
        if (preg_match('/^is([A-Z][A-z]*[A-Z][A-z]*)/', $method, $matches)) {
            return static::isValidEnumKey($matches[1]);
        }
        
        return false;
    }
    
    /**
     * Parse method to isEnum call and execute the call.
     *
     * @param  string $method
     * @param  array $args
     * @return null|bool
     */
    protected function callEnum($method, $args)
    {
        if (! static::canCallEnum($method)) {
            return;
        }
        
        return $this->isEnum(lcfirst(preg_replace('/^is/', '', $method)));
    }
    
    /**
     * Handle dynamic method calls.
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (static::canCallEnum($method)) {
            return $this->callEnum($method, $args);
        }
        
        if (is_callable(['parent', '__call'])) {
            return parent::__call($method, $args);
        }
        
        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}

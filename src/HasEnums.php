<?php namespace CupOfTea\Enums;

use ReflectionClass;
use InvalidArgumentException;

trait HasEnums
{
    /**
     * The array of enumerators of a given group.
     *
     * @param  null|string $group
     * @return array
     */
    public static function enums($group = null)
    {
        $constants = (new ReflectionClass(get_called_class()))->getConstants();
        
        if ($group) {
            $group = static::_toEnumKey($group);
            
            return array_filter($constants, function ($key) use ($group) {
                return strpos($key, $group . '_') === 0;
            }, ARRAY_FILTER_USE_KEY);
        }
        
        return $constants;
    }
    
    /**
     * Check if the given value is valid within the given group.
     *
     * @param  mixed $value
     * @param  null|string $group
     * @return bool
     */
    public static function isValidEnumValue($value, $group = null)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('An enumerator value must be greater than or equal to 1.');
        }
        
        $constants = static::enums($group);
        
        return in_array($value, $constants);
    }
    
    /**
     * Check if the given key exists.
     *
     * @param  mixed $key
     * @return bool
     */
    public static function isValidEnumKey($key)
    {
        return array_key_exists($key, static::enums());
    }
    
    /**
     * Check if the class is the given Enumerator.
     *
     * @param  string $key
     * @param  null|string $group
     * @return bool
     */
    public function isEnum($key, $group = null)
    {
        $enumKey = $this->_toEnumKey($key, $group);
        
        if (! static::isValidEnumKey($enumKey)) {
            throw new InvalidArgumentException("The key {$key} is not a valid Enumerator key" . ($group ? " for group {$group}." : '.'));
        }
        
        $getProp = function ($group) {
            return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($group)))));
        };
        
        if (! $group) {
            $parts = explode('_', $enumKey);
            
            do {
                array_pop($parts);
                
                $group = $this->_toEnumKey(implode('_', $parts));
                $prop = $getProp($group);
            } while (! property_exists($this, $prop) && count($parts));
        } else {
            $group = $this->_toEnumKey($group);
            $prop = $getProp($group);
        }
        
        if (! property_exists($this, $prop)) {
            return false;
        }
        
        return $this->$prop == static::enums($group)[$enumKey];
    }
    
    /**
     * Convert string to an Enumerator key.
     *
     * @param  string $key
     * @param  null|string $group
     * @return string
     */
    private static function _toEnumKey($key, $group = null)
    {
        if (! ctype_upper(str_replace('_', '', $key))) {
            $key = preg_replace('/\s+/u', '', $key);
            $key = strtoupper(preg_replace('/(.)(?=[A-Z])/u', '$1_', $key));
        }
        
        if ($group) {
            return static::_toEnumKey((string) $group) . '_' . $key;
        }
        
        return $key;
    }
}

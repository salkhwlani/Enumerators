<?php namespace CupOfTea\Enums;

use ReflectionClass;

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
		$key = static::_toEnumKey($key);
		
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
		$key = $this->_toEnumKey($key, $group);
		
		if (! $group) {
			$parts = explode('_', $key);
			
			do {
				array_pop($parts);
				
				$group = $this->_toEnumKey(implode('_', $parts));
				$prop = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($group)))));
			} while (! property_exists($this, $prop) && count($parts));
		} else {
			$group = $this->_toEnumKey($group);
        	$prop = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($group)))));
		}
		
		if (! property_exists($this, $prop)) {
			return false;
		}
		
        return $this->$prop == static::enums($group)[$key];
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

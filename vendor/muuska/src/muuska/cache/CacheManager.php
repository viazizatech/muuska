<?php
namespace muuska\cache;

interface CacheManager{
    /**
     * @param string $key
     * @return bool
     */
    public function isStored($key);
    
    /**
     * @param string $key
     * @param string $string
     * @param int $lifeTime
     * @return bool
     */
    public function store($key, $string, $lifeTime = null);
    
    /**
     * @param string $key
     */
    public function clean($key);
    
    public function clear();
    
    /**
     * @param string $key
     * @return string
     */
    public function retrieve($key);
}
<?php
namespace muuska\cache;

use muuska\util\App;

class FileCacheManager implements CacheManager{
    /**
     * {@inheritDoc}
     * @see \muuska\cache\CacheManager::clear()
     */
    public function clear()
    {
        App::getFileTools()->clearDirectoryContent(App::getApp()->getCacheDir());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\cache\CacheManager::retrieve()
     */
    public function retrieve($key)
    {
        $file = $this->getFullFile($key);
        return file_exists($file) ? file_get_contents($file) : '';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\cache\CacheManager::store()
     */
    public function store($key, $string, $lifeTime = null)
    {
        return App::getFileTools()->filePutContents($this->getFullFile($key), $string);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\cache\CacheManager::isStored()
     */
    public function isStored($key)
    {
        return file_exists($this->getFullFile($key));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\cache\CacheManager::clean()
     */
    public function clean($key)
    {
        @unlink($this->getFullFile($key));
    }
        
    /**
     * @param string $key
     * @return string
     */
    public function getFullFile($key)
    {
        return App::getApp()->getCacheDir().$key;
    }
}
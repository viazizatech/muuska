<?php
namespace muuska\util;

class DefaultAutoloader
{
	/**
	 * @var string
	 */
	protected $baseNamespace;
	
	/**
	 * @var string
	 */
	protected $baseDir;
	
	/**
	 * @param string $baseNamespace
	 * @param string $baseDir
	 */
	public function __construct($baseNamespace, $baseDir)
    {
        $this->baseNamespace = $baseNamespace;
        $this->baseDir = $baseDir;
    }
	
    /**
     * @param string $baseNamespace
     * @param string $baseDir
     */
    public static function registerNew($baseNamespace, $baseDir)
    {
        $object = new DefaultAutoloader($baseNamespace, $baseDir);
        spl_autoload_register(array($object, 'autoload'));
    }
	
	/**
	 * @param string $class
	 */
	public function autoload($class)
    {
		if(strpos($class, $this->baseNamespace.'\\') === 0){
			$classRelativeFile = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
			$file = $this->baseDir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR .$classRelativeFile;
			require_once $file;
			/*if(file_exists($file)){
				require_once $file;
			}*/
		}
	}
}

<?php
namespace muuska\util\setup;

use muuska\util\App;
use muuska\constants\FolderPath;

class DefaultThemeInstaller implements Installer
{
    /**
     * @var \muuska\util\theme\Theme
     */
    protected $theme;
    
    /**
     * @param \muuska\util\theme\Theme $theme
     */
    public function __construct(\muuska\util\theme\Theme $theme) {
        $this->theme = $theme;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\setup\Installer::install()
     */
    public function install(SetupInput $input){
        App::getFileTools()->copyAssets($this->theme->getCoreDir(), $this->theme->getSubPathInApp());
        App::getFileTools()->copyDirContent($this->theme->getCoreDir().FolderPath::CONFIG.'/'.FolderPath::CONTENT_POSITIONS, App::getTools()->getThemeFinalContentPositionDir($this->theme));
    }
}
<?php
namespace muuska\util\setup;

interface Uninstaller
{
    /**
     * @param SetupInput $input
     * @return \muuska\util\NavigationResult
     */
    public function uninstall(SetupInput $input);
}
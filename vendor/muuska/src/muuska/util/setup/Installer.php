<?php
namespace muuska\util\setup;

interface Installer
{
    /**
     * @param SetupInput $input
     * @return \muuska\util\NavigationResult
     */
    public function install(SetupInput $input);
}
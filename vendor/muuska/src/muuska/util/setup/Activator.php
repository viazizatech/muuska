<?php
namespace muuska\util\setup;

interface Activator
{
    /**
     * @param SetupInput $input
     * @return \muuska\util\NavigationResult
     */
    public function activate(SetupInput $input);
}
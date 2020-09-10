<?php
namespace muuska\util\setup;

interface Deactivator
{
    /**
     * @param SetupInput $input
     * @return \muuska\util\NavigationResult
     */
    public function deactivate(SetupInput $input);
}
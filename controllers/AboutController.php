<?php


namespace app\controllers;


use muuska\app\Controller;

/**
 * Class AboutController
 *
 * @author  ViaZi Za Tech <contact@viaziza.com>
 * @package app\controllers
 */
class AboutController extends Controller
{
    public function index()
    {
        return $this->render('about');
    }
}

<?php
namespace myapp;

use muuska\project\AbstractSubApplication;
use muuska\controller\event\ControllerPageFormatingListener;

class AdminSubApplication extends AbstractSubApplication implements ControllerPageFormatingListener
{
    public function createController(\muuska\controller\ControllerInput $input) {
        $result = null;
        if ($input->checkName('association')) {
            $result = new \myapp\controller\admin\AssociationAdminController($input);
        }elseif ($input->checkName('exercice')) {
            $result = new \myapp\controller\admin\ExerciceAdminController($input);
        }elseif ($input->checkName('tontine')) {
            $result = new \myapp\controller\admin\TontineAdminController($input);
        }elseif ($input->checkName('membre')) {
            $result = new \myapp\controller\admin\MembreAdminController($input);
        }elseif ($input->checkName('poste')) {
                $result = new \myapp\controller\admin\PosteAdminController($input);
        }elseif ($input->checkName('seance')) {
            $result = new \myapp\controller\admin\SeanceAdminController($input);
        }elseif ($input->checkName('emprunt')) {
                $result = new \myapp\controller\admin\EmpruntAdminController($input);
        }elseif ($input->checkName('interet')) {
                    $result = new \myapp\controller\admin\InteretAdminController($input);
        }elseif ($input->checkName('remboursement')) {
                $result = new \myapp\controller\admin\RemboursementAdminController($input);
        }elseif ($input->checkName('epargne')) {
                $result = new \myapp\controller\admin\EpargneAdminController($input);
        }elseif ($input->checkName('fond')) {
                    $result = new \myapp\controller\admin\FondAdminController($input);
        } elseif ($input->checkName('souscrire')) {
                        $result = new \myapp\controller\admin\SouscrireAdminController($input);
         } elseif ($input->checkName('etre_membre')) {
                            $result = new \myapp\controller\admin\EtreMembreAdminController($input);
        } elseif ($input->checkName('timbre')) {
                            $result = new \myapp\controller\admin\TimbreAdminController($input);
        }elseif ($input->checkName('caisse')) {
                                $result = new \myapp\controller\admin\CaisseAdminController($input);
         } elseif ($input->checkName('sanction')) {
                                    $result = new \myapp\controller\admin\SanctionAdminController($input);
         } elseif ($input->checkName('annonce')) {
                                $result = new \myapp\controller\admin\AnnonceAdminController($input);
        };
        
        return $result;
    }
    
    public function onAppControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event){
        $event->addMainNavItem(array('title' => 'association','title' => 'annonce'));
    }
    
}
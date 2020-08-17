<?php
namespace muuska\util\event;

use muuska\util\App;

class DefaultEventTrigger implements EventTrigger
{
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireDAOEvent()
     */
    public function fireDAOEvent($code, \muuska\dao\event\DAOEvent $event) {
        $project = $event->getProject();
        $project->onDaoEvent($code, $event);
        if(!$event->isPropagationStopped()){
            $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode($code));
            foreach ($projects as $project) {
                $project->onAppDaoEvent($code, $event);
                if($event->isPropagationStopped()){
                    break;
                }
            }
        }
        return !$event->isDefaultPrevented();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireControllerActionProcessing()
     */
    public function fireControllerActionProcessing($code, \muuska\controller\event\ControllerActionProcessingEvent $event) {
        $subProject = $event->getSubProject();
        if($subProject !== null){
            $subProject->onControllerActionProcessing($code, $event);
        }
        if(!$event->isPropagationStopped()){
            $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode($code));
            foreach ($projects as $project) {
                $subProject = $project->getSubProject($event->getSubAppName());
                if($subProject !== null){
                    $subProject->onAppControllerActionProcessing($code, $event);
                }
                if($event->isPropagationStopped()){
                    break;
                }
            }
        }
        return !$event->isDefaultPrevented();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireControllerOtherEvent()
     */
    public function fireControllerOtherEvent($code, \muuska\controller\event\ControllerEvent $event) {
        $subProject = $event->getSubProject();
        if($subProject !== null){
            $subProject->onControllerOtherEvent($code, $event);
        }
        if(!$event->isPropagationStopped()){
            $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode($code));
            /** @var \muuska\project\Project $project */
            foreach ($projects as $project) {
                $subProject = $project->getSubProject($event->getSubAppName());
                if($subProject !== null){
                    $subProject->onAppControllerOtherEvent($code, $event);
                }
                if($event->isPropagationStopped()){
                    break;
                }
            }
        }
        return !$event->isDefaultPrevented();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireControllerPageFormating()
     */
    public function fireControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event) {
        if($event->hasTheme()){
            $event->getTheme()->formatControllerPage($event);
        }
        $subProject = $event->getSubProject();
        if($subProject !== null){
            $subProject->onControllerPageFormating($event);
        }
        if(!$event->isPropagationStopped()){
            $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode(null));
            /** @var \muuska\project\Project $project */
            foreach ($projects as $project) {
                $subProject = $project->getSubProject($event->getSubAppName());
                if($subProject !== null){
                    $subProject->onAppControllerPageFormating($event);
                }
                if($event->isPropagationStopped()){
                    break;
                }
            }
        }
        return !$event->isDefaultPrevented();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireRequestParsing()
     */
    public function fireRequestParsing($code, \muuska\http\event\RequestParsingEvent $event) {
        $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode($code));
        /** @var \muuska\project\Project $project */
        foreach ($projects as $project) {
            $project->onRequestParsing($code, $event);
            if($event->isPropagationStopped()){
                break;
            }
        }
        return !$event->isDefaultPrevented();
    }
 
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireRequestFinalParsing()
     */
    public function fireRequestFinalParsing($code, \muuska\http\event\RequestParsingEvent $event) {
        $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode($code));
        /** @var \muuska\project\Project $project */
        foreach ($projects as $project) {
            $project->onFinalRequestUriParsing($code, $event);
            if($event->isPropagationStopped()){
                break;
            }
        }
        return !$event->isDefaultPrevented();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireRouteLoading()
     */
    public function fireRouteLoading(\muuska\http\event\RouteLoadingEvent $event) {
        $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode());
        /** @var \muuska\project\Project $project */
        foreach ($projects as $project) {
            $project->onRouteLoading($event);
            if($event->isPropagationStopped()){
                break;
            }
        }
        return !$event->isDefaultPrevented();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireUrlCreation()
     */
    public function fireUrlCreation(\muuska\url\event\UrlCreationEvent $event) {
        $subProject = $event->getSubProject();
        if($subProject !== null){
            $subProject->onUrlCreation($event);
        }
        if(!$event->isPropagationStopped()){
            $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode());
            foreach ($projects as $project) {
                $subProject = $project->getSubProject($event->getSubAppName());
                if($subProject !== null){
                    $subProject->onAppUrlCreation($event);
                }
                if($event->isPropagationStopped()){
                    break;
                }
            }
        }
        return !$event->isDefaultPrevented();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireOtherEvent()
     */
    public function fireOtherEvent($code, \muuska\util\event\EventObject $event) {
        $projects = App::getApp()->getProjectsForEvent($code);
        foreach ($projects as $project) {
            $project->onOtherEvent($event);
            if($event->isPropagationStopped()){
                break;
            }
        }
        return !$event->isDefaultPrevented();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireMailPageFormating()
     */
    public function fireMailPageFormating(\muuska\mail\event\MailPageFormatingEvent $event){
        if($event->hasTheme()){
            $event->getTheme()->formatMailPage($event);
        }
        $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode());
        foreach ($projects as $project) {
            $project->onMailPageFormating($event);
            if($event->isPropagationStopped()){
                break;
            }
        }
        return !$event->isDefaultPrevented();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\event\EventTrigger::fireAppInitialization()
     */
    public function fireAppInitialization(AppInitializationEvent $event)
    {
        $projects = App::getApp()->getProjectsForEvent($event->getFinalEventCode());
        foreach ($projects as $project) {
            $project->onAppInitialization($event);
            if($event->isPropagationStopped()){
                break;
            }
        }
        return !$event->isDefaultPrevented();
    }
}

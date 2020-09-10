<?php
namespace muuska\http\constants;

class RedirectionType{
	const DEFAULT_ACTION = 'default_action';
	const SAME_ACTION = 'same_action';
	const INNER_ACTION = 'inner_action';
	const OTHER_CONTROLLER = 'other_controller';
	const BACK_TO_CALLER = 'back_to_caller';
	const LOGIN = 'login';
	const HOME = 'home';
	const CUSTOM = 'custom';
}
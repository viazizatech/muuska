<?php
namespace muuska\constants\operator;

abstract class Operator{
	const EQUALS = 1;
	const DIFFERENT = 2;
	const CONTAINS = 3;
	const NOT_CONTAINS = 4;
	const START_WITH = 5;
	const NOT_START_WITH = 6;
	const END_WITH = 7;
	const NOT_END_WITH = 8;
	const BETWEEN = 9;
	const NOT_BETWEEN = 10;
	const IN_LIST = 11;
	const NOT_IN_LIST = 12;
}
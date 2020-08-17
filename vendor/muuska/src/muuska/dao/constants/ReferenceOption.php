<?php

namespace muuska\dao\constants;

abstract class ReferenceOption{
    const RESTRICT = 1;
    const CASCADE = 2;
    const SET_NULL = 3;
    const NO_ACTION = 4;
    const SET_DEFAULT = 5;
}
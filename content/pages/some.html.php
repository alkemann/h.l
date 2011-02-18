<?php
\util\Debug::$options['depth'] = 2;
d(
    array(4,5,6),
    array(array(array(3=>3))),
    array('one' => 1, 'two' => 2, 'three' => array('3')),
    array(
        'level 1' => array(
            'level 2' => array(
                'level 3' => array(
                    'level 4' => array(
                        'level 5' => array (1,2,3)
                    )
                )
            ),
            'sibling to level 2'
        ),
        'sibling to level 1'
    )
);

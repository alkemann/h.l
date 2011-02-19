<?php
\util\Debug::$options['depth'] = 2;
\util\Debug::$options['avoid'][] = 'array';
\util\Debug::$options['avoid'][] = 'object';
d(
    true,
    123,
    new stdClass(),
    \util\Debug::get_instance(),
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

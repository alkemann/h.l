<?php
    $name = 'Alek';
    $nr = 3;
    $plu = 'Brother Love!';
    
    \l10n\Message::locale('no_NB');
?>
<div>
    <h1><?php __('Welcome')?></h1>
    <p><?php __('About the time we can make the ends meet, somebody moves the ends.')?></p>
    <p><?php __('You wun, {:name}!', array('name' => $name))?></p>
    <p><?php __n('Gratz on {:wins} win, {:name}. {:plu}!', 'Gratz on {:wins} wins, {:name}. {:plu}!', 
            $nr, array('name' => $name, 'plu' => $plu, 'wins' => $nr))?></p>
    <p><?php 
        __n(
                'Gratz on {:wins} win, {:name}. {:plu}!',
                'Gratz on {:wins} wins, {:name}. {:plu}!', 
                1, 
                array(
                    'name' => $name,
                    'plu' => $plu,
                    'wins' => 1
                )
            )
    ?></p>
</div>
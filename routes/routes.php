<?php
// Convention  \routes + http path + \routes.php
return array(
    function($router) {
        $router->addGet('index.browse', '/')
            ->addValues([
                'format' => '.html',
                // Idea decide on a convention and auto guess the location of provider based on that
                'provider' => 'Websoftwares\\Application\\Index\\Domain\\IndexProvider'
        ]);
    }
);
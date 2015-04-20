<?php

// Convention  \routes + http path + \routes.php
return array(
    function ($router) {
        $router->addGet('Websoftwares\Application\Logout\Action\LogoutGetAction', '/logout')
            ->addValues([
                'format' => '.html',
                // Idea decide on a convention and auto guess the location of provider based on that
                // 'provider' => 'Websoftwares\\Application\\Logout\\LogoutProvider',
        ]);
    }
);

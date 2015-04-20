<?php

// Convention  \routes + http path + \routes.php
return array(
    function ($router) {
        $router->addGet('registration.get.form', '/registration')
            ->addValues([
                'format' => '.html',
                // Idea decide on a convention and auto guess the location of provider based on that
                'provider' => 'Websoftwares\\Application\\Registration\\RegistrationProvider',
        ]);
    },
    function ($router) {
        $router->addPost('registration.post.form', '/registration')
            ->addValues([
                'format' => '.html',
                // Idea decide on a convention and auto guess the location of provider based on that
                'provider' => 'Websoftwares\\Application\\Registration\\RegistrationProvider',
        ]);
    },
);

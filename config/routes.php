<?php
return array(
	// Index
    function ($router) {
        $router->get('index.browse.get', '/')
            ->defaults([
                'format' => '.html',
        	])
        	->extras([
        		'provider' => 'Websoftwares\\Application\\Index\\IndexProvider',
        		'middleware' => [
    				function($request, $response) {
    					// awesome sauce
					}
    			]
        	]);
    },
    function ($router) {
        $router->post('index.browse.post', '/')
            ->defaults([
                'format' => '.html',
            ])
            ->extras([
        		'provider' => 'Websoftwares\\Application\\Index\\IndexProvider',
        	]);
    },
   	// Logout
    function ($router) {
        $router->get('Websoftwares\Application\Logout\Action\LogoutGetAction', '/logout')
            ->defaults([
                'format' => '.html',
        ]);
    },
    // Registration
    function ($router) {
        $router->get('registration.get.form', '/registration')
            ->defaults([
                'format' => '.html',
        ])->extras([
                'provider' => 'Websoftwares\\Application\\Registration\\RegistrationProvider',
            ]);
    },
    function ($router) {
        $router->post('registration.post.form', '/registration')
            ->defaults([
                'format' => '.html',
        ])->extras([
                'provider' => 'Websoftwares\\Application\\Registration\\RegistrationProvider',
            ]);
    },
	// Activation
    function ($router) {
        $router->get('activation.get', '/activation/{token}')->defaults([
	    	'format' => '.html',
        ])->extras([
            'provider' => 'Websoftwares\\Application\\Activation\\ActivationProvider',
        ]);
    }
);
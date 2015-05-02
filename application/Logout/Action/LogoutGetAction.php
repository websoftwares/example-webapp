<?php

namespace Websoftwares\Application\Logout\Action;

/**
 * LogoutGetAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class LogoutGetAction
{
    /**
     * __invoke.
     *
     *
     * @return string
     */
    public function __invoke()
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit();
    }
}

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
     * @param array $params
     *
     * @return string
     */
    public function __invoke(array $params = [])
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit();
    }
}

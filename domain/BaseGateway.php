<?php

namespace Websoftwares\Domain;

/**
 * BaseGateway.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class BaseGateway
{
    /**
     * buildUpdateQuery.
     *
     * Takes the object properties and creates the query string
     * values and returns array with params and string
     *
     * @param string $table
     * @param string $where
     *
     * @return array
     */
    protected function buildUpdateQuery(\IteratorAggregate $data, $table = '', $where = '')
    {
        $query = 'UPDATE '.$table.' SET';
        $values = array();

        $data = (array) $data;

        foreach (array_filter($data, 'strlen') as $name => $value) {
            $query .= ' '.$name.' = :'.$name.','; // the :$name part is the placeholder, e.g. :zip
            $values[':'.$name] = $value; // save the placeholder
        }
        if ($where) {
            $query = substr($query, 0, -1).'';
            $query .= $where;
        } else {
            $query = substr($query, 0, -1).';'; // remove last , and add a ;
        }

        return array('values' => $values, 'query' => $query);
    }
}

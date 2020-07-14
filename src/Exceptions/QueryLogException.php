<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dipenparmar12\QueryLog\Exceptions;

use Exception;

class QueryLogException extends Exception
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'Dipenparmar12/QueryLog:, An error occurred';
}

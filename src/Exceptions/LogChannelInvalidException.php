<?php

/*
 * This file is part of Dipen Parmar.
 *
 * (c) Dipen Parmar <dipenparmar12@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dipenparmar12\QueryLog\Exceptions;

class LogChannelInvalidException extends QueryLogException
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'In given channels one or more channel is not defined.';
}

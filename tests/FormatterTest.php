<?php

/*
 * This file is part of the Mailer package.
 *
 * (c) Anekdotes Communication inc. <info@anekdotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Anekdotes\Formatter\Formatter;
use PHPUnit_Framework_TestCase;

class FormatterTest extends PHPUnit_Framework_TestCase
{
    //Tests the instantion of the formatter
    public function testInstantiateFormatter()
    {
        $stub = $this->createMock(MailerAdapter::class);
        $mailer = new Mailer($stub);
        $this->assertInstanceOf(Mailer::class, $mailer);
    }
}

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
    //Tests the make method of the formatter
    public function testFormatterMake1()
    {
        $formatter = Formatter::make([], []);
        $this->assertFalse($formatter);
    }

    //Tests the make method of the formatter
    public function testFormatterMake2()
    {
        $formatter = Formatter::make('foo', 'bar');
        $this->assertFalse($formatter);
    }

    //Tests the make method of the formatter
    public function testFormatterMake3()
    {
        $formatter = Formatter::make('foo', 'bar');
        $this->assertFalse($formatter);
    }

    //Tests the phoneNumber of format method of the formatter
    public function testFormatterFormatPhone1()
    {
        $values = [
          'foo' => 'bar',
        ];
        $rules = [
          'foo' => ['phoneNumber'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '',
        ]);
    }

    //Tests the phoneNumber of format method of the formatter
    public function testFormatterFormatPhone2()
    {
        $values = [
          'foo' => '450',
        ];
        $rules = [
          'foo' => ['phoneNumber'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '450',
        ]);
    }

    //Tests the phoneNumber of format method of the formatter
    public function testFormatterFormatPhone3()
    {
        $values = [
          'foo' => '4504441',
        ];
        $rules = [
          'foo' => ['phoneNumber'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '450-4441',
        ]);
    }

    //Tests the phoneNumber of format method of the formatter
    public function testFormatterFormatPhone4()
    {
        $values = [
          'foo' => '4504441919',
        ];
        $rules = [
          'foo' => ['phoneNumber'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '(450) 444-1919',
        ]);
    }

    //Tests the phoneNumber of format method of the formatter
    public function testFormatterFormatPhone5()
    {
        $values = [
          'foo' => '14504441919',
        ];
        $rules = [
          'foo' => ['phoneNumber'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '1 (450) 444-1919',
        ]);
    }

    //Tests the phoneNumber of format method of the formatter
    public function testFormatterFormatPhone6()
    {
        $values = [
          'foo' => '145044419192',
        ];
        $rules = [
          'foo' => ['phoneNumber'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '145 (044) 419-192',
        ]);
    }

    //Tests the postalCode of format method of the formatter
    public function testFormatterFormatPostal1()
    {
        $values = [
          'foo' => 'j4y2b4',
        ];
        $rules = [
          'foo' => ['postalCode'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => 'J4Y 2B4',
        ]);
    }

    //Tests the postalCode of format method of the formatter
    public function testFormatterFormatPostal2()
    {
        $values = [
          'foo' => 'j4y',
        ];
        $rules = [
          'foo' => ['postalCode'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => 'J4Y',
        ]);
    }

    //Tests the postalCode of format method of the formatter
    public function testFormatterFormatPostal3()
    {
        $values = [
          'foo' => 'j4y2b4424',
        ];
        $rules = [
          'foo' => ['postalCode'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => 'J4Y 2B4',
        ]);
    }

    //Tests the float of format method of the formatter
    public function testFormatterFormatFloat1()
    {
        $values = [
          'foo' => '122.2ABD',
        ];
        $rules = [
          'foo' => ['float'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '122.2',
        ]);
    }

    //Tests the float of format method of the formatter
    public function testFormatterFormatFloat2()
    {
        $values = [
          'foo' => 'a122.2a',
        ];
        $rules = [
          'foo' => ['float'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '0.0',
        ]);
    }

    //Tests the float of format method of the formatter
    public function testFormatterFormatFloat3()
    {
        $values = [
          'foo' => '123',
        ];
        $rules = [
          'foo' => ['float'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '123',
        ]);
    }

    //Tests the int of format method of the formatter
    public function testFormatterFormatInt1()
    {
        $values = [
          'foo' => '123',
        ];
        $rules = [
          'foo' => ['int'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '123',
        ]);
    }

    //Tests the int of format method of the formatter
    public function testFormatterFormatInt2()
    {
        $values = [
          'foo' => '123a',
        ];
        $rules = [
          'foo' => ['int'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '123',
        ]);
    }

    //Tests the int of format method of the formatter
    public function testFormatterFormatInt3()
    {
        $values = [
          'foo' => '123.12a',
        ];
        $rules = [
          'foo' => ['int'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '123',
        ]);
    }

    //Tests the integer of format method of the formatter
    public function testFormatterFormatInteger1()
    {
        $values = [
          'foo' => '123.12a',
        ];
        $rules = [
          'foo' => ['integer'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '123',
        ]);
    }

    //Tests the datetime of format method of the formatter
    public function testFormatterFormatDate1()
    {
        $values = [
          'foo' => '1231',
        ];
        $rules = [
          'foo' => ['datetime'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '1231',
        ]);
    }

    //Tests the datetime of format method of the formatter
    public function testFormatterFormatDate2()
    {
        $values = [
          'foo' => '',
        ];
        $rules = [
          'foo' => ['datetime'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => null,
        ]);
    }

    //Tests the datetime of format method of the formatter
    public function testFormatterFormatRandom1()
    {
        $values = [
          'foo' => '1a',
        ];
        $rules = [
          'foo' => ['int:1,2'],
        ];
        $newValues = Formatter::make($values, $rules);
        $this->assertEquals($newValues, [
          'foo' => '1',
        ]);
    }
}

<?php
/**
 * Phavour PHP Framework Library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       1.0.0
 * @package     Phavour
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Phavour\Tests\Cache;

use Phavour\Cache\AdapterNull;

/**
 * @author Roger Thomas
 * AdapterNullTest
 */
class AdapterNullTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AdapterNull
     */
    private $adapter = null;

    public function setUp()
    {
        $this->adapter = new AdapterNull();
    }

    public function testAllReturnFalse()
    {
        $this->assertFalse($this->adapter->flush());
        $this->assertFalse($this->adapter->set('abc', '123', 86400));
        $this->assertFalse($this->adapter->renew('abc', 86400));
        $this->assertFalse($this->adapter->get('abc'));
        $this->assertFalse($this->adapter->has('abc'));
        $this->assertFalse($this->adapter->remove('abc'));
    }
}

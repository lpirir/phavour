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
namespace Phavour\Tests;

use Phavour\Application;
use Phavour\Cache\AdapterNull;

/**
 * @author Roger Thomas
 * ApplicationTest
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $app = null;

    public function setUp()
    {
        $this->app = new Application(APP_BASE);
        $this->app->setCacheAdapter(new AdapterNull());
    }

    public function testCantUseCacheFirst()
    {
        @ob_start();
        $app = new Application(APP_BASE);
        $app->setup();
        $app->setCacheAdapter(new AdapterNull());
        $content = @ob_get_clean();
        $this->assertContains('500: Unexpected Error', $content);
    }

    public function testSetup()
    {
        $this->app->setup();
    }

    public function testInvalidRoute()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_URI'] = '/this/isnt/a/valid/path/ever';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        @ob_start();
        $result = $this->app->run();
        $content = @ob_get_clean();
        $this->assertContains('404: Page Not Found', $content);
    }

    public function testValidRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        @ob_start();
        $result = $this->app->run();
        $content = @ob_get_clean();
        $this->assertContains('Welcome to Phavour', $content);
    }
}

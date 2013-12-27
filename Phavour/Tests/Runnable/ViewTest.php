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
namespace Phavour\Tests\Runnable;

use Phavour\Runnable\View;
use Phavour\Http\Response;
use Phavour\Router;

/**
 * @author Roger Thomas
 * ViewTest
 */
class ViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var View
     */
    private $view = null;

    public function setUp()
    {
        $this->view = new View('DefaultPackage', 'Index', 'index');
    }

    public function testPackageName()
    {
        $this->view->setPackage('None');
        $this->assertEquals('NonePackage', $this->view->getPackage());
        $this->view->setPackage('NonePackage');
        $this->assertEquals('NonePackage', $this->view->getPackage());
    }

    public function testClassName()
    {
        $this->assertEquals('Index', $this->view->getClass());
        $this->view->setClass('Abc123');
        $this->assertEquals('Abc123', $this->view->getClass());
    }

    public function testMethodName()
    {
        $this->assertEquals('index', $this->view->getMethod());
        $this->view->setScriptName('noSuchMethod');
        $this->assertEquals('noSuchMethod', $this->view->getMethod());
        $this->assertEquals('noSuchMethod', $this->view->getScriptName());
        $this->view->setMethod('noSuchMethodAlt');
        $this->assertEquals('noSuchMethodAlt', $this->view->getMethod());
        $this->assertEquals('noSuchMethodAlt', $this->view->getScriptName());
    }

    public function testMagicMethods()
    {
        $this->view->abc = '123';
        $this->assertEquals('123', $this->view->abc);
        $this->assertNull($this->view->noSuchVariable);
        $this->view->set('name', 'joe');
        $this->assertEquals('joe', $this->view->get('name'));
    }

    public function testGetSetMethods()
    {
        $this->view->set('name', 'joe');
        $this->assertEquals('joe', $this->view->get('name'));
        $this->assertNull($this->view->get('age'));
    }

    public function testApplicationPath()
    {
        $this->view->setApplicationPath('/example/path');
        $this->assertEquals('/example/path', $this->view->getApplicationPath());
    }

    public function testEnableDisabled()
    {
        $this->view->enableView();
        $this->assertTrue($this->view->isEnabled());
        $this->view->disableView();
        $this->assertFalse($this->view->isEnabled());
    }

    public function testRender()
    {
        $this->view = new View('TestPackage', 'UserTest', 'name');
        $this->view->disableView();
        $this->assertTrue($this->view->render());
    }

    public function testRenderContains()
    {
        $this->view->setResponse(new Response());
        $this->view->setApplicationPath(APP_BASE);
        $this->view->data = 'joe';
        @ob_start();
        $this->view->render('index', 'Index', 'DefaultPackage');
        $result = @ob_get_clean();
        $this->assertContains('joe', $result);
    }

    public function testLayoutContains()
    {
        $this->view->setResponse(new Response());
        $this->view->setApplicationPath(APP_BASE);
        $this->view->data = '123';
        @ob_start();
        $this->view->setLayout('DefaultPackage::default');
        $this->view->render('index', 'Index', 'DefaultPackage');
        $result = @ob_get_clean();
        $this->assertContains('123', $result);
    }

    public function testLayoutNoPackage()
    {
        $this->view->setResponse(new Response());
        $this->view->setApplicationPath(APP_BASE);
        $this->view->data = 'abc';
        @ob_start();
        $this->view->setLayout('default');
        $this->view->render('index', 'Index', 'DefaultPackage');
        $result = @ob_get_clean();
        $this->assertContains('abc', $result);
    }

    public function testRenderInvalid()
    {
        $this->view->setResponse(new Response());
        $this->view->setApplicationPath(APP_BASE);
        $this->view->name = 'abc';
        try {
            $this->view->render('namae', 'UserTest', 'TestPackage');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Phavour\Runnable\View\Exception\ViewFileNotFoundException', $e);
            $this->assertContains('Invalid view file', $e->getMessage());
            return;
        }
        $this->fail('expected exception');
    }

    public function testRenderInvalidLayout()
    {
        $this->view->setResponse(new Response());
        $this->view->setApplicationPath(APP_BASE);
        $this->view->name = 'abc';
        try {
            $this->view->setLayout('nosuchlayout');
            $this->view->render('namae', 'UserTest', 'TestPackage');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Phavour\Runnable\View\Exception\LayoutFileNotFoundException', $e);
            $this->assertContains('Invalid layout file path', $e->getMessage());
            return;
        }
        $this->fail('expected exception');
    }

    public function testGetUrl()
    {
        $this->assertEmpty($this->view->urlFor('abc.123'));
        $this->view->setRouter(new Router());
        $this->assertEquals('abc.123', $this->view->urlFor('abc.123'));
    }
}

<?php

namespace Lighthouse\CoreBundle\Tests\Samba;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Samba\Samba as SambaBase;

class Samba extends ContainerAwareTestCase
{
    /**
     * @param $url
     * @param $expectedParsedUrl
     *
     * @dataProvider parserUrlProvider
     */
    public function testParseUrlMethod($url, $expectedParsedUrl)
    {
        $expectedParsedUrl = $expectedParsedUrl + array(
            'type' => 'path',
            'path' => 'to\dir',
            'host' => 'host',
            'user' => 'user',
            'pass' => 'password',
            'domain' => '',
            'share' => 'base_path',
            'port' => 139,
            'scheme' => 'smb',
        );

        $samba = new SambaBase();

        $parsedUrl = $samba->parseUrl($url);

        $this->assertEquals($expectedParsedUrl, $parsedUrl);
    }

    public function parserUrlProvider()
    {
        return array(
            'full base url' => array(
                "smb://user:password@host/base_path/to/dir",
                array(),
            ),
            'full base url with file' => array(
                "smb://user:password@host/base_path/to/dir/file.doc",
                array(
                    'path' => 'to\dir\file.doc',
                ),
            ),
            'base url without password' => array(
                "smb://user@host/base_path/to/dir",
                array(
                    'pass' => '',
                ),
            ),
            'base url without user and password' => array(
                "smb://host/base_path/to/dir",
                array(
                    'user' => '',
                    'pass' => '',
                ),
            ),
            'base url with port' => array(
                "smb://user:password@host:222/base_path/to/dir",
                array(
                    'port' => '222',
                ),
            ),
            'base url with port and domain' => array(
                "smb://domain.local;user:password@host:222/base_path/to/dir",
                array(
                    'port' => '222',
                    'domain' => 'domain.local',
                ),
            ),
            'base url without path' => array(
                "smb://user:password@host/base_path",
                array(
                    'path' => '',
                    'type' => 'share',
                ),
            ),
            'url without share' => array(
                "smb://user:password@host",
                array(
                    'path' => '',
                    'share' => '',
                    'type' => 'host',
                ),
            ),
            'base url empty' => array(
                "",
                array(
                    'user' => '',
                    'pass' => '',
                    'domain' => '',
                    'host' => '',
                    'share' => '',
                    'path' => '',
                    'type' => '**error**',
                    'scheme' => '',
                ),
            ),
        );
    }

    public function testLookMethod()
    {
        $url = "smb://user:password@host/base_path/to/dir/file.doc";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('client'));

        $parsedUrl = $sambaMock->parseUrl($url);

        $sambaMock
            ->expects($this->once())
            ->method('client')
            ->with($this->equalTo("-L 'host'"), $this->equalTo($parsedUrl));

        $sambaMock->look($parsedUrl);
    }

    public function testExecuteMethod()
    {
        $url = "smb://user:password@host/base_path/to/dir/file.doc";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('client'));

        $parsedUrl = $sambaMock->parseUrl($url);

        $expectedClientParams = "-d 0 '//host/base_path' -c 'test_command'";

        $sambaMock
            ->expects($this->once())
            ->method('client')
            ->with($this->equalTo($expectedClientParams), $this->equalTo($parsedUrl));

        $sambaMock->execute('test_command', $parsedUrl);
    }

    public function testUnlinkMethod()
    {
        $url = "smb://user:password@host/base_path/to/dir/file.doc";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('execute'));
        $parsedUrl = $sambaMock->parseUrl($url);

        $expectedExecuteCommand = 'del "to\dir\file.doc"';

        $sambaMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedExecuteCommand), $this->equalTo($parsedUrl));

        $sambaMock->unlink($url);
    }

    public function testRenameMethod()
    {
        $url = "smb://user:password@host/base_path/to/dir/file.doc";
        $urlNew = "smb://user:password@host/base_path/to/dir/file_new.doc";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('execute'));
        $parsedUrl = $sambaMock->parseUrl($url);
        $parsedUrlNew = $sambaMock->parseUrl($urlNew);

        $expectedExecuteCommand = 'rename "to\dir\file.doc" "to\dir\file_new.doc"';

        $sambaMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedExecuteCommand), $this->equalTo($parsedUrlNew));

        $sambaMock->rename($url, $urlNew);
    }

    public function testMkDirMethod()
    {
        $url = "smb://user:password@host/base_path/to/dir";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('execute'));
        $parsedUrl = $sambaMock->parseUrl($url);

        $expectedExecuteCommand = 'mkdir "to\dir"';

        $sambaMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedExecuteCommand), $this->equalTo($parsedUrl));

        $sambaMock->mkdir($url, '', '');
    }

    public function testRmDirMethod()
    {
        $url = "smb://user:password@host/base_path/to/dir";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('execute'));
        $parsedUrl = $sambaMock->parseUrl($url);

        $expectedExecuteCommand = 'rmdir "to\dir"';

        $sambaMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedExecuteCommand), $this->equalTo($parsedUrl));

        $sambaMock->rmdir($url);
    }

    public function testStatCacheMethods()
    {
        $urlFile = "smb://user:password@host/base_path/to/dir/file.doc";
        $urlDir = "smb://user:password@host/base_path/to/dir";

        $sambaMock = $this->getMock('\Lighthouse\CoreBundle\Samba\Samba', array('execute'));

        $this->assertFalse($sambaMock->getstatcache($urlFile));
        $this->assertFalse($sambaMock->getstatcache($urlDir));

        $infoFile = array(
            'attr' => 'F',
            'size' => 4,
            'time' => 777,
        );
        $statFile = stat("/etc/passwd");
        $statFile[7] = $statFile['size'] = $infoFile['size'];
        $statFile[8]
            = $statFile[9]
            = $statFile[10]
            = $statFile['atime']
            = $statFile['mtime']
            = $statFile['ctime']
            = $infoFile['time'];

        $infoDir = $infoFile;
        $infoDir['attr'] = 'D';
        $statDir = stat("/tmp");
        $statDir[7] = $statDir['size'] = $infoDir['size'];
        $statDir[8]
            = $statDir[9]
            = $statDir[10]
            = $statDir['atime']
            = $statDir['mtime']
            = $statDir['ctime']
            = $infoDir['time'];

        $this->assertEquals($statFile, $sambaMock->addstatcache($urlFile, $infoFile));
        $this->assertEquals($statDir, $sambaMock->addstatcache($urlDir, $infoDir));

        $this->assertEquals($statFile, $sambaMock->getstatcache($urlFile));
        $this->assertEquals($statDir, $sambaMock->getstatcache($urlDir));

        $sambaMock->clearstatcache($urlFile);

        $this->assertFalse($sambaMock->getstatcache($urlFile));
        $this->assertEquals($statDir, $sambaMock->getstatcache($urlDir));

        $this->assertEquals($statFile, $sambaMock->addstatcache($urlFile, $infoFile));
        $sambaMock->clearstatcache();
        $this->assertFalse($sambaMock->getstatcache($urlFile));
        $this->assertFalse($sambaMock->getstatcache($urlDir));
    }

    public function testUrlStatMethod()
    {

    }
}

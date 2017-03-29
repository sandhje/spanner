<?php
namespace Proxy;

use OpenSourcerers\Spanner\Proxy\FilesystemProxy;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 *
 * @author Sandhje
 *        
 */
class FilesystemProxyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('foo'));
    }
    
    public function testPositiveIsDir()
    {
        // Arrange
        vfsStream::create(array("bar" => array()));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->isDir("vfs://foo/bar");        
        
        // Assert
        $this->assertTrue($result);
    }
    
    public function testNegativeIsDir()
    {
        // Arrange
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->isDir("vfs://foo/bar");
    
        // Assert
        $this->assertFalse($result);
    }
    
    public function testPositiveIsFile()
    {
        // Arrange
        vfsStream::create(array("bar.php" => "foobar"));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->isFile("vfs://foo/bar.php");        
        
        // Assert
        $this->assertTrue($result);
    }
    
    public function testNagetiveIsFile()
    {
        // Arrange
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->isFile("vfs://foo/bar.php");
    
        // Assert
        $this->assertFalse($result);
    }
    
    public function testPositiveIsReadable()
    {
        // Arrange
        vfsStream::create(array("bar.php" => "foobar"));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->isReadable("vfs://foo/bar.php");        
        
        // Assert
        $this->assertTrue($result);
    }
    
    public function testNegativeIsReadable()
    {
        // Arrange
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->isReadable("vfs://foo/bar.php");
    
        // Assert
        $this->assertFalse($result);
    }
    
    public function testLoadPhpFile()
    {
        // Arrange
        $barContent = "<?php return array('a' => 'b') ?>";
        vfsStream::create(array("bar.php" => $barContent));
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->load("vfs://foo/bar.php");
    
        // Assert
        $this->assertEquals(array('a' => 'b'), $result);
    }
    
    public function testLoadTextFile()
    {
        // Arrange
        $barContent = "foobar";
        vfsStream::create(array("bar.txt" => $barContent));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->load("vfs://foo/bar.txt");        
        
        // Assert
        $this->assertEquals($barContent, $result);
    }
    
    public function testPathinfo()
    {
        // Arrange
        $barContent = "foobar";
        vfsStream::create(array("bar.txt" => $barContent));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->pathinfo("vfs://foo/bar.txt");
        
        // Assert
        $this->assertEquals(array(
            "dirname" => "vfs://foo",
            "basename" => "bar.txt",
            "extension" => "txt",
            "filename" => "bar"
        ), $result);
    }
}

?>
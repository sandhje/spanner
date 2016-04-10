<?php
namespace Proxy;

use Sandhje\Spanner\Proxy\FilesystemProxy;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

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
    
    public function testPositiveIs_dir()
    {
        // Arrange
        vfsStream::create(array("bar" => array()));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->is_dir("vfs://foo/bar");        
        
        // Assert
        $this->assertTrue($result);
    }
    
    public function testNegativeIs_dir()
    {
        // Arrange
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->is_dir("vfs://foo/bar");
    
        // Assert
        $this->assertFalse($result);
    }
    
    public function testPositiveIs_file()
    {
        // Arrange
        vfsStream::create(array("bar.php" => "foobar"));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->is_file("vfs://foo/bar.php");        
        
        // Assert
        $this->assertTrue($result);
    }
    
    public function testNagetiveIs_file()
    {
        // Arrange
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->is_file("vfs://foo/bar.php");
    
        // Assert
        $this->assertFalse($result);
    }
    
    public function testPositiveIs_readable()
    {
        // Arrange
        vfsStream::create(array("bar.php" => "foobar"));
        
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->is_readable("vfs://foo/bar.php");        
        
        // Assert
        $this->assertTrue($result);
    }
    
    public function testNegativeIs_readable()
    {
        // Arrange
        $barFile = new vfsStreamFile("bar.php");
        $barFile->chmod(111);
        $root = vfsStreamWrapper::getRoot();
        $root->addChild($barFile);
    
        // Act
        $filesystemProxy = new FilesystemProxy();
        $result = $filesystemProxy->is_readable("vfs://foo/bar.php");
    
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
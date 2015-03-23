<?php

namespace MobileDetectTest\UnitTests;

use MobileDetect\Device;
use MobileDetect\MobileDetect;
use MobileDetect\Type;

class DeviceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \MobileDetect\Exception\InvalidDeviceSpecificationException
     * @expectedExceptionMessage The 'type' property is required.
     */
    public function testEmptyFactory()
    {
        Device::create();
    }

    public function testFactorySetCorrectly()
    {
        $device = Device::create(array(
            'type'            => $type = Type::DESKTOP,
            'user_agent'      => $ua = 'Blah',
            'model'           => $model = 'Samsung Galaxy',
            'model_version'   => $modelVer = 'S4',
            'os'              => $os = 'Android',
            'os_version'      => $osVer = '3.5',
            'browser'         => $browser = 'Chrome',
            'browser_version' => $browserVer = '31.5.1245',
            'vendor'          => $vendor = 'Samsung',
        ));

        // make sure everything was set correctly
        $this->assertSame($type, $device->getType());
        $this->assertSame($ua, $device->getUserAgent());
        $this->assertSame($model, $device->getModel());
        $this->assertSame($modelVer, $device->getModelVersion());
        $this->assertSame($os, $device->getOperatingSystem());
        $this->assertSame($osVer, $device->getOperatingSystemVersion());
        $this->assertSame($browser, $device->getBrowser());
        $this->assertSame($browserVer, $device->getBrowserVersion());
        $this->assertSame($vendor, $device->getVendor());

        //check the bool methods
        $this->assertTrue($device->isDesktop());
        $this->assertFalse($device->isMobile());
        $this->assertFalse($device->isTablet());
        $this->assertFalse($device->isBot());

        //check the toArray method
        $expectedArr = array(
            'isMobile'               => $device->isMobile(),
            'isTablet'               => $device->isTablet(),
            'isDesktop'              => $device->isDesktop(),
            'isBot'                  => $device->isBot(),
            'browser'                => $device->getBrowser(),
            'browserVersion'         => $device->getBrowserVersion(),
            'model'                  => $device->getModel(),
            'modelVersion'           => $device->getModelVersion(),
            'operatingSystem'        => $device->getOperatingSystem(),
            'operatingSystemVersion' => $device->getOperatingSystemVersion(),
            'userAgent'              => $device->getUserAgent(),
            'vendor'                 => $device->getVendor(),
        );
        $actualArr = $device->toArray();
        $this->assertSame($expectedArr, $actualArr);
    }

    public function testUserAgentIsPassedForPropertyVersions()
    {
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.363 '.
            '(KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36';
        $md = new MobileDetect($ua);
        $device = $md->detect();

        $this->assertSame('537.363', $device->getVersion('WebKit'));
        $this->assertSame('537.36', $device->getVersion('Safari'));
    }
}
<?php

namespace App\Helpers;

use Nette\Http\Request;
use Nette\Http\UrlScript;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneralHelperTest
 * @package App\Helpers
 * @covers \App\Helpers\GeneralHelper
 */
class GeneralHelperTest extends TestCase
{

    /**
     * @covers \App\Helpers\GeneralHelper::checkPortRange
     */
    public function testCheckPortRange(): void
    {
        $this->assertTrue(GeneralHelper::checkPortRange('4000-8000'));
        $this->assertFalse(GeneralHelper::checkPortRange('65536'));
        $this->assertTrue(GeneralHelper::checkPortRange('2564'));
        $this->assertFalse(GeneralHelper::checkPortRange('0-65536'));
        $this->assertFalse(GeneralHelper::checkPortRange('-1-65534'));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::stripQuotes
     */
    public function testStripQuotes(): void
    {
        $email = 'tester@goodaccess.com';
        $this->assertSame($email, GeneralHelper::stripQuotes('"'.$email.'"'));
        $this->assertSame($email, GeneralHelper::stripQuotes("'".$email."'"));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::extractDomain
     */
    public function testExtractDomain(): void
    {
        $this->assertSame('goodaccess.com', GeneralHelper::extractDomain('tester@goodaccess.com'));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::calculateUserIP
     */
    public function testCalculateUserIP(): void
    {
        $this->assertSame('172.24.28.35', GeneralHelper::calculateUserIP('172.24.28.0/22', '3', '32'));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::findPartnerProgramInRequest
     */
    public function testFindPartnerProgramInRequest(): void
    {
        $request = new Request(new UrlScript('https://app.goodaccess.com/order/'));
        $request2 = new Request(new UrlScript('https://partner.goodaccess.com/msp/?partnerProgram=998gf542-eb27-4df88-a5cf-0f476773799a'));

        $this->assertNull(GeneralHelper::findPartnerProgramInRequest($request));
        $this->assertSame('998gf542-eb27-4df88-a5cf-0f476773799a', GeneralHelper::findPartnerProgramInRequest($request2));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::findOrganizationInRequest
     */
    public function testFindOrganizationInRequest(): void
    {
        $request = new Request(new UrlScript('https://app.goodaccess.com/order/'));
        $request2 = new Request(new UrlScript('https://app.goodaccess.com/?organizationID=998gf542-eb27-4df88-a5cf-0f476773799a'));
        $request3 = new Request(new UrlScript('https://app.goodaccess.com/?organizationUuid=998gf542-eb27-4df88-a5cf-0f476773799a'));
        $request4 = new Request(new UrlScript('https://app.goodaccess.com/'), ['organizationID' => '998gf542-eb27-4df88-a5cf-0f476773799a']);

        $this->assertFalse(GeneralHelper::findOrganizationInRequest($request));
        $this->assertSame('998gf542-eb27-4df88-a5cf-0f476773799a', GeneralHelper::findOrganizationInRequest($request2));
        $this->assertSame('998gf542-eb27-4df88-a5cf-0f476773799a', GeneralHelper::findOrganizationInRequest($request3));
        $this->assertSame('998gf542-eb27-4df88-a5cf-0f476773799a', GeneralHelper::findOrganizationInRequest($request4));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::cidr_match
     */
    public function testCidr_match(): void
    {
        $this->assertFalse(GeneralHelper::cidr_match('www.seznam.cz ', '192.168.109.0/24'));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::isIPPrivate
     */
    public function testIsIPPrivate(): void
    {
        $this->assertTrue(GeneralHelper::isIPPrivate('127.0.0.1'));
        $this->assertTrue(GeneralHelper::isIPPrivate('109.202.79.90'));
    }

    /**
     * @covers \App\Helpers\GeneralHelper::parseEmail
     */
    public function testParseEmail(): void
    {
        $array = [
            'local' => 'tester',
            'domain' => '@goodaccess.com'
        ];
        $this->assertSame($array, GeneralHelper::parseEmail('tester@goodaccess.com'));
    }

    /**
     * @dataProvider provideGapsData
     * @covers \App\Helpers\GeneralHelper::getGaps
     */
    public function testGetGaps($expectedResult, $input): void
    {
        $this->assertSame($expectedResult, GeneralHelper::getGaps($input));
    }

    /**
     * @return array[]
     */
    public function provideGapsData(): array
    {
        return [
            [
                5,
                [1,2,3,4,6,8],
            ],
            [
                2,
                [1,3,4,6,8],
            ],
            [
                7,
                [1,2,3,4,5,6],
            ],
        ];
    }

    /**
     * @covers \App\Helpers\GeneralHelper::checkSubnetCollision
     */
    public function testCheckSubnetCollision(): void
    {
        $this->assertTrue(GeneralHelper::checkSubnetCollision('10.0.0.0/23', '172.24.28.0/22'));
        $this->assertTrue(GeneralHelper::checkSubnetCollision('10.0.0.0/23', '172.24.28.0/22', '172.24.28.0/22'));
    }

    /**
     * @dataProvider provideIpRangeData
     * @covers \App\Helpers\GeneralHelper::ipRange
     */
    public function testIpRange($expectedResult, $input): void
    {
        $this->assertSame($expectedResult, GeneralHelper::ipRange($input));
    }

    /**
     * @return array[]
     */
    public function provideIpRangeData(): array
    {
        return [
            [
                [
                    0 => '10.0.0.0',
                    1 => '10.0.1.255'
                ],
                '10.0.0.0/23'
            ],
            [
                [
                    0 => '192.168.0.0',
                    1 => '192.168.3.255'
                ],
                '192.168.0.10/22'
            ]
        ];
    }

    /**
     * @covers \App\Helpers\GeneralHelper::random
     */
    public function testRandom(): void
    {
        $length = 15;
        $randomString = GeneralHelper::random($length);
        $this->assertSame($length, strlen($randomString));
    }
}

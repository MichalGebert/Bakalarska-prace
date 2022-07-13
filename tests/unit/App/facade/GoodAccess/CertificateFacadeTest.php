<?php

namespace unit;

use App\Facade\Goodaccess\CertificateFacade;
use PHPUnit\Framework\TestCase;

class CertificateFacadeTest extends TestCase
{

    public function testGetCACertificate(): void
    {
        $organizationID = 2;

        $certificateFacade = new CertificateFacade();
        $filePath = $certificateFacade->getCACertificate($organizationID);
        $this->assertSame('/path/pki-ca/'.$organizationID.'/rsa/pki/certificate.crt', $filePath);
    }
}

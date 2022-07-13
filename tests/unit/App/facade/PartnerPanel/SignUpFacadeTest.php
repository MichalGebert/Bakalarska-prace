<?php

use App\Facade\PartnerPanel\SignUpFacade;
use App\Model\Account\AccountRepository;
use App\Model\General\CountryRepository;
use App\Model\General\PartnerTypeRepository;
use App\Services\General\LogService;

class SignUpFacadeTest extends \Codeception\Test\Unit
{
    public function testGetPartnerType(): void
    {
        $type = 'MSP';
        $partnerTypeRepository = $this->createMock(PartnerTypeRepository::class);
        $partnerTypeRepository->method('getByParameter')->willReturn($type);
        $countryRepository = $this->createMock(CountryRepository::class);

        $signUpFacade = new SignUpFacade($countryRepository, $partnerTypeRepository);
        $partnerType = $signUpFacade->getPartnerType($type);
        $this->assertSame($type, $partnerType);
    }

    public function testGetAllCountries(): void
    {
        $countryRepository = $this->createMock(CountryRepository::class);
        $countryRepository->method('getAll')->willReturn(null);
        $partnerTypeRepository = $this->createMock(PartnerTypeRepository::class);

        $signUpFacade = new SignUpFacade($countryRepository, $partnerTypeRepository);
        $countryIRows = $signUpFacade->getAllCountries();
        $this->assertNull($countryIRows);
    }
}

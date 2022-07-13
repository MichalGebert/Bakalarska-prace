<?php

use App\Facade\PartnerPanel\TrainingFacade;
use App\Model\General\PartnerTrainingRepository;
use Codeception\Test\Unit;

class TrainingFacadeTest extends Unit
{

    public function testGetPartnerTrainingJSON(): void
    {
        $partnerTrainingIRows = [[
            PartnerTrainingRepository::COL_NAME => 'name',
            PartnerTrainingRepository::COL_DOWNLOAD_LINK => 'download_link',
            PartnerTrainingRepository::COL_FILE_TYPE => 'file_type',
            PartnerTrainingRepository::COL_EXTERNAL_LINK => 'external_link'
        ]];
        $expected = [[
            "name"          =>  'name',
            "download_link" =>  'download_link',
            "file_type"     =>  'file_type',
            "external_link" =>  'external_link'
        ]];

        $partnerTrainingRepository = $this->getMockBuilder(PartnerTrainingRepository::class)->disableOriginalConstructor()->getMock();
        $partnerTrainingRepository->method('getAllByParameter')->willReturn($partnerTrainingIRows);
        $trainingFacade = new TrainingFacade($partnerTrainingRepository);

        $returnArr = $trainingFacade->getPartnerTrainingJSON(1);
        $this->assertEquals($expected, $returnArr);
    }
}

<?php

namespace App\Tests\Domain\Strava\Gear;

use App\Domain\Strava\Gear\DbalImportedGearRepository;
use App\Domain\Strava\Gear\GearId;
use App\Domain\Strava\Gear\Gears;
use App\Domain\Strava\Gear\ImportedGearRepository;
use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\ValueObject\Measurement\Length\Meter;
use App\Tests\ContainerTestCase;

class DbalImportedGearRepositoryTest extends ContainerTestCase
{
    private ImportedGearRepository $importedGearRepository;

    public function testFindAndSave(): void
    {
        $gear = ImportedGearBuilder::fromDefaults()
            ->withGearId(GearId::fromUnprefixed(1))
            ->withDistanceInMeter(Meter::from(1230))
            ->build();
        $this->importedGearRepository->save($gear);

        $this->assertEquals(
            $gear,
            $this->importedGearRepository->find($gear->getId())
        );
    }

    public function testItShouldThrowWhenNotFound(): void
    {
        $this->expectException(EntityNotFound::class);
        $this->importedGearRepository->find(GearId::fromUnprefixed('1'));
    }

    public function testFindAll(): void
    {
        $gearOne = ImportedGearBuilder::fromDefaults()
            ->withGearId(GearId::fromUnprefixed(1))
            ->withDistanceInMeter(Meter::from(1230))
            ->build();
        $this->importedGearRepository->save($gearOne);
        $gearTwo = ImportedGearBuilder::fromDefaults()
            ->withGearId(GearId::fromUnprefixed(2))
            ->withDistanceInMeter(Meter::from(10230))
            ->build();
        $this->importedGearRepository->save($gearTwo);
        $gearThree = ImportedGearBuilder::fromDefaults()
            ->withGearId(GearId::fromUnprefixed(3))
            ->withDistanceInMeter(Meter::from(230))
            ->build();
        $this->importedGearRepository->save($gearThree);
        $gearFour = ImportedGearBuilder::fromDefaults()
            ->withGearId(GearId::fromUnprefixed(4))
            ->withDistanceInMeter(Meter::from(100230))
            ->withIsRetired(true)
            ->build();
        $this->importedGearRepository->save($gearFour);

        $this->assertEquals(
            Gears::fromArray([$gearTwo, $gearOne, $gearThree, $gearFour]),
            $this->importedGearRepository->findAll()
        );
    }

    public function testUpdate(): void
    {
        $gear = ImportedGearBuilder::fromDefaults()
            ->withGearId(GearId::fromUnprefixed(1))
            ->withDistanceInMeter(Meter::from(1000))
            ->build();
        $this->importedGearRepository->save($gear);

        $this->assertEquals(
            1000,
            $gear->getDistance()->toMeter()->toFloat()
        );

        $gear->updateDistance(Meter::from(30000));
        $this->importedGearRepository->save($gear);

        $this->assertEquals(
            30000,
            $this->importedGearRepository->find(GearId::fromUnprefixed(1))->getDistance()->toMeter()->toFloat()
        );
    }

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->importedGearRepository = new DbalImportedGearRepository(
            $this->getConnection()
        );
    }
}

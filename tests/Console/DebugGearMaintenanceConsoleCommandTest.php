<?php

namespace App\Tests\Console;

use App\Console\DebugGearMaintenanceConsoleCommand;
use App\Domain\Strava\Gear\Maintenance\GearMaintenanceConfig;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class DebugGearMaintenanceConsoleCommandTest extends ConsoleCommandTestCase
{
    use MatchesSnapshots;

    private DebugGearMaintenanceConsoleCommand $debugGearMaintenanceConsoleCommand;

    public function testExecute(): void
    {
        $command = $this->getCommandInApplication('app:debug:gear-maintenance');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertMatchesTextSnapshot($commandTester->getDisplay());
    }

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->debugGearMaintenanceConsoleCommand = new DebugGearMaintenanceConsoleCommand(
            GearMaintenanceConfig::fromYmlString(
                file_get_contents(__DIR__.'/../../config/gear-maintenance.yml')
            )
        );
    }

    protected function getConsoleCommand(): Command
    {
        return $this->debugGearMaintenanceConsoleCommand;
    }
}

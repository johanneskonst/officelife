<?php

namespace Tests\Unit\ViewHelpers\Project;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\ProjectDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Project\ProjectDecisionsViewHelper;

class ProjectDecisionsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_decisions(): void
    {
        $projectDecision = factory(ProjectDecision::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $projectDecision->project->company_id,
        ]);
        $jim = factory(Employee::class)->create([
            'company_id' => $projectDecision->project->company_id,
        ]);
        $projectDecision->deciders()->attach([$michael->id]);
        $projectDecision->deciders()->attach([$jim->id]);
        $collection = ProjectDecisionsViewHelper::decisions($projectDecision->project);

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => $michael->avatar,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => $jim->avatar,
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $collection->toArray()[0]['deciders']->toArray()
        );
        $this->assertEquals(
            $projectDecision->id,
            $collection->toArray()[0]['id']
        );
        $this->assertEquals(
            $projectDecision->title,
            $collection->toArray()[0]['title']
        );
    }
}

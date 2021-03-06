<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Team;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;

class EmployeeTeamController extends Controller
{
    /**
     * Assign a team to the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return Collection
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'team_id' => $request->input('id'),
        ];

        $employee = (new AddEmployeeToTeam)->execute($data);

        return EmployeeShowViewHelper::teams($employee->teams, $employee);
    }

    /**
     * Remove the team for the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $teamId
     * @return Collection
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'team_id' => $teamId,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($data);

        return EmployeeShowViewHelper::teams($employee->teams, $employee);
    }
}

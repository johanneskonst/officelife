<?php

namespace App\Http\Controllers\Company\Project;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use App\Services\Company\Project\AddEmployeeToProject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Project\ProjectMembersViewHelper;
use App\Services\Company\Project\RemoveEmployeeFromProject;

class ProjectMembersController extends Controller
{
    /**
     * Display the list of members in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Project/Members/Index', [
            'tab' => 'members',
            'project' => ProjectViewHelper::info($project),
            'members' => ProjectMembersViewHelper::members($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Returns all potential members, displayed in the Add member modal.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $potentialMembers = ProjectMembersViewHelper::potentialMembers($project);

        return response()->json([
            'data' => $potentialMembers,
        ], 200);
    }

    /**
     * Add an employee to the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'employee_id' => $request->input('employee.value'),
            'role' => $request->input('role'),
        ];

        $employee = (new AddEmployeeToProject)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'role' => $request->input('role'),
                'added_at' => DateHelper::formatDate(Carbon::now()),
                'position' => (! $employee->position) ? null : [
                    'id' => $employee->position->id,
                    'title' => $employee->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $loggedCompany,
                    'employee' => $employee,
                ]),
            ],
        ], 201);
    }

    /**
     * Remove an employee from the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return JsonResponse
     */
    public function remove(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'employee_id' => $request->input('employee'),
        ];

        (new RemoveEmployeeFromProject)->execute($data);

        return response()->json([
            'data' => [
                'id' => $request->input('employee.value'),
            ],
        ], 201);
    }
}

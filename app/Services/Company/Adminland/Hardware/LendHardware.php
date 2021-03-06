<?php

namespace App\Services\Company\Adminland\Hardware;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;

class LendHardware extends BaseService
{
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'hardware_id' => 'required|integer|exists:hardware,id',
        ];
    }

    /**
     * Lend a piece of hardware to an employee.
     *
     * @param  array    $data
     * @return Hardware
     */
    public function execute(array $data): Hardware
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $hardware = Hardware::where('company_id', $data['company_id'])
            ->findOrFail($data['hardware_id']);

        $hardware->employee_id = $this->employee->id;
        $hardware->save();

        $this->log($data, $hardware);

        return $hardware;
    }

    /**
     * Create an audit log.
     *
     * @param array    $data
     * @param Hardware $hardware
     */
    private function log(array $data, Hardware $hardware): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'hardware_lent',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'hardware_id' => $hardware->id,
                'hardware_name' => $hardware->name,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}

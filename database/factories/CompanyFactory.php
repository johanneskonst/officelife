<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use App\Models\Company\Project;
use App\Models\Company\ProjectStatus;
use App\Models\Company\RateYourManagerAnswer;

$factory->define(App\Models\Company\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Models\Company\Employee::class, function (Faker $faker) {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'user_id' => factory(App\Models\User\User::class)->create()->id,
        'company_id' => $companyId,
        'position_id' => function () use ($companyId) {
            return factory(App\Models\Company\Position::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'pronoun_id' => function () {
            return factory(App\Models\User\Pronoun::class)->create()->id;
        },
        'uuid' => $faker->uuid,
        'avatar' => 'https://api.adorable.io/avatars/285/abott@adorable.png',
        'permission_level' => config('officelife.permission_level.administrator'),
        'email' => 'dwigth@dundermifflin.com',
        'first_name' => 'Dwight',
        'last_name' => 'Schrute',
        'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'),
        'consecutive_worklog_missed' => 0,
        'employee_status_id' => function () use ($companyId) {
            return factory(App\Models\Company\EmployeeStatus::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'amount_of_allowed_holidays' => 30,
    ];
});

$factory->define(App\Models\Company\AuditLog::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'action' => 'account_created',
        'author_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\EmployeeLog::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'action' => 'account_created',
        'author_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\DirectReport::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'manager_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'employee_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
    ];
});

$factory->define(App\Models\Company\Position::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'Assistant to the regional manager',
    ];
});

$factory->define(App\Models\Company\Flow::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'Birthdate',
        'type' => 'employee_joins_company',
    ];
});

$factory->define(App\Models\Company\Step::class, function () {
    return [
        'flow_id' => function () {
            return factory(App\Models\Company\Flow::class)->create()->id;
        },
        'number' => 3,
        'unit_of_time' => 'days',
        'modifier' => 'after',
        'real_number_of_days' => 3,
    ];
});

$factory->define(App\Models\Company\Action::class, function () {
    return [
        'step_id' => function () {
            return factory(App\Models\Company\Step::class)->create()->id;
        },
        'type' => 'notification',
        'recipient' => 'manager',
        'specific_recipient_information' => null,
    ];
});

$factory->define(App\Models\Company\Task::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'title' => 'Welcome the new employee',
    ];
});

$factory->define(App\Models\Company\Notification::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'action' => 'notification',
        'objects' => '{"user": 1}',
        'read' => false,
    ];
});

$factory->define(App\Models\Company\Worklog::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'content' => 'This is what I have done',
    ];
});

$factory->define(App\Models\Company\EmployeeStatus::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'Permanent',
    ];
});

$factory->define(App\Models\Company\Morale::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'emotion' => 1,
        'comment' => 'I hate Toby',
    ];
});

$factory->define(App\Models\Company\MoraleCompanyHistory::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'average' => 2.3,
        'number_of_employees' => 30,
    ];
});

$factory->define(App\Models\Company\CompanyNews::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'author_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'author_name' => 'Dwight Schrute',
        'title' => 'Party at the office',
        'content' => 'Michael and Dwight invite you to a party.',
    ];
});

$factory->define(App\Models\Company\Country::class, function () {
    return [
        'name' => 'France',
    ];
});

$factory->define(App\Models\Company\Place::class, function (Faker $faker) {
    return [
        'street' => '1725 Slough Ave',
        'city' => 'Scranton',
        'province' => 'PA',
        'postal_code' => '',
        'country_id' => function () {
            return factory(App\Models\Company\Country::class)->create()->id;
        },
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'placable_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'placable_type' => 'App\Models\Company\Employee',
    ];
});

$factory->define(App\Models\Company\CompanyPTOPolicy::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'year' => 2020,
        'total_worked_days' => 250,
        'default_amount_of_allowed_holidays' => 30,
        'default_amount_of_sick_days' => 3,
        'default_amount_of_pto_days' => 5,
    ];
});

$factory->define(App\Models\Company\EmployeeDailyCalendarEntry::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'new_balance' => 10,
        'daily_accrued_amount' => 1,
        'current_holidays_per_year' => 100,
        'default_amount_of_allowed_holidays_in_company' => 100,
        'log_date' => '2010-01-01',
    ];
});

$factory->define(App\Models\Company\EmployeePlannedHoliday::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'planned_date' => '2010-01-01',
        'full' => true,
        'actually_taken' => false,
        'type' => 'holiday',
    ];
});

$factory->define(App\Models\Company\CompanyCalendar::class, function () {
    return [
        'company_pto_policy_id' => function () {
            return factory(App\Models\Company\CompanyPTOPolicy::class)->create()->id;
        },
        'day' => '2010-01-01',
        'day_of_year' => 1,
        'day_of_week' => 1,
        'is_worked' => true,
    ];
});

$factory->define(App\Models\Company\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Models\Company\WorkFromHome::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'date' => '2010-01-01',
        'work_from_home' => true,
    ];
});

$factory->define(App\Models\Company\Question::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'What is your favorite movie?',
        'active' => true,
    ];
});

$factory->define(App\Models\Company\Answer::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'question_id' => function () use ($companyId) {
            return factory(App\Models\Company\Question::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'employee_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'body' => 'This is my answer',
    ];
});

$factory->define(App\Models\Company\Hardware::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'iPhone',
        'serial_number' => '123',
    ];
});

$factory->define(App\Models\Company\Ship::class, function () {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create()->id;
        },
        'title' => 'New API',
    ];
});

$factory->define(App\Models\Company\Skill::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'PHP',
    ];
});

$factory->define(App\Models\Company\ExpenseCategory::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'travel',
    ];
});

$factory->define(App\Models\Company\Expense::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'employee_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'expense_category_id' => function (array $data) {
            return factory(App\Models\Company\ExpenseCategory::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'status' => 'created',
        'title' => 'Restaurant',
        'amount' => '100',
        'currency' => 'USD',
        'expensed_at' => '1999-01-01',
    ];
});

$factory->define(App\Models\Company\RateYourManagerSurvey::class, function () {
    return [
        'manager_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'active' => false,
    ];
});

$factory->define(App\Models\Company\RateYourManagerAnswer::class, function () {
    return [
        'rate_your_manager_survey_id' => function () {
            return factory(App\Models\Company\RateYourManagerSurvey::class)->create()->id;
        },
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'rating' => RateYourManagerAnswer::BAD,
        'comment' => 'A really bad manager',
    ];
});

$factory->define(App\Models\Company\OneOnOneEntry::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'manager_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'employee_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'happened_at' => '2020-03-02 00:00:00',
    ];
});

$factory->define(App\Models\Company\OneOnOneTalkingPoint::class, function () {
    return [
        'one_on_one_entry_id' => function () {
            return factory(App\Models\Company\OneOnOneEntry::class)->create([])->id;
        },
        'description' => 'what are you doing right now',
        'checked' => false,
    ];
});

$factory->define(App\Models\Company\OneOnOneActionItem::class, function () {
    return [
        'one_on_one_entry_id' => function () {
            return factory(App\Models\Company\OneOnOneEntry::class)->create([])->id;
        },
        'description' => 'what are you doing right now',
        'checked' => false,
    ];
});

$factory->define(App\Models\Company\OneOnOneNote::class, function () {
    return [
        'one_on_one_entry_id' => function () {
            return factory(App\Models\Company\OneOnOneEntry::class)->create([])->id;
        },
        'note' => 'what are you doing right now',
    ];
});

$factory->define(App\Models\Company\GuessEmployeeGame::class, function () {
    return [
        'employee_who_played_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'employee_to_find_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'first_other_employee_to_find_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'second_other_employee_to_find_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
    ];
});

$factory->define(App\Models\Company\Project::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'API v3',
        'code' => '123456',
        'description' => 'it is going well',
        'status' => Project::CREATED,
    ];
});

$factory->define(App\Models\Company\ProjectLink::class, function () {
    return [
        'project_id' => function () {
            return factory(App\Models\Company\Project::class)->create()->id;
        },
        'type' => 'slack',
        'label' => '#dunder-mifflin',
        'url' => 'https://slack.com/dunder',
    ];
});

$factory->define(App\Models\Company\ProjectStatus::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'project_id' => function () use ($companyId) {
            return factory(App\Models\Company\Project::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'author_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'status' => ProjectStatus::ON_TRACK,
        'title' => 'Title',
        'description' => 'it is going well',
    ];
});

$factory->define(App\Models\Company\ProjectDecision::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'project_id' => function () use ($companyId) {
            return factory(App\Models\Company\Project::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'author_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'title' => 'This is a title',
        'decided_at' => Carbon::now(),
    ];
});

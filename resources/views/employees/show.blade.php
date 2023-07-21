@extends('templates.layout')

@section('title')
  {{ __('messages.Employee Detail') }}
@endsection

@section('heading')
  {{ __('messages.Employee Detail') }}
@endsection

@section('content')
<!-- in this case, $employee->image not work on external css so i put in -->
<style>
  .file-input-detail {
    display: inline-block;
    overflow: hidden;
    border-radius: 50%;
    width: 108px;
    height: 108px;
    margin-top: 20px;
    margin-left: 20px;
    background: url('{{ $employee->image }}');
    background-size: cover;
    text-align: center;
    cursor: default;
  }
</style>
    <!-- back button -->
    <a href="{{ route('employees') }}" class="btn btn-primary btn-sm float-right mt-4 px-3 btn-rounded">{{ __('messages.Employee List') }}</a>

      <!-- image input -->
      <!-- if no profile -->
      @if (empty($employee->image))
        <div class="form-row">
          <div class="form-group d-flex justify-content-center align-items-center">    
            <label class="file-input-detail-none"></label>
          </div>
        </div>
      <!-- if profile exists -->
      @else
        <div class="form-row">
          <div class="form-group d-flex justify-content-center align-items-center">    
            <label class="file-input-detail"></label>
          </div>
        </div>
      @endif

    <div class="container">
    <p class="text-primary font-weight-bold">{{ __('messages.Personal Info') }}</p>
      <!-- employee id -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Employee ID') }}:</div>
        <div class="col-6">{{ $employee->employee_id }}</div>
      </div>
      <!-- name -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Name') }}:</div>
        <div class="col-6">{{ $employee->name }}</div>
      </div>
      <!-- nrc -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.NRC') }}:</div>
        <div class="col-6">{{ $employee->nrc }}</div>
      </div>
      <!-- date of birth -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Date of Birth') }}:</div>
        <div class="col-6">{{ date('d-m-Y', strtotime($employee->date_of_birth)) }}</div>
      </div>
      <!-- gender -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Gender') }}:</div>
        <div class="col-6">{{ $employee->gender == '1' ? 'Male' : 'Female' }}</div>
      </div>
      <!-- phone -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Phone') }}:</div>
        <div class="col-6">{{ $employee->phone }}</div>
      </div>
      <!-- email -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Email') }}:</div>
        <div class="col-6">{{ $employee->email }}</div>
      </div>
      <!-- address -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Address') }}:</div>
        <div class="col-6">{{ $employee->address }}</div>
      </div>

      <p class="text-primary mt-5 font-weight-bold">{{ __('messages.Profession') }}</p>
      <!-- languages -->
      <div class="row mt-2">
          <div class="col-6">{{ __('messages.Languages') }}:</div>
          <div class="col-6">
              @php
              $selectedLanguagesArray = [];
              if (in_array(1, $selectedLanguages)) {
                  $selectedLanguagesArray[] = 'English';
              }
              if (in_array(2, $selectedLanguages)) {
                  $selectedLanguagesArray[] = 'Japan';
              }
              $selectedLanguagesString = implode(', ', $selectedLanguagesArray);
              @endphp
              {{ $selectedLanguagesString }}
          </div>
      </div>
      <!-- programming languages -->
      <div class="row mt-2">
          <div class="col-6">{{ __('messages.Programming Languages') }}:</div>
          <div class="col-6">
              @php
              $selectedLanguages = [];
              foreach ($programmingLanguages as $programmingLanguage) {
                  if (in_array($programmingLanguage->id, $selectedProgrammingLanguages)) {
                      $selectedLanguages[] = $programmingLanguage->name;
                  }
              }
              $selectedLanguagesString = implode(', ', $selectedLanguages);
              @endphp
              {{ $selectedLanguagesString }}
          </div>
      </div>
      <!-- career part -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Career Part') }}:</div>
        <div class="col-6">{{ $employee->career_part == '1' ? 'Front-end Developer' : ($employee->career_part == '2' ? 'Back-end Developer' : ($employee->career_part == '3' ? 'Full-stack Developer' : 'Mobile Developer')) }}</div>
      </div>
      <!-- level -->
      <div class="row mt-2">
        <div class="col-6">{{ __('messages.Level') }}:</div>
        <div class="col-6">{{ $employee->level == '1' ? 'Beginner' : ($employee->level == '2' ? 'Junior Engineer' : ($employee->level == '3' ? 'Engineer' : 'Senior Engineer')) }}</div>
      </div>

      <p class="text-primary mt-5 font-weight-bold">{{ __('messages.Assigned Projects') }}</p>
      <!-- if assigned projects exist -->
      @if ($employeeProjects->isNotEmpty())
      <div class="row mx-2 mt-4 mb-5">
        <table class="table table-sm">
          <thead>
            <tr>
              <th scope="col" class="pl-3" style="width: 90px;">{{ __('messages.No.') }}</th>
              <th scope="col" class="pl-3">{{ __('messages.Project Name') }}</th>
              <th scope="col" class="pl-3">{{ __('messages.Start Date') }}</th>
              <th scope="col" class="pl-3">{{ __('messages.End Date') }}</th>
              <th scope="col">{{ __('messages.Documentations') }}</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($employeeProjects->sortBy(function ($project) {
            return abs(strtotime($project->start_date) - strtotime(date('Y-m-d')));
          })->unique('start_date') as $key => $employeeProject)
            <tr>
              <td class="pl-3">
                {{ $loop->iteration }}
              </td>
              <td class="pl-3">
                {{ $employeeProject->name }}
              </td>
              <td class="pl-3">
                {{ date('d-m-Y', strtotime($employeeProject->start_date)) }}
              </td>
              <td class="pl-3">
                {{ date('d-m-Y', strtotime($employeeProject->end_date)) }}
              </td>
              <td>
                @foreach ($employeeProjects->where('project_id', $employeeProject->project_id)->unique('file_name') as $docProject)
                  <!-- show docs for each project -->
                  <a href="{{ route('docs.download', ['fileName' => $docProject->file_name]) }}">{{ $docProject->file_name }}</a>
                  @if (!$loop->last)
                    <br> <!-- new line between documentations -->
                  @endif
                @endforeach
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <!-- if assigned project does not exist -->
      @else
        <p class="mb-5">{{ $employee->name }} {{ __('messages.does not have any assigned project currently.')}}</p>
      @endif
@endsection
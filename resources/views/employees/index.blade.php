@extends('templates.layout')

@section('title')
  {{ __('messages.Employee List') }}
@endsection

@section('heading')
  {{ __('messages.Employees') }}
@endsection

<!-- to hide success or error message automatically after 5s -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  jQuery(document).ready(function() {
    setTimeout(function() {
      jQuery('#success-message, #error-message').fadeOut('slow');
    }, 4000);
  });
</script>

<!-- show messages for crud success or fail -->
<div class="container" id="msg-box">
  @if(session('success'))
    <p id="success-message" class="alert alert-success">
      {{ session('success') }}
    </p>
  @endif
  @if(session('error'))
    <p id="error-message" class="alert alert-danger">
      {{ session('error') }}
    </<button type="button" class="btn btn-secondary" data-toggle="popover" data-placement="top" title="Popup title" data-content="Popup content">Trigger</button>>
  @endif

  @if($errors->any())
    <div id="error-message" class="alert alert-danger">
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
  @endif
</div>

@section('content')
  <!-- search and download form -->
  <!-- search form -->
  <div class="row">
    <div class="col-md-2">
      <form action="{{ route('employees.search') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="employee-id">{{__('messages.Employee ID')}}: </label>
          <input type="text" maxlength="5" class="form-control" value="{{ $searchInputs['employee_id'] ?? '' }}" id="employee-id" name="employee_id" placeholder="{{ __('messages.e.g.') }} 00001" {{ \App\Models\Employee::count() === 0 ? 'disabled' : '' }}>
        </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="career-part">{{__('messages.Career Part')}}:</label>
        <select class="form-control form-control-sm" id="career-part" name="career_part" {{ DB::table('employees')->count() === 0 ? 'disabled' : '' }}>
          <option value="0">{{ __('messages.Select a Career Part') }}</option>
          <?php
          $careerPartLabels = [
            1 => 'Front-end Developer',
            2 => 'Back-end Developer',
            3 => 'Full-stack Developer',
            4 => 'Mobile Developer',
          ];

          $distinctCareerParts = array_unique(json_decode(json_encode($distinctCareerParts), true)); // convert object to array and remove duplicates

          sort($distinctCareerParts); // sort the values in ascending order

          foreach ($distinctCareerParts as $careerPart):
            ?>
            <option value="<?= $careerPart ?>" <?= $searchInputs['career_part'] == $careerPart ? 'selected' : '' ?>>
              <?= isset($careerPartLabels[$careerPart]) ? $careerPartLabels[$careerPart] : 'Unknown' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="col-md-2">
      <div class="form-group">
        <label for="level">{{__('messages.Level')}}:</label>
        <select class="form-control form-control-sm" id="level" name="level" {{ DB::table('employees')->count() === 0 ? 'disabled' : '' }}>
          <option value="0">{{ __('messages.Select a Level') }}</option>
          <?php
          $levelLabels = [
            1 => 'Beginner',
            2 => 'Junior Engineer',
            3 => 'Engineer',
            4 => 'Senior Engineer',
          ];

          $distinctLevels = array_unique(json_decode(json_encode($distinctLevels), true)); // convert object to array and remove duplicates

          sort($distinctLevels); // sort the values in ascending order

          foreach ($distinctLevels as $level):
            ?>
            <option value="<?= $level ?>" <?= $searchInputs['level'] == $level ? 'selected' : '' ?>>
              <?= isset($levelLabels[$level]) ? $levelLabels[$level] : 'Unknown' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="col-md-1 mt-2">
      <button type="submit" class="btn btn-primary mt-4 btn-rounded" onMouseOver="this.style.color='#87CEFA'" onMouseOut="this.style.color='#FFF'" {{ \App\Models\Employee::count() === 0 ? 'disabled' : '' }}><i class="fa fa-search"></i></button>
    </form>
  </div>
  
  <!-- download form -->
  <div class="col-md-3 mb-2">
    <div class="container mt-2 ml-4">
      <form action="{{ route('download') }}" method="POST">
        {{-- get current page number --}}
        <input type="hidden" name="page" value="{{ $employees->currentPage() }}">
        @csrf
        <div class="form-row align-items-center">
          <div class="col-4">
            <div class="form-check form-check-inline mt-4">
              <input checked class="form-check-input" type="radio" name="download_option" id="pdf" value="1" {{ \App\Models\Employee::count() === 0 ? 'disabled' : '' }}>
              <label class="form-check-label" for="pdf">PDF</label>
            </div>
          </div>
          <div class="col-4">
            <div class="form-check form-check-inline mt-4">
              <input class="form-check-input" type="radio" name="download_option" id="excel" value="2" {{ \App\Models\Employee::count() === 0 ? 'disabled' : '' }}>
              <label class="form-check-label" for="excel">Excel</label>
            </div>
          </div>
          <div class="col-4 pr-5">
            <!-- accept search inputs when download -->
            <input type="hidden" name="search_inputs" value="{{ json_encode($searchInputs) }}">
            <button type="submit" class="btn btn-secondary btn-sm btn-rounded mt-4 ml-3" style="width: 116px;" {{ \App\Models\Employee::count() === 0 ? 'disabled' : '' }}>
              <div class="d-flex align-items-center">
                <i class="fa fa-download mr-2" aria-hidden="true"></i>
                <span style="white-space: nowrap;">{{ __('messages.Download') }}</span>
              </div>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

    <!-- hide table if data not found -->
    <div class="col-6">
    @if ($employees->isEmpty())
        <p>{{ __('messages.No employees found.') }}</p>
    @else
    </div>
    <!-- showing total employees count -->
    @php
      $rowCount = $employees->total();
    @endphp
    <div class="col-12 mb-3">
      <span>{{ __('messages.Total Results') }}: {{ $rowCount }}</span>
    </div>

    <!-- table -->
      <div class="table-responsive ml-3 mr-3">
          <table class="table table-bordered table-sm" id="table">
            <thead class="text-center text-primary">
              <tr>
                <th rowspan="2" style="vertical-align: middle; width: 50px;">{{ __('messages.No.') }}</th>
                <th rowspan="2" style="vertical-align: middle; width: 100px;">{{ __('messages.ID') }}</th>
                <th rowspan="2" style="vertical-align: middle;">{{ __('messages.Name') }}</th>
                <th rowspan="2" style="vertical-align: middle;">{{ __('messages.Email') }}</th>
                <th rowspan="2" style="vertical-align: middle; width: 170px;">{{ __('messages.Career') }}</th>
                <th rowspan="2" style="vertical-align: middle;">{{ __('messages.Level') }}</th>
                <th rowspan="2" style="vertical-align: middle;">{{ __('messages.Phone') }}</th>
                <th colspan="3" style="vertical-align: middle; width:150px;">{{__('messages.Action')}}</th>
              </tr>
              <tr class="text-center" style="font-size: 12px">
                <th>{{ __('messages.View') }}</th>
                <th>{{ __('messages.Edit') }}</th>
                <th>{{ __('messages.Delete') }}</th>
              </tr>
            </thead>

            <tbody style="font-size: 14px;">
            <!-- counting including each pagination -->
              @php 
                $rowCount = ($employees->currentPage() - 1) * $employees->perPage();
              @endphp

              @foreach ($employees as $employee)
              <tr class="text-center">
                <td>{{ $rowCount + $loop->iteration }}</td>
                <td>{{ $employee->employee_id }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>
                  @if ($employee->career_part == 1)
                    Front-end Developer
                  @elseif ($employee->career_part == 2)
                    Back-end Developer
                  @elseif ($employee->career_part == 3)
                    Full-stack Developer
                  @elseif ($employee->career_part == 4)
                    Mobile Developer
                  @endif
                </td>
                <td>
                  @if ($employee->level == 1)
                    Beginner
                  @elseif ($employee->level == 2)
                    Junior Engineer
                  @elseif ($employee->level == 3)
                    Engineer
                  @elseif ($employee->level == 4)
                    Senior Engineer
                  @endif
                </td>
                <td>{{ $employee->phone }}</td>
                <!-- view -->
                <td>
                  <a href="{{ route('employees.show', ['id' => $employee->id]) }}" class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i>
                  </a>
                </td>
                <!-- edit -->
                <td>
                  <a href="{{ route('employees.edit', ['id' => $employee->id]) }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-pencil"></i>
                  </a>
                </td>
                <!-- delete -->
                <td> <!-- Added 'text-center' class -->
                  <button type="button" data-toggle="modal" data-target="#deleteModal{{ $employee->id }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
              <!-- delete modal confirm box -->
              <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('messages.Delete Employee') }}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                        {{ __('messages.Are you sure to delete this employee?') }}
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
                          <!-- actual delete button -->
                          <form action="{{ route('employees.delete', ['id' => $employee->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('messages.Delete') }}</button>
                          </form>
                      </div>
                    </div>
                  </div>
              </div>
              @endforeach
              @endif
            </tbody>
          </table>
      </div>

    <!-- pagination and go back -->
    <div class="row pr-3">
      <div class="col-md-6">
        <!-- pagination -->
        <div class="d-flex mt-4 ml-3">
          {{ $employees->links() }}
        </div>
      </div>
    </div>
@endsection

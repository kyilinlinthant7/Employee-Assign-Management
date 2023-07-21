@extends('templates.layout')

@section('title')
  {{ __('messages.Employee Registration') }}
@endsection

@section('heading')
  {{ __('messages.Employee Registration') }}
@endsection

@section('content')
    <!-- error messages if something wrong -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
        <br>
        <h1>Error</h1>
        <p>Error Code: {{ session('errorCode') }}</p>
    </div>
    @endif

    <!-- back button -->
    <a href="{{ route('employees') }}" class="btn btn-primary btn-sm float-right mt-4 px-3 btn-rounded">{{ __('messages.Employee List') }}</a>
    
    <!-- employee register form -->
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <!-- image input -->
      <div class="form-row">
        <div class="form-group d-flex justify-content-center align-items-center">    
          <label class="file-input" title="{{ __('messages.Upload Image') }}">
            <label style="font-size: 10px;">{{ __('messages.Upload Image') }}</label>
              <input id="file-upload" class="form-control-file" type="file"  name="image" data-image="" onchange="loadPhoto(event)">
                <div id="preview"></div>
              </input>
            </label>
            <button class="btn btn-sm btn-secondary" type="button" id="remove-button" style="display: none;">Remove</button>
            <!-- image error message -->
            @error('image')
              <span class="text-danger ml-3">{{ $message }}</span>
            @enderror
          </label>
        </div>
      </div>

      <div class="form-row">
        <!-- employee id input -->
        <div class="form-group col-md-6">
          <label for="employee-id">{{ __('messages.Employee ID') }}:<span class="text-danger">*</span></label>
          <input type="text" disabled class="form-control" id="employee-id" name="employee_id"
          @if(isset($formattedEmployeeId))
              value="{{ $formattedEmployeeId }}"
              placeholder="{{ $formattedEmployeeId }}"
          @endif
          >
        </div>

        <!-- name input -->
        <div class="form-group col-md-6">
          <label for="employee-name">{{ __('messages.Employee Name') }}:<span class="text-danger">*</span></label>
          <!-- name error message -->
          @error('name')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="text" class="form-control" maxlength="50" id="employee-name" name="name" value="{{ old('name') }}">
        </div>
      </div>

      <!-- nrc input -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nrc">NRC:<span class="text-danger">*</span></label>
          <!-- nrc error message -->
          @error('nrc')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="text" maxlength="50" class="form-control" id="nrc" name="nrc" value="{{ old('nrc') }}">
        </div>
        <!-- date of birth input -->
        <div class="form-group col-md-6">
          <!-- <span class="text-secondary" style="font-size: 10px;">Date of Birth must be at least 18 years old.</span> -->
          <label for="dob">{{ __('messages.Date of Birth') }}:<span class="text-danger">*</span></label>
          <!-- date of birth error message -->
          @error('date_of_birth')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="date" class="form-control" id="dob" name="date_of_birth" value="{{ old('date_of_birth') ? old('date_of_birth') : \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}">
        </div>
      </div>

      <div class="form-row">
        <!-- phone input -->
        <div class="form-group col-md-6">
          <label for="phone">{{ __('messages.Phone') }}:<span class="text-danger">*</span></label>
          <!-- phone error message -->
          @error('phone')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="text" maxlength="50" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
        </div>

        <!-- email input -->
        <div class="form-group col-md-6">
          <label for="email">{{ __('messages.Email') }}:<span class="text-danger">*</span></label>
          <!-- email error message -->
          @error('email')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="email" maxlength="50" class="form-control" id="email" name="email" value="{{ old('email') }}">
        </div>
      </div>

      <!-- gender input -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>{{ __('messages.Gender') }}:<span class="text-danger">*</span></label>
          <!-- gender error message -->
          @error('gender')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="male" value="1" {{ old('gender') === '1' ? 'checked' : '' }} checked>
            <label class="form-check-label" for="male">{{ __('messages.Male') }}</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="female" value="2" {{ old('gender') === '2' ? 'checked' : '' }}>
            <label class="form-check-label" for="female">{{ __('messages.Female') }}</label>
          </div>
        </div>
      </div>

      <!-- address input -->
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="address">{{ __('messages.Address') }}:<span class="text-danger">*</span></label>
          <!-- address error message -->
          @error('address')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <textarea class="form-control h-100" id="address" name="address"> {{ old('address') }}</textarea>
        </div>
      </div>

      <!-- languages input -->
      <div class="form-row mt-5">
        <div class="form-group col-md-6">
          <label>{{ __('messages.Languages') }}:<span class="text-danger">*</span></label>
          <!-- languages error message -->
          @error('languages')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="languages[]" value="1" {{ in_array(1, old('languages', [])) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('messages.English') }}</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="languages[]" value="2" {{ in_array(2, old('languages', [])) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('messages.Japan') }}</label>
          </div>
        </div>
      </div>

      <!-- programming languages input -->
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>{{ __('messages.Programming Languages') }}:<span class="text-danger">*</span></label>
          <!-- programming languages error message -->
          @error('programming_languages')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <br>
          <!-- get programming languages from database -->
          @foreach($programmingLanguages as $programmingLanguage)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="programming_languages[]" value="{{ $programmingLanguage->id }}" {{ in_array($programmingLanguage->id, old('programming_languages', [])) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $programmingLanguage->name }}</label>
            </div>
          @endforeach
        </div>
      </div>

      <div class="form-row">
        <!-- career part input -->
        <div class="form-group col-md-6">
          <label for="career-part">{{ __('messages.Career Part') }}:<span class="text-danger">*</span></label>
          <!-- career part error message -->
          @error('career_part')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <select class="form-control" id="career-part" name="career_part">
            <!-- <option value="0">Choose Career Part</option> -->
            <option value="0">{{ __('messages.Select a Career Part') }}</option>
            <option value="1" {{ old('career_part') == 1 ? 'selected' : '' }}>Front-end Developer</option>
            <option value="2" {{ old('career_part') == 2 ? 'selected' : '' }}>Back-end Developer</option>
            <option value="3" {{ old('career_part') == 3 ? 'selected' : '' }}>Full-stack Developer</option>
            <option value="4" {{ old('career_part') == 4 ? 'selected' : '' }}>Mobile Developer</option>
          </select>
        </div>

        <!-- level input -->
        <div class="form-group col-md-6">
          <label for="level">{{ __('messages.Level') }}:<span class="text-danger">*</span></label>
          <!-- level error message -->
          @error('level')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <select class="form-control" id="level" name="level">
            <!-- <option value="0">Choose Level</option> -->
            <option value="0">{{ __('messages.Select a Level') }}</option>
            <option value="1" {{ old('level') == 1 ? 'selected' : '' }}>Beginner</option>
            <option value="2" {{ old('level') == 2 ? 'selected' : '' }}>Junior Engineer</option>
            <option value="3" {{ old('level') == 3 ? 'selected' : '' }}>Engineer</option>
            <option value="4" {{ old('level') == 4 ? 'selected' : '' }}>Senior Engineer</option>
          </select>
        </div>
      </div>
      
      <!-- buttons -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="submit" class="btn btn-primary btn-rounded px-3">{{ __('messages.Submit') }}</button>
          <button type="reset" class="btn btn-secondary btn-rounded px-3">{{ __('messages.Reset') }}</button>
        </div>
      </div>
    </form>
@endsection
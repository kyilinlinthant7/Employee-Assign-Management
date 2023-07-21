@extends('templates.layout')

@section('title')
  {{ __('messages.Employee Edit') }}
@endsection

@section('heading')
  {{ __('messages.Employee Edit') }}
@endsection

@section('content')
    <!-- back button -->
    <a href="{{ route('employees') }}" class="btn btn-primary btn-sm float-right mt-4 px-3 btn-rounded">{{ __('messages.Employee List') }}</a>
    
    <!-- employee register form -->
    <form action="{{ route('employees.update', ['id' => $employee->id]) }}) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- image input -->
      {{-- if there is no image exists --}}
      @if (empty($employee->image))
      <div class="form-row">
        <div class="form-group d-flex justify-content-center align-items-center">    
          <label class="file-input-edit" title="{{ __('messages.Upload Image') }}">
          <label style="font-size: 10px; margin-left: -28px; z-index: 9999; position: absolute;">{{ __('messages.Upload Image') }}</label>
            <div id="preview-edit-none">
              <input id="file-upload-edit" class="form-control-file" type="file" name="image" data-image="" onchange="loadPhotoEditNone(event)"> 
                <img src="/images/default-profile.jpg" id="preview-image-none" />
            </div>
          </label>
          <button class="btn btn-sm btn-secondary" type="button" id="remove-button-edit-none" style="display: none;">Remove</button>
          {{-- image error message --}}
          @error('image')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
        </div>
      </div>
      
      {{-- if there is image exists --}}
      @else
      <div class="form-row">
        <div class="form-group d-flex justify-content-center align-items-center">    
          <label class="file-input-exists" title="{{ __('messages.Upload Image') }}">
            <label class="ml-5 pb-2" style="font-size: 10px;">{{ __('messages.Upload Image') }}</label>
              <input id="file-upload-edit" class="form-control-file" type="file"  name="image" data-image="" onchange="loadPhotoEditExists(event)">
                <div id="preview-edit-exists">
                  <img id="image-element" src="{{ $employee->image }}" alt="">
                </div>
            </label> 
            <button class="btn btn-sm btn-secondary ml-5" type="button" id="remove-button-before" onclick="removeImageBefore()">Remove</button>
            <button class="btn btn-sm btn-secondary ml-5" type="button" style="display: none;" id="remove-button-edit-exists">Remove</button>
            <!-- image error message -->
            @error('image')
              <span class="text-danger ml-3">{{ $message }}</span>
            @enderror
          </label>
        </div>
      </div>
      @endif

      <div class="form-row" id="nextArea">
        <!-- employee id input -->
        <input type="hidden" name="id" value="{{ $employee->id }}">
        <div class="form-group col-md-6">
          <label for="employee-id">{{ __('messages.Employee ID') }}:<span class="text-danger">*</span></label>
          <input type="text" disabled class="form-control" id="employee-id" name="employee_id" value="{{ $employee->employee_id }}">
        </div>

        <!-- name input -->
        <div class="form-group col-md-6">
          <label for="employee-name">{{ __('messages.Employee Name') }}:<span class="text-danger">*</span></label>
          <!-- name error message -->
          @error('name')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="text" maxlength="50" class="form-control" id="employee-name" name="name" value="{{ $employee->name }}">
        </div>
      </div>

      <!-- nrc input -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nrc">{{ __('messages.NRC') }}:<span class="text-danger">*</span></label>
          <!-- nrc error message -->
          @error('nrc')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="text" maxlength="50" class="form-control" id="nrc" name="nrc" value="{{ $employee->nrc }}">
        </div>

        <!-- date of birth input -->
        <div class="form-group col-md-6">
          <label for="dob">{{ __('messages.Date of Birth') }}:<span class="text-danger">*</span></label>
          <!-- date of birth error message -->
          @error('date_of_birth')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="date" class="form-control" id="dob" name="date_of_birth" value="{{ $employee->date_of_birth }}">
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
          <input type="text" maxlength="50" class="form-control" id="phone" name="phone" value="{{ $employee->phone }}">
        </div>

        <!-- email input -->
        <div class="form-group col-md-6">
          <label for="email">Email:<span class="text-danger">*</span></label>
          <!-- email error message -->
          @error('email')
            <span class="text-danger ml-3">{{ $message }}</span>
          @enderror
          <input type="email" maxlength="50" class="form-control" id="email" name="email" value="{{ $employee->email }}">
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
            <input class="form-check-input" type="radio" name="gender" id="male" value="1" {{ $employee->gender == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="male">{{ __('messages.Male') }}</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="female" value="2" {{ $employee->gender == '2' ? 'checked' : '' }}>
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
          <textarea class="form-control h-100" id="address" name="address">{{ $employee->address }}</textarea>
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
            <input class="form-check-input" type="checkbox" name="languages[]" value="1" {{ in_array(1, $selectedLanguages) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('messages.English') }}</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="languages[]" value="2" {{ in_array(2, $selectedLanguages) ? 'checked' : '' }}>
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
          @foreach($programmingLanguages as $programmingLanguage)
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="programming_languages[]" value="{{ $programmingLanguage->id }}" {{ in_array($programmingLanguage->id, $selectedProgrammingLanguages) ? 'checked' : '' }}>
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
            <option value="0">Select a Career Part</option>
            <option value="1" {{ $employee->career_part == '1' ? 'selected' : '' }}>Front-end Developer</option>
            <option value="2" {{ $employee->career_part == '2' ? 'selected' : '' }}>Back-end Developer</option>
            <option value="3" {{ $employee->career_part == '3' ? 'selected' : '' }}>Full-stack Developer</option>
            <option value="4" {{ $employee->career_part == '4' ? 'selected' : '' }}>Mobile Developer</option>
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
            <option value="0">Select a Level</option>
            <option value="1" {{ $employee->level == '1' ? 'selected' : '' }}>Beginner</option>
            <option value="2" {{ $employee->level == '2' ? 'selected' : '' }}>Junior Engineer</option>
            <option value="3" {{ $employee->level == '3' ? 'selected' : '' }}>Engineer</option>
            <option value="4" {{ $employee->level == '4' ? 'selected' : '' }}>Senior Engineer</option>
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



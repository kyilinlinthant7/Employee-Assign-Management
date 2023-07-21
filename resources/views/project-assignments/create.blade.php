@extends('templates.layout')

@section('title')
{{ __('messages.Project Assignment') }}
@endsection

@section('heading')
    {{ __('messages.Project Assignment') }}
@endsection

@section('content')
    <!-- back button -->
    <div class="mb-3">
        <a href="{{ route('employees') }}" class="btn btn-primary btn-sm float-right mt-4 px-3 btn-rounded el-btn">{{ __('messages.Employee List') }}</a>
    </div>

    <!-- form -->
    <div class="form-container">
        <!-- check whether the employees exist or not -->
        @if ($employees->isEmpty())
            <h5 class="text-danger">{{ __('messages.No employees to assign!') }}</h5>
            <!-- show blank form with disabled inputs -->
            <form>
                <!-- success message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ __('messages.Employee ID') }}:<span class="text-danger">*</span></label>
                        <select class="form-control" disabled>
                            <option value="">{{ __('messages.Choose an Employee ID') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ __('messages.Employee Name') }}:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>{{ __('messages.Project Name') }}:<span class="text-danger">*</span></label>
                        <span style="font-size: 12px;" class="text-secondary">{{ __('messages.You can add new projects by clicking "+" button.') }}</span>
                        <div class="input-group">
                            <select class="form-control" disabled>
                                <option value="0">{{ __('messages.Choose a Project') }}</option>
                            </select>
                            <div class="input-group-prepend">
                                <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#plusModal">+</a>
                                <a class="btn btn-outline-secondary text-secondary" style="opacity: 0.7; pointer-events: none;">−</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ __('messages.Start Date') }}:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end-date">{{ __('messages.End Date') }}:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="files">{{ __('messages.Documentation') }}: </label>
                        <span style="font-size: 12px;" class="text-secondary">{{ __('messages.Note: File sizes should not be more than 10 MB.') }}</span>
                        <input class="form-control-file" type="file" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <button class="btn btn-primary btn-rounded px-5" disabled>{{ __('messages.Save') }}</button>
                    </div>
                </div>
            </form>
        @else
        <form action="{{ route('project-assignments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- success message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <!-- employee id and name -->
            <!-- employee_id error message -->
            @error('employee_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="employee-id">{{ __('messages.Employee ID') }}:<span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-control" id="employee-id">
                        <option value="0">{{ __('messages.Choose an Employee ID') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->employee_id }}" {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>{{ $employee->employee_id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="employee-name">{{ __('messages.Employee Name') }}:<span class="text-danger">*</span></label>
                    <input type="text" readonly maxlength="50" class="form-control" id="employee-name" name="employee_name">
                </div>
            </div>
            
            <!-- project name -->
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="project-name">{{ __('messages.Project Name') }}:<span class="text-danger">*</span></label>
                    <!-- start_date error message -->
                    @error('project_name')
                        <span class="text-danger ml-3">{{ $message }}</span>
                    @enderror
                    <div class="input-group">
                        <!-- if there is no project, cannot choose a project -->
                        @if ($projects->isEmpty())
                        <select class="form-control" id="select-options" name="project_name" disabled>
                            <option value="0">{{ __('messages.No Project to choose! Click "+" button to add new projects.') }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#plusModal">+</a>
                            @if($projects->isEmpty())
                            <a class="btn btn-outline-secondary" style="opacity: 0.7; text-decoration: none; cursor: not-allowed;" title="{{ __('messages.No project to remove!') }}" href="#" onclick="return false;">−</a>
                            @else
                            <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#minusModal">−</a>
                            @endif
                        </div>
                        @else
                        <select class="form-control" id="select-options" name="project_name">
                            <option value="0">{{ __('messages.Choose a Project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->name }}" {{ old('project_name') == $project->name ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-prepend">
                            <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#plusModal">+</a>
                            @if($projects->isEmpty())
                            <a class="btn btn-outline-secondary" style="opacity: 0.7; text-decoration: none; cursor: not-allowed;" title="{{ __('messages.No project to remove!') }}" href="#" onclick="return false;">−</a>
                            @else
                            <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#minusModal">−</a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- start date - end date -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="start-date">{{ __('messages.Start Date') }}:<span class="text-danger">*</span></label>
                    <!-- start_date error message -->
                    @error('start_date')
                        <span class="text-danger ml-3">{{ $message }}</span>
                    @enderror
                    <input type="date" class="form-control" id="start-date" name="start_date"  value="{{ old('start_date') }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="end-date">{{ __('messages.End Date') }}:<span class="text-danger">*</span></label>
                    <!-- end_date error message -->
                    @error('end_date')
                        <span class="text-danger ml-3">{{ $message }}</span>
                    @enderror
                    <input type="date" class="form-control" id="end-date" name="end_date" value="{{ old('end_date') }}">
                </div>
            </div>

            <!-- documentations -->
            <style>
                .file-preview {
                  margin-bottom: 5px;
                }
                .remove-button {
                  font-size: 12px;
                  padding: 2px 5px;
                  margin-left: 5px;
                  background-color: #f8f9fa;
                  color: #6c757d;
                  border: none;
                }  
                /* hide the "No file chosen" text */
                input[type="file"] {
                    color: transparent;
                }
              </style>
              
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>{{ __('messages.Documentation') }}:<span class="text-danger">*</span></label>
                    <!-- Documentation error messages -->
                    @if ($errors->has('files'))
                        @foreach ($errors->get('files') as $message)
                            <span class="text-danger ml-3">{{ $message }}</span><br>
                        @endforeach
                    @endif
                    <!-- Individual file error messages -->
                    @if ($errors->has('files.*'))
                        @foreach ($errors->get('files.*') as $messages)
                            @foreach ($messages as $message)
                                <span class="text-danger ml-3">{{ $message }}</span><br>
                            @endforeach
                        @endforeach
                    @endif
                    <span style="font-size: 12px;" class="text-secondary">{{ __('messages.Note: File sizes should not be more than 10 MB.') }}</span>
                    <input id="file-upload" class="form-control-file" type="file" name="files[]" multiple data-image="" onchange="handleFiles(event)">
                    <span id="chosen-files-count">No file chosen</span>
                    <div id="file-preview-container">
                        @php
                            $selectedFiles = session('selected_files');
                            $fileNames = old('selected_files', []);
                        @endphp
                        @if($selectedFiles)
                            @foreach($selectedFiles as $file)
                                <div class="file-preview">
                                    <input type="hidden" name="selected_files[]" value="{{ $file }}">
                                    <span>{{ $file }}</span>
                                    <button class="remove-button" onclick="removeFile(this)">Remove</button>
                                </div>
                            @endforeach
                        @endif
                        @foreach($fileNames as $fileName)
                            <div class="file-preview">
                                <input type="hidden" name="selected_files[]" value="{{ $fileName }}">
                                <span>{{ $fileName }}</span>
                                <button class="remove-button" onclick="removeFile(this)">Remove</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>                       
            <div class="form-row">
                <div class="form-group col-md-6">
                    <button type="submit" class="btn btn-primary btn-rounded px-5">{{ __('messages.Save') }}</button>
                </div>
            </div>
        </form>  
        @endif
    </div>
    
    <!-- modal for plus -->
    <div class="modal fade" id="plusModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header --> 
                <div class="modal-header">
                  <h5 class="modal-title">{{ __('messages.Project Registration') }}</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
          
                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- add new project form -->
                    <form action="{{ route('projects.store') }}?modalResult={modalResult}" method="POST" id="plusModalForm">
                        @csrf
                        <div class="form-group">
                            <label for="textbox">{{ __('messages.Name') }}:<span class="text-danger">*</span></label>
                            <!-- project name adding error message -->
                            @error('project_name_add')
                                <span class="text-danger ml-3">{{ $message }}</span>
                            @enderror
                            <input type="text" name="project_name_add" maxlength="100" class="form-control" id="textbox" placeholder="Enter project name">
                        </div>
                </div>
          
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 btn-rounded">{{ __('messages.Save') }}</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
        
    <!-- modal for minus -->
    <div class="modal fade" id="minusModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                  <h5 class="modal-title">{{ __('messages.Project Remove') }}</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    @if ($project && $project->id)
                        <form action="{{ route('projects.delete', ['id' => $project->id]) }}" method="POST" id="minusModalForm">
                    @else
                        <form action="" method="POST" id="minusModalForm">
                    @endif
                        @csrf
                        @method('DELETE')          
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}:<span class="text-danger">*</span></label>
                            <!-- project name removing error message -->
                            @if(session('error'))
                                <span class="text-danger">
                                    {{ session('error') }}
                                </span>
                            @endif
                            <select class="form-control" id="project-name" name="project_id">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger px-3 btn-rounded">{{ __('messages.Remove') }}</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    
    <script>
        // check if there are any errors related to plusModal
        @error('project_name_add')
            $('#plusModal').modal('show');
        @enderror

        // check if there are any errors related to minusModal
        @if(session('error'))
            $('#minusModal').modal('show');
        @endif

        // hide error messages after closed modals
        $('#plusModal').on('hidden.bs.modal', function () {
            // remove the error message related to project_name_add
            $('#plusModalForm .text-danger').remove();
        });
        $('#minusModal').on('hidden.bs.modal', function () {
            // remove the error message
            $('#minusModalForm .text-danger').remove();
        });

        // auto naming by filtering employee id performance
        document.addEventListener('DOMContentLoaded', function() {
        var employees = {!! json_encode($employees->toArray()) !!};

        document.getElementById('employee-id').addEventListener('change', function() {
            var employeeId = this.value;

            var selectedEmployee = employees.find(function(employee) {
                return employee.employee_id == employeeId;
            });

            if (selectedEmployee) {
                document.getElementById('employee-name').value = selectedEmployee.name;
            } else {
                document.getElementById('employee-name').value = '';
            }
        });

        // trigger the change event on page load
        document.getElementById('employee-id').dispatchEvent(new Event('change'));
        });      
    </script>
@endsection

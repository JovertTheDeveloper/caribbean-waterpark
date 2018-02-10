@extends('root.layouts.main')

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide"><i class="la la-gear"></i></span>

                    <h3 class="m-portlet__head-text">Edit category</h3>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('root.categories.update', $category->id) }}" id="form-category" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed m-form--state">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="m-portlet__body">
                <!-- Name -->
                <div class="form-group m-form__group row {{ $errors->has('name') ? 'has-danger' : '' }}">
                    <label for="name" class="col-lg-2 col-form-label">Name: </label>

                    <div class="col-lg-6">
                        <input type="text" name="name" id="name" class="form-control m-input {{ $errors->has('name') ?
                            'form-control-danger' :'' }}" placeholder="The name of the category" value="{{
                                $category->name }}">

                        <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                    </div>
                </div>
                <!--/. Name -->

                <!-- Description -->
                <div class="form-group m-form__group row {{ $errors->has('description') ? 'has-danger' : '' }}">
                    <label for="description" class="col-lg-2 col-form-label">Description</label>

                    <div class="col-lg-6">
                        <textarea name="description" id="description" class="summernote"
                            {{ $errors->has('description') ? 'form-control-danger' :'' }}>{{ $category->description }}
                        </textarea>

                        <div class="form-control-feedback">{{ $errors->first('description') }}</div>
                    </div>
                </div>
                <!--/. Description -->

                <!-- Bottom -->
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-brand">Update</button>
                                <a type="button" href="{{ route('root.categories.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/. Bottom -->
            </div>

        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var category = function () {
            var formValidationInit = function () {
                $("form[id=form-category]").validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },

                        description: {
                            maxlength: 500
                        },
                    },

                    //display error alert on form submit
                    invalidHandler: function(event, validator) {
                        mApp.scrollTo("form[id=form-category]");

                        swal({
                            "title": "",
                            "text": "There are some errors in your submission. Please correct them.",
                            "type": "error",
                            "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide"
                        });
                    },
                });
            }

            var descriptionInit = function () {
                $('.summernote').summernote({
                    height: 150
                });
            }
                
            return {
                init: function() {
                    descriptionInit();
                    formValidationInit();
                }
            };
        }();

        $(document).ready(function() {
            category.init();
        });
    </script>
@endsection
<div class="card mb-3">
    <div class="card-header">{{ __('lang.Create Post') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-2">
                <label for="title">{{ __('lang.Title') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                    id="title" value="{{ old('title') }}" placeholder={{ __('lang.Title') }} required />
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="body">{{ __('lang.Body') }} <span class="text-danger">*</span></label>
                <textarea class="form-control" name="body" id="body" placeholder={{ __('lang.Body') }} required>{{ old('body') }}</textarea>
                @error('body')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="photo" id="photo" />
                </div>
                @error('photo')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="bg-transparent border-top-0 d-flex justify-content-end">
                <button class="btn btn-primary">{{ __('lang.Create') }}</button>
            </div>
        </form>
    </div>
</div>

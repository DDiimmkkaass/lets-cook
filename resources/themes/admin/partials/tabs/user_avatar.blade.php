<div class="image-block">
    <div class="text-muted input-sm image-top-helper">
        @lang('labels.click_on_image_to_select_avatar')
    </div>

    <div class="preview-image profile-user-img img-circle"
         id="preview_image">

        @include('partials.image', ['src' => $model->avatar, 'attributes' => ['width' => 128, 'height' => 128, 'class' => 'img-responsive img-circle']])

        {!! Form::file('avatar', ['onchange' => 'loadImagePreview(this, \'preview_image\')']) !!}
    </div>

    <div class="text-muted input-sm remove-image @if (!$model->avatar) hidden @endif" data-preview_id="preview_image" data-image_input_id="avatar">
        @lang('labels.remove_avatar')
    </div>

    {!! Form::hidden('avatar', $model->avatar, ['id' => 'avatar']) !!}
</div>
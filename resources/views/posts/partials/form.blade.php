<div class="form-group">
    <label for="title">Title</label>
    <input id="title" type="text" name="title"  class="form-control" value="{{ old('title',optional( $post ?? null)->title)}}">
</div>
{{-- @error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror --}}
<div class="form-group">
    <label id="content">Content</label>
    <textarea id="content" name="content" class="form-control">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>

<div class="form-group">
    <label for="thumbnail">Thumbnail</label>
    <input id="thumbnail" type="file" name="thumbnail"  class="form-control-file">
</div>

@errors   
@enderrors
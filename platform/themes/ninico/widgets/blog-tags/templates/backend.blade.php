<div class="form-group">
    <label class="form-label" for="widget-name">{{ __('Name') }}</label>
    <input type="text" class="form-control" name="name" value="{{ Arr::get($config, 'name') }}">
</div>

<div class="form-group">
    <label class="form-label" for="limit">{{ __('Limit') }}</label>
    <input type="number" class="form-control" name="limit" value="{{ Arr::get($config, 'limit') }}">
</div>

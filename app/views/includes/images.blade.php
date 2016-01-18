<div class="form-group">
    <h3>Параметры</h3>
    <ul>
        <li>id: {{$image->id}}</li>
        <li>ширина: {{$image->width}}</li>
        <li>высота: {{$image->height}}</li>
    </ul>
</div>

<div class="form-group">
    <h3>Миниатюра</h3>
    {{HTML::image($image->thumbnail, null, ['width' => '50%', 'height' => '50%'])}}
</div>

<div class="form-group">
    <h3>Исходное</h3>
    {{HTML::link($image->regular)}}
</div>
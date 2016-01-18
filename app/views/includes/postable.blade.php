<div class="row">
    <div class="col-lg-12">
        <h1>Тело</h1>
    </div>

    <div class="col-lg-12">
        @include('includes.form.textarea', ['data' => ['text', $postable->text, 'Текст']])
    </div>

    @if(!$postable->geos->isEmpty())
        <section class="col-xs-4">
            <div class="form-group">
                <h2>Гео</h2>
                @foreach($postable->geos as $geo)
                    @include('includes.geo', compact('geo'))
                @endforeach
            </div>
        </section>
    @endif

    @if(!$postable->carsWithNumbers->isEmpty())
        <section class="col-xs-4">
            <div class="form-group">
                <h2>Машина с номером</h2>
                @foreach($postable->carsWithNumbers as $car)
                    @include('includes.car-number', compact('car'))
                @endforeach
            </div>
        </section>
    @endif

    @if(!$postable->cars->isEmpty())
        <section class="col-xs-4">
            <div class="form-group">
                <h2>Машина</h2>
                @foreach($postable->cars as $car)
                    @include('includes.car', compact('car'))
                @endforeach
            </div>
        </section>
    @endif

</div>

<div class="row">

    @if(!$postable->images->isEmpty())
        <section class="col-xs-4">
            <div class="form-group">
                <h2>Изображения</h2>
                @foreach($postable->images as $image)
                    @include('includes.images', compact('image'))
                @endforeach
            </div>
        </section>
    @endif
</div>

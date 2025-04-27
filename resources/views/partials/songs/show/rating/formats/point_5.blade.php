<form action="" method="post" id="rating-form" class="d-flex flex-column py-4" data-song="{{ $song->id }}">
    <style>
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 40px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }
    </style>
    <div class="rate">
        <input type="radio" id="star5" name="score" value="100" {{ $format_rating == 100 ? 'checked' : '' }} />
        <label for="star5" title="text">5 stars</label>
        <input type="radio" id="star4" name="score" value="80" {{ $format_rating == 80 ? 'checked' : '' }} />
        <label for="star4" title="text">4 stars</label>
        <input type="radio" id="star3" name="score" value="60" {{ $format_rating == 60 ? 'checked' : '' }} />
        <label for="star3" title="text">3 stars</label>
        <input type="radio" id="star2" name="score" value="40" {{ $format_rating == 40 ? 'checked' : '' }} />
        <label for="star2" title="text">2 stars</label>
        <input type="radio" id="star1" name="score" value="20" {{ $format_rating == 20 ? 'checked' : '' }} />
        <label for="star1" title="text">1 star</label>
    </div>
</form>

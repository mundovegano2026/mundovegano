<div class="rating">
    <span class="rating-title">{{ $title }}</span> 
    @if(isset($score) && $score > 0)
        <i class="{{ isset($small) && $small ? 'small' : '' }} glyphicon glyphicon-star {{ $score == '0.5' ? 'half' : ($score >= 1 ? 'full' : '')}}"></i>
        <i class="{{ isset($small) && $small ? 'small' : '' }} glyphicon glyphicon-star {{ $score == '1.5' ? 'half' : ($score >= 2 ? 'full' : '')}}"></i>
        <i class="{{ isset($small) && $small ? 'small' : '' }} glyphicon glyphicon-star {{ $score == '2.5' ? 'half' : ($score >= 3 ? 'full' : '')}}"></i>
        <i class="{{ isset($small) && $small ? 'small' : '' }} glyphicon glyphicon-star {{ $score == '3.5' ? 'half' : ($score >= 4 ? 'full' : '')}}"></i>
        <i class="{{ isset($small) && $small ? 'small' : '' }} glyphicon glyphicon-star {{ $score == '4.5' ? 'half' : ($score >= 5 ? 'full' : '')}}"></i>
        @if(isset($text_score) && $text_score && $score > 0)
            <small>( {{ $score }} em 5 )</small>
        @endif
    @else
        {{ $no_score_message }}
    @endif
</div>
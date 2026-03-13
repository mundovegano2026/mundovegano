<template>
    <div class="rating" :id="id">
        <i class="large glyphicon glyphicon-star" :class="[{small: smallClass, fixed: score, selected: score >= 1, half: score >= 0.5 && score < 1}]" data-pos="1" @click="starRate" @mouseenter="starOver" @mouseleave="starReset"></i>
        <i class="large glyphicon glyphicon-star" :class="[{small: smallClass, fixed: score, selected: score >= 2, half: score >= 1.5 && score < 2}]" data-pos="2" @click="starRate" @mouseenter="starOver" @mouseleave="starReset"></i>
        <i class="large glyphicon glyphicon-star" :class="[{small: smallClass, fixed: score, selected: score >= 3, half: score >= 2.5 && score < 3}]" data-pos="3" @click="starRate" @mouseenter="starOver" @mouseleave="starReset"></i>
        <i class="large glyphicon glyphicon-star" :class="[{small: smallClass, fixed: score, selected: score >= 4, half: score >= 3.5 && score < 4}]" data-pos="4" @click="starRate" @mouseenter="starOver" @mouseleave="starReset"></i>
        <i class="large glyphicon glyphicon-star" :class="[{small: smallClass, fixed: score, selected: score >= 5, half: score >= 4.5 && score < 5}]" data-pos="5" @click="starRate" @mouseenter="starOver" @mouseleave="starReset"></i>
    </div> 
</template>

<script>
export default {
    props: ['score','idKey', 'small', 'edit'],
    data() {
        return {
            rating: this.score,
            id: this.idKey,
            smallClass: this.small
        }
    },
    methods: {        
        starReset() {
            if(!this.score || this.edit)
                $('#' + this.id + '.rating .glyphicon-star').removeClass("full");
        },
        starOver(e) {
            if(!this.score || this.edit) {
                var curPosition = e.currentTarget.getAttribute('data-pos');
                for(var i = 0; i < curPosition; i++) {
                    $($('#' + this.id + '.rating .glyphicon-star')[i]).addClass('full');
                }
            }
        },
        starRate(e) {
            if(!this.score || this.edit) {
                $('#' + this.id + '.rating .glyphicon-star').removeClass('selected');
                var curPosition = e.currentTarget.getAttribute('data-pos');
                for(var i = 0; i < curPosition; i++) {
                    $($('#' + this.id + '.rating .glyphicon-star')[i]).addClass('selected');
                }
            }
        }
    }
}
</script>
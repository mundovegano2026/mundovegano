<template>
    <div style="cursor: pointer; padding-top: 15px">
        <a :href="`/categorias/${category.id}`" class="row table-row">
            <div class="col-md-3 table-col">
                <img style="width: 100%" :src="'/' + category.image">
            </div>     
            <div class="col-md-9 table-col" style="padding-left: 10px; text-align: left">
                {{ category.name.trim() }}
            </div>
        </a>
    </div>
    
</template>
<script>
export default {
    props: ['cat'],
    computed: {
        productCount() {
            return typeof this.category.productList != 'undefined' ? this.category.productList.length : 0
        }
    },
    data() {
        return {
            category: this.cat
        }
    }
}
</script>
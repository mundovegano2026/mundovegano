<template>
    <div style="cursor: pointer">
        <router-link :to="`/categorias/${category.id}`">
            <div class="col-md-6">
                <img style="width: 100%" :src="'/' + (category.image.indexOf('.')!=-1 ? category.image : 'storage/category_images/outros.png')">
            </div>     
            <div class="col-md-6 category-description">
                {{ category.name.trim() }}
                <p><small>{{ productCount + (!(productCount > 1) ? ' artigo' : ' artigos') }}</small></p>
            </div>
        </router-link>
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
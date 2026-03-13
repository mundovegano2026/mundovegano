<template>
    <div class="col-xs-12 col-sm-12 col-md-3">
        <router-link class="product-link" :to="`/artigos/${product.id}`">
        
            <div class="product-link-image-container">
                <span class="product-link-image-helper"></span>
                <img class="product-link-image" style="max-width: 100%" :src="'/' + product.image">
            </div>
            <div v-if="!dateOnly" class="product-link-label">
                <span class="badge badge-primary" style="margin-right: 5px">
                    <router-link :to="`/categorias-nome/${product.category.name}`">{{ product.category.name }}</router-link>
                </span>
            </div>
            <div class="product-link-title">{{ product.name }}</div>
            <div v-if="dateOnly"><i class="fa fa-calendar" style="margin-right: 7px"></i>{{ product.created_at }}</div>
            <div v-if="!dateOnly">{{ product.commentCount + (product.commentCount == 1 ? ' comentário' : ' comentários') }}</div>
            <div v-if="!dateOnly && !noDescription">{{product.obs | truncate(100, '...')}}</div>

        </router-link>
    </div>
</template>
<script>
export default {
    props: ['prod', 'date_only', 'no_description'],
    data() {
        return {
            product: this.prod,
            dateOnly: this.date_only,
            noDescription: this.no_description
        }
    },
    methods: {
        goToProduct() {
            window.location.href = '/artigos/' + this.prod.id
        }
    }
}
</script>
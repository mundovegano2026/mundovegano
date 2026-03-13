<template>
    <div v-if="dataLoaded">
        <section id="main" class="wrapper">
            <div class="inner">
            <header class="align-center">
                <h2>{{ brand.name }}</h2>

                <div class="row" v-for="group in visibleProductChunks" :key="group[0].id">
                    <product v-for="product in group" :key="product.id" :prod="product"></product>
                </div>
                                    
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li v-if="productChunksCurrentPage > 1" class="page-item"><a class="page-link" href="#!" @click="productChunksCurrentPage--">Anterior</a></li>
                        <template v-if="productChunksPageCount > 1">
                            <li class="page-item" v-for="page in productChunksPageCount" :class="[{ active: page == productChunksCurrentPage}]" :key="page"><a class="page-link" href="#!" @click="productChunksCurrentPage = page">{{ page }}</a></li>
                        </template>
                        <li v-if="productChunksPageCount > 1 && productChunksPageCount > productChunksCurrentPage" class="page-item"><a class="page-link" href="#!" @click="productChunksCurrentPage++">Próxima</a></li>
                    </ul>
                </nav>
            </header>

            </div>
        </section>
        

    </div>
    
</template>
<script>
    import Product from './inc/ProductComponent.vue';
    export default {
        data() {
            return {
                dataLoaded: false,
                productChunksCurrentPage: 1,
                productChunksPerPage: 3 // How many groups of 3 products
            }
        },
        components: {
            product: Product
        },
        computed: {
            brand() {
                return this.$store.getters.currentBrand;
            },
            groupedProducts() {
                return _.chunk(this.brand.productList, 4);
            },
            visibleProductChunks() {
                let endChunk = this.productChunksCurrentPage * this.productChunksPerPage;
                let firstChunk = endChunk - this.productChunksPerPage;
                return this.groupedProducts.slice(firstChunk, endChunk);
            },
            productChunksPageCount() {
                return Math.ceil(this.groupedProducts.length / this.productChunksPerPage);
            }
        },
        async created() {
            if(this.$route.params.id)
                this.$store.dispatch('fetchBrand', this.$route.params.id)
                .then(res=> {
                    this.dataLoaded = true;
                });
            else
                this.$store.dispatch('fetchBrandName', this.$route.params.name)
                .then(res=> {
                    this.dataLoaded = true;
                });;
        }
    }
   
</script>
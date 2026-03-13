<template>
    <div v-if="dataLoaded">
        <section id="main" class="wrapper">
            <div class="inner">
            <header class="align-center">
                <h2>{{ currentTag.name }}</h2>

                <div v-if="visibleProductChunks.length">
                    <div class="row" v-for="group in visibleProductChunks" :key="group[0].id">
                        <product v-for="product in group" :key="product.id" :prod="product"></product>
                    </div>
                </div>
                <div v-else>
                    <h4>Ainda não existem produtos com esta tag!</h4>
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
    import SubCategory from './inc/SubCategoryComponent.vue';
    import Product from './inc/ProductComponent.vue';
    export default {
        data() {
            return {
                currentTag: this.$store.getters.currentTag,
                dataLoaded: false,
                productChunksCurrentPage: 1,
                productChunksPerPage: 3 // How many groups of 3 products
            }
        },
        components: {
            subCategory: SubCategory,
            product: Product
        },
        watch: {
            '$store.getters.currentTag': function (id) {
                console.log("TESTE");
                console.log(this.$store.getters.currentTag);
                this.currentTag = this.$store.getters.currentTag;
            } 
        },
        computed: {
            // currentTag() {
            //     return this.$store.getters.currentTag;
            // },
            groupedProducts() {
                return _.chunk(this.currentTag.productList, 4);
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
        created() {

            this.$store.dispatch('fetchTag', this.$route.params.id)
            .then(res=> {
                this.dataLoaded = true;
            });
            
            // Remove modal transparency when accessed from modal
            $('.modal-backdrop').remove();
            $('.modal-open').removeClass('modal-open');
        }
    }
   
</script>
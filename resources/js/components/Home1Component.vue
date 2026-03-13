<template>

    <div>
        <section id="main" class="wrapper">
                <div class="inner">
                <header class="align-center">
                    <h2>CATEGORIAS</h2>

                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="carousel carousel-showmanymoveone slide" id="carousel-tilenav" data-interval="false">
                                <div class="carousel-inner">                                
                                    <div v-for="(category, index) in categories" :key="category.id" class="item" :class="{ active: index < 6}">
                                        <div class="col-xs-12 col-sm-6 col-md-15">
                                            <a href="#!" :catIndex="index" style="text-decoration: none" @click.prevent="showCategories">
                                                <img :src="category.image" class="img-responsive">
                                                <h4>{{ category.name }}</h4>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a :class="{ hidden: categories.length <= 6}" class="left carousel-control" href="#carousel-tilenav" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
                                <a :class="{ hidden: categories.length <= 6}" class="right carousel-control" href="#carousel-tilenav" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="row product-list hidden-sm hidden-xs" v-for="group in visibleProductChunks" :key="group[0].id">
                        <product v-for="product in group" :key="product.id" :prod="product" :no_description="true"></product>
                    </div>
                                        
                    <nav class="hidden-sm hidden-xs" aria-label="Page navigation example">
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
            
       <!-- Modal Sub Categories -->
        <div id="subCategoriesModal" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ currentSelectedCategory.name }}</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="list-group">
                            <a v-for="sub in currentSelectedCategory.subCategories" :catId="sub.id" :key="sub.id" href="#!" class="list-group-item" @click.prevent="showCategories">{{ sub.name }}</a>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
        

    </div>
    
</template>
<script>
    import Product from './inc/ProductComponent.vue';
    export default {
        data() {
            return {
                currentSelectedCategory: {
                    name: ''
                },
                groupedProducts: [],
                visibleProductChunks: [],
                productChunksPageCount: 0,
                productChunksCurrentPage: 1,
                productChunksPerPage: 3 // How many groups of 3 products
            }
        },
        components: {
            product: Product
        },
        watch: {
            '$store.getters.currentCategory': function() {

                // Concatenate category and subcategories products
                let productGroupList = [];
                productGroupList.concat(this.category.productList);
                if(this.category.subCategories != null) {
                    for(var i = 0; i < this.category.subCategories.length; i ++) {
                        for(var j = 0; j < this.category.subCategories[i].productList.length; j++) {
                            productGroupList.push(this.category.subCategories[i].productList[j]);
                        }
                    }
                }
                
                // Divide products in groups of 4
                this.groupedProducts = _.chunk(productGroupList, 4); 
                
                // Paginate
                let endChunk = this.productChunksCurrentPage * this.productChunksPerPage;
                let firstChunk = endChunk - this.productChunksPerPage;
                this.visibleProductChunks = this.groupedProducts.slice(firstChunk, endChunk);
                this.productChunksPageCount = Math.ceil(this.groupedProducts.length / this.productChunksPerPage);

            }
        },
        computed: {
            categories() {
                return this.$store.getters.mainCategories;
            },
            category() {
                return this.$store.getters.currentCategory;
            }
        },
        methods: {
            initCarousel() {
                var noItems = $('.carousel-showmanymoveone .item').length;
                var carousel = $('.carousel-showmanymoveone');
                if(this.categories.length > 6) {
                    carousel.find('.item').each(function(){
                        var itemToClone = $(this);

                        for (var i=1;i<noItems;i++) {
                            itemToClone = itemToClone.next();

                            // wrap around if at end of item collection
                            if (!itemToClone.length) {
                                itemToClone = $(this).siblings(':first');
                            }

                            // grab item, clone, add marker class, add to collection
                            itemToClone.children(':first-child').clone()
                                .addClass("cloneditem-"+(i))
                                .appendTo($(this));
                        }
                    });
                }
            },
            getCategoryById(id, categoryList) {

                let that = this;
                if(that.currentSelectedCategory != null) return;
                
                for(var i = 0; i < categoryList.length; i++) {
                    let category = categoryList[i];
                    console.log(category.id + " = " + id);
                    if(category.id == id) {
                        that.currentSelectedCategory = category;
                        return;
                    }
                    if(category.subCategories != null) {
                        return that.getCategoryById(id, category.subCategories);
                    }
                }

            },
            showCategories(e) {
                let category = this.categories[$(e.target).parent().attr('catindex')];
                if(typeof category == "undefined") {
                    // Search subcategory 
                    this.currentSelectedCategory = null;
                    this.getCategoryById($(e.target).attr('catID'), this.categories);  
                    if(this.currentSelectedCategory.subCategories == null) {
                        // Redirect to category page
                        $('#subCategoriesModal').modal('hide');
                        this.$router.push({ path: '/categorias/' + this.currentSelectedCategory.id });
                    }

                } else {
                    // Open category
                    this.currentSelectedCategory = category;
                    $('#subCategoriesModal').modal('show');
                }

            }
        },
        mounted() {
            this.initCarousel();
        },
        created() {
            if(typeof this.$store.getters.currentCategory.name != "undefined") {
                this.$store.dispatch('fetchCategory', this.$store.getters.mainCategories[0].id);
            }
        }
    }
   
</script>
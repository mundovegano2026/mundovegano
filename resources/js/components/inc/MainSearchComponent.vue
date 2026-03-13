<template>
    <form v-if="cat" action="" method="post" class="searchandfilter" @submit.prevent="doSearch">

        <div  class="row">
            <div class="col-xs-12 col-sm-12 col-md-5">
                <input type="text" name="main_search" id="main_search" v-model="searchPhrase" @keyup="fetchSuggestions" class="search-field" placeholder="Pesquisa um produto, marca, ..." autocomplete="off" />
                <div id="searchList" class="suggestion-list">
                    <div id="productList" class="suggestion-area" :class="{ active: productSearchCount > 0 }"><span class="suggestion-title">Artigos</span><span class="suggestion-results"></span></div>
                    <div id="brandList" class="suggestion-area" :class="{ active: brandSearchCount > 0 }"><span class="suggestion-title">Marcas</span><span class="suggestion-results"></span></div>
                    <div id="categoryList" class="suggestion-area" :class="{ active: categorySearchCount > 0 }"><span class="suggestion-title">Categorias</span><span class="suggestion-results"></span></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4">
                <select v-model="searchCategory" name="main_search_category" id="main_search_category" class="search-field">
                    <!-- <option selected="selected">Todas as categorias</option> -->
                    <option  v-for="category in hierarchicalCategories" :value="category.name.indexOf('Todas')>-1? '' : category.name" :key="category.id" :class="{ optionGroup: !category.subCategory, optionChild: category.subCategory }">{{ (category.subCategory ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '') + category.name }}</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3">
                <input type="button" class="search-button" @click="doSearch" value="Pesquisar">
            </div>
        </div>                 
    </form>   
    <div v-else>
        <div class="input-container">
            <input class="input-field" v-model="searchPhrase" @keypress="fetchSuggestions" type="text" placeholder="Artigo ou marca" name="main_search" id="main_search" autocomplete="off">
            <i class="fa fa-search icon"></i>
        </div>
        <div id="searchList" class="suggestion-list mt">
            <div id="productList" class="suggestion-area" :class="{ active: productSearchCount > 0 }"><span class="suggestion-title">Artigos</span><span class="suggestion-results"></span></div>
            <div id="brandList" class="suggestion-area" :class="{ active: brandSearchCount > 0 }"><span class="suggestion-title">Marcas</span><span class="suggestion-results"></span></div>
            <div id="categoryList" class="suggestion-area" :class="{ active: categorySearchCount > 0 }"><span class="suggestion-title">Categorias</span><span class="suggestion-results"></span></div>
        </div>
    </div>     
</template>
<script>
    import utils from '../../utils';
    export default {
        props: ['cat'],
        data() {
            return {
                validCategory: false,
                validProduct: false,
                validBrand: false,
                searchPhrase: '',
                searchCategory: '',
                categorySearchFinished: false,
                productSearchFinished: false,
                brandSearchFinished: false,
                displaySearchResult: false,
                categorySearchCount: 0,
                productSearchCount: 0,
                brandSearchCount: 0,
                cancelSourceCategory: null,
                cancelSourceProduct: null,
                cancelSourceBrand: null
            }
        },
        watch: {
            displaySearchResult: function(val) {
                if(val) 
                    $('#searchList').fadeIn();
                else
                    $('#searchList').fadeOut();
            },
            categorySearchFinished: function(val) {
                this.displaySearchResult = val && this.productSearchFinished && this.brandSearchFinished;
            },
            productSearchFinished: function(val) {
                this.displaySearchResult = val && this.categorySearchFinished && this.brandSearchFinished;
            },
            brandSearchFinished: function(val) {
                this.displaySearchResult = val && this.productSearchFinished && this.categorySearchFinished;
            }
        },
        computed: {
            hierarchicalCategories() {
                let categoryList = this.cat.filter(c=>c.level==1);

                // REACTIVATE TO INCLUDE SUBCATEGORIES!
                // for(var i = 0; i < categoryList.length; i++) {
                //     let category = categoryList[i];
                //     category.subCategory = false;
                //     let subCategoryList = this.cat.filter(s=>s.parent == category.id);
                //     for(var j = 0; j < subCategoryList.length; j++) {
                //         let subCategory = subCategoryList[j];
                //         subCategory.subCategory = true;
                //         categoryList.splice(i+1, 0, subCategory);
                //         i++;
                //     }
                // }
                console.log(categoryList);
                categoryList.unshift({
                    name: "Todas as categorias",
                    selected: true
                });
                return categoryList;
            }
        },
        methods: {
            doSearch() {             
                if(this.validCategory) {
                    this.$router.push({ path: '/categorias-nome/' + this.searchPhrase });
                }  else {
                    // Send for search algorithm
                    if(this.searchCategory) 
                        this.$router.push({path: '/pesquisa/' + this.searchPhrase + "/" + this.searchCategory });
                    else
                        this.$router.push({path: '/pesquisa/' + this.searchPhrase});
                }
            },
            fetchSuggestions() {
                
                let searchList = $('#searchList');
                let searchField = $('#main_search');
                searchList.width(searchField.outerWidth());

                this.displaySearchResult = false;    
                this.categorySearchFinished = false;
                this.brandSearchFinished = false;
                this.productSearchFinished = false;
                this.validCategory = false;
                this.validBrand = false;
                this.validProduct = false;

                if(this.searchPhrase.length < 3) return;

                this.fetchCategories();
                this.fetchBrands();
                this.fetchProducts();
            },
            fetchBrands() {
                
                /* Autocomplete Brand Suggestions */
                let that = this;
     
                this.cancelSearch(this.cancelSourceBrand);
                this.cancelSourceBrand = axios.CancelToken.source();
       
                axios.get('/api/brands/fetchname/' + that.searchPhrase, { 
                    cancelToken: this.cancelSourceBrand.token 
                })
                .then(res => {

                    let brandList = $('#brandList');
                    let searchField = $('#main_search');
                    
                    let suggestionList = '<hr class="search-separator"><ul class="dropdown-menu" style="display:block; position:relative; width: 100%">';

                    $(res.data).each(function(i, val){
                        suggestionList += '<li class="suggestion"><a class="suggestion-link brand-link">' + val.name + '</a></li>';                   
                    
                    }); 
                    suggestionList += '</ul>';
                    brandList.find('.suggestion-results').html(suggestionList);
                    this.brandSearchCount = res.data.length;
                    this.brandSearchFinished = true;

                    $('li.suggestion a.brand-link').click(function(e) {
                        let brand = $(e.currentTarget).text();
                        // searchField.val(brand);  
                        // that.searchPhrase = brand; 
                        // that.validBrand = true;
                        // this.displaySearchResult = false;                        
                        
                        // Redirect to Brands page
                        that.$router.push({path: '/marcas-nome/' + brand});
                    });
                })
                .catch(error => {
                    console.log(error);
                }); 
            },
            fetchProducts() {
                
                /* Autocomplete Category Suggestions */
                let that = this;
     
                this.cancelSearch(this.cancelSourceProduct);
                this.cancelSourceProduct = axios.CancelToken.source();
       
                axios.get('/api/products/fetchname/' + that.searchPhrase, { 
                    cancelToken: this.cancelSourceProduct.token 
                })
                .then(res => {

                    let productList = $('#productList');
                    let searchField = $('#main_search');
                    
                    let suggestionList = '<hr class="search-separator"><ul class="dropdown-menu" style="display:block; position:relative; width: 100%">';

                    $(res.data).each(function(i, val){
                        suggestionList += '<li class="suggestion"><a class="suggestion-link product-link">' + val.name + '</a></li>';                   
                    
                    }); 
                    suggestionList += '</ul>';
                    productList.find('.suggestion-results').html(suggestionList);
                    this.productSearchCount = res.data.length;
                    this.productSearchFinished = true;

                    $('li.suggestion a.product-link').click(function(e) {
                        let prod = $(e.currentTarget).text();
                        // searchField.val(prod);  
                        // that.searchPhrase = prod; 
                        // that.validProduct = true;
                        // this.displaySearchResult = false;                        
                        
                        // Redirect to products page
                        that.$router.push({path: '/artigos-nome/' + prod});
                    });
                })
                .catch(error => {
                    console.log(error);
                }); 
            },
            fetchCategories() {
                /* Autocomplete Category Suggestions */
                let that = this;
     
                this.cancelSearch(this.cancelSourceCategory);
                this.cancelSourceCategory = axios.CancelToken.source();
       
                axios.get('/api/categories/fetchname/' + that.searchPhrase, { 
                    cancelToken: this.cancelSourceCategory.token 
                })
                .then(res => {

                    let categoryList = $('#categoryList');
                    let searchField = $('#main_search');

                    let suggestionList = '<hr class="search-separator"><ul class="dropdown-menu" style="display:block; position:relative; width: 100%">';

                    $(res.data).each(function(i, val){
                        suggestionList += '<li class="suggestion"><a class="suggestion-link category-link">' + val.name + '</a></li>';                   
                    
                    }); 
                    suggestionList += '</ul>';
                    categoryList.find('.suggestion-results').html(suggestionList);
                    this.categorySearchCount = res.data.length;
                    this.categorySearchFinished = true;

                    $('li.suggestion a.category-link').click(function(e) {
                        let cat = $(e.currentTarget).text();
                        // Enable staying on same page
                        // searchField.val(cat);  
                        // that.searchPhrase = cat; 
                        // that.validCategory = true;
                        // this.displaySearchResult = false;

                        // Redirect to category page
                        that.$router.push({path: '/categorias-nome/' + cat});
                    });
                })
                .catch(error => {
                    console.log(error);
                });                 
                
            },
            cancelSearch (source) {
                if (source) {
                    source.cancel('Start new search, stop active search');
                }
            }
        },
        created() {
        }
    }
</script>
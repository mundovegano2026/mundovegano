<template>
    
    <div>
        <!-- Search Input -->
        <!-- <div class="input-container">
            <input class="input-field" type="text" placeholder="Artigo ou marca" name="search">
            <i class="fa fa-search icon"></i>
        </div> -->
        <appMainSearch></appMainSearch>

        <!-- Category List -->
        <ul style="padding-left: 0">
            CATEGORIAS 
            <listCategory v-for="cat in categories" :key="cat.id" :cat="cat"></listCategory>         
        </ul>
    </div>
        
</template>

<script>
    import ListCategory from './ListCategoryComponent.vue';
    import MainSearch from './MainSearchComponent.vue';

    export default {
        props: ['categories'],
        components: {
            listCategory: ListCategory,
            appMainSearch: MainSearch
        }
    }
   
</script>
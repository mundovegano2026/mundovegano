<template>
    <div class="row page-content">
        <div class="main-content col-md-8">

            <h2>Despesas do Mundo Vegano</h2>
            
            <div style="margin-top: 30px" class="instructions-paragraph" v-html="accounting.value"></div>
          

        </div>

        <!-- SIDEBAR -->
        <div class="sidebar hidden-xs hidden-sm col-md-4">

            <!-- Search Input
            <div class="input-container">
                <input class="input-field" type="text" placeholder="Username" name="usrnm">
                <i class="fa fa-search icon"></i>
            </div> -->

            <!-- Category List -->
            <sidebar :categories="categories"></sidebar>

        </div>
         
        
    </div>
</template>

<script>
    import SideBar from './inc/SideBarComponent.vue';
    import utils from '../utils';

    export default {
        data() {
            return {
            }
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                return this.$store.getters.user;
            },
            categories() {
                return this.$store.getters.mainCategories;
            },
            accounting() {
                return this.$store.getters.accounting;
            },
            contactRegistered() {
                return this.$store.getters.contactRegistered;
            }
        },
        methods: {
            getAccounting: function() {
                
                this.$store.dispatch('fetchAccounting');

            }
        },
        components: {
            sidebar: SideBar
        },
        created() {
            this.getAccounting();
        }
    }
   
</script>
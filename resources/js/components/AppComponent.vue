<template>

    <div :id="pageId" :class="{ subpage: isSubpage, catmenu: isCategoriesMenu}" style="width: 100vw !important">

        <!-- <navbar></navbar> -->
        <component :class="{ design1: this.$router.history.current.name == 'Home1' || this.$router.history.current.name == 'CategoriesMenu' }" :is="this.currentHeader"></component>        

        <div v-if="this.$router.history.current.name != 'Home1'" class="container content-wrapper">
            <div class="row">
                <div class="col-xs-12">
                    <transition name="slide-fade" mode="out-in">
                        <router-view :key="$route.params.id"></router-view>
                    </transition>
                </div>
            </div>                
        </div>

        <footer id="footer">
            <div class="inner">
                <div class="copyright">
                    &copy; Mundo Vegano 2020. - <router-link to="/termos">Política de Privacidade.</router-link>
                </div>

            </div>
        </footer>
    </div>
</template>
<script>
    import HomeNavBar from './HomeNavbarComponent.vue';
    import HomeNavBarFullScreen from './HomeNavbarComponentFullScreen.vue';
    import NavBar from './NavbarComponent.vue';
    
    export default {
        watch:{
            $route (to, from){
                this.currentHeader = this.$router.history.current.name == "Home" ? 'appHeaderHome' : (this.$router.history.current.name == "Home1" || this.$router.history.current.name == "CategoriesMenu" ? 'appHeaderHomeFullScreen' : 'appHeader');
                this.isSubpage = this.$router.history.current.name == "Home" || this.$router.history.current.name == "Home1" || this.$router.history.current.name == "CategoriesMenu" ? false : true;
                this.pageId = (this.$router.history.current.name == 'Home1' || this.$router.history.current.name == 'CategoriesMenu') ? 'banner1' : '';
                this.isCategoriesMenu = this.$router.history.current.name == 'CategoriesMenu' ? true : false;
            }
        },
        components: {
            appHeaderHome: HomeNavBar,
            appHeaderHomeFullScreen: HomeNavBarFullScreen,
            appHeader: NavBar
        },
        data() {
            return {
                pageId: this.$router.history.current.name == 'Home1' || this.$router.history.current.name == 'CategoriesMenu' ? 'banner1' : '',
                currentHeader: this.$router.history.current.name == "Home" ? 'appHeaderHome' : (this.$router.history.current.name == "Home1" || this.$router.history.current.name == "CategoriesMenu" ? 'appHeaderHomeFullScreen' : 'appHeader'),
                isSubpage: this.$router.history.current.name == "Home" || this.$router.history.current.name == "Home1" || this.$router.history.current.name == "CategoriesMenu" ? false : true,
                isCategoriesMenu: this.$router.history.current.name == 'CategoriesMenu' ? true : false
            }
        },
        created() {
            this.$store.dispatch('initCategories');
            this.$store.dispatch('initNewProducts');
            $('document').ready(function() {
                // Resize dropdown list option width
                $('ul.dropdown-menu').each(function(i, el) {
                    let dropDown = $(el);
                    let parentMenu = dropDown.prev();
                    let newWidth = parentMenu.outerWidth() + parseInt(parentMenu.css('padding-left').replace('px','')) * 2;
                    console.log(newWidth + 'px');
                    dropDown.css('min-width', newWidth + 'px');
                });
            });             
            
            $('body').click(function(evt){    
    
                if($(evt.target).hasClass('suggestion')){
                    return;
                }        
        
                $('#brandList').fadeOut(); 
                $('#storeList').fadeOut();  
                $('#searchList').fadeOut(); 
                
                // Close menu
                var clickover = $(event.target);
                console.log(clickover);
                console.log(clickover.hasClass("dropdown"));
                var _opened = $("#navbarCollapse").hasClass("show");
                if (_opened === true && !clickover.hasClass("navbar-toggle") && !clickover.hasClass("icon-bar") && !clickover.hasClass("dropdown")) {
                    $("#navbarCollapse").removeClass('show');
                    $("#navbarCollapse").removeClass('in');
                }
        
            });
            
            $('body').on('click', '.stopProp', function(e){
                e.stopPropagation();
                let el = $(e.target);
                if(!el.hasClass('stopProp')) {
                    el = el.parents('.stopProp').first();
                }
                let dropDownMenu = el.find('.dropdown-menu').first();
                dropDownMenu.toggleClass('show');
                dropDownMenu.off('click').click(function() {
                    $("#navbarCollapse").removeClass('show');
                    $("#navbarCollapse").removeClass('in');
                });
            });

        }
    }
</script>
import Home from './components/HomeComponent.vue';
import Home1 from './components/Home1Component.vue';
import NewProduct from './components/NewProductComponent.vue';
import Tags from './components/TagsComponent.vue';
import Categories from './components/CategoriesComponent.vue';
import CategoriesMenu from './components/CategoriesMenuComponent.vue';
import ProductDetail from './components/ProductDetailComponent.vue';
import BrandDetail from './components/BrandDetailComponent.vue';
import Login from './components/LoginComponent.vue';
import Support from './components/SupportComponent.vue';
import Accounting from './components/AccountingComponent.vue';
import Register from './components/RegisterComponent.vue';
import About from './components/AboutComponent.vue';
import Contact from './components/ContactComponent.vue';
import Forum from './components/ForumComponent.vue';
import Topic from './components/TopicComponent.vue';
import Post from './components/PostComponent.vue';
import ResultList from './components/ResultListComponent.vue';
import UserDetails from './components/UserDetailsComponent.vue';
import Forgotpwd from './components/Forgotpwd.vue';
import Recoverpwd from './components/RecoverpwdComponent.vue';
import Terms from './components/TermsComponent.vue';
import Manual from './components/ManualComponent.vue';

export const routes = [
    { path: '/', component: Home, name: 'Home', type: 'Landing' }, 
    { path: '/design1', component: Home1, name: 'Home1', type: 'Landing' },    
    { path: '/novo', component: NewProduct, name: 'NewProduct', type: 'Generic' },    
    { path: '/tags/:id', component: Tags, name: 'Tags', type: 'Generic' },  
    { path: '/categorias/:id', component: Categories, name: 'Category', type: 'Generic' },  
    // { path: '/categorias', component: Home, name: 'Category', type: 'Generic' },  
    { path: '/categorias', component: CategoriesMenu, name: 'CategoriesMenu', type: 'Landing' },  
    { path: '/categorias-nome/:name', component: Categories, name: 'CategoryName', type: 'Generic' },    
    { path: '/artigos/:id', component: ProductDetail, name: 'Product', type: 'Generic' },  
    { path: '/artigos-nome/:name', component: ProductDetail, name: 'Product', type: 'Generic' },
    { path: '/marcas-nome/:name', component: BrandDetail, name: 'Brand', type: 'Generic' },
    { path: '/login', name: 'Login', component: Login},
    { path: '/despesas', name: 'Accounting', component: Accounting},
    { path: '/apoiar', name: 'Support', component: Support},
    { path: '/conta', name: 'UserDetails', component: UserDetails},
    { path: '/registar', name: 'Register', component: Register},
    { path: '/sobre', name: 'About', component: About},
    { path: '/contacto', name: 'Contact', component: Contact},
    // { path: '/forum', name: 'Forum', component: Forum},
    { path: '/topico/:board_id/:board_name', name: 'Topic', component: Topic},
    { path: '/pesquisa/:searchText', name: 'ResultList', component: ResultList},
    { path: '/pesquisa/:searchText/:searchCategory', name: 'ResultListCategory', component: ResultList},
    { path: '/post/:post_id/:post_title', name: 'Post', component: Post},
    { path: '/forgotpwd', name: 'Forgotpwd', component: Forgotpwd},
    { path: '/recoverpwd', name: 'Recoverpwd', component: Recoverpwd},
    { path: '/termos', name: 'Terms', component: Terms},
    { path: '/manual', name: 'Manual', component: Manual}
];
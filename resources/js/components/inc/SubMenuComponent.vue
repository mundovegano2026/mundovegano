<template>
    <ul v-if="itemList.length" class="submenu dropdown-menu">
        <li v-for="item in itemList" :key="item.id">
            <router-link :to="linkPath + item.id" class="dropdown-item">{{ item.name }}</router-link>
            <app-sub-menu v-if="item.subCategories" :list="item.subCategories" link="/categorias/"></app-sub-menu>
        </li>
    </ul>
</template>
<script>
    export default {
        name: "app-sub-menu",
        props: ['list', 'link'],
        data() {
            return {
                itemList: this.list,
                linkPath: this.link
            }
        }
    }
</script>
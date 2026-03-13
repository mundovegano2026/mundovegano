<template>
    <div class="page-content main-content">
          
        <h2>Fóruns</h2>
    
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Tópico</th>
                    <th>Artigos</th>
                    <th class="hidden-xs">Data</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="board in boards" :key="board.id">
                    <td><router-link :to="'/topico/' + board.id + '/' + board.name">{{ board.name }}</router-link></td>
                    <td class="hidden-xs">{{ board.posts + (board.posts == 1 ? " publicação" : " publicações") }}</td>
                    <td class="visible-xs">{{ board.posts }}</td>
                    <td class="hidden-xs">{{ board.last_date }} </td>
                </tr>
                </tbody>
            </table>

    </div>
</template>

<script>

    export default {
        data() {
            return {
            }
        },
        watch: {
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                return this.$store.getters.user;
            },
            boards() {
                return this.$store.getters.boards;
            }
            
        },
        methods: {   
        }, 
        created() {

            let that = this;
            this.$store.dispatch('fetchBoards');

        }
    }
   
</script>
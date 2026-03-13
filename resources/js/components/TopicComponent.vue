<template>
    <div class="page-content main-content">
          
        <h2>{{ this.$route.params.board_name }}</h2>
    
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Publicação</th>
                    <th>Artigos</th>
                    <th class="hidden-xs">Data</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="post in visiblePosts" :key="post.id">
                    <td><router-link :to="'/post/' + post.id + '/' + post.title">{{ post.title }}</router-link></td>
                    <td>{{ post.commentsCount }}</td>
                    <td class="hidden-xs">{{ post.newest }}</td>
                </tr>
                </tbody>
            </table>
                    
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li v-if="postsCurrentPage > 1" class="page-item"><a class="page-link" href="#!" @click="postsCurrentPage--">Anterior</a></li>
                    <template v-if="postsPageCount > 1">
                        <li class="page-item" v-for="page in postsPageCount" :class="[{ active: page == postsCurrentPage}]" :key="page"><a class="page-link" href="#!" @click="postsCurrentPage = page">{{ page }}</a></li>
                    </template>
                    <li v-if="postsPageCount > 1 && postsPageCount > postsCurrentPage" class="page-item"><a class="page-link" href="#!" @click="postsCurrentPage++">Próxima</a></li>
                </ul>
            </nav>

            <form v-if="!postRegistered && isLogged" class="post-form" type="multipart/form-data" @submit.prevent="submitPost" style="margin-top: 25px">  

                <h3>Partilha uma nova questão</h3>
                <!-- Name -->  
                <div class="form-group">
                    <input type="text" class="form-control input-lg" name="title" id="title" v-model="comment.title" placeholder="Título">
                </div>

                <!-- Message -->  
                <div class="form-group">
                    <textarea id="body" name="body" v-model="comment.body" placeholder="Texto"></textarea>
                </div>

                <button type="submit">PUBLICAR</button>

            </form>
            <div v-if="postRegistered"><h3>Obrigado pela contribuição!</h3></div>
            <div v-if="!isLogged"><router-link to="/login">Inicia sessão para submeter uma questão</router-link></div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                comment: {
                    title: '',
                    body: '',
                    forum_post_parent_id: 0,
                    forum_board_id: this.$route.params.board_id
                },
                postsCurrentPage: 1,
                postsPerPage: 5
            }
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                return this.$store.getters.user;
            },
            posts() {
                return this.$store.getters.forum_posts;
            },
            postRegistered() {
                return this.$store.getters.postRegistered;
            },
            visiblePosts() {
                let endPost = this.postsCurrentPage * this.postsPerPage;
                let firstPost = endPost - this.postsPerPage;
                return this.posts.slice(firstPost, endPost);
            },
            postsPageCount() {
                return Math.ceil(this.posts.length / this.postsPerPage);
            }
            
        },
        methods: {  
            submitPost() {
      
                let that = this;

                const formData = new FormData();
                formData.append('post', JSON.stringify(this.comment));
                formData.append('user', JSON.stringify(this.user));

                let requestURL = this.isLogged ? '/api/post' : '/api/postguest';
                // Send register request
                axios({
                    method: 'post',
                    url: requestURL,
                    data: formData,
                    headers: {'content-type': `multipart/form-data; boundary=${formData._boundary}` }
                })
                .then(res => {
                    
                    if(typeof res.data != 'undefined' && typeof res.data != 'undefined') {
                        if(res.data.error) {
                            utils.addVueFlash("danger", ".subpage", res.data.error);
                        } else {
                            this.$store.dispatch('setPost', res.data.post);
                        }
                    } else {
                        utils.addVueFlash("danger", ".subpage", "Erro ao registar publicação.");
                    }
                    
                })
                .catch(error => {
                   console.log(error);
                   utils.addVueFlash("danger", ".subpage", "Erro ao registar publicação.");
              
                });

            }   
        }, 
        created() {

            let that = this;
            this.$store.dispatch('fetchPosts', this.$route.params.board_id);

        }
    }
   
</script>
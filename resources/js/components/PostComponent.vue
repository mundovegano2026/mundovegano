<template>
    <div class="page-content main-content">

        <h3>{{ post.path }}</h3>

        <h2>{{ this.$route.params.post_title }}</h2>
    
        <div v-if="post" class="post-body">
            {{ post.body }}
        </div>

        <h3 v-if="post.comments">Respostas</h3>      

        <div v-for="comment in visibleComments" :key="comment.id" class="card mb-2 post-card">
            <div class="card-body p-2 p-sm-3">
                <div class="media forum-item">
                   <div class="media-body">
                        <h6><a href="#" data-toggle="collapse" data-target=".forum-content" class="text-body">{{ comment.user }}</a></h6>
                        <p class="text-secondary">
                            {{ comment.body }}
                        </p>
                        <p class="text-muted">Enviado a <span class="text-secondary font-weight-bold">{{ comment.created_at }}</span></p>
                    </div>
                </div>
            </div>
        </div>      

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-if="commentsCurrentPage > 1" class="page-item"><a class="page-link" href="#!" @click="commentsCurrentPage--">Anterior</a></li>
                <template v-if="commentsPageCount > 1">
                    <li class="page-item" v-for="page in commentsPageCount" :class="[{ active: page == commentsCurrentPage}]" :key="page"><a class="page-link" href="#!" @click="commentsCurrentPage = page">{{ page }}</a></li>
                </template>
                <li v-if="commentsPageCount > 1 && commentsPageCount > commentsCurrentPage" class="page-item"><a class="page-link" href="#!" @click="commentsCurrentPage++">Próxima</a></li>
            </ul>
        </nav>

        <form v-if="!commentRegistered && isLogged" class="post-form" type="multipart/form-data" @submit.prevent="submitComment" style="margin-top: 25px">  

            <h3>Deixa uma resposta</h3>
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

        <div v-if="commentRegistered"><h3>Obrigado pela contribuição!</h3></div>            
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
                    forum_post_parent_id: this.$route.params.post_id,
                    forum_board_id: 0
                },
                commentsCurrentPage: 1,
                commentsPerPage: 5
            }
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                return this.$store.getters.user;
            },
            post() {
                return this.$store.getters.forum_post;
            },
            commentRegistered() {
                return this.$store.getters.commentRegistered;
            },
            visibleComments() {
                let endComment = this.commentsCurrentPage * this.commentsPerPage;
                let firstComment = endComment - this.commentsPerPage;
                return this.post.comments.slice(firstComment, endComment);
            },
            commentsPageCount() {
                return Math.ceil(this.post.comments.length / this.commentsPerPage);
            }
            
        },
        methods: {  
            submitComment() {
      
                let that = this;

                const formData = new FormData();
                this.comment.forum_board_id = this.post.forum_board_id;
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
                            this.$store.dispatch('setComment', res.data.post);
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
            this.$store.dispatch('fetchPost', this.$route.params.post_id);

        }
    }
   
</script>
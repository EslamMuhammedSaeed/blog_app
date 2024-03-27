<template>
    <div class="card mb-3" v-for="post in posts" :key="post.id">
        <div class="card-header d-flex justify-content-end">
            <div v-if="user == post.user_id">
                <button
                    v-if="postEdit != post.id"
                    class="btn btn-primary mx-2"
                    @click="edit(post.id)"
                >
                    {{ lang.edit }}
                </button>
                <!-- <button class="btn btn-primary mx-2" @click="edit(post.id)">
                    Edit
                </button> -->
                <button class="btn btn-danger" @click="remove(post.id)">
                    {{ lang.delete }}
                </button>
            </div>
        </div>

        <div class="card-body" v-if="postEdit != post.id">
            <h4>
                {{ post.title }}
            </h4>
            <div class="mb-2">
                {{ post.body }}
            </div>
            <div v-if="post.photo">
                <img class="img-fluid" :src="post.photo" />
            </div>
        </div>

        <div class="card-body" v-if="postEdit == post.id">
            <form @submit.prevent="submit(post.id)">
                <div class="form-group mb-2">
                    <label for="title">{{ lang.Title }}</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="fields.title"
                        id="title"
                        required
                    />
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                </div>
                <div class="form-group mb-2">
                    <label for="title">{{ lang.Body }}</label>
                    <textarea
                        class="form-control"
                        v-model="fields.body"
                        id="body"
                        required
                    ></textarea>
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                </div>
                <div class="form-group mb-3">
                    <div class="custom-file">
                        <input
                            type="file"
                            class="custom-file-input"
                            name="photo"
                            id="photo"
                            v-on:change="onFileChange"
                        />
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary mb-2" type="submit">
                        {{ lang.edit }}
                    </button>
                </div>

                <div v-if="post.photo">
                    <img class="img-fluid" :src="post.photo" />
                </div>
            </form>
        </div>
        <div class="card-footer">
            <form
                class="mb-2"
                @submit.prevent="storeComment(post.id)"
                v-if="user != post.user_id"
            >
                <div class="d-flex justify-content-between">
                    <input class="form-control" type="text" v-model="comment" />
                    <input
                        class="btn btn-primary mx-1"
                        type="submit"
                        value="Comment"
                    />
                </div>
            </form>
            <div
                class="d-flex align-items-center w-100 justify-content-between border-top"
                v-for="comment in post.comments"
                :key="comment.id"
            >
                <div
                    class="d-flex justify-content-start align-items-center mx-2"
                    v-if="user == post.user_id"
                >
                    <input
                        type="checkbox"
                        id="checkbox"
                        v-model="checked[comment.id]"
                        v-on:change="removeComment(post.id, comment.id)"
                    />
                </div>
                <div
                    class="d-flex align-items-center text-muted m-0 py-2 w-100"
                >
                    {{ `${comment.user.name} : ${comment.body}` }}
                </div>

                <p class="text-muted m-0 py-2 w-100">
                    {{ comment.created_at }}
                </p>
            </div>
            <div
                class="d-flex justify-content-end"
                v-if="
                    deletedCommentsPostId == post.id &&
                    user == post.user_id &&
                    checkedComments.length > 0
                "
            >
                <button class="btn btn-danger" @click="deleteComments">
                    {{ lang.delete_comments }}
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            errors: {},
            success: false,
            posts: {},
            user: window.user_id,
            postEdit: null,
            photo: null,
            comment: null,
            checked: [],
            checkedComments: [],
            deletedCommentsPostId: null,
            lang: null,
        };
    },

    mounted() {
        axios.get("/api/posts").then((response) => {
            this.posts = response.data;
        });
        this.lang = window.i18n.lang;
        console.log(this.lang);
    },
    methods: {
        onFileChange(e) {
            console.log(e.target.files[0]);
            this.photo = e.target.files[0];
        },
        submit(id) {
            const config = {
                headers: { "content-type": "multipart/form-data" },
            };

            let formData = new FormData();
            formData.append("photo", this.photo);
            formData.append("title", this.fields.title);
            formData.append("body", this.fields.body);
            axios
                .post(`/posts/${id}`, formData, config)
                .then(() => {
                    this.postEdit = null;
                    axios.get("/api/posts").then((response) => {
                        this.posts = response.data;
                    });
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },

        edit(id) {
            console.log("edit");
            this.postEdit = id;
            this.fields = this.posts.find((post) => {
                return post.id == id;
            });
        },
        remove(postId) {
            axios
                .delete("/api/posts/" + postId)
                .then(() => {
                    this.errors = {};
                    this.success = true;

                    axios.get("/api/posts").then((response) => {
                        this.posts = response.data;
                    });
                    setInterval(() => {
                        this.success = false;
                    }, 2500);
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },
        storeComment(postId) {
            axios
                .post("/comments", {
                    body: this.comment,
                    post_id: postId,
                })
                .then(() => {
                    this.comment = "";
                    axios.get("/api/posts").then((response) => {
                        this.posts = response.data;
                    });
                });
        },
        removeComment(postId, commentId) {
            this.deletedCommentsPostId = postId;
            if (this.checked[commentId]) {
                let commentIndex = this.checkedComments.indexOf(commentId);
                if (commentIndex == -1) {
                    this.checkedComments.push(commentId);
                }
            } else {
                let commentIndex = this.checkedComments.indexOf(commentId);
                if (commentIndex > -1) {
                    this.checkedComments.splice(commentIndex, 1);
                }
            }
            console.log(
                postId,
                commentId,
                this.checkedComments,
                this.deletedCommentsPostId
            );
        },
        deleteComments() {
            axios
                .post("/comments/delete", {
                    comments: this.checkedComments,
                    post_id: this.deletedCommentsPostId,
                })
                .then(() => {
                    this.deletedCommentsPostId = null;
                    axios.get("/api/posts").then((response) => {
                        this.posts = response.data;
                    });
                });
        },
    },
};
</script>

<style scoped></style>

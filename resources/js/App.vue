<template>
    <div>
        <posts-index></posts-index>
    </div>
</template>

<script>
import postsIndex from "./components/PostsIndex.vue";

// const _ = import("lodash");
// Vue.prototype.trans = (string) => _.get(window.i18n, string);
export default {
    mounted() {
        console.log("Component mounted.");
    },
    components: {
        "posts-index": postsIndex,
    },
};
</script>

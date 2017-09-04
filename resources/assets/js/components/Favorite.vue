<template>
    <button :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>
<script>
    export default {
        props:['reply'],
        computed: {
            classes () {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default']
            },
            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },
        data () {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },
        methods: {
            toggle() {
                this.isFavorited ? this.destroy() : this.create();
            },
            destroy () {
                axios.delete(this.endpoint);
                    this.favoritesCount--;
                    this.isFavorited = false;
            },
            create() {
                axios.post(this.endpoint);
                    this.isFavorited = true;
                    this.favoritesCount++;
            }

        }
    }
</script>

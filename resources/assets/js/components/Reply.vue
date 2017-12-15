<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="'/profiles/'+reply.owner.name" v-text="reply.owner.name"></a> said <span v-text="ago"></span>
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form v-on:click="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-links" v-on:click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else>
                <div class="body" v-html="body"></div>
            </div>
        </div>
        <div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-xs mr-1" v-on:click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" v-on:click="destroy">Delete</button>
            </div>
            <button class="btn btn-default btn-xs ml-a" v-on:click="markBestReply" v-if="authorize('owns', reply.thread)" v-show="!isBest">Best Reply?</button>
        </div>
    </div>
</template>
<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    export default {
        props: ['reply'],
        components: { Favorite },
        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow();
            }
        },
        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            }
        },
        created(){
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(error.response.reply, 'danger');
                });

                this.editing = false;
                flash('changes saved sucessfully');
            },
            destroy() {
                axios.delete('/replies/' + this.id);
                this.$emit('deleted', this.id);
            },
            markBestReply() {
                axios.post('/replies/' + this.id + '/best');
                window.events.$emit('best-reply-selected', this.id);  
            }
        }
    }
</script>

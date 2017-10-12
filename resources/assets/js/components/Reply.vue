<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a> said <span v-text="ago"></span>
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="this.data"></favorite>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form v-on:click="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-links" v-on:click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else>
                <div class="body" v-html="body"></div>
            </div>
        </div>
        <div class="panel-footer level">
            <div v-if="canUpdate">
                <button class="btn btn-xs mr-1" v-on:click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" v-on:click="destroy">Delete</button>
            </div>
            <button class="btn btn-default btn-xs ml-a" v-on:click="markBestReply" v-show="!isBest">Best Reply?</button>
        </div>
    </div>
</template>
<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    export default {
        props: ['data'],
        components: { Favorite },
        computed: {
            ago() {
                return moment(this.data.created_at).fromNow();
            },
            signedIn() {
                return window.App.signedIn;
            },
            canUpdate() {
                return this.authorize(user => this.data.user_id == window.App.user.id);
            }
        },
        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: false 
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });

                this.editing = false;
                flash('changes saved sucessfully');
            },
            destroy() {
                axios.delete('/replies/' + this.data.id);
                this.$emit('deleted', this.data.id);
            },
            markBestReply() {
                this.isBest = true;
            }
        }
    }
</script>

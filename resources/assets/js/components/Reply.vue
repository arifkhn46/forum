<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a> said {{ data.created_at }}
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="this.data"></favorite>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" v-on:click="update">Update</button>
                <button class="btn btn-xs btn-links" v-on:click="editing = false">Cancel</button>
            </div>
            <div v-else>
                <div class="body" v-text="body"></div>
            </div>
        </div>
        <div class="panel-footer level" v-if="canUpdate">
            <button class="btn btn-xs mr-1" v-on:click="editing = true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1" v-on:click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
    import Favorite from './Favorite.vue';
    export default {
        props: ['data'],
        components: { Favorite },
        computed: {
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
                body: this.data.body
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;
                flash('changes saved sucessfully');
            },
            destroy() {
                axios.delete('/replies/' + this.data.id);
                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>

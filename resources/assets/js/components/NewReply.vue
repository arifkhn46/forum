<template>
	<div>
		<div <div v-if="signedIn">
	        <div class="form-group">
	            <textarea name="body"
	            		  id="body"
	            		  class="form-control"
	            		  rows="5"
	                      placeholder="Have something to say?"
	                      v-model="body"
	                      required>
	                      </textarea>
	        </div>
	        <button class="btn btn-default" type="submit" @click="addReply">Post</button>
	     </div>
	    <p class="text-center" v-else>Please <a href="/login">sign in</a> to participate in this
	        discussion. </p>
	</div>
</template>
<script>
	export default {
		props: ['endpoint'],
		data() {
			return {
				body: '',
			}
		},
		computed: {
			signedIn () {
				return window.App.signedIn;
			}
		},
		methods: {
			addReply() {
				axios.post(this.endpoint, { body: this.body })
					 .then(data => {
					 	this.body = '';
					 	flash('Your reply has been posted!');
					 	this.$emit('created', data.data);
					 });
			}
		}
	}
</script>
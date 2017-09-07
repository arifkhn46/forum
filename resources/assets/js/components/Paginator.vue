<template>
    <div>
        <ul class="pagination" v-if="shouldPaginated">
            <li v-show="prevUrl"><a href="#" rel="previous" @click.prevent="page--">Previous</a></li>
            <li v-show="nextUrl"><a href="#" rel="next" @click.prevent="page++">Next</a></li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: ['dataSet'],
        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false,
            }
        },
        computed: {
            shouldPaginated() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },
        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            page() {
                this.broadcast().updateUrl();
            }
        },
        methods: {
            broadcast() {
                return this.$emit('changed', this.page);
            },
            updateUrl() {
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>
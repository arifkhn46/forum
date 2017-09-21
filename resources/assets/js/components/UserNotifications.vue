<template>
    <li class="dropdown" v-if="notifications">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>
        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data: function() {
            return {
                notifications: false
            }
        },
        created: function () {
            axios.get('/profiles/'+window.App.user.name+'/notifications').
                    then(response => this.notifications = response.data);

        },
        methods: {
            markAsRead: function(notification) {
                // /profiles/{$userId}/notifications/{$notificationId}
                axios.delete('/profiles/'+window.App.user.name+'/notifications/'+notification.id);
            }
        }
    }
</script>
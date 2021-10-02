<template>
    <div class="people-list" id="people-list">
        <div class="search">
            <input
                type="text"
                placeholder="search..."
                class="people-list-search"
            />
        </div>

        <div class="pl-4 text-white">
            <h5>{{ $authUser.name }}</h5>
        </div>

        <ul class="list">
            <li
                v-for="(user, id) in users"
                @click="getMessages(user.id)"
                :key="user.id"
                class="clearfix"
                :class="
                    userMessages.user && userMessages.user.id === user.id
                        ? 'active-chat'
                        : ''
                "
            >
                <img
                    :src="
                        `https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_${
                            id < 9 ? '0' : ''
                        }${id > 8 ? 10 : id}.jpg`
                    "
                    alt="avatar"
                />
                <div class="about">
                    <div class="name">{{ $_.upperFirst(user.name) }}</div>
                    <div class="status">
                        <div
                            v-if="
                                users[user.id] &&
                                    users[user.id].typing_info &&
                                    users[user.id].typing_info.message
                            "
                        >
                            Typing...
                        </div>
                        <div>
                            <i
                                class="fa fa-circle"
                                :class="
                                    user.online_status ? 'online' : 'offline'
                                "
                            ></i>
                            {{ user.online_status ? "online" : "offline" }}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "LeftBar",
    computed: {
        ...mapGetters({
            users: "user/getUsers",
            userMessages: "user/getMessages"
        })
    },

    methods: {
        getMessages(userId) {
            this.$store.dispatch("user/getMessages", userId);

            this.$router.push({
                name: "chats",
                params: { conv_id: userId }
            });
        }
    }
};
</script>

<style scoped></style>

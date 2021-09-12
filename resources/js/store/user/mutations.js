import Vue from "vue";

export function storeUsers(state, users) {
    users.forEach(user => {
        Vue.set(state.users, user.id, user);
    });
}

export function storeMessages(state, messages) {
    state.messages = messages;
}

export function updateUserInfo(state, payload) {
    const userId = payload.user_id;

    if (payload.typing_info) {
        Vue.set(state.users, userId, {
            ...state.users[userId],
            typing_info: payload.typing_info
        });

        // clearTimeout(this.typingTimer[userId]);
        //
        // this.typingTimer[userId] = setTimeout(() => {
        //     this.$store.commit("user/updateUserInfo", {
        //         user_id: userId,
        //         typing_info: {}
        //     });
        // }, 2000);
    }
}

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
    if (payload.typing_info) {
        console.log("up typing");

        Vue.set(state.users, payload.user_id, {
            ...state.users[payload.user_id],
            typing_info: payload.typing_info
        });
    }
}

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

    // for safe
    if (!userId) {
        return false;
    }

    if (payload.hasOwnProperty("typing_info")) {
        Vue.set(state.users, userId, {
            ...state.users[userId],
            typing_info: payload.typing_info
        });
    }

    if (payload.hasOwnProperty("online_status")) {
        Vue.set(state.users, userId, {
            ...state.users[userId],
            online_status: payload.online_status
        });

        console.log({ aaaaaaaaaaaaa: state.users[userId] });
    }
}

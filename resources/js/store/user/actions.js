export function getUsers(context) {
    return new Promise((resolve, reject) => {
        axios
            .get("users")
            .then(res => {
                context.commit("storeUsers", res.data);
                resolve(res);
            })
            .catch(err => {
                reject(err);
            });
    });
}

export function getMessages(context, userId) {
    return new Promise((resolve, reject) => {
        axios
            .get(`users/${userId}/messages`)
            .then(res => {
                context.commit("storeMessages", res.data);
                resolve(res);
            })
            .catch(err => {
                reject(err);
            });
    });
}

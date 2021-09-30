const routes = [
    {
        path: "/",
        meta: { requiresAuth: true },
        component: () => import("../layouts/MainLayout"),
        children: [
            {
                path: "chats/:conv_id",
                name: "chats",
                component: () => import("../pages/Chat.vue")
            }
        ]
    }

    // {
    //     path: "*",
    //     component: () => import("../views/error/404")
    // }
];

export default routes;

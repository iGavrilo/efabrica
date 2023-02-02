export default {
    build: {
        manifest: true,
        outDir: "www",
        emptyOutDir: false,
        rollupOptions: {
            input: '/www/assets/js/main.js',
        },
    }
}
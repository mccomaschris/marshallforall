document.addEventListener('alpine:init', () => {
    Alpine.data('stickyHeader', () => ({
        atTop: true,

        init() {
            window.addEventListener('scroll', () => {
                this.atTop = window.scrollY === 0;
            });
        }
    }));
});

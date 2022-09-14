<template>
    <div :class="containerClass" @click="onWrapperClick">
        <AppTopBar @menu-toggle="onMenuToggle"/>
        <div class="layout-sidebar" @click="onSidebarClick">
            <AppMenu :model="menu" @menuitem-click="onMenuItemClick"/>
        </div>

        <div class="layout-main-container">
            <div class="layout-main">
                <router-view/>
            </div>
            <AppFooter/>
        </div>

        <!--		<AppConfig :layoutMode="layoutMode" @layout-change="onLayoutChange" />-->
        <transition name="layout-mask">
            <div class="layout-mask p-component-overlay" v-if="mobileMenuActive"></div>
        </transition>
    </div>
</template>

<script>
import AppTopBar from './AppTopbar.vue';
import AppMenu from './AppMenu.vue';
import AppConfig from './AppConfig.vue';
import AppFooter from './AppFooter.vue';

export default {
    data() {
        return {
            layoutMode: 'static',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menu: [
                {
                    label: 'Genel bakış',
                    items: [{
                        label: 'Durum', icon: 'pi pi-fw pi-home', to: '/'
                    }]
                },
                {
                    label: 'Mağazalar', icon: 'pi pi-fw pi-sitemap',
                    items: [
                        {label: 'Mağazaları yönet', icon: 'pi pi-fw pi-id-card', to: '/stores'},
                        {label: 'Siparişler', icon: 'pi pi-fw pi-shopping-bag', to: '/orders'},
                        {label: 'Ürünler', icon: 'pi pi-fw pi-check-square', to: '/products'},
                        // {label: "Float Label", icon: "pi pi-fw pi-bookmark", to: "/floatlabel"},
                        // {label: "Invalid State", icon: "pi pi-fw pi-exclamation-circle", to: "invalidstate"},
                        // {label: 'Button', icon: 'pi pi-fw pi-mobile', to: '/button'},
                        // {label: 'Table', icon: 'pi pi-fw pi-table', to: '/table'},
                        // {label: 'List', icon: 'pi pi-fw pi-list', to: '/list'},
                        // {label: 'Tree', icon: 'pi pi-fw pi-share-alt', to: '/tree'},
                        // {label: 'Panel', icon: 'pi pi-fw pi-tablet', to: '/panel'},
                        // {label: 'Overlay', icon: 'pi pi-fw pi-clone', to: '/overlay'},
                        // {label: 'Menu', icon: 'pi pi-fw pi-bars', to: '/menu'},
                        // {label: 'Message', icon: 'pi pi-fw pi-comment', to: '/messages'},
                        // {label: 'File', icon: 'pi pi-fw pi-file', to: '/file'},
                        // {label: 'Chart', icon: 'pi pi-fw pi-chart-bar', to: '/chart'},
                        // {label: 'Misc', icon: 'pi pi-fw pi-circle-off', to: '/misc'},
                    ]
                },
                {
                    label: 'Ayarlar', icon: 'pi pi-fw pi-clone',
                    items: [
                        {label: 'Profil bilgilerim', icon: 'pi pi-fw pi-id-card', to: '/profile'}, //changed from /crud to /profile by cosmos*
                        {label: 'Ayarlarım', icon: 'pi pi-fw pi-user-edit', to: '/timeline'},
                        {label: 'Sipariş Durumları', icon: 'pi pi-fw pi-tags', to: '/order_statuses'},
                    ]
                },

                {
                    label: 'Yardım',
                    items: [
                        {
                            label: 'Destek', icon: 'pi pi-fw pi-question', command: () => {
                                window.location = "#/documentation"
                            }
                        },
                        {
                            label: 'Bilgi bankası', icon: 'pi pi-fw pi-search', command: () => {
                                // window.location = "https://github.com/primefaces/sakai-vue"
                            }
                        },
                        {
                            label: 'Çıkış',
                            icon : 'pi pi-fw pi-times',
                            command: () => {
                                window.location = route('logout')
                            }
                        }

                    ]
                },
            ]
        }
    },
    watch: {
        $route() {
            this.menuActive = false;
            this.$toast.removeAllGroups();
        }
    },
    methods: {
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }

            this.menuClick = false;
        },
        onMenuToggle() {
            this.menuClick = true;

            if (this.isDesktop()) {
                if (this.layoutMode === 'overlay') {
                    if (this.mobileMenuActive === true) {
                        this.overlayMenuActive = true;
                    }

                    this.overlayMenuActive = !this.overlayMenuActive;
                    this.mobileMenuActive = false;
                } else if (this.layoutMode === 'static') {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            } else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }

            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
        onLayoutChange(layoutMode) {
            this.layoutMode = layoutMode;
        },
        addClass(element, className) {
            if (element.classList)
                element.classList.add(className);
            else
                element.className += ' ' + className;
        },
        removeClass(element, className) {
            if (element.classList)
                element.classList.remove(className);
            else
                element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        },
        isDesktop() {
            return window.innerWidth >= 992;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === 'static')
                    return !this.staticMenuInactive;
                else if (this.layoutMode === 'overlay')
                    return this.overlayMenuActive;
            }

            return true;
        }
    },
    computed: {
        containerClass() {
            return ['layout-wrapper', {
                'layout-overlay': this.layoutMode === 'overlay',
                'layout-static': this.layoutMode === 'static',
                'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                'layout-mobile-sidebar-active': this.mobileMenuActive,
                'p-input-filled': this.$primevue.config.inputStyle === 'filled',
                'p-ripple-disabled': this.$primevue.config.ripple === false,
                'layout-theme-light': this.$appState.theme.startsWith('saga')
            }];
        },
        logo() {
            return (this.$appState.darkTheme) ? "images/logo-white.svg" : "images/logo.svg";
        }
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, 'body-overflow-hidden');
        else
            this.removeClass(document.body, 'body-overflow-hidden');
    },
    components: {
        'AppTopBar': AppTopBar,
        'AppMenu': AppMenu,
        'AppConfig': AppConfig,
        'AppFooter': AppFooter,
    }
}
</script>

<style lang="scss">
@import './App.scss';
</style>

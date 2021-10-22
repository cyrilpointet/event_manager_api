<template>
    <v-app class="global">
        <v-app-bar class="flex-grow-0" :class="$store.state.color">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title>
                {{ $store.state.title }}
            </v-toolbar-title>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" absolute temporary dark>
            <NavMenu />
        </v-navigation-drawer>

        <v-container>
            <transition name="fade" mode="out-in">
                <router-view />
            </transition>
        </v-container>

        <MsgDisplayer />
        <ConfirmModal />
        <PwaInstallPrompt />
    </v-app>
</template>

<script>
import ConfirmModal from "./components/common/ConfirmModal";
import NavMenu from "./components/common/NavMenu";
import MsgDisplayer from "./components/common/MsgDisplayer";
import { mapGetters, mapState } from "vuex";
import PwaInstallPrompt from "./components/common/PwaInstallPrompt";

export default {
    name: "App",
    components: { ConfirmModal, NavMenu, MsgDisplayer, PwaInstallPrompt },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
        }),
    },
    data: () => {
        return {
            drawer: false,
        };
    },
};
</script>

<style lang="scss" scoped>
.global {
    background-color: #c5c5c5 !important;
}
</style>

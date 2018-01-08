<template>
  <v-app id="inspire">
    <v-navigation-drawer
      fixed
      v-model="drawerRight"
      :stateless="right"
      right
      clipped
      app
    >
      <v-list dense>
        <v-list-tile @click.stop="right = !right">
          <v-list-tile-action>
            <v-icon>exit_to_app</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Open Temporary Drawer</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar
      color="red darken-4"
      dark
      fixed
      app
      clipped-right
    >
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>
        <router-link to="/" class="white--text headline">Una Auto</router-link>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-side-icon @click.stop="drawerRight = !drawerRight"></v-toolbar-side-icon>
    </v-toolbar>
    <v-navigation-drawer
      fixed
      v-model="drawer"
      :stateless="left"
      app
    >

      <v-list class="mt-2">
        <v-list-tile tag="div" class="pl-1">
          <v-list-tile-avatar>
            <v-icon>perm_data_setting</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>{{ authEmail }}</v-list-tile-title>
          </v-list-tile-content>
          <v-list-tile-action>
            <v-menu offset-y>
              <v-btn slot="activator" icon>
                <v-icon>expand_more</v-icon>                
              </v-btn>
              <v-list> 
                <v-divider></v-divider>
                <v-list-tile>
                  <v-list-tile-title>

                  <form action="/logout" method="post">
                    <input type="hidden" name="_token" :value="token">
                    <button type="submit"><v-icon>lock_open</v-icon> Logout</button>
                  </form>
                  </v-list-tile-title>
                </v-list-tile>                                
              </v-list>
            </v-menu>          
          </v-list-tile-action>
        </v-list-tile>
      </v-list> 
      <v-toolbar flat class="transparent">
        <v-list class="pa-0">
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title>Tools</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-toolbar>    
      <v-list dense>
        <!--<v-list-tile @click.stop="left = !left">
          <v-list-tile-action>
            <v-icon>exit_to_app</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Open Temporary Drawer</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>-->
        <v-list-tile 
          @click="$router.push(item.link)"
          v-for="item in menuItems"
          :key="item.title"
          :to="item.link"         
        >
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ item.title}}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>        
      </v-list>
    </v-navigation-drawer>
    <v-navigation-drawer
      temporary
      v-model="left"
      fixed
    ></v-navigation-drawer>
    <v-content>
      <!-- For showing user alerts and feedback -->
      <v-snackbar
        v-model="snackbar"
        :timeout="timeout"
        :absolute="true"
        :multi-line="true"
        class="absolute-center"
      >
        {{ snackBarText }}
        <v-btn flat class="pink--text" @click.native.stop="snackbar = false">Close</v-btn>
      </v-snackbar>

      <v-container fluid fill-height>

        <router-view></router-view>

      </v-container>
    </v-content>
    <v-navigation-drawer
      right
      temporary
      v-model="right"
      fixed
    ></v-navigation-drawer>
    <v-footer color="red darken-4" class="white--text" app>
      <span>ShopWatch V0.5</span>
      <v-spacer></v-spacer>
      <span>&copy; 2017</span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    data: function(){
      return {
        snackbar: false,
        mode: '',
        timeout: 6000,
        snackBarText: '',      
        drawer: null,
        drawerRight: null,
        right: null,
        left: null,
        menuItems: [
          { icon: 'android', title: 'Users', link: '/users' },
          { icon: 'account_box', title: 'Customers', link: '/customers' }
        ],
        token: window.Laravel.csrfToken,
        authEmail: AUTH_EMAIL         
      }
    },

    props: {
      source: String
    },

    created () {
      this.$router.app.$on('snackbar', (config) => {
        this.snackbar = true;
        this.snackBarText = config.text;    
      }); 
    }     
  }
</script>

<style scoped>
  .absolute-center{
    top: 40%;
  }
</style>
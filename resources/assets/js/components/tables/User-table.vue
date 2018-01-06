<template>
 	<v-card class="elevation-0">
    <v-card-title>
      Users
      <v-spacer></v-spacer>
      <v-text-field
        append-icon="search"
        label="Search"
        single-line
        hide-details
        v-model="search"
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="users"
      :search="search"
    >
      <template slot="items" slot-scope="props">
        <td class="text-xs-left">{{ props.item.name }}</td>
        <td class="text-xs-left">{{ props.item.email }}</td>
        <td class="text-xs-left">{{ props.item.role }}</td>
        <td class="text-xs-left">{{ props.item.hourly_wage }}</td>
      </template>
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>
    </v-data-table>
  </v-card>		
</template>

<script>
	export default{
		data (){
			return {
				search: '',
				loading: true,
 				headers: [
          {
            text: 'Name',
            align: 'left',
            sortable: true,
            value: 'name'
          },
          {
            text: 'Email',
            align: 'left',
            sortable: true,
            value: 'email'
          },
          {
            text: 'Role',
            align: 'left',
            sortable: true,
            value: 'role'
          },
          {
            text: 'Hourly',
            align: 'left',
            sortable: true,
            value: 'hourly_wage'
          }                           
        ]
			}
		},

		computed: {
			users (){
				return this.$store.getters.users;
			}
		},

		created (){
			this.$store.dispatch('getUsers')
				.then((response) => {
					// Toggle table loader
					this.loading = false;
				});
		}		
	}
</script>
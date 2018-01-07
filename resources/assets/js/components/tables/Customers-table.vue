<template>
 	<v-card class="elevation-0">
    <v-card-title>
      Customers
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
      :items="customers"
      :search="search"
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-xs-left">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.last }}</td>
          <td class="text-xs-left">{{ props.item.phone_one }}</td>
          <td class="text-xs-left">{{ props.item.phone_two }}</td>
          <td class="text-xs-right">
            <v-menu offset-y >
              <v-btn slot="activator" icon>
                <v-icon>settings</v-icon>
              </v-btn>              
              <v-list>
                <v-list-tile @click="openDialog(props.item, 'editDialog')">
                  <v-list-tile-title>Edit</v-list-tile-title>
                </v-list-tile>                
              </v-list>
            </v-menu>            

          </td>
        </tr>
      </template>      
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>
    </v-data-table>

    <!-- Edit customer dialog -->
    <v-dialog v-model="editDialog" persistent max-width="500px">
      <v-card>
        <v-system-bar window class="blue darken-4">
          <v-spacer></v-spacer>
          <v-tooltip top>
            <v-btn icon class="mr-0" slot="activator" @click="closeDialog('editDialog')">
              <v-icon class="white--text mr-0">close</v-icon>
            </v-btn>                      
            <span>Close dialog</span>
          </v-tooltip>            
        </v-system-bar>
        <v-card-text>
          <customer-form action="updateCustomer" :customer="selectedCustomer" edit-state="true" @saved="closeDialog('editDialog')"></customer-form>
        </v-card-text>
      </v-card>
    </v-dialog>      

  </v-card>		
</template>

<script>
  import CustomerForm from './../forms/Customer-form';

	export default{
		data (){
			return {
				search: '',
				loading: true,
 				headers: [
          {
            text: 'First Name',
            align: 'left',
            sortable: true,
            value: 'first'
          },
          {
            text: 'Last Name',
            align: 'left',
            sortable: true,
            value: 'last'
          },
          {
            text: 'Primary Phone',
            align: 'left',
            sortable: true,
            value: 'phone_one'
          },
          {
            text: 'Secondary Phone',
            align: 'left',
            sortable: true,
            value: 'phone_two'
          },
          {
            text: '',
            align: 'right',
            sortable: false,
          }                                    
        ],
        editDialog: false,
        selectedCustomer: ''
			}
		},

		computed: {
			customers (){
				return this.$store.getters.customers;
			}
		},

    components: {
      'customer-form': CustomerForm
    },

    methods: {
      openDialog (customer, dialog){
        // The the customer for editing
        this.selectedCustomer = customer;
        // Toggle Dialog
        this[dialog] = true;
      },

      closeDialog (dialog){
        // Toggle Dialog
        this[dialog] = false;        
      }
      
    },

		created (){
			this.$store.dispatch('filterCustomers')
				.then((response) => {
					// Toggle table loader
					this.loading = false;
				});
		}		
	}
</script>
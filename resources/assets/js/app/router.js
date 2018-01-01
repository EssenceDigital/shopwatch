// Load Vue based dependencies
import Vue from 'vue';
import VueRouter from 'vue-router';

import UsersView from './../views/Users';

// Register router with Vue
Vue.use(VueRouter);

export default new VueRouter({
	routes: [
		{
			path: '/users',
			name: 'Users',
			component: UsersView
		},
		{
			path: '/customers',
			name: 'Customers',
			component: CustomersView
		},	
		{
			path: '/customers/:id/work-orders',
			name: 'Customer Work Orders',
			component: CustomerWorkOrdersView,
			props: true
		},
		{
			path: '/customers/:id/invoices',
			name: 'Customer Invoices',
			component: CustomerInvoicesView,
			props: true
		},
		{
			path: '/vehicles/:id/work-orders',
			name: 'Vehicle Work Orders',
			component: VehicleWorkOrdersView,
			props: true
		},	
		{
			path: '/vehicles/:id/invoices',
			name: 'Vehicle Invoices',
			component: VehicleInvoicesView,
			props: true
		},
		{
			path: '/work-orders',
			name: 'Work Orders',
			component: WorkOrdersView
		},
		{
			path: '/work-orders/:id',
			name: 'Work Order',
			component: WorkOrderView,
			props: true
		},
		{
			path: '/invoices',
			name: 'Invoices',
			component: InvoicesView
		},	
		{
			path: '/invoices/:id',
			name: 'Invoice',
			component: InvoicesView,
			props: true
		},	
		{
			path: '/config',
			name: 'Business Config',
			component: BusConfigView
		},	
		{
			path: '/user-settings',
			name: 'User Settings',
			component: UserSettingsView
		}																					
	]
});

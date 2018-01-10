<template v-if="invoices">
	<v-container fluid class="pa-0">
		<v-layout row wrap>
			<v-flex xs12 v-for="invoice in invoices" :key="invoice.id" class="pl-2 pr-2 mt-4">
				<invoice-ticket :invoice="invoice"></invoice-ticket>
			</v-flex>
		</v-layout>				
	</v-container>		
</template>

<script>
	import InvoiceTicket from './../tickets/Invoice-ticket';

	export default{

		computed: {
			invoices (){
				return this.$store.getters.invoices;
			}
		},

		components: {
			'invoice-ticket': InvoiceTicket
		}
	}
</script>
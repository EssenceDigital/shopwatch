<template v-if="workOrders">
	<v-container fluid>
		<v-layout row wrap>
			<v-flex xs4 v-for="wo in workOrders" :key="wo.id" class="pl-2 pr-2 mt-4">
				<wo-ticket :wo="wo"></wo-ticket>
			</v-flex>
		</v-layout>				
	</v-container>		
</template>

<script>
	import WoTicket from './../tickets/Wo-ticket';

	export default{
		data (){
			return {

			}
		},

		computed: {
			workOrders (){
				return this.$store.getters.workOrders;
			}
		},

		components: {
			'wo-ticket': WoTicket
		},

		created (){
			// Get WOs
			this.$store.dispatch('filterWorkOrders', {
				created_at: '',
				is_invoiced: 'false-bool'
			});
		}
	}
</script>
<template v-if="part">
	<v-layout row>
		<v-flex xs3>
			<p class="pa-2 mb-0">
				{{ part.part_invoice_number }}
			</p>
		</v-flex>
		<v-flex xs7>
			<p class="pa-2 mb-0">
				{{ part.title }}
			</p>
		</v-flex>
		<v-flex xs2 class="text-xs-right">
			<p class="pa-2 mb-0">
				{{ part.billing_price | money }}
			</p>
		</v-flex>																				
	</v-layout>	
</template>

<script>
	import PartForm from './../forms/Job-parts-form';

	export default{
		props: {
			part: {
				required: true
			},
			invoiceState: {
				default: false
			}
		},

		data (){
			return {
				editDialog: false
			}
		},

		components: {
			'part-form': PartForm
		}

	}
</script>
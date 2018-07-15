import UIkit from 'uikit'
import Icons from 'uikit/dist/js/uikit-icons'

import Vue from 'vue'
import axios from 'axios'

import * as VueGoogleMaps from '~/node_modules/vue2-google-maps/src/main'

UIkit.use(Icons)

// Vue google maps
Vue.use(VueGoogleMaps, {
	load: {
		key: 'AIzaSyAdIWKddhAQhiUvx-UyIfrUPcbL6FAv0WQ'
	}
})

Vue.component('google-map', VueGoogleMaps.Map)
Vue.component('google-marker', VueGoogleMaps.Marker)

// Vue instance
new Vue({
	el: '#app',
	// delimiters: ['${', '}'],
	data: {
		mounted: false,
		backdrop: false,
		// contact form
		loading: false,
		response: null,
		action: 'contact-form/send',
		fromName: '',
		fromEmail: '',
		message: {
			company: '',
			enquiry: ''
		},
		errors: {},
		// google map
		mapCenter: {
			lat: 51.517094,
			lng: -0.139440
		},
		mapType: 'roadmap',
		mapStyle: 'customized',
		markers: [
			{
				coordinates: {
					lat: 51.517094,
					lng: -0.139440
				}
			}
		]
	},
	computed: {
		mapStyles: function()
		{
			switch (this.mapStyle)
			{
				case 'customized':

				return [
					{
						"stylers": [
							{
								"saturation": -80
							}
						]
					}
				]
			}
		}
	},
	mounted: function() {
		var _this = this
		setTimeout(function() {
			_this.mounted = true
		}, 100)

		// Request defaults
		axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest'
		axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'

		// sticky sidebar
		UIkit.sticky('#sidebar > div')

		// Popover
		UIkit.util.on(document, 'beforeshow', '.uk-dropdown', function() {
			_this.backdrop = true
		})
		UIkit.util.on(document, 'beforehide', '.uk-dropdown', function() {
			_this.backdrop = false
		})
	},
	methods: {
		onSubmit: function(e) {
			var _this = this
			_this.loading = true

			var csrf = document.getElementsByName('CRAFT_CSRF_TOKEN')[0]

			var formData = serialize({
				action: _this.action,
				CRAFT_CSRF_TOKEN: csrf ? csrf.value : '',
				fromName: _this.fromName,
				fromEmail: _this.fromEmail,
				'message[company]': _this.message.company,
				'message[enquiry]': _this.message.enquiry,
				'message[recaptchaToken]': recaptchaToken
			})

			axios.post('/', formData)
			.then(function(response) {
				var data = response.data

				// captcha
				if ( typeof grecaptcha !== 'undefined' )
				{
					grecaptcha.reset()
				}

				// error/success
				if (data.errors) {
					// error
					UIkit.notification({
						message: '<span uk-icon="warning"></span> Failed to send message.',
						status: 'danger',
						pos: 'top-right',
						timeout: 3000,
					})

					if (data.errors.recaptchaToken) {
						UIkit.notification({
							message: '<span uk-icon="warning"></span> ' + data.errors.recaptchaToken,
							status: 'danger',
							pos: 'top-right',
							timeout: 3000,
						})
					}

					_this.errors = data.errors
				} else {
					// success
					UIkit.notification({
						message: '<span uk-icon="check"></span> Your message has been sent.',
						status: 'success',
						pos: 'top-right',
						timeout: 3000,
					})
					_this.fromName = ''
					_this.fromEmail = ''
					_this.message.company = ''
					_this.message.enquiry = ''
					_this.errors = {}
				}

				_this.loading = false
			})
			.catch(function(err) {
				UIkit.notification({
					message: '<span uk-icon="warning"></span> Failed to send message.',
					status: 'danger',
					pos: 'top-right',
					timeout: 3000,
				})

				_this.loading = false
			})
		},
	}
})

function serialize(obj) {
	var query = []

	for (var key in obj) {
		query.push(key + "=" + encodeURIComponent(obj[key]))
	}

	return query.join('&')
}

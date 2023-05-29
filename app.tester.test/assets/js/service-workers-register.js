
var service_worker_registration = null;

$(document).ready(function()
{
	
	Notification.requestPermission(function(status)
	{
		console.log('stratus: ' + status);
		$('.push_status').html(status);
	});
	
	
	if('serviceWorker' in navigator)
	{
		navigator.serviceWorker.register('/service-worker-root.js').then(function(registration)
		{
			console.log('ServiceWorker registration successful with scope: ', registration.scope);
			console.log(registration);
			service_worker_registration = registration;
			
			$('.service_worker_status').html('granted');
		}, function(err)
		{
			console.log('ServiceWorker registration failed: ', err);
			$('.service_worker_status').html('failed');
		});
		
		navigator.serviceWorker.getRegistrations().then(function (registrations)
		{
			for (let registration of registrations)
			{
				registration.update();
			}
		});
		
	}else
		console.log('No serviceWorker in navigator object');
	
});

function urlB64ToUint8Array(base64String)
{
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  
  for (let i = 0; i < rawData.length; ++i)
  {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}







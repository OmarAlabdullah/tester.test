
var revision = 109;

self.addEventListener('install', function(e)
{
	console.log('--service worker install--');
	self.skipWaiting();
	e.waitUntil(caches.open('drs.' + revision).then(function(cache)
	{
		console.log('Opened cache');
		return cache.addAll(
		[
			
		]);
	}));
});

self.addEventListener('fetch', function(event)
{
	
});

self.addEventListener('activate', function(event) 
{
	console.log('--service worker activate--');
	console.log('--remove all cache--');
  event.waitUntil(
    caches.keys().then(function(cacheNames)
    {
      return Promise.all(
        cacheNames.filter(function(cacheName)
        {
        	if(cacheName == 'drs.' + revision)
        		console.log('dont remove this cache: ' + cacheName);
        	else
        		console.log('remove cache: ' + cacheName);
          return true;
        }).map(function(cacheName)
        {
          return caches.delete(cacheName);
        })
      );
    })
  );
});

self.addEventListener('message', function(e)
{
	console.log('--service worker message--');
	console.log(e.data);
	//self.postMessage(e.data);
}, false);

self.addEventListener('push', function(event)
{
  console.log('--service worker push received--');
  
  const title = 'DRS Infra';
  const options = {
    body: event.data.text(),
    icon: '/assets/images/gas-icon-512.png', //large and color on desktop
    badge: '/assets/images/gas-icon-512.png'
  };
	
  event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event)
{
  console.log('[Service Worker] Notification click Received.');

  event.notification.close();

  event.waitUntil(
    clients.openWindow('https://app.drs-infra.nl')
  );
});
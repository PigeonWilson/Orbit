# Orbit

What is it? A private client and server infrastructure that captures and exfiltrates geolocation data from any device. Uses the capabilities of the device on which it is launched. General accuracy is less than 5 meters on a mobile device and less than 20 meters on a static device. GPS data update rate is configured using a web interface. 

The client is compatible with all devices, regardless of platform.

# Current state
For the moment, the waf and login are functional. The next step is the API for geolocation and the hack to keep the window in resource priority while the page is active. The idea is for each user to have their own history encrypted in aes 256 with a unique key. With the browsing history encrypted, it's up to the user to decide when and with whom to share a saved itinerary. 

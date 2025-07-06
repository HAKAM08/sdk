@extends('layouts.app')

@section('title', 'Fishing Map & Weather')

@section('styles')
<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .weather-card {
        background-color: #f8fafc;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    .weather-icon {
        width: 50px;
        height: 50px;
    }
    .location-search {
        margin-bottom: 20px;
    }
    .forecast-day {
        background-color: #f0f9ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    .forecast-day:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .fishing-tip {
        background-color: #ecfdf5;
        border-left: 4px solid #10b981;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 0 8px 8px 0;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-blue-900">Fishing Map & Weather</h1>
    
    <div class="mb-8">
        <div class="bg-blue-100 p-4 rounded-lg">
            <p class="text-blue-800">Use this page to check weather conditions and find great fishing spots. Plan your next fishing trip with accurate weather forecasts and interactive maps.</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Weather Information -->
        <div class="lg:col-span-1">
            <div class="location-search">
                <h2 class="text-xl font-semibold mb-4 text-blue-900">Search Location</h2>
                <div class="flex">
                    <input type="text" id="location-input" class="flex-grow px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter city name">
                    <button id="search-button" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div id="current-weather" class="weather-card">
                <h2 class="text-xl font-semibold mb-4 text-blue-900">Current Weather</h2>
                <div id="weather-loading" class="text-center py-4">
                    <p>Search for a location to see weather information</p>
                </div>
                <div id="weather-content" class="hidden">
                    <div class="flex items-center mb-4">
                        <img id="weather-icon" src="" alt="Weather icon" class="weather-icon mr-4">
                        <div>
                            <h3 id="location-name" class="text-lg font-medium">-</h3>
                            <p id="current-date" class="text-sm text-gray-600">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Temperature</p>
                            <p id="current-temp" class="text-2xl font-bold">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Feels Like</p>
                            <p id="feels-like" class="text-2xl font-bold">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Humidity</p>
                            <p id="humidity" class="text-2xl font-bold">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Wind</p>
                            <p id="wind-speed" class="text-2xl font-bold">-</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Weather Condition</p>
                        <p id="weather-description" class="text-lg">-</p>
                    </div>
                </div>
            </div>
            
            <div id="fishing-conditions" class="weather-card mt-6">
                <h2 class="text-xl font-semibold mb-4 text-blue-900">Fishing Conditions</h2>
                <div id="fishing-loading" class="text-center py-4">
                    <p>Search for a location to see fishing conditions</p>
                </div>
                <div id="fishing-content" class="hidden">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Fishing Quality</p>
                        <div class="flex items-center">
                            <div id="fishing-quality-bar" class="bg-gray-200 h-4 w-full rounded-full overflow-hidden">
                                <div id="fishing-quality-value" class="bg-blue-600 h-full rounded-full" style="width: 0%"></div>
                            </div>
                            <span id="fishing-quality-text" class="ml-2 font-medium">-</span>
                        </div>
                    </div>
                    <div class="fishing-tip">
                        <h3 class="font-medium mb-2">Fishing Tip</h3>
                        <p id="fishing-tip-text">Search for a location to get fishing tips based on weather conditions.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Map and Forecast -->
        <div class="lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4 text-blue-900">Interactive Fishing Map</h2>
            <div id="map" class="mb-8"></div>
            
            <h2 class="text-xl font-semibold mb-4 mt-8 text-blue-900">5-Day Forecast</h2>
            <div id="forecast-loading" class="text-center py-4 bg-white rounded-lg shadow">
                <p>Search for a location to see forecast</p>
            </div>
            <div id="forecast-container" class="hidden grid grid-cols-1 md:grid-cols-5 gap-4"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize variables
    let map;
    let markers = [];
    const openWeatherMapApiKey = '{{ $openWeatherMapApiKey }}';
    const googleMapsApiKey = '{{ $googleMapsApiKey }}';
    let currentLocation = { lat: 33.5731, lng: -7.5898 }; // Default to Casablanca
    
    // Initialize Google Map
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: currentLocation,
            zoom: 10,
            mapTypeId: 'terrain',
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain']
            }
        });
        
        // Add some example fishing spots
        const fishingSpots = [
            { lat: 33.5731, lng: -7.6298, name: 'Casablanca Port', type: 'Saltwater' },
            { lat: 33.6131, lng: -7.5598, name: 'Ain Diab Beach', type: 'Saltwater' },
            { lat: 33.5331, lng: -7.6098, name: 'Oued El Maleh', type: 'Freshwater' },
            { lat: 33.9592, lng: -6.8498, name: 'Bouregreg River', type: 'Freshwater' },
            { lat: 35.1667, lng: -5.2667, name: 'Al Hoceima Bay', type: 'Saltwater' }
        ];
        
        // Add markers for fishing spots
        fishingSpots.forEach(spot => {
            addFishingSpotMarker(spot);
        });
        
        // Allow users to add their own fishing spots
        map.addListener('click', function(event) {
            const confirmAdd = confirm('Add this location as a fishing spot?');
            if (confirmAdd) {
                const spotName = prompt('Enter a name for this fishing spot:');
                const spotType = prompt('Enter the type (Saltwater/Freshwater):');
                if (spotName) {
                    const newSpot = {
                        lat: event.latLng.lat(),
                        lng: event.latLng.lng(),
                        name: spotName,
                        type: spotType || 'Unknown'
                    };
                    addFishingSpotMarker(newSpot);
                }
            }
        });
    }
    
    // Add a marker for a fishing spot
    function addFishingSpotMarker(spot) {
        const marker = new google.maps.Marker({
            position: { lat: spot.lat, lng: spot.lng },
            map: map,
            title: spot.name,
            icon: {
                url: spot.type === 'Saltwater' ? 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png' : 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(32, 32)
            },
            animation: google.maps.Animation.DROP
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="width: 200px">
                    <h3 style="font-weight: bold; margin-bottom: 5px">${spot.name}</h3>
                    <p><strong>Type:</strong> ${spot.type}</p>
                    <button id="get-weather-btn" style="background-color: #3b82f6; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-top: 5px; cursor: pointer;">Get Weather Here</button>
                </div>
            `
        });
        
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });
        
        google.maps.event.addListener(infoWindow, 'domready', function() {
            document.getElementById('get-weather-btn').addEventListener('click', function() {
                getWeatherData(spot.lat, spot.lng);
                map.setCenter({ lat: spot.lat, lng: spot.lng });
            });
        });
        
        markers.push(marker);
    }
    
    // Get weather data from OpenWeatherMap API
    function getWeatherData(lat, lng) {
        showLoading();
        
        // Current weather
        fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lng}&units=metric&appid=${openWeatherMapApiKey}`)
            .then(response => response.json())
            .then(data => {
                displayCurrentWeather(data);
                calculateFishingConditions(data);
            })
            .catch(error => {
                console.error('Error fetching current weather:', error);
                alert('Failed to fetch weather data. Please try again.');
                hideLoading();
            });
        
        // 5-day forecast
        fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lng}&units=metric&appid=${openWeatherMapApiKey}`)
            .then(response => response.json())
            .then(data => {
                displayForecast(data);
            })
            .catch(error => {
                console.error('Error fetching forecast:', error);
            });
    }
    
    // Display current weather data
    function displayCurrentWeather(data) {
        document.getElementById('weather-loading').classList.add('hidden');
        document.getElementById('weather-content').classList.remove('hidden');
        
        document.getElementById('location-name').textContent = data.name + ', ' + data.sys.country;
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('current-temp').textContent = Math.round(data.main.temp) + '°C';
        document.getElementById('feels-like').textContent = Math.round(data.main.feels_like) + '°C';
        document.getElementById('humidity').textContent = data.main.humidity + '%';
        document.getElementById('wind-speed').textContent = (data.wind.speed * 3.6).toFixed(1) + ' km/h';
        document.getElementById('weather-description').textContent = data.weather[0].description.charAt(0).toUpperCase() + data.weather[0].description.slice(1);
        document.getElementById('weather-icon').src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
    }
    
    // Display 5-day forecast
    function displayForecast(data) {
        const forecastContainer = document.getElementById('forecast-container');
        forecastContainer.innerHTML = '';
        document.getElementById('forecast-loading').classList.add('hidden');
        forecastContainer.classList.remove('hidden');
        
        // Group forecast data by day
        const dailyForecasts = {};
        
        data.list.forEach(item => {
            const date = new Date(item.dt * 1000).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
            
            if (!dailyForecasts[date]) {
                dailyForecasts[date] = {
                    date: date,
                    icon: item.weather[0].icon,
                    description: item.weather[0].description,
                    tempMin: item.main.temp_min,
                    tempMax: item.main.temp_max,
                    humidity: item.main.humidity,
                    windSpeed: item.wind.speed
                };
            } else {
                dailyForecasts[date].tempMin = Math.min(dailyForecasts[date].tempMin, item.main.temp_min);
                dailyForecasts[date].tempMax = Math.max(dailyForecasts[date].tempMax, item.main.temp_max);
            }
        });
        
        // Display each day's forecast
        Object.values(dailyForecasts).slice(0, 5).forEach(forecast => {
            const forecastDay = document.createElement('div');
            forecastDay.className = 'forecast-day';
            forecastDay.innerHTML = `
                <div class="text-center">
                    <h3 class="font-medium">${forecast.date}</h3>
                    <img src="https://openweathermap.org/img/wn/${forecast.icon}@2x.png" alt="Weather icon" class="mx-auto">
                    <p class="capitalize">${forecast.description}</p>
                    <p class="font-bold">${Math.round(forecast.tempMin)}° / ${Math.round(forecast.tempMax)}°</p>
                    <div class="text-sm text-gray-600 mt-2">
                        <p>Humidity: ${forecast.humidity}%</p>
                        <p>Wind: ${(forecast.windSpeed * 3.6).toFixed(1)} km/h</p>
                    </div>
                </div>
            `;
            forecastContainer.appendChild(forecastDay);
        });
    }
    
    // Calculate fishing conditions based on weather
    function calculateFishingConditions(data) {
        document.getElementById('fishing-loading').classList.add('hidden');
        document.getElementById('fishing-content').classList.remove('hidden');
        
        // Simple algorithm to determine fishing quality
        let fishingQuality = 50; // Start with neutral quality
        
        // Temperature factor (15-25°C is ideal)
        const temp = data.main.temp;
        if (temp >= 15 && temp <= 25) {
            fishingQuality += 20;
        } else if (temp < 5 || temp > 35) {
            fishingQuality -= 20;
        }
        
        // Wind factor (light winds are better)
        const windSpeed = data.wind.speed;
        if (windSpeed < 3) {
            fishingQuality += 15;
        } else if (windSpeed > 8) {
            fishingQuality -= 15;
        }
        
        // Pressure factor (stable or rising pressure is good)
        const pressure = data.main.pressure;
        if (pressure > 1013) {
            fishingQuality += 10;
        } else if (pressure < 1000) {
            fishingQuality -= 10;
        }
        
        // Weather condition factor
        const weatherId = data.weather[0].id;
        if (weatherId >= 800 && weatherId < 803) { // Clear to partly cloudy
            fishingQuality += 15;
        } else if (weatherId >= 200 && weatherId < 300) { // Thunderstorm
            fishingQuality -= 25;
        } else if (weatherId >= 500 && weatherId < 600) { // Rain
            fishingQuality -= 10;
        }
        
        // Ensure quality is between 0 and 100
        fishingQuality = Math.max(0, Math.min(100, fishingQuality));
        
        // Update UI
        document.getElementById('fishing-quality-value').style.width = `${fishingQuality}%`;
        document.getElementById('fishing-quality-text').textContent = `${fishingQuality}%`;
        
        // Set color based on quality
        const qualityBar = document.getElementById('fishing-quality-value');
        if (fishingQuality < 30) {
            qualityBar.classList.remove('bg-blue-600', 'bg-green-500');
            qualityBar.classList.add('bg-red-500');
        } else if (fishingQuality < 70) {
            qualityBar.classList.remove('bg-red-500', 'bg-green-500');
            qualityBar.classList.add('bg-blue-600');
        } else {
            qualityBar.classList.remove('bg-red-500', 'bg-blue-600');
            qualityBar.classList.add('bg-green-500');
        }
        
        // Set fishing tip based on conditions
        const tipElement = document.getElementById('fishing-tip-text');
        if (fishingQuality >= 70) {
            tipElement.textContent = 'Excellent fishing conditions! Fish are likely to be active. Try using topwater lures or fast retrieves.';
        } else if (fishingQuality >= 50) {
            tipElement.textContent = 'Good fishing conditions. Fish may be moderately active. Try a variety of retrieval speeds to find what works.';
        } else if (fishingQuality >= 30) {
            tipElement.textContent = 'Fair fishing conditions. Fish might be less active. Try slower presentations and deeper waters.';
        } else {
            tipElement.textContent = 'Poor fishing conditions. Fish activity will likely be minimal. Focus on deep, sheltered areas and use slow-moving baits.';
        }
    }
    
    // Show loading indicators
    function showLoading() {
        document.getElementById('weather-loading').classList.remove('hidden');
        document.getElementById('weather-content').classList.add('hidden');
        document.getElementById('fishing-loading').classList.remove('hidden');
        document.getElementById('fishing-content').classList.add('hidden');
        document.getElementById('forecast-loading').classList.remove('hidden');
        document.getElementById('forecast-container').classList.add('hidden');
    }
    
    // Hide loading indicators
    function hideLoading() {
        document.getElementById('weather-loading').classList.add('hidden');
        document.getElementById('fishing-loading').classList.add('hidden');
        document.getElementById('forecast-loading').classList.add('hidden');
    }
    
    // Search for a location
    document.getElementById('search-button').addEventListener('click', function() {
        const location = document.getElementById('location-input').value;
        if (location) {
            // Geocode the location
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: location }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const position = results[0].geometry.location;
                    map.setCenter(position);
                    getWeatherData(position.lat(), position.lng());
                } else {
                    alert('Location not found. Please try a different search term.');
                }
            });
        }
    });
    
    // Allow pressing Enter to search
    document.getElementById('location-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('search-button').click();
        }
    });
</script>

<!-- Load Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap" async defer></script>
@endsection
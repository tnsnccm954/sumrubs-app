<template>
    <div>
        <div id="map" style="width: 100%; height: 100vh"></div>
    </div>
</template>

<script>
import { onMounted, watch, ref, defineProps } from "vue";

export default {
    props: {
        lat: {
            type: String,
            default: "13.82", // Set a default value for lat
        },
        lng: {
            type: String,
            default: "100.529", // Set a default value for lng
        },
        title: {
            type: String,
            default: "บางซื่อ",
        },
        zoom: {
            type: Number,
            default: 18,
        },
        defautMarkers: {
            type: Array,
            default: [
                {
                    lat: parseFloat(13.82),
                    lng: parseFloat(100.529),
                },
            ],
        },
        markers: {
            type: Array,
            // default: [
            //     {
            //         lat: parseFloat(13.82),
            //         lng: parseFloat(100.529),
            //     },
            // ],
        },
        center: {
            type: Object,
            default: {
                lat: parseFloat(13.82),
                lng: parseFloat(100.529),
            },
        },
    },
    setup(props) {
        // Function to initialize the map and add a marker
        const initializeMap = (
            markers = props.defautMarkers,
            center = props.center
        ) => {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: center ? center : markers[0],
                zoom: props.zoom, // Adjust the zoom level as needed
            });

            markers.forEach((coordinate) => {
                const marker = new google.maps.Marker({
                    position: coordinate,
                    map: map,
                    title: props.title, // Marker title
                });
            });
        };

        // Load the Google Map after the component is mounted
        onMounted((props) => {
            initializeMap();
        });

        watch(
            () => props.markers,
            (newVal, oldVal) => {
                initializeMap(newVal);
            },
            { deep: true }
        );

        watch(
            () => props.center,
            (newVal, oldVal) => {
                console.log(newVal);
                console.log(oldVal);
                initializeMap(props.markers, newVal);
            },
            { deep: true }
        );
    },
};
</script>

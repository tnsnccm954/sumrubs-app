<script>
import { onMounted, ref, defineProps, watch, reactive } from "vue";
import axios from "axios";
import vSelect from "vue-select";
import SrSpinner from "@/Components/utilities/SrSpinner.vue";
import MapRenderer from "@/Components/MapRenderer.vue";

export default {
    components: {
        SrSpinner,
        MapRenderer,
        vSelect,
    },
    setup() {
        const rootNode = document.getElementById("app");
        const pageData = JSON.parse(rootNode.getAttribute("data-page"));
        const nearbySearchUrl = "/api/map/searchplaces/";

        const query = ref("");
        const shouldShowFilters = ref(false);
        const filters = reactive({});
        const searchResults = ref([]);
        const pageTokens = reactive({});
        const isLoading = ref(false);
        const Options = reactive({});
        const selected = reactive({});
        const markup = ref(null);
        const MarkersCoor = ref([]);

        watch(query, () => {
            console.log(query.value);
        });

        watch(filters, () => {
            console.log(filters);
        });

        function setMarkup(newMarkup) {
            markup.value = newMarkup;
        }

        function setShowFilters() {
            shouldShowFilters.value = !shouldShowFilters.value;
        }

        const searchNearby = async (pageToken = null) => {
            isLoading.value = true;
            scrollToTop();
            const params = {
                query: query.value,
                ...filters,
                nextPage: pageToken,
            };
            const bindedParams = Object.entries(params)
                .filter((param) => param[1] != null)
                .map((param) => `${param[0]}=${param[1]}`)
                .join("&");
            const searchNearbyRes = await axios.get(
                nearbySearchUrl + "?" + bindedParams
            );
            const searchNearbyResults = searchNearbyRes.data.data;
            const MarksCoorTemp = searchNearbyResults.map(
                (place) => place.geometry.location
            );
            MarkersCoor.value = MarksCoorTemp;
            pageTokens.old_page = pageToken;
            pageTokens.next_page = searchNearbyRes?.data.next_page;
            searchResults.value = [...searchNearbyResults];
            isLoading.value = false;
        };

        const scrollToTop = () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        };

        return {
            query,
            shouldShowFilters,
            selected,
            filters,
            searchResults,
            pageTokens,
            isLoading,
            Options,
            MarkersCoor,
            setShowFilters,
            searchNearby,
            setMarkup,
            markup,
        };
    },
};
</script>

<template>
    <div class="body-container d-flex">
        <div
            class="side-container border-end col-sm-12 col-md-5 col-lg-4 col-xl-3 px-1"
        >
            <!-- Search Navbar -->
            <!-- we'll split into component later -->
            <div id="search-bar" class="nav-container col px-2">
                <nav
                    class="navbar flex-nowrap bg-body-tertiary row-gap-2 px-3 border-bottom"
                >
                    <div class="container-fluid px-0 g-2">
                        <a class="navbar-brand bold">Sumrubs</a>
                        <div class="d-flex gap-2">
                            <input
                                class="form-control"
                                type="text"
                                placeholder="Search"
                                aria-label="Search"
                                v-model="query"
                            />
                            <button
                                class="btn btn-outline-dark"
                                type="button"
                                @click="searchNearby()"
                            >
                                Search
                            </button>
                            <button
                                class="btn btn-secondary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseExample"
                                aria-expanded="{{ shouldShowFilters }}"
                                aria-controls="collapseExample"
                                @click="setShowFilters"
                            >
                                <i class="fa-solid fa-sliders"></i>
                            </button>
                        </div>
                    </div>
                </nav>
                <div class="col collapse" id="collapseExample">
                    <div
                        class="card border-0 rounded-0 rounded-bottom card-body p-3 row-gap-3"
                    >
                        <!-- <div class="row g-2 align-items-center">
                                <div class="col-auto align-self-center">
                                    <label class="col-form-label"
                                        >ใกล้ กับ:
                                    </label>
                                </div>
                                <div class="col">
                                    <input class="form-control" />
                                </div>
                            </div> -->
                        <div class="row g-3 align-items-center">
                            <div class="col-auto align-self-center">
                                <label class="col-form-label">จังหวัด: </label>
                            </div>
                            <div class="col">
                                <v-select
                                    v-model="filters.provinceId"
                                ></v-select>
                            </div>
                            <div class="col-auto align-self-center">
                                <label class="col-form-label">อำเภอ: </label>
                            </div>
                            <div class="col">
                                <v-select
                                    v-model="filters.districtId"
                                ></v-select>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto align-self-center">
                                <label class="col-form-label"
                                    >แขวง/ตำบล:
                                </label>
                            </div>
                            <div class="col">
                                <v-select
                                    v-model="filters.subDistrictId"
                                ></v-select>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto align-self-center">
                                <label class="col-form-label"
                                    >ในระยะทาง :
                                </label>
                            </div>
                            <div class="col">
                                <v-select v-model="filters.radius"></v-select>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto align-self-center">
                                <label class="col-form-label">สถานะ : </label>
                            </div>
                            <div class="col">
                                <div class="form-check d-flex gap-2">
                                    <input
                                        class="form-check-input align-self-center my-auto"
                                        type="checkbox"
                                        v-model="filters.isOpen"
                                    />
                                    <label class="col-form-label"
                                        >เปิดอยู่เท่านั้น</label
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Entity Renderrer -->
            <!-- we'll split into component later -->
            <sr-spinner load-type="mask" :is-loading="isLoading"></sr-spinner>
            <div
                v-if="!isLoading"
                class="card-container row row-gap-4 m-3 mb-3"
            >
                <div
                    class="card col-12 restuaruant-card px-0"
                    v-for="result of searchResults"
                >
                    <h5 class="card-header p-3 d-flex justify-content-between">
                        <div>{{ result.name }}</div>
                        <!-- <div class="ml-auto">
                            <button class="btn btn-danger" type="button">
                                <i
                                    class="fa-solid fa-diamond-turn-right fa-lg"
                                ></i>
                            </button>
                        </div> -->
                    </h5>
                    <div class="card-body">
                        <!-- <h5 class="card-title">Special title treatment</h5> -->
                        <div style="min-height: 80px">
                            <p class="card-text py-3 px-3">
                                {{ result.vicinity }}
                            </p>
                        </div>

                        <div
                            class="card-section-footer justify-content-between row mx-0 my-2 px-0"
                        >
                            <div class="col-auto px-0 align-content-center">
                                <span
                                    v-if="result.rating"
                                    class="badge py-3 px-3"
                                    :class="
                                        result.rating > 2
                                            ? 'text-bg-success'
                                            : 'text-bg-warning'
                                    "
                                    >rating {{ result.rating }}</span
                                >
                            </div>
                            <div class="ml-auto col-auto row gap-2">
                                <button
                                    class="btn btn-primary col py-2 text-bg-primary"
                                >
                                    more...
                                </button>
                                <button
                                    class="btn btn-danger col d-sm-none d-md-block"
                                    type="button"
                                    @click="
                                        () =>
                                            setMarkup(result.geometry.location)
                                    "
                                >
                                    <i class="fa-solid fa-location-pin"></i>
                                </button>
                                <!-- <button class="btn btn-info col py-2">
                                    more...
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="
                        searchResults.length > 0 &&
                        (!!pageTokens.next_page || !!pageTokens.old_page)
                    "
                    class="paginator d-flex col d-flex gap-3 justify-content-center text-center h4"
                >
                    <!-- <a>{{ "<<" }}</a> -->
                    <button
                        class="btn"
                        type="button"
                        @click="searchNearby(pageTokens.old_page)"
                    >
                        {{ "<" }}
                    </button>
                    <button
                        class="btn"
                        type="button"
                        @click="searchNearby(pageTokens.next_page)"
                    >
                        {{ ">" }}
                    </button>
                    <!-- <a>{{ ">>" }}</a> -->
                </div>
            </div>
        </div>

        <div class="content-container col px-0">
            <map-renderer :center="markup" :markers="MarkersCoor" />
        </div>
    </div>
</template>

<style>
.body-container {
    max-height: 100vh;
}
.side-container {
    height: inherit;
    overflow: scroll;
    /* margin-bottom: 2rem; */
    /* background-color: #333; */
}
.card-container {
    height: inherit;
}
.restuaruant-card {
    min-height: 150px;
}
</style>

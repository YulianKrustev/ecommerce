<div class="container">
    <!-- Slider -->
    <div data-hs-carousel='{
    "loadingClasses": "opacity-0",
    "isAutoPlay": true
  }' class="relative">
        <div class="hs-carousel relative overflow-hidden w-full h-[100dvh] bg-white rounded-lg">
            <div class="hs-carousel-body absolute top-0 bottom-0 start-0 flex  transition-transform duration-700 opacity-0 h-full">
                @foreach($carouselImages as $item)
                    <div class="hs-carousel-slide ">
                        <div class="flex justify-around p-1">
                            <div class=" max-w-md z-10">
                                <h2 class="text-5xl font-bold text-black mb-4">{{ $item['title'] }}</h2>
                                <p class="text-lg text-gray-700 mb-6">test</p>
                                <a href="" class="btn-link btn-link_lg default-underline fw-medium animated-button">
                                    Shop Now
                                </a>
                            </div>
                        <span class="self-center text-4xl text-gray-800 transition duration-700">
                            <img src="{{ "/storage/" . $item['image_path'] }}" alt="{{ $item['alt_text'] }}" >
                        </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
    <!-- End Slider -->


    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.querySelector(".animated-button");
        setTimeout(() => {
            button.classList.add("show");
        }, 500); // Delay the animation by 500ms
    });


</script>

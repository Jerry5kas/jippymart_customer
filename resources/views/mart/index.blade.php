<x-layouts.app>
    <x-mart.top-cat-items :categories="$categories"/>
    <x-mart.carousel :banners="$banners"/>
    <x-mart.banner-card :products="$spotlight"/>
    <x-mart.categories />
    <x-mart.banner-card/>

    <!-- ✅ Our New Dynamic Section & Subcategories Block -->
    <div class="sm:w-[90%] w-full mx-auto space-y-8 py-8">
        @foreach($sections as $sectionName => $subcategories)
            <div>
                <h2 class="text-2xl font-semibold mb-4">{{ $sectionName }}</h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 lg:grid-cols-9 gap-4 justify-center">
                    @foreach($subcategories as $subcategory)
                        <x-mart.category-card
                            :title="$subcategory['title']"
                            :image="$subcategory['photo']" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <!-- ✅ End of New Section -->

    <x-mart.categories :categories="$categories"/>

    <div class="w-full sm:w-[90%] mx-auto flex md:flex-row flex-col gap-4">
        <x-mart.small-cat-carousel :products="$featured" title="Featured Products"/>
        <x-mart.small-cat-carousel :products="$trendingProducts" title="Trending Products" />
    </div>

    <div class="w-full sm:w-[90%] mx-auto flex md:flex-row flex-col gap-4">
        <x-mart.small-cat-carousel :products="$bestSellerProducts" title="Best Seller Products" />
        <x-mart.small-cat-carousel :products="$stealOfMomentProducts" title="Steal Of Products" />

    </div>

    <div class="w-full sm:w-[90%] mx-auto flex md:flex-row flex-col gap-4">
        <x-mart.small-cat-carousel :products="$newArrivalProducts" title="New Product" />
        <x-mart.small-cat-carousel :products="$seasonalProducts" title="Seasonal Products" />
   </div>

    <div class="pb-16 space-y-8">
        <x-mart.banner-card/>
        <x-mart.item-card headings="Get Your Home Needs"/>
        <x-mart.banner-card/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
    </div>
</x-layouts.app>


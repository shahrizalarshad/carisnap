<x-layouts.public>
    <div class="space-y-12 pb-12">
        <div class="border-b border-gray-200 pb-5">
            <h1 class="text-3xl font-heading font-bold text-gray-900">CariSnap Styleguide</h1>
            <p class="mt-2 text-sm text-gray-500">A visual reference for reusable Blade UI components, optimized for 390px mobile viewports.</p>
        </div>

        <!-- Typography -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Typography</h2>
            <div class="space-y-2 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <h1 class="text-4xl font-heading font-bold">Heading 1 (Bricolage Grotesque)</h1>
                <h2 class="text-3xl font-heading font-bold">Heading 2</h2>
                <h3 class="text-2xl font-heading font-semibold">Heading 3</h3>
                <h4 class="text-xl font-heading font-semibold">Heading 4</h4>
                <p class="text-base text-gray-600 font-sans mt-4">
                    Body text uses Inter. This is a paragraph showcasing how the font reads in a standard block of text. It should feel clean, modern, and highly legible even on small mobile screens.
                </p>
            </div>
        </section>

        <!-- Colors -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Brand Colors</h2>
            <div class="grid grid-cols-5 gap-2 sm:grid-cols-10">
                <div class="aspect-square bg-brand-50 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-100 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-200 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-300 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-400 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-500 rounded shadow-sm flex items-center justify-center text-white text-xs font-bold">500</div>
                <div class="aspect-square bg-brand-600 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-700 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-800 rounded shadow-sm"></div>
                <div class="aspect-square bg-brand-900 rounded shadow-sm"></div>
            </div>
        </section>

        <!-- Buttons -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Buttons</h2>
            <div class="flex flex-wrap gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <x-ui.button variant="primary">Primary Button</x-ui.button>
                <x-ui.button variant="secondary">Secondary Button</x-ui.button>
                <x-ui.button variant="outline">Outline Button</x-ui.button>
                <x-ui.button variant="ghost">Ghost Button</x-ui.button>
            </div>
            <div class="flex flex-wrap items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <x-ui.button size="sm">Small (sm)</x-ui.button>
                <x-ui.button size="md">Medium (md)</x-ui.button>
                <x-ui.button size="lg">Large (lg)</x-ui.button>
            </div>
        </section>

        <!-- Badges & Filter Pills -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Badges & Pills</h2>
            <div class="flex flex-wrap gap-2 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <x-ui.badge color="gray">Default</x-ui.badge>
                <x-ui.badge color="brand">Pro Member</x-ui.badge>
                <x-ui.badge color="green">Available</x-ui.badge>
                <x-ui.badge color="yellow">Pending</x-ui.badge>
                <x-ui.badge color="red">Declined</x-ui.badge>
            </div>
            <div class="flex flex-wrap gap-2 p-4 bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto pb-4">
                <x-ui.filter-pill :active="true">All Packages</x-ui.filter-pill>
                <x-ui.filter-pill>Wedding</x-ui.filter-pill>
                <x-ui.filter-pill>Klang Valley</x-ui.filter-pill>
                <x-ui.filter-pill>Under RM1000</x-ui.filter-pill>
            </div>
        </section>

        <!-- Star Rating -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Star Rating</h2>
            <div class="flex flex-col gap-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <x-ui.star-rating :rating="5" :count="124" />
                <x-ui.star-rating :rating="4.5" :count="28" />
                <x-ui.star-rating :rating="3" :count="2" />
                <x-ui.star-rating :rating="0" />
            </div>
        </section>

        <!-- Cards & Skeletons -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Cards & Skeletons</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Example Card -->
                <x-ui.card class="p-4 flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex-shrink-0"></div>
                        <div>
                            <h3 class="font-heading font-semibold text-gray-900">Aiman Studio</h3>
                            <p class="text-xs text-gray-500">Kuala Lumpur</p>
                        </div>
                    </div>
                    <div class="w-full h-40 bg-gray-100 rounded-xl mt-2 flex items-center justify-center text-gray-400 text-sm">
                        [Portfolio Image]
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <x-ui.star-rating :rating="4.8" :count="42" />
                        <span class="font-semibold text-brand-600">From RM1,200</span>
                    </div>
                </x-ui.card>

                <!-- Skeleton Card -->
                <x-ui.card class="p-4 flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <x-ui.skeleton type="avatar" />
                        <div class="flex-grow space-y-2">
                            <x-ui.skeleton type="title" />
                            <x-ui.skeleton type="text" class="w-1/2" />
                        </div>
                    </div>
                    <x-ui.skeleton type="image" class="mt-2" />
                    <div class="flex justify-between items-center mt-2 gap-4">
                        <x-ui.skeleton type="text" class="w-1/3" />
                        <x-ui.skeleton type="text" class="w-1/4" />
                    </div>
                </x-ui.card>
            </div>
        </section>

        <!-- Bottom Sheet Trigger -->
        <section class="space-y-4">
            <h2 class="text-xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Interactive Bottom Sheet (Mobile)</h2>
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100" x-data>
                <p class="text-sm text-gray-500 mb-4">Click below to open the bottom sheet modal. On small screens (< 640px) it slides up from the bottom. On larger screens, it acts as a centered modal.</p>
                <x-ui.button variant="primary" @click="$dispatch('open-sheet-filters')">
                    Open Filter Sheet
                </x-ui.button>
            </div>
            
            <x-ui.bottom-sheet id="filters" title="Filter Results">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Event Type</h4>
                        <div class="flex flex-wrap gap-2">
                            <x-ui.filter-pill :active="true">Wedding</x-ui.filter-pill>
                            <x-ui.filter-pill>Pre-Wedding</x-ui.filter-pill>
                            <x-ui.filter-pill>Engagement</x-ui.filter-pill>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Budget Range</h4>
                        <div class="flex flex-wrap gap-2">
                            <x-ui.filter-pill>Under RM1000</x-ui.filter-pill>
                            <x-ui.filter-pill :active="true">RM1000 - RM3000</x-ui.filter-pill>
                            <x-ui.filter-pill>RM3000+</x-ui.filter-pill>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <x-ui.button variant="ghost" @click="$dispatch('close-sheet-filters')">Clear</x-ui.button>
                        <x-ui.button variant="primary" @click="$dispatch('close-sheet-filters')">Apply Filters</x-ui.button>
                    </div>
                </div>
            </x-ui.bottom-sheet>
        </section>
    </div>
</x-layouts.public>

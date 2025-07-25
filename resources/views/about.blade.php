<x-app-layout meta-title="about my sad corporate life" meta-description="hehehehe">
     <!-- Post Section -->
        <section class="w-full  flex flex-col items-center px-3">

            <article class="w-full flex flex-col shadow my-4">
                <!-- Article Image -->
                    <a href="#" class="hover:opacity-75">
                        <img src="{{ $widget->image }}" alt="Image">
                    </a>
                    <div class="bg-white flex flex-col justify-start p-6">
                    <h1 class="text-3xl font-bold hover:text-gray-700 pb-4">{{ $widget->title }}</h1>
                    </div>
                    <div>
                        {!! $widget->body !!}
                    </div>

                   </div>
            </article>

        </section>

</x-app-layout>
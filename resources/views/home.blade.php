   <x-app-layout meta-description="doing Blog project on laravel sitting in office when the weather outside is so good">
   <section class="w-full md:w-2/3 flex flex-col items-center px-3">

    @foreach ($posts as $post)
    <x-post-item :post="$post"></x-post-item>
    
    @endforeach  

    {{$posts->onEachSide(1)->links()}}


        </section>

        <!-- Sidebar Section -->
       <x-sidebar/>
    </x-app-layout>
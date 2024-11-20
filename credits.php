<?php
echo '
<div class="container mx-auto p-6">
    <!-- Header Section -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-blue-700">'.__('Credits & Attribution').'</h1>
        <p class="mt-2 text-lg text-gray-600">'.
            __('This page acknowledges the creators of the free resources used in our projects. 
            We respect their work and provide proper attribution as required by their licenses.').'
        </p>
    </header>
    <!-- Icons Section -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">'.__('Icons').'</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-md">
                <img src="https://cdn-icons-png.flaticon.com/512/61/61456.png" alt="Edit Icon" class="w-12 h-12">
                <p class="text-sm">';
                    echo '<a href='.$session->get('absoluteURL').'/themes/'.$session->get('gibbonThemeName').'/img/m_editIcon.png'.'" title="edit icons" 
                       class="text-blue-600 hover:underline">
                        Edit icons '.__('created by').' Pixel perfect - Flaticon
                    </a>
                </p>
            </div>
            <!-- Add more icons similarly -->
        </div>
    </section>';

//     <!-- Images Section -->
//     <section class="mb-12">
//         <h2 class="text-2xl font-semibold text-blue-600 mb-4">Images</h2>
//         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
//             <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-md">
//                 <img src="https://via.placeholder.com/80" alt="Sample Image" class="w-12 h-12">
//                 <p class="text-sm">
//                     <a href="https://unsplash.com" title="Unsplash" 
//                        class="text-blue-600 hover:underline">
//                         Image from Unsplash
//                     </a>
//                 </p>
//             </div>
//             <!-- Add more images similarly -->
//         </div>
//     </section>

//     <!-- Videos Section -->
//     <section class="mb-12">
//         <h2 class="text-2xl font-semibold text-blue-600 mb-4">Videos</h2>
//         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
//             <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-md">
//                 <img src="https://via.placeholder.com/80" alt="Video Placeholder" class="w-12 h-12">
//                 <p class="text-sm">
//                     <a href="https://www.pexels.com/videos" title="Pexels Videos" 
//                        class="text-blue-600 hover:underline">
//                         Videos from Pexels
//                     </a>
//                 </p>
//             </div>
//             <!-- Add more videos similarly -->
//         </div>
//     </section>

//     <!-- Footer Section -->
//     <footer class="text-center mt-8 text-sm text-gray-500">
//         &copy; ' . date("Y") . ' Your Company. All rights reserved.
//     </footer>
// </div>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NovaFrame</title>
    <link rel="icon" href="<?php echo baseurl('logo.ico') ?>">
    <link rel="stylesheet" href="<?php echo baseurl('assets/css/output.css') ?>">
</head>
<body>
    <div class="bg-white relative isolate h-screen py-24 sm:py-32 font-sans">
        <div
                class="fixed -z-20 inset-0 h-full w-full bg-white bg-[radial-gradient(#dedfe0_1px,transparent_1px)] [background-size:28px_28px]"
        ></div>

        <img src="<?php echo baseurl('logo.png') ?>" alt="NovaFrame logo" class="fixed -z-10 bottom-12 left-1/2 -translate-x-1/2 w-24 h-24 opacity-80">

        <!--        <div class="absolute inset-0 bg-[url(/novaframe/public/welcome/grid.svg)] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>-->
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-60 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">Small PHP Framework</h2>

                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    NovaFrame <span class="text-sm text-violet-500">v <?php echo app()->version() ?></span>
                </p>
                <p class="mt-4 text-lg leading-8 text-gray-600" style="z-index: 999">
                    Inspired by various PHP Frameworks, especially from <a href="https://laravel.com" target="_blank" class="text-violet-500">Laravel</a>, <a href="https://codeigniter.com" target="_blank" class="text-violet-500">Codeigniter</a> and <a href="https://phplucidframe.com" target="_blank" class="text-violet-500">PHPLucidFrame</a>
                </p>
            </div>
            <div class="mx-auto max-w-2xl mt-14 lg:mt-24 lg:max-w-4xl z-50">
                <dl class="grid max-w-xl grid-cols-1 gap-x-5 gap-y-5 lg:max-w-none lg:grid-cols-2 lg:gap-y-10">
                    <div class="relative p-5 border border-zinc-200 border-opacity-10 hover:shadow-md transition duration-300 bg-transparent backdrop-blur-md hover:border hover:border-blue-100 rounded-lg hover:cursor-pointer">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            About
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">
                            Novaframe is an application development basic framework with MVC pattern that provides common tasks that every web application needed. It aims to simplify the development  process.
                        </dd>
                    </div>
                    <div class="relative p-5 border border-zinc-200 border-opacity-10 hover:shadow-md transition duration-300 bg-transparent backdrop-blur-md hover:border hover:border-blue-100 rounded-lg hover:cursor-pointer">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            Page
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">
                            <p class="">This page is generated by NovaFrame, and you can find at:</p><br>
                            <table class="table w-full text-sm">
                                <tr class="border-b">
                                    <td class="border-e px-3">Route</td>
                                    <td class="px-3">app/Routes/app.php</td>
                                </tr>
                                <tr>
                                    <td class="border-e px-3">View</td>
                                    <td class="px-3">app/Views/welcome.php</td>
                                </tr>
                            </table>
                        </dd>
                    </div>
                    <div class="relative grid-cols-2 p-5 border border-zinc-200 border-opacity-10 hover:shadow-md transition duration-300 bg-transparent backdrop-blur-md hover:border hover:border-blue-100 rounded-lg hover:cursor-pointer">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            Documentation
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">
                            <a class="text-violet-500 block" href="https://github.com/naingaunglwin-dev/novaframe" target="_blank">Novaframe Framework Doc</a>
                        </dd>
                    </div>
                    <div class="relative grid-cols-2 p-5 border border-zinc-200 border-opacity-10 hover:shadow-md transition duration-300 bg-transparent backdrop-blur-md mb-44 md:mb-0 hover:border hover:border-blue-100 rounded-lg hover:cursor-pointer">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            Contributing
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">
                            Novaframe is an open-source project, and contributions are welcome. If you have any suggestions, bug reports, or feature requests, please open an issue or submit a pull request on the project repository.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="fixed inset-x-0 top-[calc(79%-30rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(79%-30rem)]" aria-hidden="true">
            <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-60 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>

        <div class="fixed bottom-2 text-center w-full text-sm text-indigo-600 opacity-40">
            &copy; 2025 Naing Aung Lwin
        </div>
    </div>
</body>
</html>

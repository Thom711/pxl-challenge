<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PXL - Challenge</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <main class="flex justify-center">
                <div class="w-1/2 bg-white p-10 rounded-lg mt-14">
                    <form action="/" method="POST" enctype="multipart/form-data">
                        @csrf
                    
                        <div class="mb-6">
                            <label class="block mb-2 uppercase font-bold" for="file">
                                Select File Here: <br>
                                <span class="lowercase text-xs">(It's in resources/opdracht)</span>
                            </label>

                            <input class="border border-gray-400 p-2 w-full rounded" type="file" name="file"id="file">

                            @error('file')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <button type="submit" class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg shadow-md 
                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                                Submit
                            </button>
                        </div>

                        <div class="{{ $job ? 'text-xs text-blue-400 border border-blue-400 w-full rounded flex justify-center font-bold' : ''}}">
                            <p>{{ $job }}</p>
                        </div>
                    </form>
                </div>
            </main>

            <footer class="flex justify-center">
                <div class="w-1/2 bg-white p-10 rounded-lg mt-20 flex justify-between items-center">
                    <div>
                        <img src="images/tiny.jpg" class="rounded-full">
                    </div>
                    <div>
                        <p>Hello people at PXL!</p>
                        <p>I had fun making this assignment.</p>
                        <p>And I hope to see you soon!</p>
                        <br>
                        <p>Greetings, Thom van der Veldt</p>
                        <p>March, 2021</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>

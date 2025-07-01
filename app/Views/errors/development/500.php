<!doctype html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap" rel="stylesheet">

    <!-- Highlight.js CSS -->
    <link id="hljs-theme" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/atom-one-light.min.css" />
    <!-- Highlight.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.0/languages/php.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        :root {
            --error-highlight: rgba(255, 63,63, 0.4);
            --dark-error-highlight: rgba(255, 63, 63, 0.2);
        }
    </style>
    <style>
        /*! tailwindcss v4.1.6 | MIT License | https://tailwindcss.com */@layer properties;@layer theme, base, components, utilities;@layer theme{:host,:root{--font-sans:Geist,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-100:oklch(93.6% 0.032 17.717);--color-red-200:oklch(88.5% 0.062 18.334);--color-red-300:oklch(80.8% 0.114 19.571);--color-red-400:oklch(70.4% 0.191 22.216);--color-red-500:oklch(63.7% 0.237 25.331);--color-red-600:oklch(57.7% 0.245 27.325);--color-green-100:oklch(96.2% 0.044 156.743);--color-green-300:oklch(87.1% 0.15 154.449);--color-green-400:oklch(79.2% 0.209 151.711);--color-green-500:oklch(72.3% 0.219 149.579);--color-sky-500:oklch(68.5% 0.169 237.323);--color-sky-600:oklch(58.8% 0.158 241.966);--color-blue-300:oklch(80.9% 0.105 251.813);--color-blue-600:oklch(54.6% 0.245 262.881);--color-indigo-600:oklch(51.1% 0.262 276.966);--color-indigo-700:oklch(45.7% 0.24 277.023);--color-purple-100:oklch(94.6% 0.033 307.174);--color-purple-300:oklch(82.7% 0.119 306.383);--color-purple-800:oklch(43.8% 0.218 303.724);--color-purple-900:oklch(38.1% 0.176 304.987);--color-gray-100:oklch(96.7% 0.003 264.542);--color-gray-200:oklch(92.8% 0.006 264.531);--color-gray-300:oklch(87.2% 0.01 258.338);--color-gray-400:oklch(70.7% 0.022 261.325);--color-gray-500:oklch(55.1% 0.027 264.364);--color-gray-600:oklch(44.6% 0.03 256.802);--color-gray-700:oklch(37.3% 0.034 259.733);--color-gray-800:oklch(27.8% 0.033 256.848);--color-gray-900:oklch(21% 0.034 264.665);--color-zinc-200:oklch(92% 0.004 286.32);--color-zinc-300:oklch(87.1% 0.006 286.286);--color-zinc-400:oklch(70.5% 0.015 286.067);--color-zinc-500:oklch(55.2% 0.016 285.938);--color-zinc-600:oklch(44.2% 0.017 285.786);--color-zinc-700:oklch(37% 0.013 285.805);--color-zinc-800:oklch(27.4% 0.006 286.033);--color-neutral-400:oklch(70.8% 0 0);--color-neutral-500:oklch(55.6% 0 0);--color-black:#000;--color-white:#fff;--spacing:0.25rem;--container-md:28rem;--text-xs:0.75rem;--text-xs--line-height:1.33333;--text-sm:0.875rem;--text-sm--line-height:1.42857;--text-base:1rem;--text-base--line-height:1.5;--text-lg:1.125rem;--text-lg--line-height:1.55556;--text-xl:1.25rem;--text-xl--line-height:1.4;--text-3xl:1.875rem;--text-3xl--line-height:1.2;--text-7xl:4.5rem;--text-7xl--line-height:1;--font-weight-normal:400;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--radius-md:0.375rem;--radius-lg:0.5rem;--radius-xl:0.75rem;--default-transition-duration:150ms;--default-transition-timing-function:cubic-bezier(0.4,0,0.2,1);--default-font-family:var(--font-sans);--default-mono-font-family:var(--font-mono)}}@layer base{*,::backdrop,::file-selector-button,:after,:before{border:0 solid;box-sizing:border-box;margin:0;padding:0}:host,html{line-height:1.5;-webkit-text-size-adjust:100%;font-family:var(--default-font-family,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji");font-feature-settings:var(--default-font-feature-settings,normal);font-variation-settings:var(--default-font-variation-settings,normal);tab-size:4;-webkit-tap-highlight-color:transparent}hr{border-top-width:1px;color:inherit;height:0}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:var(--default-mono-font-family,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace);font-feature-settings:var(--default-mono-font-feature-settings,normal);font-size:1em;font-variation-settings:var(--default-mono-font-variation-settings,normal)}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{border-collapse:collapse;border-color:inherit;text-indent:0}:-moz-focusring{outline:auto}progress{vertical-align:baseline}summary{display:list-item}menu,ol,ul{list-style:none}audio,canvas,embed,iframe,img,object,svg,video{display:block;vertical-align:middle}img,video{height:auto;max-width:100%}::file-selector-button,button,input,optgroup,select,textarea{background-color:transparent;border-radius:0;color:inherit;font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;opacity:1}:where(select:is([multiple],[size])) optgroup{font-weight:bolder}:where(select:is([multiple],[size])) optgroup option{padding-inline-start:20px}::file-selector-button{margin-inline-end:4px}::placeholder{opacity:1}@supports (not (-webkit-appearance:-apple-pay-button)) or (contain-intrinsic-size:1px){::placeholder{color:currentcolor;@supports (color:color-mix(in lab,red,red)){color:color-mix(in oklab,currentcolor 50%,transparent)}}}textarea{resize:vertical}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-date-and-time-value{min-height:1lh;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-datetime-edit,::-webkit-datetime-edit-day-field,::-webkit-datetime-edit-hour-field,::-webkit-datetime-edit-meridiem-field,::-webkit-datetime-edit-millisecond-field,::-webkit-datetime-edit-minute-field,::-webkit-datetime-edit-month-field,::-webkit-datetime-edit-second-field,::-webkit-datetime-edit-year-field{padding-block:0}:-moz-ui-invalid{box-shadow:none}::file-selector-button,button,input:where([type=button],[type=reset],[type=submit]){appearance:button}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[hidden]:where(:not([hidden=until-found])){display:none!important}}@layer utilities{.collapse{visibility:collapse}.visible{visibility:visible}.absolute{position:absolute}.fixed{position:fixed}.relative{position:relative}.static{position:static}.sticky{position:sticky}.top-0{top:calc(var(--spacing)*0)}.top-4{top:calc(var(--spacing)*4)}.top-5{top:calc(var(--spacing)*5)}.top-6{top:calc(var(--spacing)*6)}.right-1{right:calc(var(--spacing)*1)}.right-2{right:calc(var(--spacing)*2)}.bottom-0{bottom:calc(var(--spacing)*0)}.bottom-1{bottom:calc(var(--spacing)*1)}.left-\[20\%\]{left:20%}.left-\[23\%\]{left:23%}.left-\[25\%\]{left:25%}.left-\[30\%\]{left:30%}.isolate{isolation:isolate}.z-10{z-index:10}.col-1{grid-column:1}.col-11{grid-column:11}.col-span-1{grid-column:span 1/span 1}.container{width:100%;@media (width >= 40rem){max-width:40rem}@media (width >= 48rem){max-width:48rem}@media (width >= 64rem){max-width:64rem}@media (width >= 80rem){max-width:80rem}@media (width >= 96rem){max-width:96rem}}.m-auto{margin:auto}.mx-1{margin-inline:calc(var(--spacing)*1)}.mx-2{margin-inline:calc(var(--spacing)*2)}.mx-auto{margin-inline:auto}.my-1{margin-block:calc(var(--spacing)*1)}.my-10{margin-block:calc(var(--spacing)*10)}.my-12{margin-block:calc(var(--spacing)*12)}.ms-10{margin-inline-start:calc(var(--spacing)*10)}.ms-12{margin-inline-start:calc(var(--spacing)*12)}.me-2{margin-inline-end:calc(var(--spacing)*2)}.me-3{margin-inline-end:calc(var(--spacing)*3)}.me-10{margin-inline-end:calc(var(--spacing)*10)}.mt-1{margin-top:calc(var(--spacing)*1)}.mt-2{margin-top:calc(var(--spacing)*2)}.mt-4{margin-top:calc(var(--spacing)*4)}.mt-5{margin-top:calc(var(--spacing)*5)}.mt-6{margin-top:calc(var(--spacing)*6)}.mt-10{margin-top:calc(var(--spacing)*10)}.mr-2{margin-right:calc(var(--spacing)*2)}.mb-1{margin-bottom:calc(var(--spacing)*1)}.mb-2{margin-bottom:calc(var(--spacing)*2)}.mb-4{margin-bottom:calc(var(--spacing)*4)}.mb-5{margin-bottom:calc(var(--spacing)*5)}.ml-1{margin-left:calc(var(--spacing)*1)}.ml-2{margin-left:calc(var(--spacing)*2)}.ml-4{margin-left:calc(var(--spacing)*4)}.block{display:block}.contents{display:contents}.flex{display:flex}.grid{display:grid}.hidden{display:none}.inline{display:inline}.inline-block{display:inline-block}.inline-flex{display:inline-flex}.table{display:table}.size-4{height:calc(var(--spacing)*4);width:calc(var(--spacing)*4)}.h-3{height:calc(var(--spacing)*3)}.h-4{height:calc(var(--spacing)*4)}.h-56{height:calc(var(--spacing)*56)}.h-dvh{height:100dvh}.h-full{height:100%}.h-px{height:1px}.h-screen{height:100vh}.w-1{width:calc(var(--spacing)*1)}.w-1\/2{width:50%}.w-1\/3{width:33.33333%}.w-2{width:calc(var(--spacing)*2)}.w-2\/3{width:66.66667%}.w-2\/5{width:40%}.w-3{width:calc(var(--spacing)*3)}.w-3\/4{width:75%}.w-3\/5{width:60%}.w-3\/6{width:50%}.w-4{width:calc(var(--spacing)*4)}.w-4\/5{width:80%}.w-4\/6{width:66.66667%}.w-5{width:calc(var(--spacing)*5)}.w-5\/6{width:83.33333%}.w-\[52\%\]{width:52%}.w-\[54\%\]{width:54%}.w-\[54\.5\%\]{width:54.5%}.w-\[55\%\]{width:55%}.w-\[64\%\]{width:64%}.w-\[65\%\]{width:65%}.w-\[800px\]{width:800px}.w-fit{width:fit-content}.w-full{width:100%}.w-screen{width:100vw}.max-w-96{max-width:calc(var(--spacing)*96)}.max-w-150{max-width:calc(var(--spacing)*150)}.min-w-\[400px\]{min-width:400px}.flex-1{flex:1}.flex-shrink{flex-shrink:1}.flex-shrink-0{flex-shrink:0}.shrink{flex-shrink:1}.shrink-0{flex-shrink:0}.flex-grow,.grow{flex-grow:1}.table-fixed{table-layout:fixed}.border-collapse{border-collapse:collapse}.rotate-90{rotate:90deg}.transform{transform:var(--tw-rotate-x,) var(--tw-rotate-y,) var(--tw-rotate-z,) var(--tw-skew-x,) var(--tw-skew-y,)}.resize{resize:both}.list-decimal{list-style-type:decimal}.list-disc{list-style-type:disc}.list-none{list-style-type:none}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}.place-content-center{place-content:center}.items-center{align-items:center}.items-end{align-items:flex-end}.justify-between{justify-content:space-between}.justify-center{justify-content:center}.justify-end{justify-content:flex-end}.justify-evenly{justify-content:space-evenly}.gap-1{gap:calc(var(--spacing)*1)}.gap-3{gap:calc(var(--spacing)*3)}.gap-10{gap:calc(var(--spacing)*10)}.space-y-2{:where(&>:not(:last-child)){--tw-space-y-reverse:0;margin-block-end:calc(var(--spacing)*2*(1 - var(--tw-space-y-reverse)));margin-block-start:calc(var(--spacing)*2*var(--tw-space-y-reverse))}}.space-x-1{:where(&>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-end:calc(var(--spacing)*1*(1 - var(--tw-space-x-reverse)));margin-inline-start:calc(var(--spacing)*1*var(--tw-space-x-reverse))}}.space-x-10{:where(&>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-end:calc(var(--spacing)*10*(1 - var(--tw-space-x-reverse)));margin-inline-start:calc(var(--spacing)*10*var(--tw-space-x-reverse))}}.truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.overflow-scroll{overflow:scroll}.overflow-x-scroll{overflow-x:scroll}.overflow-y-scroll{overflow-y:scroll}.rounded{border-radius:.25rem}.rounded-full{border-radius:calc(infinity*1px)}.rounded-lg{border-radius:var(--radius-lg)}.rounded-md{border-radius:var(--radius-md)}.rounded-xl{border-radius:var(--radius-xl)}.rounded-t{border-top-left-radius:.25rem;border-top-right-radius:.25rem}.rounded-t-lg{border-top-left-radius:var(--radius-lg);border-top-right-radius:var(--radius-lg)}.rounded-b{border-bottom-left-radius:.25rem;border-bottom-right-radius:.25rem}.rounded-b-lg{border-bottom-left-radius:var(--radius-lg);border-bottom-right-radius:var(--radius-lg)}.border{border-style:var(--tw-border-style);border-width:1px}.border-2{border-style:var(--tw-border-style);border-width:2px}.border-4{border-style:var(--tw-border-style);border-width:4px}.border-t{border-top-style:var(--tw-border-style);border-top-width:1px}.border-t-0{border-top-style:var(--tw-border-style);border-top-width:0}.border-t-\[12px\]{border-top-style:var(--tw-border-style);border-top-width:12px}.border-t-\[14px\]{border-top-style:var(--tw-border-style);border-top-width:14px}.border-r{border-right-style:var(--tw-border-style);border-right-width:1px}.border-b{border-bottom-style:var(--tw-border-style);border-bottom-width:1px}.border-b-4{border-bottom-style:var(--tw-border-style);border-bottom-width:4px}.border-b-8{border-bottom-style:var(--tw-border-style);border-bottom-width:8px}.border-b-\[12px\]{border-bottom-style:var(--tw-border-style);border-bottom-width:12px}.border-black{border-color:var(--color-black)}.border-gray-200{border-color:var(--color-gray-200)}.border-gray-300{border-color:var(--color-gray-300)}.border-gray-400{border-color:var(--color-gray-400)}.border-white{border-color:var(--color-white)}.border-zinc-200{border-color:var(--color-zinc-200)}.border-zinc-300{border-color:var(--color-zinc-300)}.border-zinc-400{border-color:var(--color-zinc-400)}.border-zinc-600{border-color:var(--color-zinc-600)}.border-zinc-700{border-color:var(--color-zinc-700)}.bg-\[var\(--error-highlight\)\]{background-color:var(--error-highlight)}.bg-black{background-color:var(--color-black)}.bg-blue-300{background-color:var(--color-blue-300)}.bg-gray-100{background-color:var(--color-gray-100)}.bg-gray-200{background-color:var(--color-gray-200)}.bg-gray-300{background-color:var(--color-gray-300)}.bg-gray-400{background-color:var(--color-gray-400)}.bg-gray-500{background-color:var(--color-gray-500)}.bg-green-100{background-color:var(--color-green-100)}.bg-green-300{background-color:var(--color-green-300)}.bg-green-500{background-color:var(--color-green-500)}.bg-indigo-600{background-color:var(--color-indigo-600)}.bg-purple-100{background-color:var(--color-purple-100)}.bg-red-200{background-color:var(--color-red-200)}.bg-transparent{background-color:transparent}.bg-white{background-color:var(--color-white)}.bg-gradient-to-r{--tw-gradient-position:to right in oklab;background-image:linear-gradient(var(--tw-gradient-stops))}.from-transparent{--tw-gradient-from:transparent;--tw-gradient-stops:var(--tw-gradient-via-stops,var(--tw-gradient-position),var(--tw-gradient-from) var(--tw-gradient-from-position),var(--tw-gradient-to) var(--tw-gradient-to-position))}.via-neutral-500{--tw-gradient-via:var(--color-neutral-500);--tw-gradient-via-stops:var(--tw-gradient-position),var(--tw-gradient-from) var(--tw-gradient-from-position),var(--tw-gradient-via) var(--tw-gradient-via-position),var(--tw-gradient-to) var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-via-stops)}.to-transparent{--tw-gradient-to:transparent;--tw-gradient-stops:var(--tw-gradient-via-stops,var(--tw-gradient-position),var(--tw-gradient-from) var(--tw-gradient-from-position),var(--tw-gradient-to) var(--tw-gradient-to-position))}.p-0{padding:calc(var(--spacing)*0)}.p-0\.5{padding:calc(var(--spacing)*.5)}.p-1{padding:calc(var(--spacing)*1)}.p-2{padding:calc(var(--spacing)*2)}.p-3{padding:calc(var(--spacing)*3)}.p-6{padding:calc(var(--spacing)*6)}.px-1{padding-inline:calc(var(--spacing)*1)}.px-2{padding-inline:calc(var(--spacing)*2)}.px-2\.5{padding-inline:calc(var(--spacing)*2.5)}.px-3{padding-inline:calc(var(--spacing)*3)}.px-4{padding-inline:calc(var(--spacing)*4)}.px-6{padding-inline:calc(var(--spacing)*6)}.px-8{padding-inline:calc(var(--spacing)*8)}.py-0{padding-block:calc(var(--spacing)*0)}.py-0\.5{padding-block:calc(var(--spacing)*.5)}.py-1{padding-block:calc(var(--spacing)*1)}.py-2{padding-block:calc(var(--spacing)*2)}.py-3{padding-block:calc(var(--spacing)*3)}.py-5{padding-block:calc(var(--spacing)*5)}.py-10{padding-block:calc(var(--spacing)*10)}.text-center{text-align:center}.text-end{text-align:end}.text-right{text-align:right}.text-start{text-align:start}.font-sans{font-family:var(--font-sans)}.text-3xl{font-size:var(--text-3xl);line-height:var(--tw-leading,var(--text-3xl--line-height))}.text-7xl{font-size:var(--text-7xl);line-height:var(--tw-leading,var(--text-7xl--line-height))}.text-base{font-size:var(--text-base);line-height:var(--tw-leading,var(--text-base--line-height))}.text-lg{font-size:var(--text-lg);line-height:var(--tw-leading,var(--text-lg--line-height))}.text-sm{font-size:var(--text-sm);line-height:var(--tw-leading,var(--text-sm--line-height))}.text-xl{font-size:var(--text-xl);line-height:var(--tw-leading,var(--text-xl--line-height))}.text-xs{font-size:var(--text-xs);line-height:var(--tw-leading,var(--text-xs--line-height))}.text-\[0\.9rem\]{font-size:.9rem}.text-\[0\.95rem\]{font-size:.95rem}.leading-7{--tw-leading:calc(var(--spacing)*7);line-height:calc(var(--spacing)*7)}.leading-9{--tw-leading:calc(var(--spacing)*9);line-height:calc(var(--spacing)*9)}.font-bold{--tw-font-weight:var(--font-weight-bold);font-weight:var(--font-weight-bold)}.font-medium{--tw-font-weight:var(--font-weight-medium);font-weight:var(--font-weight-medium)}.font-normal{--tw-font-weight:var(--font-weight-normal);font-weight:var(--font-weight-normal)}.font-semibold{--tw-font-weight:var(--font-weight-semibold);font-weight:var(--font-weight-semibold)}.text-nowrap{text-wrap:nowrap}.text-wrap{text-wrap:wrap}.break-all{word-break:break-all}.text-black{color:var(--color-black)}.text-blue-600{color:var(--color-blue-600)}.text-gray-300{color:var(--color-gray-300)}.text-gray-400{color:var(--color-gray-400)}.text-gray-500{color:var(--color-gray-500)}.text-gray-600{color:var(--color-gray-600)}.text-gray-700{color:var(--color-gray-700)}.text-gray-800{color:var(--color-gray-800)}.text-gray-900{color:var(--color-gray-900)}.text-purple-800{color:var(--color-purple-800)}.text-red-400{color:var(--color-red-400)}.text-red-500{color:var(--color-red-500)}.text-red-600{color:var(--color-red-600)}.text-white{color:var(--color-white)}.text-zinc-500{color:var(--color-zinc-500)}.lowercase{text-transform:lowercase}.uppercase{text-transform:uppercase}.italic{font-style:italic}.ordinal{--tw-ordinal:ordinal;font-variant-numeric:var(--tw-ordinal,) var(--tw-slashed-zero,) var(--tw-numeric-figure,) var(--tw-numeric-spacing,) var(--tw-numeric-fraction,)}.underline{text-decoration-line:underline}.opacity-10{opacity:10%}.opacity-25{opacity:25%}.shadow{--tw-shadow:0 1px 3px 0 var(--tw-shadow-color,rgba(0,0,0,.1)),0 1px 2px -1px var(--tw-shadow-color,rgba(0,0,0,.1));box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-\[0px_4px_16px_rgba\(17\,17\,26\,0\.1\)\,_0px_8px_24px_rgba\(17\,17\,26\,0\.1\)\,_0px_16px_56px_rgba\(17\,17\,26\,0\.1\)\]{--tw-shadow:0px 4px 16px var(--tw-shadow-color,rgba(17,17,26,.1)),0px 8px 24px var(--tw-shadow-color,rgba(17,17,26,.1)),0px 16px 56px var(--tw-shadow-color,rgba(17,17,26,.1));box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-md{--tw-shadow:0 4px 6px -1px var(--tw-shadow-color,rgba(0,0,0,.1)),0 2px 4px -2px var(--tw-shadow-color,rgba(0,0,0,.1));box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.outline{outline-style:var(--tw-outline-style);outline-width:1px}.blur{--tw-blur:blur(8px)}.blur,.invert{filter:var(--tw-blur,) var(--tw-brightness,) var(--tw-contrast,) var(--tw-grayscale,) var(--tw-hue-rotate,) var(--tw-invert,) var(--tw-saturate,) var(--tw-sepia,) var(--tw-drop-shadow,)}.invert{--tw-invert:invert(100%)}.filter{filter:var(--tw-blur,) var(--tw-brightness,) var(--tw-contrast,) var(--tw-grayscale,) var(--tw-hue-rotate,) var(--tw-invert,) var(--tw-saturate,) var(--tw-sepia,) var(--tw-drop-shadow,)}.transition{transition-duration:var(--tw-duration,var(--default-transition-duration));transition-property:color,background-color,border-color,outline-color,text-decoration-color,fill,stroke,--tw-gradient-from,--tw-gradient-via,--tw-gradient-to,opacity,box-shadow,transform,translate,scale,rotate,filter,-webkit-backdrop-filter,backdrop-filter,display,visibility,content-visibility,overlay,pointer-events;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function))}.transition-transform{transition-duration:var(--tw-duration,var(--default-transition-duration));transition-property:transform,translate,scale,rotate;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function))}.duration-300{--tw-duration:300ms;transition-duration:.3s}.hover\:cursor-pointer{&:hover{@media (hover:hover){cursor:pointer}}}.hover\:bg-\[var\(--error-highlight\)\]{&:hover{@media (hover:hover){background-color:var(--error-highlight)}}}.hover\:bg-gray-100{&:hover{@media (hover:hover){background-color:var(--color-gray-100)}}}.hover\:bg-gray-400{&:hover{@media (hover:hover){background-color:var(--color-gray-400)}}}.hover\:text-gray-700{&:hover{@media (hover:hover){color:var(--color-gray-700)}}}.sm\:p-5{@media (width >= 40rem){padding:calc(var(--spacing)*5)}}.md\:col-span-2{@media (width >= 48rem){grid-column:span 2/span 2}}.md\:block{@media (width >= 48rem){display:block}}.md\:flex{@media (width >= 48rem){display:flex}}.md\:h-\[460px\]{@media (width >= 48rem){height:460px}}.md\:h-full{@media (width >= 48rem){height:100%}}.md\:w-5\/6{@media (width >= 48rem){width:83.33333%}}.md\:min-w-\[600px\]{@media (width >= 48rem){min-width:600px}}.md\:grid-cols-3{@media (width >= 48rem){grid-template-columns:repeat(3,minmax(0,1fr))}}.md\:border{@media (width >= 48rem){border-bottom-width:1px;border-left-width:1px;border-right-width:1px;border-style:var(--tw-border-style);border-top-width:1px}}.md\:px-1{@media (width >= 48rem){padding-inline:calc(var(--spacing)*1)}}.lg\:w-4\/6{@media (width >= 64rem){width:66.66667%}}.rtl\:text-right{&:where(:dir(rtl),[dir=rtl],[dir=rtl] *){text-align:right}}.dark\:border-\[\#282c34\]{&:where(.dark,.dark *){border-color:#282c34}}.dark\:border-\[\#282c35\]{&:where(.dark,.dark *){border-color:#282c35}}.dark\:border-\[\#414752\]{&:where(.dark,.dark *){border-color:#414752}}.dark\:border-gray-200{&:where(.dark,.dark *){border-color:var(--color-gray-200)}}.dark\:border-gray-300{&:where(.dark,.dark *){border-color:var(--color-gray-300)}}.dark\:border-gray-400{&:where(.dark,.dark *){border-color:var(--color-gray-400)}}.dark\:border-gray-600{&:where(.dark,.dark *){border-color:var(--color-gray-600)}}.dark\:border-white{&:where(.dark,.dark *){border-color:var(--color-white)}}.dark\:border-zinc-200{&:where(.dark,.dark *){border-color:var(--color-zinc-200)}}.dark\:bg-\[\#3d414a\]{&:where(.dark,.dark *){background-color:#3d414a}}.dark\:bg-\[\#6d717a\]{&:where(.dark,.dark *){background-color:#6d717a}}.dark\:bg-\[\#282c34\]{&:where(.dark,.dark *){background-color:#282c34}}.dark\:bg-\[\#343942\]{&:where(.dark,.dark *){background-color:#343942}}.dark\:bg-\[\#414752\]{&:where(.dark,.dark *){background-color:#414752}}.dark\:bg-\[var\(--dark-error-highlight\)\]{&:where(.dark,.dark *){background-color:var(--dark-error-highlight)}}.dark\:bg-gray-300{&:where(.dark,.dark *){background-color:var(--color-gray-300)}}.dark\:bg-gray-500{&:where(.dark,.dark *){background-color:var(--color-gray-500)}}.dark\:bg-gray-700{&:where(.dark,.dark *){background-color:var(--color-gray-700)}}.dark\:bg-gray-900{&:where(.dark,.dark *){background-color:var(--color-gray-900)}}.dark\:bg-green-400{&:where(.dark,.dark *){background-color:var(--color-green-400)}}.dark\:bg-purple-900{&:where(.dark,.dark *){background-color:var(--color-purple-900)}}.dark\:via-neutral-400{&:where(.dark,.dark *){--tw-gradient-via:var(--color-neutral-400);--tw-gradient-via-stops:var(--tw-gradient-position),var(--tw-gradient-from) var(--tw-gradient-from-position),var(--tw-gradient-via) var(--tw-gradient-via-position),var(--tw-gradient-to) var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-via-stops)}}.dark\:text-gray-200{&:where(.dark,.dark *){color:var(--color-gray-200)}}.dark\:text-gray-300{&:where(.dark,.dark *){color:var(--color-gray-300)}}.dark\:text-gray-400{&:where(.dark,.dark *){color:var(--color-gray-400)}}.dark\:text-gray-500{&:where(.dark,.dark *){color:var(--color-gray-500)}}.dark\:text-gray-600{&:where(.dark,.dark *){color:var(--color-gray-600)}}.dark\:text-purple-300{&:where(.dark,.dark *){color:var(--color-purple-300)}}.dark\:text-white{&:where(.dark,.dark *){color:var(--color-white)}}.dark\:opacity-40{&:where(.dark,.dark *){opacity:40%}}.dark\:hover\:bg-\[\#343942\]{&:where(.dark,.dark *){&:hover{@media (hover:hover){background-color:#343942}}}}.dark\:hover\:bg-\[\#414752\]{&:where(.dark,.dark *){&:hover{@media (hover:hover){background-color:#414752}}}}.dark\:hover\:bg-\[var\(--dark-error-highlight\)\]{&:where(.dark,.dark *){&:hover{@media (hover:hover){background-color:var(--dark-error-highlight)}}}}.dark\:hover\:bg-gray-600{&:where(.dark,.dark *){&:hover{@media (hover:hover){background-color:var(--color-gray-600)}}}}}@property --tw-rotate-x{syntax:"*";inherits:false}@property --tw-rotate-y{syntax:"*";inherits:false}@property --tw-rotate-z{syntax:"*";inherits:false}@property --tw-skew-x{syntax:"*";inherits:false}@property --tw-skew-y{syntax:"*";inherits:false}@property --tw-space-y-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-space-x-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-border-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-gradient-position{syntax:"*";inherits:false}@property --tw-gradient-from{syntax:"<color>";inherits:false;initial-value:#0000}@property --tw-gradient-via{syntax:"<color>";inherits:false;initial-value:#0000}@property --tw-gradient-to{syntax:"<color>";inherits:false;initial-value:#0000}@property --tw-gradient-stops{syntax:"*";inherits:false}@property --tw-gradient-via-stops{syntax:"*";inherits:false}@property --tw-gradient-from-position{syntax:"<length-percentage>";inherits:false;initial-value:0}@property --tw-gradient-via-position{syntax:"<length-percentage>";inherits:false;initial-value:50%}@property --tw-gradient-to-position{syntax:"<length-percentage>";inherits:false;initial-value:100%}@property --tw-leading{syntax:"*";inherits:false}@property --tw-font-weight{syntax:"*";inherits:false}@property --tw-ordinal{syntax:"*";inherits:false}@property --tw-slashed-zero{syntax:"*";inherits:false}@property --tw-numeric-figure{syntax:"*";inherits:false}@property --tw-numeric-spacing{syntax:"*";inherits:false}@property --tw-numeric-fraction{syntax:"*";inherits:false}@property --tw-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-shadow-color{syntax:"*";inherits:false}@property --tw-shadow-alpha{syntax:"<percentage>";inherits:false;initial-value:100%}@property --tw-inset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-shadow-color{syntax:"*";inherits:false}@property --tw-inset-shadow-alpha{syntax:"<percentage>";inherits:false;initial-value:100%}@property --tw-ring-color{syntax:"*";inherits:false}@property --tw-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-ring-color{syntax:"*";inherits:false}@property --tw-inset-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-ring-inset{syntax:"*";inherits:false}@property --tw-ring-offset-width{syntax:"<length>";inherits:false;initial-value:0}@property --tw-ring-offset-color{syntax:"*";inherits:false;initial-value:#fff}@property --tw-ring-offset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-outline-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-blur{syntax:"*";inherits:false}@property --tw-brightness{syntax:"*";inherits:false}@property --tw-contrast{syntax:"*";inherits:false}@property --tw-grayscale{syntax:"*";inherits:false}@property --tw-hue-rotate{syntax:"*";inherits:false}@property --tw-invert{syntax:"*";inherits:false}@property --tw-opacity{syntax:"*";inherits:false}@property --tw-saturate{syntax:"*";inherits:false}@property --tw-sepia{syntax:"*";inherits:false}@property --tw-drop-shadow{syntax:"*";inherits:false}@property --tw-drop-shadow-color{syntax:"*";inherits:false}@property --tw-drop-shadow-alpha{syntax:"<percentage>";inherits:false;initial-value:100%}@property --tw-drop-shadow-size{syntax:"*";inherits:false}@property --tw-duration{syntax:"*";inherits:false}@layer properties{@supports ((-webkit-hyphens:none) and (not (margin-trim:inline))) or ((-moz-orient:inline) and (not (color:rgb(from red r g b)))){*,::backdrop,:after,:before{--tw-rotate-x:initial;--tw-rotate-y:initial;--tw-rotate-z:initial;--tw-skew-x:initial;--tw-skew-y:initial;--tw-space-y-reverse:0;--tw-space-x-reverse:0;--tw-border-style:solid;--tw-gradient-position:initial;--tw-gradient-from:#0000;--tw-gradient-via:#0000;--tw-gradient-to:#0000;--tw-gradient-stops:initial;--tw-gradient-via-stops:initial;--tw-gradient-from-position:0%;--tw-gradient-via-position:50%;--tw-gradient-to-position:100%;--tw-leading:initial;--tw-font-weight:initial;--tw-ordinal:initial;--tw-slashed-zero:initial;--tw-numeric-figure:initial;--tw-numeric-spacing:initial;--tw-numeric-fraction:initial;--tw-shadow:0 0 #0000;--tw-shadow-color:initial;--tw-shadow-alpha:100%;--tw-inset-shadow:0 0 #0000;--tw-inset-shadow-color:initial;--tw-inset-shadow-alpha:100%;--tw-ring-color:initial;--tw-ring-shadow:0 0 #0000;--tw-inset-ring-color:initial;--tw-inset-ring-shadow:0 0 #0000;--tw-ring-inset:initial;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-offset-shadow:0 0 #0000;--tw-outline-style:solid;--tw-blur:initial;--tw-brightness:initial;--tw-contrast:initial;--tw-grayscale:initial;--tw-hue-rotate:initial;--tw-invert:initial;--tw-opacity:initial;--tw-saturate:initial;--tw-sepia:initial;--tw-drop-shadow:initial;--tw-drop-shadow-color:initial;--tw-drop-shadow-alpha:100%;--tw-drop-shadow-size:initial;--tw-duration:initial}}}
    </style>
</head>
<body style="font-family: Geist, ui-sans-serif, system-ui, sans-serif" class="dark:bg-gray-500 bg-gray-100 dark:text-white h-dvh text-sm-base p-2 sm:p-5">
<section class="dark:bg-[#282c34] bg-gray-200 rounded-xl h-full overflow-y-scroll shadow-[0px_4px_16px_rgba(17,17,26,0.1),_0px_8px_24px_rgba(17,17,26,0.1),_0px_16px_56px_rgba(17,17,26,0.1)]">
    <div class="container w:2/3 md:w-5/6 lg:w-4/6 mx-auto px-2">
        <hr class="w-5/6 sm:w3/4 border-t-[14px] rounded-b-lg border-zinc-300 dark:border-white mx-auto">
        <div class="px-2 py-5 border-zinc-600 rounded-lg flex justify-evenly items-center">
            <div>
                  <span class="text-xs text-red-600 font-bold inline-flex items-center rounded-md bg-red-200 px-2 py-1 me-2">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                        <path
                            fill-rule="evenodd"
                            d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"                                  clip-rule="evenodd"                          />
                     </svg>
                     <?php echo $type ?>
                  </span>
                <div class="dark:text-white">
                    <?php echo $message ?>
                </div>
            </div>
            <div>
                <div class="flex justify-center items-center border border-gray-400 dark:border-gray-600 rounded-lg p-1 mb-2 text-sm text-gray-800 dark:text-gray-300">
                    <span class="me-2">
                        <?php echo 'PHP ' . PHP_VERSION . '<br>novaframe ' . app()->version() ?>
                    </span>
                    <button id="themeToggle" class="px-3 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-400 dark:hover:bg-gray-600 transition hover:cursor-pointer">
                        <i id="themeIcon" class="fa-solid fa-sun text-sm"></i>
                    </button>
                </div>
                <span class="dark:text-white px-2"><?php echo date("H:i") . " " . date_default_timezone_get(); ?></span>
            </div>
        </div>
        <hr class="h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-neutral-500 to-transparent opacity-25 dark:via-neutral-400" />
        <div class="text-base mt-10 mb-5 overflow-x-scroll">
            <div class="flex items-center">
                <span class="uppercase text-xs text-red-600 font-bold inline-flex items-center rounded-md bg-red-200 py-1 px-2 me-2">
                    message
                </span>
                <div class="dark:text-white">
                    <?php echo "$message in $name on line $line" ?>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:h-[460px]">
            <div class="overflow-y-scroll h-56 md:h-full border dark:border-[#414752] border-gray-300 rounded-lg p-0.5">
                <table class="w-full">
                    <tbody>
                    <?php
                        $autoload = include DIR_ROOT . '/vendor/composer/autoload_psr4.php';
                        foreach ($backtraces as $file => $data):?>
                            <?php foreach ($data as $l => $d): ?>
                                <tr class="border-2 dark:border-[#282c34] border-gray-200 file-row hover:cursor-pointer" data-name="<?php echo htmlspecialchars($file . '~' . $l) ?>">
                                    <td class="text-wrap block rounded-lg px-4 py-3 dark:hover:bg-[#414752] hover:bg-gray-100 hover:text-gray-700 <?php if ($file === $name && $l == $line) echo 'dark:bg-[#414752] bg-gray-100' ?>">
                                        <a href="#" class="file-link text-sm font-medium dark:text-white text-gray-600 break-all" style="word-break: break-word!important;">
                                         <span class="flex items-center">
                                            <?php if ($file === $name && $l == $line): ?>
                                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                               <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                            </svg>
                                            <?php
                                                endif;
                                                echo $file . ':' . $l;
                                            ?>
                                         </span>
                                            <span class="text-gray-400">
                                                <?php
                                                $exists = false;
                                                $class  = '';
                                                foreach ($autoload as $namespace => $path) {
                                                    foreach ($path as $f) {
                                                        $f = str_replace('/', '\\', $f);
                                                        $replaced = str_replace($f, substr($namespace, 0, -1), str_replace("/", '\\', str_replace(".php", "", $file)));
                                                        $exists = class_exists($replaced, false);
                                                        $class = $replaced;
                                                        if ($exists) {
                                                            break 2;
                                                        }
                                                    }
                                                }
                                                if ($exists):
                                                    ?>
                                                    <span class="dark:bg-gray-300 bg-gray-400 text-xs font-medium text-gray-600 px-1 py-0.5 rounded">
                                                        <?php echo $class ?>
                                                    </span>
                                                <?php
                                                elseif ($file === str_replace('/', '\\', DIR_ROOT . 'public/index.php')) :
                                                    ?>
                                                    <span class="bg-green-500 text-white text-xs font-medium me-2 px-2.5 py-0.5 rounded">Frontend Controller</span>
                                                <?php
                                                endif;
                                                ?>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="border dark:border-[#414752] border-gray-300 rounded col-span-1 md:col-span-2">
                <?php foreach ($backtraces as $file => $data): ?>
                    <?php  ?>
                    <?php foreach ($data as $l => $d): ?>
                        <div class="file-content  rounded-t-lg h-full <?php if ($file !== $name || ($file === $name && $l !== $line)) echo 'hidden' ?>" data-name="<?php echo htmlspecialchars($file . '~' . $l) ?>">
                            <div class="p-3 overflow-x-scroll text-nowrap border-b border-gray-400 bg-gray-300 rounded-t text-gray-700">
                                <?php echo "{$file} at line " . $l ?>
                            </div>
                            <div class="px-2 py-1 overflow-x-scroll">
                                <table class="w-full overflow-x-scroll">
                                    <tbody>
                                    <?php foreach ($d as $key => $traces): ?>
                                        <tr class="border-2 dark:border-[#282c34] border-gray-200">
                                            <td style="font-family: 'Geist Mono', 'ui-monospace', Inter, 'ui-sans-serif', 'system-ui';" class="<?php if ($traces['display_line'] === $l) echo 'bg-[var(--error-highlight)] dark:bg-[var(--dark-error-highlight)]' ?> hover:bg-[var(--error-highlight)] dark:hover:bg-[var(--dark-error-highlight)] hover:cursor-pointer font-sans leading-9 text-start rounded-lg px-2 text-nowrap">
                                                <code class="z-10 text-[0.95rem]" style="font-family: 'Geist Mono', 'ui-monospace', Inter, 'ui-sans-serif', 'system-ui'; background: transparent!important;">
                                                    <?php
                                                    $context = str_replace(" ", "&nbsp;", $traces['context']);
                                                    echo "{$traces['display_line']}&nbsp;&nbsp;{$context}";
                                                    ?>
                                                </code>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <hr class="my-10 h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-neutral-500 to-transparent opacity-25 dark:via-neutral-400" />
        <span class="bg-purple-100 text-purple-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">Included Files</span>
        <div class="mx-auto border border-gray-300 rounded-lg mt-2">
            <?php $count = 1; foreach ($included as $path => $array): ?>
                <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
                    <h2 id="accordion-flush-heading-<?php echo $count ?>"
                        class="bg-gray-300 <?php echo $count === 1 ? 'rounded-t-lg' : ''; ?>
                        <?php echo $count === count($included) ? 'rounded-b-lg' : ''; ?>"
                    >
                        <button type="button" class="flex items-center justify-between w-full py-5 px-8 font-medium rtl:text-right text-gray-700 <?php if ($count !== count($included)) echo 'border-b border-gray-400' ?> dark:text-gray-500 gap-3" data-accordion-target="#accordion-flush-body-<?php echo $count ?>" aria-expanded="<?php echo ($count === 1 ? 'true' : 'false') ?>" aria-controls="accordion-flush-body-<?php echo $count ?>">
                            <span><?php echo $path; ?></span>
                            <svg data-accordion-icon class="w-3 h-3 shrink-0 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 8 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 13 5.7-5.326a.909.909 0 0 0 0-1.348L1 1"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-flush-body-<?php echo $count ?>" class="<?php if ($count !== 1) echo 'hidden' ?>" aria-labelledby="accordion-flush-heading-<?php echo $count ?>">
                        <div class="py-5 border-b border-gray-200 overflow-x-scroll">
                            <ul class="ms-12 leading-7">
                                <?php foreach (array_reverse($array) as $file): ?>
                                    <li class="list-decimal text-opacity-50">
                                        <span class="text-red-400"><?php echo $file['basepath'] ?></span><?php echo DS . $file['file'] ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php $count++; endforeach; ?>
        </div>
        <hr class="my-10 h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-neutral-500 to-transparent opacity-25 dark:via-neutral-400" />

        <span class="bg-purple-100 text-purple-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">Request Header</span>
        <div class="rounded-lg border dark:border-gray-400 border-black mt-4 w-full p-0">
            <table class="table-fixed border-collapse w-full mx-auto">
                <colgroup>
                    <col class="w-1/3">
                    <col class="w-2/3">
                </colgroup>
                <thead>
                <tr class="rounded-lg">
                    <th class="border-r dark:border-gray-400 border-black px-4 py-2">Header</th>
                    <th class="px-4 py-2">Value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($headers as $key => $value): ?>
                    <tr>
                        <td class="border-t border-r dark:border-gray-400 border-black px-4 py-2"><?php echo $key; ?></td>
                        <td class="border-t dark:border-gray-400 border-black px-4 py-2 overflow-scroll"><?php echo $value; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <hr class="w-5/6 sm:w3/4 mt-10 border-b-[12px] rounded-t-lg border-zinc-300 dark:border-white mx-auto">
    </div>
</section>
</body>
</html>
<script>hljs.highlightAll();</script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lightTheme = "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/atom-one-light.min.css";
        //const lightIntegrity = "sha512-o5v54Kh5PH0dgnf9ei0L+vMRsbm5fvIvnR/XkrZZjN4mqdaeH7PW66tumBoQVIaKNVrLCZiBEfHzRY4JJSMK/Q==";

        const darkTheme = "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/atom-one-dark.min.css";
        //const darkIntegrity = "sha512-Jk4AqjWsdSzSWCSuQTfYRIF84Rq/eV0G2+tu07byYwHcbTGfdmLrHjUSwvzp5HvbiqK4ibmNwdcG49Y5RGYPTg==";

        const themeLink = document.getElementById("hljs-theme");

        function setHighlightTheme() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (prefersDark) {
                themeLink.href = darkTheme;
            } else {
                themeLink.href = lightTheme;
            }
        }

        setHighlightTheme();

        // Listen to changes in system dark mode preference and update theme dynamically
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', setHighlightTheme);
    });

    // Get all file rows and file contents
    const fileRows = document.querySelectorAll('.file-row');
    const fileContents = document.querySelectorAll('.file-content');

    // Add click event listeners to each row
    fileRows.forEach(row => {
        row.addEventListener('click', function () {
            // Get the clicked row's data-name
            const selectedFileName = this.getAttribute('data-name');

            // Hide all file contents
            fileContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show the correct file content
            const relatedContent = document.querySelector(`.file-content[data-name="${CSS.escape(selectedFileName)}"]`);
            if (relatedContent) {
                relatedContent.classList.remove('hidden');
            }

            // Remove background from all <td> in each row
            fileRows.forEach(row => {
                const td = row.querySelector('td');
                if (td) {
                    td.classList.remove('dark:bg-[#414752]', 'bg-gray-100');
                }
            });

            // Add background to the <td> in the clicked row
            const clickedTd = this.querySelector('td');
            if (clickedTd) {
                clickedTd.classList.add('dark:bg-[#414752]', 'bg-gray-100');
            }
        });
    });

    // Accordion functionality on page load
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('[data-accordion-target]');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-accordion-target');
                const target = document.querySelector(targetId);
                const isExpanded = button.getAttribute('aria-expanded') === 'true';

                // Toggle the aria-expanded state
                button.setAttribute('aria-expanded', !isExpanded);

                // Toggle the visibility of the target content
                target.classList.toggle('hidden', isExpanded);

                // Update the accordion icon rotation
                const icon = button.querySelector('[data-accordion-icon]');
                if (icon) {
                    icon.classList.toggle('rotate-90', !isExpanded);
                }
            });

            // Handle the initial expanded state
            const targetId = button.getAttribute('data-accordion-target');
            const target = document.querySelector(targetId);
            const isExpanded = button.getAttribute('aria-expanded') === 'true';

            // Ensure the content reflects the initial state
            if (!isExpanded) {
                target.classList.add('hidden');
            }

            // Set the initial icon rotation
            const icon = button.querySelector('[data-accordion-icon]');
            if (icon) {
                icon.classList.toggle('rotate-90', isExpanded); // Rotate icon if expanded
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('td > code').forEach(codeEl => {
            const rawText = codeEl.textContent; // raw PHP line with line number
            const highlighted = hljs.highlight(rawText, { language: 'php' }).value;
            codeEl.innerHTML = highlighted;
            codeEl.classList.add('hljs'); // required for styling
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeLink = document.getElementById("hljs-theme");

        const lightTheme = "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/atom-one-light.min.css";
        const darkTheme = "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/atom-one-dark.min.css";

        const setTheme = (theme) => {
            document.documentElement.classList.toggle('dark', theme === 'dark');
            localStorage.setItem('theme', theme);
            updateIcon();
            // Update highlight.js theme css link
            themeLink.href = (theme === 'dark') ? darkTheme : lightTheme;
        };

        const updateIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            themeIcon.classList.toggle('fa-sun', !isDark);
            themeIcon.classList.toggle('fa-moon', isDark);
        };

        // Load theme from localStorage or system preference
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            themeLink.href = darkTheme;
        } else {
            document.documentElement.classList.remove('dark');
            themeLink.href = lightTheme;
        }

        updateIcon();

        themeToggleBtn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
        });
    });
</script>